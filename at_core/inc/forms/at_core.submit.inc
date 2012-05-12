<?php

/**
 * @file
 * Process all form values. Uses multiple other include files and helper
 * functions to handle various operations.
 */

global $path_to_at_core;
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');

// Helper functions for processing the page layout and font families.
require_once($path_to_at_core . '/inc/forms/at_core.submit.builders.inc');

/**
 * Custom submit function - this mostly builds and saves stylesheets for
 * various features such as the responsive layout and font styles.
 *
 * @param $form
 * @param $form_state
 */
function at_core_settings_submit($form, &$form_state) {
  global $path_to_at_core;

  //dsm($form_state);

  // Set form_state values into one variable
  $values = $form_state['values'];

  // Get the active theme name, $theme_key will return the admin theme
  $theme_name = $form_state['build_info']['args'][0];

  // Set up some paths we use to get and save files
  $path_to_panels_css = $path_to_at_core . '/layouts/css/';
  $path_to_responsive_css = drupal_get_path('theme', $theme_name) . '/css/';

  // Potential files to remove
  $rm_files = array(
    $theme_name . '_color.inc',
    $theme_name . '_settings.info',
    $theme_name . '.default.layout.css',
    $theme_name . '.lt-ie8.layout.css',
    $theme_name . '.lt-ie9.layout.css',
    $theme_name . '.responsive.layout.css',
    $theme_name . '.responsive.styles.css',
    $theme_name . '.fonts.css',
    $theme_name . '.custom.css',
  );

  // Delete files and the directory
  if ($old_path = variable_get('theme_' . $theme_name . '_files_directory')) {
    foreach ($rm_files as $file) {
      $filepath = $old_path . '/' . $file;
      if (file_exists($filepath)) {
        @drupal_unlink($filepath);
      }
    }
    if (isset($old_path)) {
      @drupal_rmdir($old_path);
    }
  }

  // Set up the new files directory for the generated files.
  $id = $theme_name . '_files_' . substr(hash('sha256', serialize($theme_name) . microtime()), 0, 8);
  $path  = 'public://adaptivetheme/' . $id;
  file_prepare_directory($path, FILE_CREATE_DIRECTORY);
  variable_set('theme_' . $theme_name . '_files_directory', $path);

  // Include processing for automagical info builder
  require_once($path_to_at_core . '/inc/forms/at_core.submit.info.inc');

  // Include processing for page & panels layouts, and responsive styles
  require_once($path_to_at_core . '/inc/forms/at_core.submit.responsive.inc');

  if ($values['enable_extensions'] === 1) {
    // Include processing for fonts
    if (isset($values['enable_font_settings']) && $values['enable_font_settings'] === 1) {
      require_once($path_to_at_core . '/inc/fonts.inc');
      require_once($path_to_at_core . '/inc/forms/at_core.submit.fonts.inc');
    }
    // Include processing for Custom CSS
    if (isset($values['enable_custom_css']) && $values['enable_custom_css'] === 1) {
      require_once($path_to_at_core . '/inc/forms/at_core.submit.customcss.inc');
    }
  }

  // Color inc save
  if (module_exists('color')) {
    if (isset($values['at-color']) && $values['at-color'] == TRUE) {
      require_once($path_to_at_core . '/inc/forms/at_core.submit.color.inc');
    }
  }
}
