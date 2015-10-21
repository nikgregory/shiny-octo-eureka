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

// Responsive Menus
$form['enable_extensions']['settings_enable_responsive_menus'] = array(
  '#type' => 'checkbox',
  '#title' => t('Responsive menus'),
  '#description' => t('Select responsive menu styles and breakpoints.'),
  '#default_value' => theme_get_setting('settings.enable_responsive_menus', $theme),
);

// Fonts
$form['enable_extensions']['settings_enable_fonts'] = array(
  '#type' => 'checkbox',
  '#title' => t('Fonts'),
  '#default_value' => theme_get_setting('settings.enable_fonts', $theme),
  '#description' => t('Apply fonts to site elements. Supports <a href="@gflink" target="_blank">Google</a> and <a href="@tklink" target="_blank">Typekit</a> fonts, as well as standard websafe fonts.', array('@tklink' => 'https://typekit.com/', '@gflink' => 'https://www.google.com/fonts')),
);

// Title styles
$form['enable_extensions']['settings_enable_titles'] = array(
  '#type' => 'checkbox',
  '#title' => t('Title styles'),
  '#default_value' => theme_get_setting('settings.enable_titles', $theme),
  '#description' => t('Set case, weight and alignment for titles (headings).'),
);

// Image alignment and captions
$form['enable_extensions']['settings_enable_images'] = array(
  '#type' => 'checkbox',
  '#title' => t('Image alignment and captions'),
  '#default_value' => theme_get_setting('settings.enable_images', $theme),
  '#description' => t('Set image alignment, captions and teaser view per content type.'),
);

// Shortcodes
$form['enable_extensions']['settings_enable_shortcodes'] = array(
  '#type' => 'checkbox',
  '#title' => t('Shortcode CSS Classes'),
  '#description' => t('Adjust and enhance theme styles with pre-styled CSS classes.'),
  '#default_value' => theme_get_setting('settings.enable_shortcodes', $theme),
);

// Mobile blocks
$form['enable_extensions']['settings_enable_mobile_blocks'] = array(
  '#type' => 'checkbox',
  '#title' => t('Mobile Blocks'),
  '#description' => t('Show or hide blocks in mobile devices.'),
  '#default_value' => theme_get_setting('settings.enable_mobile_blocks', $theme),
);

// Slideshows
$form['enable_extensions']['settings_enable_slideshows'] = array(
  '#type' => 'checkbox',
  '#title' => t('Slideshows'),
  '#description' => t('Enable slideshows and configure settings.'),
  '#default_value' => theme_get_setting('settings.enable_slideshows', $theme),
);

// Touch icons
$form['enable_extensions']['settings_enable_touch_icons'] = array(
  '#type' => 'checkbox',
  '#title' => t('Touch icons'),
  '#description' => t('Add touch icon meta tags. A default set of icons are located in <code>!touchiconpath</code>.', array('!touchiconpath' => $subtheme_path . '/images/touch-icons/')),
  '#default_value' => theme_get_setting('settings.enable_touch_icons', $theme),
);

// Custom CSS
$form['enable_extensions']['settings_enable_custom_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Custom CSS'),
  '#description' => t('Enter custom CSS rules for minor adjustment to your theme.'),
  '#default_value' => theme_get_setting('settings.enable_custom_css', $theme),
);

// Markup overrides
$form['enable_extensions']['settings_enable_markup_overrides'] = array(
  '#type' => 'checkbox',
  '#title' => t('Markup overrides'),
  '#description' => t('Options for modifying output, includes settings for:
    <ul>
      <li>Breadcrumbs</li>
      <li>Login block</li>
      <li>Comment titles</li>
      <li>Skip link</li>
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
  '#description' => t('Settings to support really old browsers like IE8. Use with caution, do not enable this unless you really need it.'),
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
    'mobile_blocks',
    'slideshows',
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
