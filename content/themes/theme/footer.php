  </main>

  <footer>
    <div class="container">
      <div class="footer-navigation">
        <?php
        wp_nav_menu(array(
          'theme_location'  => 'footer-navigation'
        ));
        ?>
      </div>

      <p>&copy; <?=date('Y')?> <?php bloginfo('name'); ?></p>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>