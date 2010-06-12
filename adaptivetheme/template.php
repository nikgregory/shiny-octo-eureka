<?php
// $Id$

/**
 * @file template.php
 * Its probably not a good idea to modify anything in this file.
 */

// Include custom functions.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.custom-functions.inc');

// Include theme overrides.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme-overrides.inc');

// Include some jQuery.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme-js.inc');

// Include process and preprocess functions.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.processes.inc');

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('rebuild_registry')) {
  drupal_theme_rebuild();
  drupal_set_message(t('The theme registry has been rebuilt. <a href="!link">Turn off</a> this feature on production websites.', array('!link' => url('admin/build/themes/settings/'. $GLOBALS['theme']))), 'warning');
}

// Add the color scheme stylesheets.
if (theme_get_setting('color_enable_schemes') == 'on') {
  drupal_add_css(drupal_get_path('theme', 'adaptivetheme') . '/css/theme/' . get_at_colors(), 'theme');
}
