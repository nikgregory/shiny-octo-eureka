<?php // $Id$
// adaptivethemes.com 62

/**
 * @file template.theme-js.inc
 * Load Javascript files as required.
 */

$path_to_js_core = drupal_get_path('theme', 'adaptivetheme') .'/js/core/';

// Load equalizeheights.js
if (theme_get_setting('equal_heights_sidebars') || theme_get_setting('equal_heights_blocks')) {
  drupal_add_js($path_to_js_core .'jquery.equalizeheights.js', 'theme', 'header', FALSE, TRUE, TRUE);
  if (theme_get_setting('equal_heights_sidebars')) {
     drupal_add_js($path_to_js_core .'equalize-columns.js', 'theme', 'header', FALSE, TRUE, TRUE); 
  }
  if (theme_get_setting('equal_heights_blocks')) {
    drupal_add_js($path_to_js_core .'equalize-blocks.js', 'theme', 'header', FALSE, TRUE, TRUE);
  }
}

// Load Firebug lite
if (theme_get_setting('load_firebug_lite')) {
  drupal_add_js($path_to_js_core .'firebug.lite.compressed.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

// Use horizontal login block overlabel js
if (theme_get_setting('horizontal_login_block_overlabel')) {
  drupal_add_js($path_to_js_core .'jquery.overlabel.js', 'theme', 'header', FALSE, TRUE, TRUE);
}