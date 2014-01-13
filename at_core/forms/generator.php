<?php

use Drupal\at_core\Theme\ThemeSettingsInfo;

$theme_name =  '';
$themeSettingsInfo = new ThemeSettingsInfo($theme);
$sourceThemeOptions = $themeSettingsInfo->baseThemeOptions();

$form['generate'] = array(
  '#type' => 'details',
  '#title' => 'Generate Themes',
  '#group' => 'atsettings',
  '#description' => t('Use this form to generate a new sub-theme. See the Help tab for information.'),
  '#tree' => TRUE,
);

// Friendly name.
$form['generate']['generate_friendly_name'] = array(
  '#type' => 'textfield',
  '#title' => t('Enter a new theme name'),
  '#maxlength' => 30,
  '#size' => 30,
  '#required' => TRUE,
  '#default_value' => '',
  '#description' => t('A unique "friendly" name. Letters, spaces and underscores only - numbers and all other chars are stripped or converted.'),
);

// Machine name.
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

$generate_type_options = array(
  'none' => t(' --  select type  -- '),
  'at_standard' => t('AT Standard starterkit'),
  'at_minimal' => t('AT Minimal starterkit'),
);
if (!empty($sourceThemeOptions)) {
  $generate_type_options = array(
    'none' => t(' --  select type  -- '),
    'at_standard' => t('AT Standard starterkit'),
    'at_minimal' => t('AT Minimal starterkit'),
    'at_clone' => t('Clone'),
    'at_skin' => t('Skin'),
  );
}

$form['generate']['generate_type'] = array(
  '#type' => 'select',
  '#title' => t('Type'),
  '#required' => TRUE,
  '#options' => $generate_type_options,
  '#default_value' => 'none',
);
$form['generate']['generate_clone_source'] = array(
  '#type' => 'select',
  '#title' => t('Theme to clone'),
  '#options' => $sourceThemeOptions,
  '#default_value' => '',
  '#description' => t('Clones are direct copies of existing sub-themes. You should use a unique name.'),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_clone')),
  ),
);
$form['generate']['generate_skin_base'] = array(
  '#type' => 'select',
  '#title' => t('Skin base theme'),
  '#options' => $sourceThemeOptions,
  '#default_value' => '',
  '#description' => t('Skins are sub-sub-themes. Select an existing sub-theme to use as the base.'),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_skin')),
  ),
);

// Templates
$form['generate']['generate_templates'] = array(
  '#type' => 'checkbox',
  '#title' => t('Include templates'),
  '#default_value' => 0,
  '#description' => t('Include copies of AT Core templates (page.html.twig is always included regardless of this setting).'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_minimal'),
        array('value' => 'at_standard'),
      ),
    ),
  ),
);

// Description.
$form['generate']['generate_description'] = array(
  '#type' => 'textfield',
  '#title' => t('Enter a brief description'),
  '#default_value' => '',
  '#description' => t('Descriptions are used on the Appearance list page. If nothing is entered a generic description is used.'),
);

// Version.
$form['generate']['generate_version'] = array(
  '#type' => 'textfield',
  '#title' => t('Enter a version string'),
  '#default_value' => '',
  '#description' => t('Numbers, hyphens and periods only. E.g. 8.0-1.0. If no version is entered a default is used.'),
);
