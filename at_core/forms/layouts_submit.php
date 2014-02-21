<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Layout\LayoutGeneratorSubmit;
use Drupal\at_core\Helpers\FileSavePrepare;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_submit_layouts(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  // Path to save generated CSS files.
  $theme_path = drupal_get_path('theme', $theme);
  $fileSavePrepare = new FileSavePrepare();
  $generated_files_path = $fileSavePrepare->prepareDirectories($backup_file_path = array($theme_path, 'generated_css'));

  // Generate and save a new layout.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] === 1) {
    if ($values['layout_type_select'] != 'disable_layout_generation') {
      $generateLayout = new LayoutGeneratorSubmit();
      $generated_values = $generateLayout->generateLayout($theme, $values);
      // Merge in new and modified values.
      $values += $generated_values;
    }
  }

  // Delete suggestion files, TODO create new helper class/method for this:
  if (isset($values['delete_suggestions'])) {
    if ($values['delete_suggestions'] === 1 && !empty($values['delete_suggestions_table'])) {
      $templates_directory = drupal_get_path('theme', $theme) . '/templates/';
      foreach ($values['delete_suggestions_table'] as $config_key => $config_value) {
        $suggestion = str_replace('_', '-', drupal_substr($config_key, 20));
        $file_path = $templates_directory . $suggestion . '.html.twig';
        if (file_exists($file_path)) {
          unlink($file_path);
        }
      }
    }
  }

  if (isset($values['settings_max_width_enable']) && $values['settings_max_width_enable'] === 1) {
    $max_width_css = 'div.regions{max-width:' . $values['settings_max_width_value'] . $values['settings_max_width_unit'] . '}';
    $file_name = $theme . '--max-width.css';
    $filepath = $generated_files_path . '/' . $file_name;
    file_unmanaged_save_data($max_width_css, $filepath, FILE_EXISTS_REPLACE);
  }

  // Manage settings and configuration.
  //$config = config($theme . '_settings');
  $config = \Drupal::config($theme . '.settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config)->save();

  // Messages
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] === 1 && $values['layout_type_select'] != 'disable_layout_generation') {
    if (!\Drupal::moduleHandler()->moduleExists('at_blocks')) {
      drupal_set_message(t('AT Blocks module is installed, the <code>{{ messages }}</code> variable has been omitted from the generated template. To view site messages in this theme you must enable the <b>Atblocks Status Messages</b> block in the <a href="!blocksettings">Block layout</a> settings.', array('!blocksettings' => base_path() . 'admin/structure/block')), 'warning');
    }
    drupal_set_message(t('You generated a new layout - you may need to clear the cache from the <a href="!performancesettings">Performance settings</a> page for Drupal to see new theme data (such as yml file definitions), .', array('!performancesettings' => base_path() . 'admin/config/development/performance')), 'warning');
  }
}
