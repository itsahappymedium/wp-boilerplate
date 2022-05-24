<?php
define('THEME_URL', get_template_directory_uri());
define('THEME_PATH', get_template_directory());
define('IMAGES', THEME_URL . '/img');


require_once(THEME_PATH . '/lib/acf.php');
require_once(THEME_PATH . '/lib/comments.php');
require_once(THEME_PATH . '/lib/roles.php');
require_once(THEME_PATH . '/lib/theme.php');


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


// Checks if $arg is is set in a template's $args variable, otherwise
// attempt to get it from an ACF field, and if all else fails, return $default
function get_template_arg($arg, $default = null, $args = array()) {
  $value = arrval($args, $arg);

  if (!$value && function_exists('get_field')) {
    $value = get_field($arg);
  }

  if (!$value && function_exists('get_sub_field')) {
    $value = get_sub_field($arg);
  }

  if ($value) return $value;

  return $default;
}


// Changes the email address that emails are sent from
add_filter('wp_mail_from', 'hm_mail_from');
function hm_mail_from($from_email) {
  if (defined('WP_MAIL_FROM') && !empty(WP_MAIL_FROM)) {
    return WP_MAIL_FROM;
  }

  return $from_email;
}

// Changes the name that emails are sent from
add_filter('wp_mail_from_name', 'hm_mail_from_name');
function hm_mail_from_name($from_name) {
  if (defined('WP_MAIL_FROM_NAME') && !empty(WP_MAIL_FROM_NAME)) {
    return WP_MAIL_FROM_NAME;
  }
  
  return $from_name;
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
?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?=GOOGLE_ANALYTICS_ID?>"></script>
  <script>
    window.dataLayer = window.dataLayer || []
    function gtag () { dataLayer.push(arguments) }
    gtag('js', new Date())
    gtag('config', '<?=GOOGLE_ANALYTICS_ID?>')
  </script>
<?php
  endif;
}