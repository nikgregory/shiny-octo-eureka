<?php

use Drupal\Core\Config\Config;

use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Layout\LayoutSubmit;
use Drupal\at_core\Helpers\FileSavePrepare;

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

    if (!empty($values['ts_name'])) {
      $suggestion = $values['ts_name'];
      $clean_suggestion = strtr($suggestion, '-', '_');
      $values["settings_suggestion_page__$clean_suggestion"] = $clean_suggestion;
    }
  }

  // Delete suggestion files
  $templates_directory = drupal_get_path('theme', $theme) . '/templates/page';
  $css_directory = $values['settings_generated_files_path'];
  foreach ($values as $values_key => $values_value) {
    if (substr($values_key, 0, 18) == 'delete_suggestion_') {
      if ($values_value == 1) {
        $delete_suggestion_keys[] = drupal_substr($values_key, 18);
      }
    }
  }
  if (isset($delete_suggestion_keys)) {
    foreach ($delete_suggestion_keys as $suggestion_to_remove) {
      $template_file_path = $templates_directory . '/' . $suggestion_to_remove . '.html.twig';
      $css_file_path = $css_directory . '/' . $theme . '-' . $suggestion_to_remove . '--layout.css';
      if (file_exists($template_file_path)) {unlink($template_file_path);}
      if (file_exists($css_file_path)) {unlink($css_file_path);}
    }
  }
  if (isset($values['settings_max_width_enable']) && $values['settings_max_width_enable'] === 1) {
    $max_width_css = 'div.regions{max-width:' . $values['settings_max_width_value'] . $values['settings_max_width_unit'] . '}';
    $file_name = $theme . '--max-width.css';
    $filepath = $values['settings_generated_files_path'] . '/' . $file_name;
    file_unmanaged_save_data($max_width_css, $filepath, FILE_EXISTS_REPLACE);
  }

  // Manage settings and configuration.
  $config = \Drupal::config($theme . '.settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config);

  // Messages
  /*
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] === 1 && $values['layout_type_select'] != 'disable_layout_generation') {
    drupal_set_message(t('You generated a new layout - you may need to clear the cache from the <a href="!performancesettings">Performance settings</a> page for Drupal to see new theme data (such as yml file definitions), .', array('!performancesettings' => base_path() . 'admin/config/development/performance')), 'warning');
  }
  */
}


