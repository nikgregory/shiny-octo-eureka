<?php

/**
 * @file
 * Generate form elments for the Polyfills settings.
 *
 * TODO: review documentation and provide links to help pages.
 */

$form['at-settings']['polyfills'] = array(
  '#type' => 'fieldset',
  '#title' => t('Polyfills'),
  '#description' => t('<h3>Polyfills</h3><p>Here you can enable commonly used Polyfills supplied with the core theme. Adaptivetheme also supports loading <a href="!docspage" target="_blank">scripts and CSS for Internet Explorer</a> using your sub-themes info file.', array('!docspage' => 'http://adaptivethemes.com/documentation/ie-styles-and-scripts')),
);

$form['at-settings']['polyfills']['fills'] = array(
  '#type' => 'fieldset',
  '#title' => t('Polyfills'),
);

// HTML5 shiv
$form['at-settings']['polyfills']['fills']['load_html5js'] = array(
  '#type' => 'checkbox',
  '#title' => t('HTML5 support in IE'),
  '#description' => t('By checking this setting the site will load the <a href="!link" target="_blank">html5shiv</a>. Turning this off will be bad news for IE6-8.', array('!link' => '//github.com/aFarkas/html5shiv')),
  '#default_value' => at_get_setting('load_html5js'),
);

// Rounding error fix (CSS)
$form['at-settings']['polyfills']['fills']['load_ltie8css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Fix CSS rounding errors in IE6-7'),
  '#description' => t('Load a stylesheet that fixes percentage rounding errors in IE6-7. You need this if you are using Gpanels or Panels layouts.'),
  '#default_value' => at_get_setting('load_ltie8css'),
);

// Respond.js
$form['at-settings']['polyfills']['fills']['load_respondjs'] = array(
  '#type' => 'checkbox',
  '#title' => t('Media query support for IE6-8'),
  '#description' => t('By checking this setting IE6, 7 and 8 will rely on <a href="!link" target="_blank">respond.js</a> to set the layout.', array('!link' => '//github.com/scottjehl/Respond', '!link2' => '//github.com/scottjehl/Respond/issues')),
  '#default_value' => at_get_setting('load_respondjs'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);

// Scalefix
$form['at-settings']['polyfills']['fills']['load_scalefixjs'] = array(
  '#type' => 'checkbox',
  '#title' => t('Scalefix for iOS'),
  '#description' => t('Fixes the iOS Orientationchange zoom bug.'),
  '#default_value' => at_get_setting('load_scalefixjs'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);
