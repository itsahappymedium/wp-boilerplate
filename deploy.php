<?php
require_once('./vendor/autoload.php');
new WP_Deployer();

use function Deployer\{ add };

add('clear_paths', array(
  'auth.encrypted.json',
  'config.encrypted.yml',
  'watcher.php'
));
?>