<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Helpers\FileSavePrepare;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_submit_advanced_settings(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];
  $at_core_path = drupal_get_path('theme', 'at_core');

  // Path to save generated CSS files.
  $theme_path = drupal_get_path('theme', $theme);
  $fileSavePrepare = new FileSavePrepare();
  $generated_files_path = $fileSavePrepare->prepareDirectories($backup_file_path = array($theme_path, 'generated_css'));

  if ($values['settings_enable_extensions'] === 1) {

    // Require submit handlers and helper functions for extensions. TODO - convert to classes and methods?
    if ((isset($values['settings_enable_fonts']) && $values['settings_enable_fonts'] === 1) ||
        (isset($values['settings_enable_titles']) && $values['settings_enable_titles'] === 1)) {
      require_once($at_core_path . '/forms/ext/fonts.inc');
      require_once($at_core_path . '/forms/ext/fonts_submit.php');
      require_once($at_core_path . '/forms/ext/titles_submit.php');
    }

    // Submit handler for Fonts.
    if (isset($values['settings_enable_fonts']) && $values['settings_enable_fonts'] === 1) {
      at_core_submit_fonts($values, $theme, $generated_files_path);
    }

    // Submit handler for Titles.
    if (isset($values['settings_enable_titles']) && $values['settings_enable_titles'] === 1) {
      at_core_submit_titles($values, $theme, $generated_files_path);
    }

    // Submit handler for Images.
    if (isset($values['settings_enable_images']) && $values['settings_enable_images'] === 1) {
      require_once($at_core_path . '/forms/ext/images_submit.php');
      at_core_submit_images($values, $theme, $generated_files_path);
    }

    // Submit handler for Markup Overrides.
    if (isset($values['settings_enable_markup_overrides']) && $values['settings_enable_markup_overrides'] === 1) {

      // Breadcrumbs
      if ((isset($values['settings_breadcrumb_title']) && $values['settings_breadcrumb_title'] === 1) || !empty($values['settings_breadcrumb_separator'])) {
        require_once($at_core_path . '/forms/ext/breadcrumb_submit.php');
        at_core_submit_breadcrumb($values, $theme, $generated_files_path);
      }

      // Login block.
      if (isset($values['settings_horizontal_login_block']) && $values['settings_horizontal_login_block'] === 1) {
        require_once($at_core_path . '/forms/ext/login_block_submit.php');
        at_core_submit_login_block($values, $theme, $generated_files_path);
      }
    }

    // Submit handler for Custom CSS.
    if (isset($values['settings_enable_custom_css']) && $values['settings_enable_custom_css'] === 1) {
      require_once($at_core_path . '/forms/ext/custom_css_submit.php');
      at_core_submit_custom_css($values, $theme, $generated_files_path);
    }
  }

  // Manage settings and configuration.
  //$config = config($theme . '_settings');
  $config = \Drupal::config($theme . '.settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config)->save();

  drupal_set_message(t('Advanced settings configuration has been saved.'), 'status');
}


























