<?php

// Advanced settings.
$form['advanced_settings'] = array(
  '#type' => 'details',
  '#title' => t('Advanced settings'),
  '#weight' => -201,
  '#open' => TRUE,
);

$form['advanced_settings']['at_settings'] = array(
  '#type' => 'vertical_tabs',
);

// Extensions
include_once($at_core_path . '/forms/ext/extensions.php');

// Extensions master toggle.
if ($form['ext']['ext_settings']['settings_enable_extensions']['#default_value'] == 1) {

  // Include fonts.inc by default.
  include_once($at_core_path . '/forms/ext/fonts.inc');

  $extensions_array = array(
    'fonts',
    'titles',
    'images',
    'touch_icons',
    'libraries',
    'custom_css',
    'markup_overrides',
    'devel',
  );

  // get form values
  $values = $form_state->getValues();

  foreach ($extensions_array as $extension) {
    $form_state_value = isset($values["settings_enable_$extension"]);
    $form_value = $form['ext']['ext_settings']['enable_ext']["settings_enable_$extension"]['#default_value'];
    if (($form_state_value && $form_state_value == 1) ||
       (!$form_state_value && $form_value == 1)) {
      include_once($at_core_path . '/forms/ext/' . $extension . '.php');
    }
  }
}

// Help (sub-theme). TODO: rethink where help goes.
// include_once($at_core_path . '/forms/help_subtheme.php');

// Submit button for advanced settings.

$form['advanced_settings']['actions'] = array(
  '#type' => 'actions',
  '#attributes' => array('class' => array('submit--advanced-settings')),
);
$form['advanced_settings']['actions']['submit'] = array(
  '#type' => 'submit',
  '#value' => t('Save advanced settings'),
  '#validate'=> array('at_core_validate_advanced_settings'),
  '#submit'=> array('at_core_submit_advanced_settings'),
  '#attributes' => array('class' => array('button--primary')),
  '#weight' => -10000,
);


//$form['#validate'][] = 'at_core_validate_advanced_settings';
//$form['#submit'][] = 'at_core_submit_advanced_settings';

//$form['actions']['submit']['#validate'][] = 'at_core_validate_advanced_settings';
//$form['actions']['submit']['#submit'][] = 'at_core_submit_advanced_settings';


// Submit handlers for the advanced settings.
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/advanced_settings_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/advanced_settings_submit.php');






















