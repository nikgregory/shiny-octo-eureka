<?php

use Drupal\at_core\Theme\ThemeSettingsInfo;

$theme_name =  '';
$themeSettingsInfo = new ThemeSettingsInfo($theme);
$baseThemeOptions  = ($themeSettingsInfo->baseThemeOptions());

// Generator settings
//----------------------------------------------------------------------
$form['generate'] = array(
  '#type' => 'details',
  '#title' => 'Generate Themes',
  '#group' => 'atsettings',
  '#description' => t('Use this form to generate a new theme. See the Help tab for information on Starterkit types and other documentation.'),
  '#tree' => TRUE,
);
$form['generate']['generate_friendly_name'] = array(
  '#type' => 'textfield',
  '#title' => t('Enter a new theme name'),
  '#maxlength' => 30,
  '#size' => 30,
  '#required' => TRUE,
  '#default_value' => '',
  '#description' => t('A unique name to construct the theme name and directory where the theme will be generated.'),
);
$form['generate']['generate_machine_name'] = array(
  '#type' => 'machine_name',
  '#maxlength' => 30,
  '#size' => 30,
  '#title' => t('Machine name'),
  '#required' => TRUE,
  '#field_prefix' => '',
  '#default_value' => '',
  '#machine_name' => array(
    'exists' => array($themeSettingsInfo, 'themeNameExists'), // class method for call_user_func()
    'source' => array('generate','generate_friendly_name'),
    'label' => t('Machine name'),
    'replace_pattern' => '[^a-z_]+',
    'replace' => '_',
  ),
);
if (!empty($baseThemeOptions)) {
  $form['generate']['generate_type'] = array(
    '#type' => 'select',
    '#title' => t('Starterkit'),
    '#required' => TRUE,
    '#options' => array(
      'at_standard' => t('Standard'),
      'at_minimal' => t('Minimal'),
      'at_skin' => t('Skin'),
    ),
    '#default_value' => 'at_standard',
  );
  $form['generate']['generate_base'] = array(
    '#type' => 'select',
    '#title' => t('Base theme'),
    '#options' => $baseThemeOptions,
    '#default_value' => '',
    '#description' => t('Skins are sub sub-themes. Select an existing sub-theme to use as the base.'),
    '#states' => array(
      'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_skin')),
    ),
  );
}
else {
  $form['generate']['generate_type'] = array(
    '#type' => 'select',
    '#title' => t('Choose sub-theme type'),
    '#required' => TRUE,
    '#options' => array(
      'at_standard' => t('Standard'),
      'at_minimal' => t('Minimal'),
    ),
    '#default_value' => 'at_standard',
  );
  $form['generate']['generate_base'] = array(
    '#type' => 'hidden',
    '#title' => t('Select base theme'),
    '#value' => 'at_core',
    '#description' => t('Standard and Minimal always use AT Core as their base.'),
  );
}
$form['generate']['generate_description'] = array(
  '#type' => 'textfield',
  '#title' => t('Enter a brief description'),
  '#default_value' => '',
  '#description' => t('Descriptions are used on the Appearance list page. If nothing is entered a generic description will be used.'),
);
