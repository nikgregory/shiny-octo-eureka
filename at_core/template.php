<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */

// Set a global for path_to_at_core, we use this everywhere
global $path_to_at_core;
$path_to_at_core = DRUPAL_ROOT . '/' . drupal_get_path('theme', 'adaptivetheme');

global $theme_name;
$theme_name = $GLOBALS['theme'];

// Include the most essential files to make this mofo run
include_once($path_to_at_core . '/inc/template.theme.inc');
include_once($path_to_at_core . '/inc/template.alter.inc');
include_once($path_to_at_core . '/inc/template.preprocess.inc');
include_once($path_to_at_core . '/inc/template.process.inc');