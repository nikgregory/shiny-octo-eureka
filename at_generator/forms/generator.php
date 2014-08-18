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
  '#title' => t('Theme name'),
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
  'at_standard' => t('Standard kit'),
  'at_minimal' => t('Minimal kit'),
);

if (!empty($sourceThemeOptions)) {
  $generate_type_options = array(
    'at_standard' => t('Standard kit'),
    'at_minimal' => t('Minimal kit'),
    'at_clone' => t('Clone'),
    'at_skin' => t('Skin'),
  );
}

$form['generate']['generate_type'] = array(
  '#type' => 'select',
  '#title' => t('Type'),
  '#required' => TRUE,
  '#options' => $generate_type_options,
);

$form['generate']['generate_type_description_standard_kit'] = array(
  '#type' => 'container',
  '#markup' => t('Standard kit includes the Site Builder layout plugin with 18 regions, 3 layout variants, and is built with SASS/Susy.'),
  '#attributes' => array('class' => array('generate-type__description')),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_standard')),
  ),
);

$form['generate']['generate_type_description_minimal_kit'] = array(
  '#type' => 'container',
  '#markup' => t('Minimal kit includes the Mimina layout plugin with 9 regions (similar to Stark), 1 layout variant, and is built with conventional CSS.'),
  '#attributes' => array('class' => array('generate-type__description')),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_minimal')),
  ),
);

$form['generate']['generate_clone_source'] = array(
  '#type' => 'select',
  '#title' => t('Clone source'),
  '#options' => $sourceThemeOptions,
  '#default_value' => '',
  '#description' => t('Clones are direct copies of existing sub-themes. You should use a unique name.'),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_clone')),
  ),
);

$form['generate']['generate_skin_base'] = array(
  '#type' => 'select',
  '#title' => t('Skin base'),
  '#options' => $sourceThemeOptions,
  '#default_value' => '',
  '#description' => t('Skins are sub-sub-themes. Select an existing sub-theme to use as the base.'),
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_skin')),
  ),
);

$form['generate']['generate_skin_sass'] = array(
  '#type' => 'checkbox',
  '#title' => t('Include SASS partials'),
  '#default_value' => 0,
  '#states' => array(
    'visible' => array('select[name="generate[generate_type]"]' => array('value' => 'at_skin')),
  ),
);

// Options
$form['generate']['options'] = array(
  '#type' => 'details',
  '#title' => t('Options'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
        array('value' => 'at_clone'),
        array('value' => 'skin'),
      ),
    ),
  ),
);

// Templates
$form['generate']['options']['generate_templates'] = array(
  '#type' => 'checkbox',
  '#title' => t('Templates'),
  '#default_value' => 0,
  '#description' => t('Include copies of Drupals front end twig templates (page.html.twig is always included regardless of this setting).'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
      ),
    ),
  ),
);

// UI Kit
$form['generate']['options']['generate_uikit'] = array(
  '#type' => 'checkbox',
  '#title' => t('UI Kit'),
  '#default_value' => 0,
  '#description' => t('Include the User Interfact Kit - a SASS/Compass UI Kit for Adativetheme and Drupal.'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
      ),
    ),
  ),
);

// Color module
$form['generate']['options']['generate_color'] = array(
  '#type' => 'checkbox',
  '#title' => t('Color Module'),
  '#default_value' => 0,
  '#description' => t('Provides Color module support - includes a starter color.inc file. Requires UI Kit'),
  '#states' => array(
    'disabled' => array(
      'input[name="generate[options][generate_uikit]"]' => array('checked' => FALSE),
    ),
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
      ),
    ),
  ),
);

// themeName.theme file
$form['generate']['options']['generate_themefile'] = array(
  '#type' => 'checkbox',
  '#title' => t('.theme'),
  '#default_value' => 0,
  '#description' => t('Include a [theme_name].theme file. Includes a subset of commonly used preprocess functions.'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
      ),
    ),
  ),
);

// theme-settings.php file
$form['generate']['options']['generate_themesettingsfile'] = array(
  '#type' => 'checkbox',
  '#title' => t('theme-settings.php'),
  '#default_value' => 0,
  '#description' => t('Include a theme-settings.php file. Includes skeleton code for the form alter, custom validation and submit functions.'),
  '#states' => array(
    'visible' => array(
      'select[name="generate[generate_type]"]' => array(
        array('value' => 'at_standard'),
        array('value' => 'at_minimal'),
      ),
    ),
  ),
);

// Description.
$form['generate']['options']['generate_description'] = array(
  '#type' => 'textfield',
  '#title' => t('Description'),
  '#default_value' => '',
  '#description' => t('Descriptions are used on the Appearance list page.'),
);

// Version.
$form['generate']['options']['generate_version'] = array(
  '#type' => 'textfield',
  '#title' => t('Version string'),
  '#default_value' => '',
  '#description' => t('Numbers, hyphens and periods only. E.g. 8.x-1.0'),
);
