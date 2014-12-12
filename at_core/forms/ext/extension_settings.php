<?php

$settings_extensions_form_open = theme_get_setting('settings.extensions_form_open', $theme);

$form['extensions'] = array(
  '#type' => 'details',
  '#title' => t('Extensions'),
  '#weight' => -201,
  '#open' => $settings_extensions_form_open,
  '#attributes' => array('class' => array('extension-settings', 'clearfix')),
);

// Enable extensions, the extension settings are hidden by default to ease the
// the UI clutter, this setting is also used as a global enable/disable for any
// extension in other logical operations.
$form['extensions']['extensions-enable-container'] = array(
  '#type' => 'container',
  '#attributes' => array('class' => array('subsystem-enabled-container', 'layouts-column-onequarter')),
);

$form['extensions']['extensions-enable-container']['settings_extensions_form_open'] = array(
  '#type' => 'checkbox',
  '#title' => t('Keep open'),
  '#default_value' => $settings_extensions_form_open,
  '#states' => array(
    'disabled' => array('input[name="settings_enable_extensions"]' => array('checked' => FALSE)),
  ),
);

$form['extensions']['extensions-enable-container']['settings_enable_extensions'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable'),
  '#default_value' => theme_get_setting('settings.enable_extensions', $theme),
);

$form['extensions']['extension_settings'] = array(
  '#type' => 'vertical_tabs',
  '#attributes' => array('class' => array('clearfix')),
  '#states' => array(
    'visible' => array(':input[name="settings_enable_extensions"]' => array('checked' => TRUE)),
  ),
);

// Extensions
$form['enable_extensions'] = array(
  '#type' => 'details',
  '#title' => t('Enable extensions'),
  '#group' => 'extension_settings',
);

$form['enable_extensions']['description'] = array(
  '#markup' => t('<p>Extensions are settings for configuring and styling your site. Enabled extensions appear in new vertical tabs.</p>'),
);

// Menus
$form['enable_extensions']['settings_enable_responsive_menus'] = array(
  '#type' => 'checkbox',
  '#title' => t('Responsive menus'),
  '#description' => t('Select responsive menu styles.'),
  '#default_value' => theme_get_setting('settings.enable_responsive_menus', $theme),
);

// Fonts
$form['enable_extensions']['settings_enable_fonts'] = array(
  '#type' => 'checkbox',
  '#title' => t('Fonts'),
  '#default_value' => theme_get_setting('settings.enable_fonts', $theme),
  '#description' => t('Apply fonts to site elements (page, titles, headings, menus and custom selectors). Supports websafe, custom stacks, Google fonts, and <a href="!link" target="_blank">Typekit fonts</a>.', array('!link' => 'https://typekit.com/')),
);

// Title styles
$form['enable_extensions']['settings_enable_titles'] = array(
  '#type' => 'checkbox',
  '#title' => t('Title styles'),
  '#default_value' => theme_get_setting('settings.enable_titles', $theme),
  '#description' => t('Set case, weight and alignment for site name and slogan, page, node, block and comment titles.'),
);

// Image alignment and captions
$form['enable_extensions']['settings_enable_images'] = array(
  '#type' => 'checkbox',
  '#title' => t('Image alignment and captions'),
  '#default_value' => theme_get_setting('settings.enable_images', $theme),
  '#description' => t('Set default image alignment, image captions and teaser image view.'),
);

// Touch icons
$form['enable_extensions']['settings_enable_touch_icons'] = array(
  '#type' => 'checkbox',
  '#title' => t('Touch icons'),
  '#description' => t('Check this setting if you want to use touch icons.'),
  '#default_value' => theme_get_setting('settings.enable_touch_icons', $theme),
);

// shortcodes
$form['enable_extensions']['settings_enable_shortcodes'] = array(
  '#type' => 'checkbox',
  '#title' => t('Add Classes'),
  '#description' => t('Enter classes for page rows, regions, blocks, and per node type.'),
  '#default_value' => theme_get_setting('settings.enable_shortcodes', $theme),
);

// Custom CSS
$form['enable_extensions']['settings_enable_custom_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Custom CSS'),
  '#description' => t('Enter custom CSS rules for minor adjustment to your theme. Useful if you do not want to build a sub-theme and need modifications to survive theme upgrades if required.'),
  '#default_value' => theme_get_setting('settings.enable_custom_css', $theme),
);

// Markup overrides
$form['enable_extensions']['settings_enable_markup_overrides'] = array(
  '#type' => 'checkbox',
  '#title' => t('Markup overrides <small>(Breadcrumb, Login block, Hide comment titles, Skip links and Attribution message)</small>'),
  '#description' => t('Many additional options for adding, removing, hiding and changing site elements and markup - includes settings and options for:
    <ul>
      <li>Breadcrumbs</li>
      <li>Login block</li>
      <li>Comment titles</li>
      <li>Skip link target</li>
      <li>Attribution</li>
    </ul>
    '),
  '#default_value' => theme_get_setting('settings.enable_markup_overrides', $theme),
);

// Devel
$form['enable_extensions']['settings_enable_devel'] = array(
  '#type' => 'checkbox',
  '#title' => t('Developer tools'),
  '#description' => t('Settings to help with theme development.'),
  '#default_value' => theme_get_setting('settings.enable_devel', $theme),
);

// Legacy browsers
$form['enable_extensions']['settings_enable_legacy_browsers'] = array(
  '#type' => 'checkbox',
  '#title' => t('Legacy browsers'),
  '#description' => t('Settings to support really old browsers like IE8.'),
  '#default_value' => theme_get_setting('settings.enable_legacy_browsers', $theme),
);

// Extensions master toggle.
if (theme_get_setting('settings.enable_extensions', $theme) == 1) {

  // Include fonts.inc by default.
  include_once($at_core_path . '/forms/ext/fonts.inc');

  $extensions_array = array(
    'responsive_menus',
    'fonts',
    'titles',
    'images',
    'touch_icons',
    'shortcodes',
    'custom_css',
    'markup_overrides',
    'devel',
    'legacy_browsers',
  );

  // get form values
  $values = $form_state->getValues();

  foreach ($extensions_array as $extension) {
    $form_state_value = isset($values["settings_enable_$extension"]);
    $form_value = $form['enable_extensions']["settings_enable_$extension"]['#default_value'];
    if (($form_state_value && $form_state_value === 1) ||
       (!$form_state_value && $form_value == 1)) {
      include_once($at_core_path . '/forms/ext/' . $extension . '.php');
    }
  }
}

// Help (sub-theme). TODO: rethink where help goes.
// include_once($at_core_path . '/forms/help_subtheme.php');

// Submit button for advanced settings.
$form['extensions']['actions'] = array(
  '#type' => 'actions',
  '#attributes' => array('class' => array('submit--advanced-settings')),
);
$form['extensions']['actions']['submit'] = array(
  '#type' => 'submit',
  '#value' => t('Save extension settings'),
  '#validate'=> array('at_core_validate_extension_settings'),
  '#submit'=> array('at_core_submit_extension_settings'),
  '#attributes' => array('class' => array('button--primary')),
  '#weight' => -10000,
);


//$form['#validate'][] = 'at_core_validate_advanced_settings';
//$form['#submit'][] = 'at_core_submit_advanced_settings';
//$form['actions']['submit']['#validate'][] = 'at_core_validate_advanced_settings';
//$form['actions']['submit']['#submit'][] = 'at_core_submit_advanced_settings';

// Submit handlers for the advanced settings.
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_submit.php');
