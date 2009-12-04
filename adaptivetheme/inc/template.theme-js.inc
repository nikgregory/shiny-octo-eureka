<?php // $Id$
// adaptivethemes.com 62

/**
 * @file template.theme-js.inc
 * Load Javascript files as required.
 */

$path_to_js_core = drupal_get_path('theme', 'adaptivetheme') .'/js/';

$regions = array(
  'leaderboard',
  'header', 
  'secondary-content',
  'content-top',
  'content-bottom',
  'tertiary-content',
  'footer'
);

foreach ($regions as $region) {
  $setting = theme_get_setting('equalize_'. $region);
  $settings[] = $setting;
  if ($setting == 1) {
  	$active_regions[] = $region;
  }
}
$region_settings = $settings;
$active_regions_list = $active_regions;

// Load equal height blocks
if (count($active_regions)>0) {
  drupal_add_js(array('active_regions' => $active_regions_list), 'setting');
  drupal_add_js($path_to_js_core .'equalize-blocks.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

// Load equal height sidebars
if (theme_get_setting('equal_heights_sidebars')) {
  drupal_add_js($path_to_js_core .'equalize-columns.js', 'theme', 'header', FALSE, TRUE, TRUE); 
}

// Load Firebug lite
if (theme_get_setting('load_firebug_lite')) {
  drupal_add_js($path_to_js_core .'firebug.lite.compressed.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

// Use horizontal login block overlabel js
if (theme_get_setting('horizontal_login_block_overlabel')) {
  drupal_add_js($path_to_js_core .'jquery.overlabel.js', 'theme', 'header', FALSE, TRUE, TRUE);
}
