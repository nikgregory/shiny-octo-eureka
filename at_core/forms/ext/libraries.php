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

// Drupal Core Libraries
$form['libraries']['drupal_core'] = array(
  '#type' => 'details',
  '#title' => t('Drupal Core Libraries'),
  '#description' => t('Dependancies are loaded automatically whether checked or not. Please see the Help section on Drupal core libraries for more advice on dependancies.'),
  '#collapsed' => FALSE,
  '#collapsible' => FALSE,
);

foreach ($core_libraries as $key => $value) {
  $core_library_setting = str_replace('.', '_', strtolower($key));
  $version = isset($value['version']) ? $value['version'] : '';
  $website = isset($value['remote']) ? $value['remote'] : '';

  $form['libraries']['drupal_core']["settings_drupal_core_$core_library_setting"] = array(
    '#type' => 'checkbox',
    '#title' => t('!title', array('!title' => $key)),
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
