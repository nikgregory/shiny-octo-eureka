<?php

/**
 * Generate form elements for the Extension settings.
 */

// Submit handlers for the advanced settings.
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_submit.php');

$settings_extensions_form_open = theme_get_setting('settings.extensions_form_open', $theme);

$form['docs'] = [
  '#type' => 'container',
  '#markup' => t('<a class="at-docs" href=":docs" target="_blank" title="External link: docs.adaptivethemes.com">View online documentation <svg class="docs-ext-link-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1408 928v320q0 119-84.5 203.5t-203.5 84.5h-832q-119 0-203.5-84.5t-84.5-203.5v-832q0-119 84.5-203.5t203.5-84.5h704q14 0 23 9t9 23v64q0 14-9 23t-23 9h-704q-66 0-113 47t-47 113v832q0 66 47 113t113 47h832q66 0 113-47t47-113v-320q0-14 9-23t23-9h64q14 0 23 9t9 23zm384-864v512q0 26-19 45t-45 19-45-19l-176-176-652 652q-10 10-23 10t-23-10l-114-114q-10-10-10-23t10-23l652-652-176-176q-19-19-19-45t19-45 45-19h512q26 0 45 19t19 45z"/></svg></a>', [':docs' => ' //docs.adaptivethemes.com/']),
  '#weight' => -1000,
];

$form['extensions'] = [
  '#type' => 'details',
  '#title' => t('Extensions'),
  '#weight' => -201,
  '#open' => $settings_extensions_form_open,
  '#attributes' => ['class' => ['extension-settings', 'clearfix']],
];

// Enable extensions, the extension settings are hidden by default to ease the
// the UI clutter, this setting is also used as a global enable/disable for any
// extension in other logical operations.
$form['extensions']['extensions-enable-container'] = [
  '#type' => 'container',
  '#attributes' => ['class' => ['subsystem-enabled-container', 'layouts-column-onequarter', 'visually-hidden']], // TODO make it visible when form accounts for base theme config
];

$form['extensions']['extensions-enable-container']['settings_extensions_form_open'] = [
  '#type' => 'checkbox',
  '#title' => t('Keep open'),
  '#default_value' => $settings_extensions_form_open,
  '#states' => [
    'disabled' => ['input[name="settings_enable_extensions"]' => ['checked' => FALSE]],
  ],
];

$form['extensions']['extensions-enable-container']['settings_enable_extensions'] = [
  '#type' => 'checkbox',
  '#title' => t('Enable'),
  '#default_value' => 1, // TODO never can be disabled unless base theme is also disabled
  //'#default_value' => theme_get_setting('settings.enable_extensions', $theme),
];

$form['extensions']['extension_settings'] = [
  '#type' => 'vertical_tabs',
  '#attributes' => ['class' => ['clearfix']],
  '#states' => [
    'visible' => [':input[name="settings_enable_extensions"]' => ['checked' => TRUE]],
  ],
];

// Extensions
$form['enable_extensions'] = [
  '#type' => 'details',
  '#title' => t('Enable extensions'),
  '#group' => 'extension_settings',
];

$form['enable_extensions']['description'] = [
  '#markup' => t('<p>Extensions are settings for configuring and styling your site. Enabled extensions appear in new vertical tabs.</p>'),
];

// Responsive Menus
$form['enable_extensions']['settings_enable_responsive_menus'] = [
  '#type' => 'checkbox',
  '#title' => t('Responsive menus'),
  '#description' => t('Select responsive menu styles and breakpoints.'),
  '#disabled' => TRUE, // TODO allow toggles off when base theme settings are accounted for
  '#default_value' => 1, // TODO never can be disabled unless base theme is also disabled
  //'#default_value' => theme_get_setting('settings.enable_responsive_menus', $theme),
];

// Shortcodes
$form['enable_extensions']['settings_enable_shortcodes'] = [
  '#type' => 'checkbox',
  '#title' => t('Shortcode CSS Classes'),
  '#description' => t('Adjust and enhance theme styles with pre-styled CSS classes.'),
  '#default_value' => theme_get_setting('settings.enable_shortcodes', $theme),
];

// Mobile blocks
$form['enable_extensions']['settings_enable_mobile_blocks'] = [
  '#type' => 'checkbox',
  '#title' => t('Mobile Blocks'),
  '#description' => t('Show or hide blocks in mobile devices.'),
  '#default_value' => theme_get_setting('settings.enable_mobile_blocks', $theme),
];

// Extensions master toggle.
if (theme_get_setting('settings.enable_extensions', $theme) == 1) {
  $extensions_array = [
    'responsive_menus',
    'shortcodes',
    'mobile_blocks',
  ];

  // Get form values.
  $values = $form_state->getValues();

  foreach ($extensions_array as $extension) {
    $form_state_value = isset($values["settings_enable_$extension"]) ? $values["settings_enable_$extension"] : 0;
    $form_value = isset($form['enable_extensions']["settings_enable_$extension"]['#default_value']) ? $form['enable_extensions']["settings_enable_$extension"]['#default_value'] : 0;
    if (($form_state_value && $form_state_value === 1) || (!$form_state_value && $form_value === 1)) {
      include_once($at_core_path . '/forms/ext/' . $extension . '.php');
    }
  }
}

// Submit button for advanced settings.
$form['extensions']['actions'] = [
  '#type' => 'actions',
  '#attributes' => ['class' => ['submit--advanced-settings']],
];
$form['extensions']['actions']['submit'] = [
  '#type' => 'submit',
  '#value' => t('Save extension settings'),
  '#validate'=> ['at_core_validate_extension_settings'],
  '#submit'=> ['at_core_submit_extension_settings'],
  '#attributes' => ['class' => ['button--primary']],
];
