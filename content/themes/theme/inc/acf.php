<?php
// Set the path where ACF JSON data should be saved to
add_filter('acf/settings/save_json', 'hm_acf_json_save');
function hm_acf_json_save($path) {
  $path = THEME_PATH . '/acf-json';
  return $path;
}

// Set the path where ACF JSON data should be loaded from
add_filter('acf/settings/load_json', 'hm_acf_json_load');
function hm_acf_json_load($paths) {
  unset($paths[0]);
  $paths[] = THEME_PATH . '/acf-json';
  return $paths;
}
?>