<?php

use Drupal\Component\Utility\String;

// Support legacy browsers.
$form['legacy_browsers'] = array(
  '#type' => 'details',
  '#title' => t('Legacy Browsers'),
  '#group' => 'at_settings',
  '#description' => t('Support legacy browsers such as IE8. There is not other support offered for IE8 - if you want the layout and all styles to be applied, you need these turned on. Without this on IE8 will display in one column and likely break if you are using the Responsive menus extension.'),
);

// Show page suggestions.
$form['legacy_browsers']['settings_legacy_browser_polyfills'] = array(
  '#type' => 'checkbox',
  '#title' => t('Load Polyfills'),
  '#description' => t('Loads respond.js (mainly to support the Layout and Responsive Menu options) and Selectivrz (mainly for the Layout and UIKit styles).'),
  '#default_value' => theme_get_setting('settings.legacy_browser_polyfills', $theme),
);
