<?php
// Sync ACF field groups to JSON files so they can be version-controlled
add_filter('acf/settings/save_json', 'hm_acf_json_version_control');
add_filter('acf/settings/load_json', 'hm_acf_json_version_control');
function hm_acf_json_version_control($paths) {
  unset($paths[0]);
  $paths[] = WP_CONTENT_DIR . '/acf-json';
  return $paths;
}
?>