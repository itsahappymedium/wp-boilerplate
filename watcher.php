<?php
require_once('./vendor/autoload.php');
new FEC_Watcher(array(
  'js/scripts.min.js' => 'style.css'
), 'content/themes/{{WP_BOILERPLATE_SLUG}}');
?>