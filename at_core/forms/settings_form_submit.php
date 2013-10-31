<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Generator\ThemeGeneratorSubmit;
use Drupal\at_core\Layout\LayoutGeneratorSubmit;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_settings_form_submit(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  // Generate a new theme.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $generateTheme = new ThemeGeneratorSubmit();
    $generateTheme->generateTheme($values);
  }

  // Generate and save a new layout.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {
    if ($values['layout_type_select'] != 'disable_layout_generation') {
      $generateLayout = new LayoutGeneratorSubmit();
      $generateLayout->generateLayout($theme, $values);
    }
  }

  // Save custom settings to the settings configuration.
  $config = config($theme . '_settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config)->save();

  drupal_theme_rebuild();

  // Been trying to call drupal_flush_all_caches here but nothing seems to work other than going to the
  // Performance settings page and actually firing it there.
  if ((!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') || (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1 && $values['layout_type_select'] != 'disable_layout_generation')) {
    drupal_set_message(t('You generated a theme or layout - you must clear the cache for Drupal to see new theme data (such as a new theme, templates and new block regions if generating a new layout). Go to the Performance Settings page in Configuration (admin/config/development/performance) and clear the cache.'), 'warning');
  }
}
