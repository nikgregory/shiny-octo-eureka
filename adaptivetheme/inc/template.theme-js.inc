<?php // $Id$
// adaptivethemes.com 62

/**
 * @file template.theme-js.inc
 * Load Javascript files as required.
 */

$path = drupal_get_path('theme', 'adaptivetheme') .'/js/';

// Load equal height blocks
$regions = array(
  'leaderboard',
  'header',
  'secondary-content',
  'highlight',
  'content-bottom',
  'tertiary-content',
  'footer'
);
foreach ($regions as $region) {
  $setting = theme_get_setting('equalize_'. $region);
  if ($setting == 1) {
    $active_regions[] = $region;
  }
}
if (!empty($active_regions)) {
  $active_regions_list = $active_regions;
  drupal_add_js(array('active_regions' => $active_regions_list),
    array(
      'type' => 'setting',
      'scope' => 'header',
    )
  );
  drupal_add_js($path .'equalize-blocks.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}

// Load equal height sidebars
if (theme_get_setting('equal_heights_sidebars')) {
  drupal_add_js($path .'equalize-columns.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}

// Load equal height gpanels
if (theme_get_setting('equal_heights_gpanels')) {
  drupal_add_js($path .'equalize-gpanels.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}

// Use horizontal login block overlabel js
if (theme_get_setting('horizontal_login_block_overlabel')) {
  drupal_add_js($path .'jquery.overlabel.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}
