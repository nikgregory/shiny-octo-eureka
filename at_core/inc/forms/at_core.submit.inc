<?php
// Helper functions for processing the page layout and font families
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/forms/at_core.submit.builders.inc');

// Custom submit function - this mostly builds and saves stylesheets for
// various features such as the responsive layout and font styles.
function at_core_settings_submit($form, &$form_state) {
  $path_to_at_core = drupal_get_path('theme', 'adaptivetheme');

  // Set form_state values into one variable
  $values = $form_state['values'];

  // Get the active theme name, $theme_key will return the admin theme
  $theme_name = $form_state['build_info']['args'][0];

  // Set up some paths we use to get and save files
  $path_to_panels_css = $path_to_at_core . '/layouts/css/';
  $path_to_responsive_css = drupal_get_path('theme', $theme_name) . '/css/';

  // Set up our files directory for the generated files. Files are never
  // overwritten, however the files are really small - total around 25kb 
  // depending, of which around 8kb is never loaded - the theme info 
  // config file, which is just a backup.
  $id = $theme_name . '_files_' . substr(hash('sha256', serialize($theme_name) . microtime()), 0, 8);
  $path  = 'public://adaptivetheme/' . $id;
  file_prepare_directory($path, FILE_CREATE_DIRECTORY);
  variable_set('theme_' . $theme_name . '_files_directory', $path);
  
  // Include processing for automagical info builder
  include_once($path_to_at_core . '/inc/forms/at_core.submit.info.inc');

  // Include processing for page & panels layouts, and responsive styles
  include_once($path_to_at_core . '/inc/forms/at_core.submit.responsive.inc');

  // Include processing for fonts
  if ($values['enable_font_settings'] === 1) {
    include_once($path_to_at_core . '/inc/forms/at_core.submit.fonts.inc');
  }
}