<!doctype html>
<head>
  <meta charset="utf-8">
  <title><?php wp_title(''); ?></title>
  <meta name="viewport" content="width=device-width">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header>
    <div class="container">
      <div class="header-left">
        <a href="<?=home_url()?>">
          <img src="<?=IMAGES?>/svg/logo.svg" class="logo">
          <span class="brand"><?=bloginfo('name');?></span>
        </a>
      </div>
      <a href="#" class="header-navigation-button"><span></span></a>
      <div class="header-navigation">
        <?php
        wp_nav_menu(array(
          'theme_location'  => 'header-navigation'
        ));
        ?>
      </div>
    </div>
  </header>