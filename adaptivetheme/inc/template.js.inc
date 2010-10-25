<?php
// $Id$

$path = drupal_get_path('theme', 'adaptivetheme') .'/js/';
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
if (theme_get_setting('equal_heights_sidebars')) {
  drupal_add_js($path .'equalize-columns.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}
if (theme_get_setting('equal_heights_gpanels')) {
  drupal_add_js($path .'equalize-gpanels.js',
    array(
      'type' => 'file',
      'scope' => 'header',
      'weight' => 999,
    )
  );
}
