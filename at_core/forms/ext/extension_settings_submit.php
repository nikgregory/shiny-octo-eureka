<?php

use Drupal\Core\Config\Config;
use Drupal\Core\Entity\EntityManagerInterface;

use Drupal\at_core\Theme\ThemeSettingsConfig;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_submit_extension_settings(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];
  $at_core_path = drupal_get_path('theme', 'at_core');

  // Path to save generated CSS files.
  $generated_files_path = $values['settings_generated_files_path'];

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

    // Submit handler for Markup Overrides.
    if (isset($values['settings_enable_markup_overrides']) && $values['settings_enable_markup_overrides'] === 1) {

      // Breadcrumbs
      if (!empty($values['settings_breadcrumb_separator'])) {
        require_once($at_core_path . '/forms/ext/breadcrumb_submit.php');
        at_core_submit_breadcrumb($values, $theme, $generated_files_path);
      }

      // Login block.
      if (isset($values['settings_horizontal_login_block']) && $values['settings_horizontal_login_block'] === 1) {
        require_once($at_core_path . '/forms/ext/login_block_submit.php');
        at_core_submit_login_block($values, $theme, $generated_files_path);
      }
    }

    // Submit handler for Mobile blocks.
    if (isset($values['settings_enable_mobile_blocks']) && $values['settings_enable_mobile_blocks'] === 1) {
      require_once($at_core_path . '/forms/ext/mobile_blocks_submit.php');
      at_core_submit_mobile_blocks($values, $theme, $generated_files_path);
    }

    // Submit handler for Custom CSS.
    if (isset($values['settings_enable_custom_css']) && $values['settings_enable_custom_css'] === 1) {
      require_once($at_core_path . '/forms/ext/custom_css_submit.php');
      at_core_submit_custom_css($values, $theme, $generated_files_path);
    }
  }

  // Flush all caches, this is the only 100% reliable way to make sure all settings are applied.
  drupal_flush_all_caches();

  // Manage settings and configuration.
  // Must get mutable config otherwise bad things happen.
  $config = \Drupal::configFactory()->getEditable($theme . '.settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config);

  drupal_set_message(t('Extensions configuration saved. Cache cleared.'), 'status');
}
