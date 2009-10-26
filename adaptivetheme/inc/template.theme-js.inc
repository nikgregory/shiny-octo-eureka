<?php // $Id$
// adaptivethemes.com 62

/**
 * @file template.theme-js.inc
 * infrequently used js.
 */

// Load equalizeheights.js
if (theme_get_setting('equal_heights_sidebars') || theme_get_setting('equal_heights_blocks')) {
  $path_to_core = drupal_get_path('theme', 'adaptivetheme') .'/js/core/';
  drupal_add_js($path_to_core .'jquery.equalizeheights.js', 'theme', 'header', FALSE, TRUE, TRUE);
  if (theme_get_setting('equal_heights_sidebars')) {
     drupal_add_js($path_to_core .'equalize-columns.js', 'theme', 'header', FALSE, TRUE, TRUE); 
  }
  if (theme_get_setting('equal_heights_blocks')) {
    drupal_add_js($path_to_core .'equalize-blocks.js', 'theme', 'header', FALSE, TRUE, TRUE);
  }
}

// Load Firebug lite
if (theme_get_setting('load_firebug_lite')) {
  $path_to_core = path_to_theme() .'/js/core/';
  drupal_add_js($path_to_core .'firebug.lite.compressed.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

// Use horizontal login block overlabel js
if (theme_get_setting('horizontal_login_block_overlabel')) {
  $path_to_core = path_to_theme() .'/js/core/';
  drupal_add_js($path_to_core .'jquery.overlabel.js', 'theme', 'header', FALSE, TRUE, TRUE);
}