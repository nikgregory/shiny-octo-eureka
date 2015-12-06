<?php

/**
 * @file
 * Generate settings for Legacy Browsers.
 */

$form['legacy-browsers'] = array(
  '#type' => 'details',
  '#title' => t('Legacy Browsers'),
  '#group' => 'extension_settings',
);

// Support legacy browsers
//----------------------------------------------------------------------
$form['legacy-browsers']['legacy-browser-polyfills'] = array(
  '#type' => 'container',
  '#markup' => t('<p>By checking this setting two poly-fills will be loaded for IE8 and below:</p><ol><li><b>Respond.js:</b> to support the Layout and Responsive Menu options.</li><li><b>Selectivrz:</b> for the Layout and UIKit styles which use many CSS3 selectors.</li></ol><p>Additionally the YUI3 library will load for IE8 only. This is for Selectivrz because Drupal core uses jQuery 2, which does not support IE8. Combined this should work to give basic layout and most styles, however no guarantees are given.</p><p>Without this setting IE8 will display in one column and some styling will fail. The advice is to NOT turn this on, it loads a lot of JavaScript for IE8 and below, it may be better to simply allow those browsers to fail a bit, rather than throwing a huge chunk of JS at them.</p>'),
);

// Show page suggestions.
$form['legacy-browsers']['legacy-browser-polyfills']['settings_legacy_browser_polyfills'] = array(
  '#type' => 'checkbox',
  '#title' => t('Loads poly-fills to support IE8'),
  '#default_value' => theme_get_setting('settings.legacy_browser_polyfills', $theme),
);
