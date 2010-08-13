<?php
// $Id$

/**
 * @file template.php
 * Its probably not a good idea to modify anything in this file.
 */

// Include theme overrides.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme-overrides.inc');

// Include some jQuery.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme-js.inc');

// Include process and preprocess functions.
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.processes.inc');

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('rebuild_registry')) {
  drupal_theme_rebuild();
  drupal_set_message(t('The theme registry has been rebuilt. <a href="!link">Turn off</a> this feature on production websites.', array('!link' => url('admin/appearance/settings/'. $GLOBALS['theme']))), 'warning');
}
