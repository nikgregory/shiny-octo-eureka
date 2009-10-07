<?php

/**
 * Initialize theme settings
 */
if (is_null(theme_get_setting('user_notverified_display')) || theme_get_setting('rebuild_registry')) {

  // Auto-rebuild the theme registry during theme development.
  if (theme_get_setting('rebuild_registry')) {
    drupal_set_message(t('The theme registry has been rebuilt. <a href="!link">Turn off</a> this feature on production websites.', array('!link' => url('admin/build/themes/settings/'. $GLOBALS['theme']))), 'warning');
  }

  global $theme_key;
  // Get node types
  $node_types = node_get_types('names');

  /**
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the theme-settings.php file.
   */
  $defaults = array(
    'admin_user_links'                      => 1,
    'block_edit_links'                      => 1,
    'at_admin_hide_help'                    => 0,
  );

  // Make the default content-type settings the same as the default theme settings,
  // so we can tell if content-type-specific settings have been altered.
  $defaults = array_merge($defaults, theme_get_settings());

  // Get default theme settings.
  $settings = theme_get_settings($theme_key);

  // Don't save the toggle_node_info_ variables
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {
      unset($settings['toggle_node_info_'. $type]);
    }
  }
  // Save default theme settings
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );
  // Force refresh of Drupal internals
  theme_get_setting('', TRUE);
}


// Load collapsed js on blocks page
if (arg(2) == 'block') {
  drupal_add_js('misc/collapse.js', 'core', 'header', FALSE, TRUE, TRUE);
}

function adaptivetheme_admin_preprocess_page(&$vars, $hook) {
 // Check whether help is disabled
  if (theme_get_setting('at_admin_hide_help')) {
    unset($vars['help']);
  }
  
  // Admin welcome message with date for the admin theme.
  $welcome = t('Welcome') .' '. $user->name;
  $conjunction = ', '. t('it\'s') .' ';
  $todays_date = date("l, F d Y" , time()); 
  $vars['admin_welcome'] = $welcome . $conjunction . $todays_date;

}
