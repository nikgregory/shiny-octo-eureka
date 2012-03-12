<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */
if ((theme_get_setting('rebuild_theme_data') == TRUE) && (!defined('MAINTENANCE_MODE'))) {
  system_rebuild_theme_data();
  drupal_theme_rebuild();
}
global $path_to_at_core;
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');
include_once($path_to_at_core . '/inc/template.theme.inc');
include_once($path_to_at_core . '/inc/template.alter.inc');
include_once($path_to_at_core . '/inc/template.preprocess.inc');
include_once($path_to_at_core . '/inc/template.process.inc');