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

  // Disables the Gutenberg Editor
  add_filter('use_block_editor_for_post', '__return_false');
}


// Disables the Gravity Forms Theme
add_filter('gform_disable_form_theme_css', '__return_true');
add_filter('gform_disable_form_legacy_css', '__return_true');


// Disables the default content editor on pages using templates that don't use the editor
add_action('init', 'hm_remove_editor_from_post_type');
function hm_remove_editor_from_post_type() {
  $no_editor_slugs = array();

  $no_editor_templates = array(
    'template-flex-page.php'
  );

  if (isset($_REQUEST['post'])) {
    $post_id = $_REQUEST['post'];
    $template = get_post_meta($post_id, '_wp_page_template', true);
    $slug = get_post_field('post_name', $post_id);

    if (in_array($template, $no_editor_templates) || in_array($slug, $no_editor_slugs)) {
      remove_post_type_support('page', 'editor');
    }
  }
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


// Enqueue admin scripts
add_action('admin_enqueue_scripts', 'hm_admin_scripts');
function hm_admin_scripts() {
  wp_register_style('hm_admin_styles', get_stylesheet_directory_uri() . '/admin.css', null, filemtime(get_template_directory() . '/admin.css'), 'all');
  wp_enqueue_style('hm_admin_styles');
}


// Remove Read More Jump
add_filter('the_content_more_link', 'hm_remove_more_jump_link');
function hm_remove_more_jump_link($link) {
  $offset = strpos($link, '#more-');
  if ($offset) $end = strpos($link, '"', $offset);
  if ($end) $link = substr_replace($link, '', $offset, $end - $offset);
  return $link;
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


// Removes unwanted columns from posts table
add_filter('manage_edit-post_columns', 'hm_remove_unwanted_post_columns', 99);
function hm_remove_unwanted_post_columns($columns) {
  $unwanted = array(
    'wpseo-title',
    'wpseo-metadesc',
    'wpseo-focuskw'
  );

  foreach($unwanted as $column) {
    if (isset($columns[$column])) {
      unset($columns[$column]);
    }
  }

  return $columns;
}


// Removes unwanted columns from pages table
add_filter('manage_edit-page_columns', 'hm_remove_unwanted_page_columns', 99);
function hm_remove_unwanted_page_columns($columns) {
  $unwanted = array(
    'wpseo-title',
    'wpseo-score-readability',
    'wpseo-links',
    'wpseo-linked'
  );

  foreach($unwanted as $column) {
    if (isset($columns[$column])) {
      unset($columns[$column]);
    }
  }

  return $columns;
}


// Removes Yoast SEO filter dropdowns from pages and posts tables
add_action('admin_init', 'hm_remove_yoast_seo_admin_filters', 20);
function hm_remove_yoast_seo_admin_filters() {
  global $wpseo_meta_columns;
  if ($wpseo_meta_columns) {
    remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown'));
    remove_action('restrict_manage_posts', array($wpseo_meta_columns, 'posts_filter_dropdown_readability'));
  }
}


// Removes the spammy Monster Insights metabox from the edit post screen
add_action('add_meta_boxes', 'hm_remove_monsterinsights_metabox', 99);
function hm_remove_monsterinsights_metabox() {
  remove_meta_box('monsterinsights-metabox', null, 'side');
}


// Removes unused contact method fields from the user edit screen
add_filter('user_contactmethods', 'remove_unused_user_contactmethods', 99);
function remove_unused_user_contactmethods($methods) {
  $unused_methods = array(
    'facebook',
    'instagram',
    'linkedin',
    'myspace',
    'pinterest',
    'soundcloud',
    'tumblr',
    'twitter',
    'youtube',
    'wikipedia'
  );

  return array_diff_key($methods, array_flip($unused_methods));
}


// Adds a chevron SVG next to menu items that have children
add_filter('walker_nav_menu_start_el', 'hm_nav_add_chevron_to_submenus', 10, 2);
function hm_nav_add_chevron_to_submenus($item_output, $menu_item) {
  if (in_array('menu-item-has-children', $menu_item->classes)) {
    $split_pos = strrpos($item_output, '</a>');
    $first_part = substr($item_output, 0, $split_pos);
    $last_part = substr($item_output, $split_pos);
    $chevron = get_svg('chevron');
    $item_output = $first_part . $chevron . $last_part;
  }

  return $item_output;
}
?>