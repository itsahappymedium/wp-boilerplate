<?php
$flex_field_name = arrval($args, 'flex_field_name', 'flex_sections');
$flex_post_id = arrval($args, 'flex_post_id', false);

if (function_exists('have_rows') && have_rows($flex_field_name, $flex_post_id)) {
  while (have_rows($flex_field_name, $flex_post_id)) {
    the_row();
    $layout = get_row_layout();
    get_template_part("flex/$layout/$layout", null, $args);
  }
}
?>