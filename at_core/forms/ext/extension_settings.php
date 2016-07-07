<?php

/**
 * @file
 * Generate form elements for the Extension settings.
 */

// Submit handlers for the advanced settings.
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/extension_settings_submit.php');

$settings_extensions_form_open = theme_get_setting('settings.extensions_form_open', $theme);

$form['docs'] = array(
  '#type' => 'container',
  '#markup' => t('<a class="at-docs" href="@docs" target="_blank">View online documentation <svg class="docs-help-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1024 1376v-192q0-14-9-23t-23-9h-192q-14 0-23 9t-9 23v192q0 14 9 23t23 9h192q14 0 23-9t9-23zm256-672q0-88-55.5-163t-138.5-116-170-41q-243 0-371 213-15 24 8 42l132 100q7 6 19 6 16 0 25-12 53-68 86-92 34-24 86-24 48 0 85.5 26t37.5 59q0 38-20 61t-68 45q-63 28-115.5 86.5t-52.5 125.5v36q0 14 9 23t23 9h192q14 0 23-9t9-23q0-19 21.5-49.5t54.5-49.5q32-18 49-28.5t46-35 44.5-48 28-60.5 12.5-81zm384 192q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg></a>', array('@docs' => 'http://docs.adaptivethemes.com/')),
  '#weight' => -1000,
);

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

// Image alignment and captions
$form['enable_extensions']['settings_enable_images'] = array(
  '#type' => 'checkbox',
  '#title' => t('Image alignment and captions'),
  '#default_value' => theme_get_setting('settings.enable_images', $theme),
  '#description' => t('Set image alignment, captions and teaser view per content type.'),
);

// Touch icons
$form['enable_extensions']['settings_enable_touch_icons'] = array(
  '#type' => 'checkbox',
  '#title' => t('Touch icons'),
  '#description' => t('Add touch icon meta tags. A default set of icons are located in <code>@touchiconpath</code>.', array('@touchiconpath' => $subtheme_path . '/images/touch-icons/')),
  '#default_value' => theme_get_setting('settings.enable_touch_icons', $theme),
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
  '#title' => t('Titles'),
  '#default_value' => theme_get_setting('settings.enable_titles', $theme),
  '#description' => t('Set case, weight, alignment and letter-spacing for titles (headings).'),
);

// Shortcodes
$form['enable_extensions']['settings_enable_shortcodes'] = array(
  '#type' => 'checkbox',
  '#title' => t('Shortcode CSS Classes'),
  '#description' => t('Adjust and enhance theme styles with pre-styled CSS classes.'),
  '#default_value' => theme_get_setting('settings.enable_shortcodes', $theme),
);

// Slideshows
$form['enable_extensions']['settings_enable_slideshows'] = array(
  '#type' => 'checkbox',
  '#title' => t('Slideshows'),
  '#description' => t('Enable slideshows and configure settings.'),
  '#default_value' => theme_get_setting('settings.enable_slideshows', $theme),
);

// Mobile blocks
$form['enable_extensions']['settings_enable_mobile_blocks'] = array(
  '#type' => 'checkbox',
  '#title' => t('Mobile Blocks'),
  '#description' => t('Show or hide blocks in mobile devices.'),
  '#default_value' => theme_get_setting('settings.enable_mobile_blocks', $theme),
);

// Custom CSS
$form['enable_extensions']['settings_enable_custom_css'] = array(
  '#type' => 'checkbox',
  '#title' => t('Custom CSS'),
  '#description' => t('Enter custom CSS rules for minor adjustment to your theme.'),
  '#default_value' => theme_get_setting('settings.enable_custom_css', $theme),
);

// CKEditor
// Check if theme is Mimic compatible.
if (theme_get_setting('settings.mimic_compatible', $theme) === 1) {
  $form['enable_extensions']['settings_enable_ckeditor'] = array(
    '#type' => 'checkbox',
    '#title' => t('CKEditor Skin'),
    '#description' => t('Select CKEditor skin.'),
    '#default_value' => theme_get_setting('settings.enable_ckeditor', $theme),
  );
}

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
  '#description' => t('Settings to support crappy old browsers like IE8. Use with caution, do not enable this unless you really, really need it.'),
  '#default_value' => theme_get_setting('settings.enable_legacy_browsers', $theme),
);

// Markup overrides
$form['enable_extensions']['settings_enable_markup_overrides'] = array(
  '#type' => 'checkbox',
  '#title' => t('Markup overrides'),
  '#description' => array(
    '#theme' => 'item_list',
    '#list_type' => 'ul',
    '#attributes' => array('class' => array('markup-overrides-desc')),
    '#items' => array(
      t('Responsive tables'),
      t('Breadcrumbs'),
      t('Search block'),
      t('Login block'),
      t('Comment titles'),
      t('Feed icons'),
      t('Skip link'),
      t('Attribution'),
    ),
  ),
  '#default_value' => theme_get_setting('settings.enable_markup_overrides', $theme),
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
    'ckeditor',
    'markup_overrides',
    'devel',
    'legacy_browsers',
  );

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
);
