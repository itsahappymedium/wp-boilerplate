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
function get_template_arg($arg, $default = null) {
  global $args;

  $value = arrval($args, $arg);

  if (!$value && function_exists('get_field')) {
    $value = get_field($arg);
  }

  if ($value) return $value;

  return $default;
}