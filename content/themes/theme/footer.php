  </main>

  <footer>
    <div class="container">
      <div class="footer-brand">
        <a href="<?=home_url()?>">
          <img src="<?=IMAGES?>/svg/logo.svg" class="logo">
        </a>
      </div>
    
      <div class="footer-navigation">
        <?php
        wp_nav_menu(array(
          'theme_location'  => 'footer-navigation',
          'depth'           => 1
        ));
        ?>
      </div>

      <div class="footer-notice">
        <p>&copy; <?=date('Y')?> <?php bloginfo('name'); ?></p>
        <p>Made with Heart by <a href="https://itsahappymedium.com" target="_blank">Happy Medium</a></p>
      </div>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>