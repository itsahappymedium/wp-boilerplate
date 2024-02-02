<?php
/* Template Name: Flex Page */
get_header();
?>

<?php if (!is_front_page()): ?>
  <section>
    <div class="container">
      <h1 class="page-title"><?php the_title(); ?></h1>

      <?php
      if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
      }
      ?>
    </div>
  </section>
<?php endif; ?>

<?php
get_template_part('flex/flex');
get_footer();
?>