<?php
define('THEME_URL', get_template_directory_uri());
define('THEME_PATH', get_template_directory());
define('IMAGES', THEME_URL . '/img');


require_once(THEME_PATH . '/inc/acf.php');
require_once(THEME_PATH . '/inc/comments.php');
require_once(THEME_PATH . '/inc/cpt.php');
require_once(THEME_PATH . '/inc/roles.php');
require_once(THEME_PATH . '/inc/taxonomies.php');
require_once(THEME_PATH . '/inc/theme.php');


// A wrapper for error_log() that will run arrays and objects through print_r()
// and also allows for an unlimited number of parameters
function write_log(...$log) {
  foreach ($log as $key => $val) {
    if (is_array($val) || is_object($val)) {
      $log[$key] = print_r($val, true);
    }
  }

  error_log(implode(' ', $log));
}


// Checks to see if $key exists in $arr and returns it, otherwise returns $default
function arrval($arr, $key, $default = null) {
  return is_array($arr) && isset($arr[$key]) ? $arr[$key] : $default;
}


// Returns the contents of an SVG file or an IMG tag with the src set to the file path
function get_svg($svg, $class = null, $alt = null, $get_contents = false, $echo = false) {
  $file = '/img/svg/' . $svg . '.svg';
  $file_path = get_stylesheet_directory() . $file;
  $file_url = get_stylesheet_directory_uri() . $file;
  $response = false;

  if (!$class) $class = '';
  $class = trim($svg . ' ' . $class);

  if (file_exists($file_path)) {
    if ($get_contents) {
      $response = file_get_contents($file_path);
    } else {
      $response = "<img src='$file_url' class='style-svg $class' alt='$alt' />";
    }

    if ($echo) echo $response;
  }

  return $response;
}


// Allows emails sent to specific addresses to always send regardless if WPMS_DO_NOT_SEND is enabled or not in non-production environments
add_filter('wp_mail', 'hm_wpmsmtp_always_send');
function hm_wpmsmtp_always_send($args) {
  if (wp_get_environment_type() !== 'production') {
    $domain_whitelist = WP_MAIL_SEND_DOMAIN_WHITELIST;
    $email_whitelist = WP_MAIL_SEND_EMAIL_WHITELIST;

    $to = is_array($args['to']) ? $args['to'] : explode(',', $args['to']);

    $to_filtered = array_filter($to, function($email) use($domain_whitelist, $email_whitelist) {
      list($handle, $domain) = explode('@', $email, 2);
      return in_array($domain, $domain_whitelist) || in_array($email, $email_whitelist);
    });

    if (!empty($to_filtered)) {
      $test_header = 'X-Mailer-Type:WPMailSMTP/Admin/Test';

      if (count($to) !== count($to_filtered)) {
        $args['to'] = $to_filtered;
      }

      if (!is_array($args['headers'])) {
        $args['headers'] = explode("\n", str_replace("\r\n", "\n", $args['headers']));
      }

      if (!in_array($test_header, $args['headers'])) {
        $args['headers'][] = $test_header;
      }
    }
  }

  return $args;
}


// Prevent Updraft Plus from creating backups from non-production environments
add_filter('updraftplus_boot_backup', 'hm_prevent_non_prod_backups');
function hm_prevent_non_prod_backups($do_backup) {
  if (wp_get_environment_type() !== 'production') return false;
  return $do_backup;
}


// Filter Yoast SEO Metabox Priority
add_filter('wpseo_metabox_prio', 'hm_filter_yoast_seo_metabox');
function hm_filter_yoast_seo_metabox() {
  return 'low';
}


// Google Analytics
add_action('wp_head', 'hm_google_analytics_tracking_code');
function hm_google_analytics_tracking_code() {
  if (defined('GOOGLE_ANALYTICS_ID') && !empty(GOOGLE_ANALYTICS_ID)):
    $google_analytics_ids = (array) GOOGLE_ANALYTICS_ID;
?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?=$google_analytics_ids[0]?>"></script>
  <script>
    window.dataLayer = window.dataLayer || []
    function gtag () { dataLayer.push(arguments) }
    gtag('js', new Date())

    <?php foreach($google_analytics_ids as $google_analytics_id): ?>
      gtag('config', '<?=$google_analytics_id?>')
    <?php endforeach; ?>
  </script>
<?php
  endif;
}