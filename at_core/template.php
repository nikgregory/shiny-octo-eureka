<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE OR ANY OF THE INCLUDED FILES.
 */

// Set globals for often used stuff.
global $theme_name, $path_to_at_core;

// $theme_key is not always what we need, so for consistancy AT uses its own global $theme_name
// and depending on the context populates it as needed. Know that when you see $theme_name it
// always means the currently active theme.
$theme_name = $GLOBALS['theme'];

// Absolute path to AT core, used for include files only.
$path_to_at_core = DRUPAL_ROOT . '/' . drupal_get_path('theme', 'adaptivetheme');

// Include the most essential files to make this mofo run.
include_once($path_to_at_core . '/inc/template.theme.inc');      // theme function overrides
include_once($path_to_at_core . '/inc/template.alter.inc');      // hook_alters
include_once($path_to_at_core . '/inc/template.preprocess.inc'); // all preprocess functions
include_once($path_to_at_core . '/inc/template.process.inc');    // all process functions