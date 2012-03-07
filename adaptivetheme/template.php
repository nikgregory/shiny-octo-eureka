<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */
if ((theme_get_setting('rebuild_theme_data') == TRUE) && (!defined('MAINTENANCE_MODE'))) {
  system_rebuild_theme_data();
  drupal_theme_rebuild();
}
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/google.web.fonts.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.helpers.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.process.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.alter.inc');