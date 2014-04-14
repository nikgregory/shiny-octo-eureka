<?php

use Drupal\at_core\Library\SystemLibraries;
use Drupal\at_core\Library\ThemeLibraries;

// Libraries Form
//----------------------------------------------------------------------
$drupal_libs = new SystemLibraries();
$core_libraries = $drupal_libs->getDrupalCoreSystemLibraries();

$theme_libs = new ThemeLibraries($theme);
$theme_libraries = $theme_libs->getThemeTrailLibraries();


$form['libraries'] = array(
  '#type' => 'details',
  '#title' => t('Libraries'),
  '#group' => 'at_settings',
);

// Theme Trail Libraries
$form['libraries']['theme_libraries'] = array(
  '#type' => 'details',
  '#title' => t('Theme Libraries'),
  '#description' => t('Dependancies are loaded automatically whether checked or not.'),
  '#collapsed' => FALSE,
  '#collapsible' => FALSE,
);

//kpr($theme_libraries);

foreach ($theme_libraries as $theme_key => $theme_library) {

  unset($theme_library_key);

  $form['libraries']['theme_libraries'][$theme_key] = array(
    '#type' => 'details',
    '#title' => t($theme_key),
    '#collapsed' => FALSE,
    '#collapsible' => FALSE,
  );

  foreach ($theme_library as $theme_library_key => $theme_library_value) {

    $unset_array = array('at.window_size', 'at.addListener', 'at.slimbox2');
    if (in_array($theme_library_key, $unset_array)) {
      unset($theme_library_key);
    }


    if (isset($theme_library_key)) {
      $theme_library_setting = str_replace('.', '_', strtolower($theme_library_key));
      $version = isset($theme_library_value['version']) ? $theme_library_value['version'] : '';
      $website = isset($theme_library_value['remote']) ? $theme_library_value['remote'] : '';


      $form['libraries']['theme_libraries'][$theme_key]["settings_theme_library_$theme_library_setting"] = array(
        '#type' => 'checkbox',
        '#title' => t('!title', array('!title' => $theme_library_key)),
        '#default_value' => theme_get_setting("settings.theme_library_$theme_library_setting", $theme),
      );
      $form['libraries']['theme_libraries'][$theme_key]["settings_theme_library_info_$theme_library_setting"] = array(
        '#type' => 'container',
        '#markup' => t('<small>Version: !version <a href="!website" target="_blank">!website</a></small>', array('!version' => $version, '!website' => $website)),
        '#attributes' => array(
          'classes' => array('core-library-info'),
        ),
        '#states' => array(
          'visible' => array(
             "input[name=\"settings_theme_library_$theme_library_setting\"]" => array('checked' => TRUE),
          ),
        ),
      );
    }



  }


}









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
