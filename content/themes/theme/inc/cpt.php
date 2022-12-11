<?php
function generate_post_type_labels($singular, $plural = null) {
  if (!$plural) $plural = $singular . 's';
  
  return array(
    'name'                  => $plural,
    'singular_name'         => $singular,
    'menu_name'             => $plural,
    'name_admin_bar'        => $singular,
    'add_new'               => 'Add New',
    'add_new_item'          => "Add New $singular",
    'new_item'              => "New $singular",
    'edit_item'             => "Edit $singular",
    'view_item'             => "View $singular",
    'all_items'             => "All $plural",
    'search_items'          => "Search $plural",
    'parent_item_colon'     => "Parent $plural:",
    'not_found'             => "No $plural found.",
    'not_found_in_trash'    => "No $plural found in Trash.",
    'featured_image'        => 'Featured Image',
    'set_featured_image'    => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image'    => 'Use as featured image',
    'archives'              => "$singular Archives",
    'insert_into_item'      => "Insert into $singular",
    'uploaded_to_this_item' => "Uploaded to this $singular",
    'filter_items_list'     => "Filter $plural list",
    'items_list_navigation' => "$plural list navigation",
    'items_list'            => "$plural list",
  );
}


// add_action('init', 'create_faq_post_type', 0);
function create_faq_post_type() {
  $faq = array(
    'label'                 => 'FAQ',
    'description'           => 'Listing FAQs',
    'labels'                => generate_post_type_labels('FAQ'),
    'supports'              => array('custom-fields', 'title', 'editor'),
    'hierarchical'          => false,
    'public'                => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 21,
    'menu_icon'             => 'dashicons-format-status',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post'
  );

  register_post_type('FAQ', $faq);
}
?>