<?php

/**
 * @file
 * Generate settings for the Extensions form.
 */

// Enable Extensions
$form['at-settings']['extend'] = array(
  '#type' => 'fieldset',
  '#title' => t('Extensions'),
  '#description' => t('<h3>Enable Additional Features</h3><p>Extensions include many extras for configuring and styling your site. Enabled extensions will appear in new vertical tabs below the main setting. Extensions can be enabled or disabled individually, so you only load what you need. Disabling the global setting will globally disable most extensions, but not enable them.</p><p>Once you have enabled an extension you will need to configure its settings, then save the theme settings again (to save its configuration).</p>'),
);

// Enable extensions, the extension settings are hidden by default to ease the
// the UI clutter, this setting is also used as a global enable/disable for any
// extension in other logical operations.
$form['at-settings']['extend']['enable_extensions'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable extensions <small>(global setting)</small>'),
  '#default_value' => at_get_setting('enable_extensions'),
);

$form['at-settings']['extend']['enable'] = array(
  '#type' => 'fieldset',
  '#title' => t('Extensions'),
  '#states' => array(
    'visible' => array('input[name="enable_extensions"]' => array('checked' => TRUE)),
  ),
);

// Fonts
$form['at-settings']['extend']['enable']['enable_font_settings'] = array(
  '#type' => 'checkbox',
  '#title' => t('Fonts'),
  '#default_value' => at_get_setting('enable_font_settings'),
  '#description' => t('Apply fonts to site elements (page, titles, headings). Supports websafe, custom and Google fonts, and <a href="!link" target="_blank">@font-your-face</a> integration.', array('!link' => 'http://drupal.org/project/fontyourface')),
);

// Title styles
$form['at-settings']['extend']['enable']['enable_heading_settings'] = array(
  '#type' => 'checkbox',
  '#title' => t('Title styles'),
  '#default_value' => at_get_setting('enable_heading_settings'),
  '#description' => t('Fine grain control over case, weight, alignment and CSS3 text shadows for page title, node, comment and block titles.'),
);

// Image alignment and captions
$form['at-settings']['extend']['enable']['enable_image_settings'] = array(
  '#type' => 'checkbox',
  '#title' => t('Image alignment and captions'),
  '#default_value' => at_get_setting('enable_image_settings'),
  '#description' => t('Set default image alignment and enable image captions.'),
);

// Apple touch icons
$form['at-settings']['extend']['enable']['enable_apple_touch_icons'] = array(
  '#type' => 'checkbox',
  '#title' => t('Apple touch icons'),
  '#description' => t('Check this setting if you want to use touch icons for iOS, Android and Nokia devices.'),
  '#default_value' => at_get_setting('enable_apple_touch_icons'),
);

// Unset CSS
$form['at-settings']['extend']['enable']['enable_exclude_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Unset CSS Files'),
  '#description' => t('Options to unset (exclude) CSS files from loading in your theme - includes settings for Core modules, CSS added by Libaries and your own declared exclusions (see your themes info file under "Stylesheets").'),
  '#default_value' => at_get_setting('enable_exclude_css'),
);

// Custom CSS
$form['at-settings']['extend']['enable']['enable_custom_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Custom CSS'),
  '#description' => t('Enter custom CSS rules for minor adjustment to your theme. Useful if you do not want to build a sub-theme and need modifications to survive theme upgrades if required.'),
  '#default_value' => at_get_setting('enable_custom_css'),
);

// Markup overrides
$form['at-settings']['extend']['enable']['enable_markup_overides'] = array(
  '#type' => 'checkbox',
  '#title' => t('Markup overrides'),
  '#description' => t('Many additional options for adding, removing, hiding and changing site elements and markup - includes settings for gutter widths, using 100% width wrappers in page templates, adding span elements to menu items, breadcrumb display and seperator, login block options, CSS classes, changing the skip link target id, hiding frontpage and comment titles, removing the the main content block from the front page, and hiding RSS icons.'),
  '#default_value' => at_get_setting('enable_markup_overides'),
);
