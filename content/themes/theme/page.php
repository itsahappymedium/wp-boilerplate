<?php get_header(); ?>

<section>
  <div class="container">
    <h1><?php the_title(); ?></h1>

    <div class="content">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<?php get_template_part('parts/flex'); ?>

<?php get_footer(); ?>