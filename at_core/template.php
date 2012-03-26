<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */

// Set globals for often used stuff.
global $theme_name, $path_to_at_core;

// $theme_key is not always what we need, so for consistancy AT uses its own
// global $theme_name and depending on the context re-populates it as needed.
$theme_name = $GLOBALS['theme_key'];

// Path to AT core.
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');

// Include the most essential files to make this mofo run.
include_once($path_to_at_core . '/inc/config.inc');              // holds config arrays for the page layout, panels and fonts
include_once($path_to_at_core . '/inc/template.helpers.inc');    // drupal_add_css() wrappers and seldom used body class generators
include_once($path_to_at_core . '/inc/template.theme.inc');      // theme function overrides
include_once($path_to_at_core . '/inc/template.alter.inc');      // hook_alters
include_once($path_to_at_core . '/inc/template.preprocess.inc'); // all preprocess functions
include_once($path_to_at_core . '/inc/template.process.inc');    // all process functions