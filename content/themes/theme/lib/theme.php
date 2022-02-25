<?php
add_action('after_setup_theme', 'hm_setup');
function hm_setup() {
  // Clean up the head
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head');

  // Add RSS links to head
  add_theme_support('automatic-feed-links');

  // Prevent File Modifications
  define('DISALLOW_FILE_EDIT', true);

  // Enable Post Thumbnails
  add_theme_support('post-thumbnails');

  // Enable HTML5
  add_theme_support('html5', array('search-form', 'gallery', 'caption'));

  // Add custom image sizes
  // add_image_size('card', 260, 390, true);
  // add_image_size('tiny', 30, 30, true);
  // add_image_size('wide_thumbnail', 300, 150, true);
  // add_image_size('profile_pic', 300, 300, true);

  // Register navigation menus
  register_nav_menu('header-navigation', 'Header Navigation');
  register_nav_menu('footer-navigation', 'Footer Navigation');

  // Add Editor Style
  // add_editor_style('editor-style.css');
}

// Don't update the theme
add_filter('http_request_args', 'hm_dont_update_theme', 5, 2);
function hm_dont_update_theme($r, $url) {
  if (strpos($url, 'http://api.wordpress.org/themes/update-check') !== 0) return $r;
  $themes = unserialize($r['body']['themes']);
  unset($themes[get_option('template')]);
  unset($themes[get_option('stylesheet')]);
  $r['body']['themes'] = serialize($themes);
  return $r;
}

// Enqueue scripts
add_action('wp_enqueue_scripts', 'hm_scripts');
function hm_scripts() {
  if (!is_admin()) {
    wp_register_style('hm_styles', get_stylesheet_directory_uri() . '/style.css', null, filemtime(get_template_directory() . '/style.css'), 'all');
    wp_enqueue_style('hm_styles');

    wp_register_script('hm_scripts', get_template_directory_uri() . '/js/scripts.min.js', array('jquery'), filemtime(get_template_directory() . '/js/scripts.min.js'), true);
    wp_enqueue_script('hm_scripts');
  }
}

// Remove Read More Jump
add_filter('the_content_more_link', 'hm_remove_more_jump_link');
function hm_remove_more_jump_link($link) {
  $offset = strpos($link, '#more-');
  if ($offset) $end = strpos($link, '"', $offset);
  if ($end) $link = substr_replace($link, '', $offset, $end - $offset);
  return $link;
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

// Changes the default WP login page logo
add_action('login_enqueue_scripts', 'hm_login_logo');
function hm_login_logo() {
?>
  <style type="text/css">
    #login h1 a, .login h1 a {
      background-image: url('<?=IMAGES?>/svg/logo.svg');
      width: 150px;
      height: 150px;
      background-size: 150px 150px;
      background-repeat: no-repeat;
      padding-bottom: 25px;
    }
  </style>
<?php
}
?>