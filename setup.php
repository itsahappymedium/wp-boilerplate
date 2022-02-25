<?php
$vars = array(
  'WP_BOILERPLATE_SLUG'                     => 'site slug',
  'WP_BOILERPLATE_VENDOR'                   => 'vendor name',
  'WP_BOILERPLATE_USER'                     => 'admin username',
  'WP_BOILERPLATE_EMAIL'                    => 'admin email address',
  'WP_BOILERPLATE_REPO'                     => 'git repository URL',
  'WP_BOILERPLATE_DEPLOY_PATH'              => 'server deploy path',
  'WP_BOILERPLATE_PUBLIC_PATH'              => 'server public path',
  'WP_BOILERPLATE_STAGING_IP'               => 'staging server IP address',
  'WP_BOILERPLATE_STAGING_USER'             => 'staging server user',
  'WP_BOILERPLATE_STAGING_URL'              => 'staging website URL',
  'WP_BOILERPLATE_STAGING_DB_USER'          => 'staging website database user',
  'WP_BOILERPLATE_STAGING_DB_PASS'          => 'staging website database password',
  'WP_BOILERPLATE_PRODUCTION_IP'            => 'production server IP address',
  'WP_BOILERPLATE_PRODUCTION_USER'          => 'production server user',
  'WP_BOILERPLATE_PRODUCTION_URL'           => 'production website URL',
  'WP_BOILERPLATE_PRODUCTION_DB_USER'       => 'production website database user',
  'WP_BOILERPLATE_PRODUCTION_DB_PASS'       => 'production website database password',
  'WP_BOILERPLATE_SOPS_AGE'                 => 'SOPS AGE public key',
  'WP_BOILERPLATE_SOPS_KMS'                 => 'SOPS KMS value',
  'WP_BOILERPLATE_GOOGLE_ANALYTICS_ID'      => 'Google Analytics Property ID'
);

$var_defaults = array(
  'WP_BOILERPLATE_SLUG'                     => 'my-new-website',
  'WP_BOILERPLATE_VENDOR'                   => 'my-company',
  'WP_BOILERPLATE_USER'                     => 'admin',
  'WP_BOILERPLATE_DEPLOY_PATH'              => '/var/www/public_html',
  'WP_BOILERPLATE_PUBLIC_PATH'              => '/var/www/public',
  'WP_BOILERPLATE_STAGING_USER'             => 'www-data',
  'WP_BOILERPLATE_PRODUCTION_USER'          => 'www-data'
);

$file_list = array(
  'content/themes/theme/scss/style.scss',
  '.lando.yml',
  '.sops.yaml',
  '.user.ini',
  'composer.json',
  'config.template.yml',
  'site-readme.md',
  'watcher.php'
);

$file_delete_list = array(
  '.git',
  'license.md',
  'readme.md',
  'setup.php'
);

$file_rename_list = array(
  'content/themes/theme'  => 'content/themes/{{WP_BOILERPLATE_SLUG}}',
  'auth.template.json'    => 'auth.json',
  'config.template.yml'   => 'config.yml',
  'site-readme.md'        => 'readme.md'
);

if (basename(dirname(__FILE__)) === 'wp-boilerplate') {
  echo "\nThis script should only be ran on a new copy of wp-boilerplate.\n\n";
  exit;
}

foreach($vars as $var => &$value) {
  if (!($default = getenv($var))) {
    if (isset($var_defaults[$var])) {
      $default = $var_defaults[$var];
    } else {
      $default = '';
    }
  }

  if (!($response = readline("Enter the $value ($default): "))) {
    $response = $default;
  }

  $value = $response;
}

foreach($file_list as $file_path) {
  if (file_exists($file_path)) {
    echo "Processing $file_path...\n";

    try {
      $file_contents = file_get_contents($file_path);

      foreach($vars as $var => $value) {
        $file_contents = str_replace('{{' . $var . '}}', $value, $file_contents);
      }

      file_put_contents($file_path, $file_contents);
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage() . "\n";
    }
  } else {
    echo "Error: Could not find $file_path\n";
  }
}

foreach($file_delete_list as $file_path) {
  if (file_exists($file_path)) {
    echo "Deleting $file_path...\n";

    try {
      if (is_dir($file_path)) {
        rmdir($file_path);
      } else {
        unlink($file_path);
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage() . "\n";
    }
  } else {
    echo "Error: Could not find $file_path\n";
  }
}

foreach($file_rename_list as $file_path => $new_name) {
  if (file_exists($file_path)) {
    foreach($vars as $var => $value) {
      $new_name = str_replace('{{' . $var . '}}', $value, $new_name);
    }

    echo "Renaming $file_path to $new_name...\n";

    try {
      rename($file_path, $new_name);
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage() . "\n";
    }
  } else {
    echo "Error: Could not find $file_path\n";
  }
}

echo "\nDone! You should now check out your config.yml file and make any necessary changes.\n";
echo "After that, run `lando start && lando dep setup:local`.\n\n";
?>