<?php

use Drupal\at_core\Library\SystemLibraries;

// Libraries Form
//----------------------------------------------------------------------
$library_drupal = new SystemLibraries();
$core_libraries = ($library_drupal->getDrupalCoreSystemLibraries());

$form['libraries'] = array(
  '#type' => 'details',
  '#title' => t('Libraries'),
  '#group' => 'at_settings',
);

// Enable layouts, this is a master setting that totally disables the page layout system.
$form['libraries']['enable']['settings_libraries_enable'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable Libraries'),
  '#default_value' => theme_get_setting('settings.libraries_enable', $theme),
);
$form['libraries']['enable']['libraries_disabled'] = array(
  '#type' => 'container',
  '#markup' => t('Enable libraries to add theme and Drupal core libraries (such as jQuery plugins). See the Help section "Libraries" for more information about libraries. Currently all libraries are disabled and no user selected libraries are loading.'),
  '#states' => array(
    'visible' => array('input[name="settings_libraries_enable"]' => array('checked' => FALSE)),
  ),
);

// Theme Libraries
// Theme libs needs to be rewritten, auto detect features are now removed awaiting https://drupal.org/node/2148679
/*
$theme_name = str_replace('_', ' ', ucfirst($theme));
$form['libraries']['theme'] = array(
  '#type' => 'details',
  '#title' => t('!theme Libraries', array('!theme' => $theme_name)),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_libraries_enable"]' => array('checked' => TRUE)),
  ),
);
*/


// Drupal Core Libraries
$form['libraries']['drupal_core'] = array(
  '#type' => 'details',
  '#title' => t('Drupal Core Libraries', array('!theme' => $theme)),
  '#description' => t('Dependancies are loaded automatically whether checked or not. Please see the Help section on Drupal core libraries for more advice on dependancies.'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_libraries_enable"]' => array('checked' => TRUE)),
  ),
);

foreach ($core_libraries as $key => $value) {
  $core_library_setting = str_replace('.', '_', strtolower($key));
  $version = isset($value['version']) ? $value['version'] : '';
  $website = isset($value['website']) ? $value['website'] : '';

  $form['libraries']['drupal_core']["settings_drupal_core_$core_library_setting"] = array(
    '#type' => 'checkbox',
    '#title' => t('!title', array('!title' => $value['title'])),
    '#default_value' => theme_get_setting("settings.drupal_core_$core_library_setting", $theme),
  );
  $form['libraries']['drupal_core']["settings_drupal_core_info_$core_library_setting"] = array(
    '#type' => 'container',
    '#markup' => t('<small>Version: !version <a href="!website" target="_blank">!website</a></small>', array('!version' => $version, '!website' => $website)),
    '#attributes' => array(
      'classes' => array('core-library-info'),
    ),
    '#states' => array(
      'visible' => array(
         "input[name=\"settings_drupal_core_$core_library_setting\"]" => array('checked' => TRUE),
      ),
    ),
  );
}
