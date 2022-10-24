<?php
$field_name = arrval($args, 'field_name', 'flex_sections');
$post_id = arrval($args, 'post_id', false);

if (function_exists('have_rows') && have_rows($field_name, $post_id)) {
  while (have_rows($field_name, $post_id)) {
    the_row();
    get_template_part('templates/' . get_row_layout(), null, $args);
  }
}
?>