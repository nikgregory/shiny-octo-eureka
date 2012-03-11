<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */
if ((theme_get_setting('rebuild_theme_data') == TRUE) && (!defined('MAINTENANCE_MODE'))) {
  system_rebuild_theme_data();
  drupal_theme_rebuild();
}
$filepath = drupal_get_path('theme', 'adaptivetheme');
include_once($filepath . '/inc/template.helpers.inc');
include_once($filepath . '/inc/template.theme.inc');
include_once($filepath . '/inc/template.alter.inc');
include_once($filepath . '/inc/template.preprocess.inc');
include_once($filepath . '/inc/template.process.inc');