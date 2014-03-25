<?php

/**
 * @file
 * Generate form elments for the touch icons settings.
 */

$form['touch_icons'] = array(
  '#type' => 'details',
  '#title' => t('Touch Icons'),
  '#group' => 'at_settings',
);

$form['touch_icons']['touch_icons_settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('Touch Icons'),
  '#weight' => 10,
);

$form['touch_icons']['touch_icons_settings']['description'] = array(
  '#markup' => t('<h3>Touch Icons</h3><p>Different devices can support different sized touch icons - see the <a href="!apple_docs" target="_blank">iOS developer documentation</a>.</p><p>A plain set of icons that use the <a href="!icon_template" target="_blank">App Icon Template</a> are included by default.</p><p>Enter the path to each touch icon - paths must be relative to your theme folder. Leave the field empty to exclude an icon.</p>', array('!apple_docs' => 'https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html', '!icon_template' => 'http://appicontemplate.com')),
);

$form['touch_icons']['touch_icons_settings']['icon-paths'] = array(
  '#type' => 'fieldset',
  '#title' => t('Touch Icon Paths'),
);

// Default
$form['touch_icons']['touch_icons_settings']['icon-paths']['settings_icon_path_default'] = array(
  '#type' => 'textfield',
  '#title' => t('Default: 60x60'),
  '#description' => t('If you only enter a path for this size it will be used by all devices.'),
  '#field_prefix' => $theme_name . '/',
  '#default_value' => check_plain(theme_get_setting('settings.icon_path_default')),
  '#states' => array(
    'required' => array('input[name="enable_apple_touch_icons"]' => array('checked' => TRUE)),
  ),
);

// iPad (standard display)
$form['touch_icons']['touch_icons_settings']['icon-paths']['settings_apple_touch_icon_path_ipad'] = array(
  '#type' => 'textfield',
  '#title' => t('iPad: 76x76'),
  '#description' => t('Apple touch icon for older iPads with standard displays.'),
  '#field_prefix' => $theme_name . '/',
  '#default_value' => check_plain(theme_get_setting('settings.apple_touch_icon_path_ipad')),
);

// iPhone retina
$form['touch_icons']['touch_icons_settings']['icon-paths']['settings_apple_touch_icon_path_iphone_retina'] = array(
  '#type' => 'textfield',
  '#title' => t('iPhone Retina: 120x120'),
  '#description' => t('Apple touch icon for iPhones with retina displays.'),
  '#field_prefix' => $theme_name . '/',
  '#default_value' => check_plain(theme_get_setting('settings.apple_touch_icon_path_iphone_retina')),
);

// iPad retina
$form['touch_icons']['touch_icons_settings']['icon-paths']['settings_apple_touch_icon_path_ipad_retina'] = array(
  '#type' => 'textfield',
  '#title' => t('iPad Retina: 152x152'),
  '#description' => t('Apple touch icon for iPads with retina displays.'),
  '#field_prefix' => $theme_name . '/',
  '#default_value' => check_plain(theme_get_setting('settings.apple_touch_icon_path_ipad_retina')),
);

$form['touch_icons']['touch_icons_settings']['icon-paths']['settings_apple_touch_icon_precomposed'] = array(
  '#type' => 'checkbox',
  '#title' => t('Use apple-touch-icon-precomposed'),
  '#description' => t('Use precomposed if you want to remove icon effects in iOS6 or below. The default is <code>apple-touch-icon</code>. '),
  '#default_value' => check_plain(theme_get_setting('settings.apple_touch_icon_precomposed')),
);
