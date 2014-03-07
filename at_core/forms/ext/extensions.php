<?php

/**
 * @file
 * Generate settings for the Extensions form.
 */

$form['ext'] = array(
  '#type' => 'details',
  '#title' => t('Extensions'),
  '#group' => 'at_settings',
);

$form['ext']['ext_settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('Extensions'),
);


$form['ext']['ext_settings']['description'] = array(
  '#markup' => t('<h3>Enable Additional Features</h3><p>Extensions include many extras for configuring and styling your site. Enabled extensions will appear in new vertical tabs below the main setting. Extensions can be enabled or disabled individually, so you only load what you need. Disabling the global setting will globally disable most extensions, but not enable them.</p><p>Once you have enabled an extension you will need to configure its settings, then save the theme settings again (to save its configuration).</p>'),
);


// Enable extensions, the extension settings are hidden by default to ease the
// the UI clutter, this setting is also used as a global enable/disable for any
// extension in other logical operations.
$form['ext']['ext_settings']['settings_enable_extensions'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable extensions <small>(global setting)</small>'),
  '#default_value' => theme_get_setting('settings.enable_extensions', $theme),
);


$form['ext']['ext_settings']['enable_ext'] = array(
  '#type' => 'fieldset',
  '#title' => t('Extensions'),
  '#states' => array(
    'invisible' => array('input[name="settings_enable_extensions"]' => array('checked' => FALSE)),
  ),
);

// Fonts
$form['ext']['ext_settings']['enable_ext']['settings_enable_fonts'] = array(
  '#type' => 'checkbox',
  '#title' => t('Fonts'),
  '#default_value' => theme_get_setting('settings.enable_fonts', $theme),
  '#description' => t('Apply fonts to site elements (page, titles, headings, menus and custom selectors). Supports websafe, custom and Google fonts, and <a href="!link" target="_blank">@font-your-face</a> integration.', array('!link' => 'http://drupal.org/project/fontyourface')),
);

// Title styles
$form['ext']['ext_settings']['enable_ext']['settings_enable_titles'] = array(
  '#type' => 'checkbox',
  '#title' => t('Title styles'),
  '#default_value' => theme_get_setting('settings.enable_titles', $theme),
  '#description' => t('Set case, weight and alignment for site name and slogan, page, node, block and comment titles.'),
);

// Image alignment and captions
$form['ext']['ext_settings']['enable_ext']['settings_enable_images'] = array(
  '#type' => 'checkbox',
  '#title' => t('Image alignment and captions'),
  '#default_value' => theme_get_setting('settings.enable_images', $theme),
  '#description' => t('Set default image alignment, image captions and teaser image view.'),
);

// Touch icons
$form['ext']['ext_settings']['enable_ext']['settings_enable_touch_icons'] = array(
  '#type' => 'checkbox',
  '#title' => t('Touch icons'),
  '#description' => t('Check this setting if you want to use touch icons.'),
  '#default_value' => theme_get_setting('settings.enable_touch_icons', $theme),
);

// Libraries
$form['ext']['ext_settings']['enable_ext']['settings_enable_libraries'] = array(
  '#type' => 'checkbox',
  '#title' => t('Libraries'),
  '#description' => t('Load Drupal core libraries (JavaScripts).'),
  '#default_value' => theme_get_setting('settings.enable_libraries', $theme),
);

// Custom CSS
$form['ext']['ext_settings']['enable_ext']['settings_enable_custom_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Custom CSS'),
  '#description' => t('Enter custom CSS rules for minor adjustment to your theme. Useful if you do not want to build a sub-theme and need modifications to survive theme upgrades if required.'),
  '#default_value' => theme_get_setting('settings.enable_custom_css', $theme),
);

// Markup overrides
$form['ext']['ext_settings']['enable_ext']['settings_enable_markup_overrides'] = array(
  '#type' => 'checkbox',
  '#title' => t('Markup overrides'),
  '#description' => t('Many additional options for adding, removing, hiding and changing site elements and markup - includes:
    <ul>
      <li>Breadcrumb Settings - hide, hide the home link, configure seperator, append the page title</li>
      <li>Login Block - hide links, hide OpenID links, horizontal login block</li>
      <li>Hide comment titles</li>
      <li>Remove menu link titles (tool tips)</li>
      <li>Accessibility settings - set the skip navigation target ID, use extra fieldsets in the advanced search form</li>
      <li>Add rel=author to user names</li>
    </ul>
    '),
  '#default_value' => theme_get_setting('settings.enable_markup_overrides', $theme),
);
