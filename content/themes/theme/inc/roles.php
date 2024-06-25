<?php
add_action('init', 'hm_custom_roles', 0);
function hm_custom_roles() {
  // Manager role for client
  add_role('manager', 'Manager', array(
    // Wordpress Core
    'activate_plugins'        => false,
    'create_posts'            => true,
    'create_users'            => true,
    'customize'               => false,
    'delete_others_pages'     => true,
    'delete_others_posts'     => true,
    'delete_pages'            => true,
    'delete_plugins'          => false,
    'delete_posts'            => true,
    'delete_private_pages'    => true,
    'delete_private_posts'    => true,
    'delete_published_pages'  => true,
    'delete_published_posts'  => true,
    'delete_site'             => false,
    'delete_themes'           => false,
    'delete_users'            => true,
    'edit_dashboard'          => true,
    'edit_others_pages'       => true,
    'edit_others_posts'       => true,
    'edit_pages'              => true,
    'edit_plugins'            => false,
    'edit_posts'              => true,
    'edit_private_pages'      => true,
    'edit_private_posts'      => true,
    'edit_published_pages'    => true,
    'edit_published_posts'    => true,
    'edit_theme_options'      => true,
    'edit_themes'             => false,
    'edit_users'              => true,
    'export'                  => false,
    'import'                  => false,
    'install_languages'       => false,
    'install_plugins'         => false,
    'install_themes'          => false,
    'list_users'              => true,
    'manage_categories'       => true,
    'manage_links'            => true,
    'manage_options'          => false,
    'moderate_comments'       => true,
    'promote_users'           => true,
    'publish_pages'           => true,
    'publish_posts'           => true,
    'read_private_pages'      => true,
    'read_private_posts'      => true,
    'read'                    => true,
    'remove_users'            => true,
    'resume_plugins'          => false,
    'resume_themes'           => false,
    'switch_themes'           => false,
    'unfiltered_html'         => true,
    'unfiltered_upload'       => true,
    'update_core'             => false,
    'update_plugins'          => false,
    'update_themes'           => false,
    'upload_files'            => true,
    'view_site_health_checks' => false,

    // Gravity Forms
    'gravityforms_create_form'      => true,
    'gravityforms_delete_entries'   => true,
    'gravityforms_delete_forms'     => true,
    'gravityforms_edit_entries'     => true,
    'gravityforms_edit_entry_notes' => true,
    'gravityforms_edit_forms'       => true,
    'gravityforms_edit_settings'    => false,
    'gravityforms_export_entries'   => true,
    'gravityforms_feed'             => true,
    'gravityforms_uninstall'        => false,
    'gravityforms_view_entries'     => true,
    'gravityforms_view_entry_notes' => true,
    'gravityforms_view_settings'    => false,

    // Safe Redirect Manager
    'srm_manage_redirects' => true,

    // ManualPress
    'manualpress_view_manual' => true,

    // The Events Calendar
    'delete_others_tribe_events'        => true,
    'delete_private_tribe_events'       => true,
    'delete_published_tribe_events'     => true,
    'delete_tribe_events'               => true,
    'edit_others_tribe_events'          => true,
    'edit_private_tribe_events'         => true,
    'edit_published_tribe_events'       => true,
    'edit_tribe_events'                 => true,
    'publish_tribe_events'              => true,
    'read_private_tribe_events'         => true,
    'delete_others_tribe_organizers'    => true,
    'delete_private_tribe_organizers'   => true,
    'delete_published_tribe_organizers' => true,
    'delete_tribe_organizers'           => true,
    'edit_others_tribe_organizers'      => true,
    'edit_private_tribe_organizers'     => true,
    'edit_published_tribe_organizers'   => true,
    'edit_tribe_organizers'             => true,
    'publish_tribe_organizers'          => true,
    'read_private_tribe_organizers'     => true,
    'delete_others_tribe_venues'        => true,
    'delete_private_tribe_venues'       => true,
    'delete_published_tribe_venues'     => true,
    'delete_tribe_venues'               => true,
    'edit_others_tribe_venues'          => true,
    'edit_private_tribe_venues'         => true,
    'edit_published_tribe_venues'       => true,
    'edit_tribe_venues'                 => true,
    'publish_tribe_venues'              => true,
    'read_private_tribe_venues'         => true,
  ));
}


// Disables the `Other Roles` section that the User Role Editor plugin adds into the user edit screen
add_filter('ure_show_additional_capabilities_section', '__return_false', 99);

// Disables the dropdowns for bulk adding roles that the User Role Editor plugin adds to the top of the users list table
add_filter('ure_bulk_grant_roles', '__return_false', 99);


// Removes roles that we don't use that are added by plugins
add_action('init', 'remove_unwanted_roles');
function remove_unwanted_roles() {
  $unwanted_roles = array(
    'wpseo_editor',
    'wpseo_manager'
  );

  foreach($unwanted_roles as $role) {
    if (get_role($role)) {
      remove_role($role);
    }
  }
}


// Disables dashboard update notifications for users that can't do the update
add_action('admin_menu', 'hide_wp_update_notification');
function hide_wp_update_notification() {
  if (!current_user_can('update_core')) {
    remove_action('admin_notices', 'update_nag', 3);
  }
}


// Removes some subpages for users who don't need them
add_action('admin_menu', 'remove_unneeded_subpages');
function remove_unneeded_subpages() {
  if (!current_user_can('switch_themes')) {
    $theme_customizer_url = add_query_arg(
      'return',
      urlencode(remove_query_arg(wp_removable_query_args(), wp_unslash($_SERVER['REQUEST_URI']))),
      'customize.php'
    );

    remove_submenu_page('themes.php', $theme_customizer_url);         // Theme customization
    remove_submenu_page('themes.php', 'themes.php');                  // Theme selection
    remove_submenu_page('themes.php', 'widgets.php');                 // Theme widgets
    remove_submenu_page('themes.php', 'edit.php?post_type=wp_block'); // Theme patterns
  }
}
?>