<?php

use Drupal\at_core\Layout\LayoutSettings;
use Drupal\at_core\Theme\ThemeSettingsInfo;

$themeInfo = new ThemeSettingsInfo($theme);
$providers = $themeInfo->baseThemeInfo('base_themes');

// Unset at_core, it has no layouts.
unset($providers['at_core']);

// Push the current theme into the array, if it has layouts we need them.
$providers[$theme] = $theme;

// Bit of a hack, the classes were built to handle one provider, later it was
// decided to allow sub-themes to inherit base theme layouts, so we need to
// loop and instantiate foreach provider. The classes could be modified to
// take an array of providers.
foreach ($providers as $key => $provider_name) {
  $layout_config[$key] = new LayoutSettings($key);
  $options_data[$key]  = $layout_config[$key]->layoutOptions();
  $settings_data[$key] = $layout_config[$key]->settingsPrepareData();
}

$form['layouts'] = array(
  '#type' => 'details',
  '#title' => t('Layouts'),
  //'#description' => t('<h2>Layout</h2>'),
  '#collapsed'=> TRUE,
  '#attributes' => array('class' => array('clearfix')),
  '#weight' => -300,
);

// Enable layouts, this is a master setting that totally disables the page layout system.
$form['layouts']['layouts-enable-container'] = array(
  '#type' => 'container',
);

$form['layouts']['layouts-enable-container']['settings_layouts_enable'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable Layouts'),
  '#default_value' => theme_get_setting('settings.layouts_enable', $theme),
);

$form['layouts']['layouts-enable-container']['settings_layouts_disabled'] = array(
  '#type' => 'container',
  '#markup' => t('Enable and configure layouts. Diabling this option assumes your theme will load it\'s own CSS layout. Previously generated templates will continure to be used by your theme, however no CSS layout will load for those templates.'),
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => FALSE)),
  ),
);

// Include col 1 - template select
include_once($at_core_path . '/forms/layouts_template_select.php');

// Include col 2 - layout select
include_once($at_core_path . '/forms/layouts_layout_select.php');

// Include col 3 - advanced options
include_once($at_core_path . '/forms/layouts_advanced_options.php');

// Hidden setting to pass the default page.html.twig setting into form_state. We
// need this to get the "plugin" key for the page.html.twig layout to compare it to
// the plugin key for any suggestions - these must match, aka a suggestion cannot select
// a layout from another plugin. This criteria is imposed to prevent users blowing up
// their sites - Layouts in a plugin all have the same regions.
$form['layouts']['template_select']['select']['settings_template_suggestion_page'] = array(
  '#type' => 'hidden',
  '#default_value' => $default_layout,
);

// Submit button for layouts.
$form['layouts']['actions'] = array(
  '#type' => 'actions',
  '#attributes' => array('class' => array('submit--layout')),
);

$form['layouts']['actions']['submit'] = array(
  '#type' => 'submit',
  '#value' => t('Save layout settings'),
  '#validate'=> array('at_core_validate_layouts'),
  '#submit'=> array('at_core_submit_layouts'),
  '#button_type' => 'primary',
  '#states' => array(
    'disabled' => array('select[name="layout_type_select"]' => array('value' => 'disable_layout_generation')),
    'enabled' => array('input[name="delete_suggestions"]' => array('checked' => TRUE)),
  ),
);

// Layout submit handlers.
include_once(drupal_get_path('theme', 'at_core') . '/forms/layouts_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/layouts_submit.php');
