<?php

use Drupal\Core\Config\Config;
use Drupal\Core\Cache\Cache;
use Drupal\Component\Utility\Unicode;

use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Layout\LayoutSubmit;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_submit_layouts(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];

  // Generate and save a new layout.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    $generateLayout = new LayoutSubmit($theme, $values);

    // Update the themes info file with new regions.
    $generateLayout->saveLayoutRegions();

    // Build and save the suggestions layout css files.
    $generateLayout->saveLayoutSuggestionsCSS();

    // Build and save the suggestions twig templates.
    $generateLayout->saveLayoutSuggestionsMarkup();

    // Add a new suggestion to the page suggestions array in config.
    if (!empty($values['ts_name'])) {
      $suggestion = trim($values['ts_name']);
      $clean_suggestion = str_replace('-', '_', $suggestion);
      $values["settings_suggestion_page__$clean_suggestion"] = $clean_suggestion;
    }

    // Delete suggestion files
    $templates_directory = drupal_get_path('theme', $theme) . '/templates/page';
    $css_directory = $values['settings_generated_files_path'];

    foreach ($values as $values_key => $values_value) {
      if (substr($values_key, 0, 18) === 'delete_suggestion_') {
        if ($values_value === 1) {
          $delete_suggestion_keys[] = Unicode::substr($values_key, 18);
        }
      }
    }

    if (isset($delete_suggestion_keys)) {
      foreach ($delete_suggestion_keys as $suggestion_to_remove) {
        $formatted_suggestion = str_replace('_', '-', $suggestion_to_remove);
        $template_file_path = $templates_directory . '/' . $formatted_suggestion . '.html.twig';
        $css_file_path = $css_directory . '/' . $theme . '--layout__' . $formatted_suggestion . '.css';
        if (file_exists($template_file_path)) {unlink($template_file_path);}
        if (file_exists($css_file_path)) {unlink($css_file_path);}
      }
    }
  }

  // Flush all caches. This is the only realy reliable way I have found to ensure
  // new templates and layouts work correctly.
  drupal_flush_all_caches();

  // Manage settings and configuration.
  // Must get mutable config otherwise bad things happen.
  $config = \Drupal::configFactory()->getEditable($theme . '.settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config);
}
