<?php

/**
 * @file
 * Generate title styles.
 */
function at_core_submit_titles($values, $theme, $generated_files_path) {
  $titles_styles = array();

  // Array of valid title types
  $titles_valid_types = array(
    'site_name',
    'site_slogan',
    'page_title',
    'node_title',
    'block_title',
    'comment_title',
  );

  // Get the font elements array.
  $font_elements = font_elements();

  // Build arrays of selectors with associated styles.
  foreach ($font_elements as $font_element_key => $font_element_value) {
    if (in_array($font_element_key, $titles_valid_types)) {
      $case = 'text-transform:';
      $weight = 'font-weight:';
      $alignment = 'text-align:';
      if ($values['settings_' . $font_element_key . '_case'] == 'small-caps') {
        $case = 'font-variant:';
      }
      $css[$font_element_key]['selector'] = $font_element_value['selector'];
      $css[$font_element_key]['styles']['case'] = $case . $values['settings_' . $font_element_key . '_case'];
      $css[$font_element_key]['styles']['weight'] = $weight . $values['settings_' . $font_element_key . '_weight'];
      $css[$font_element_key]['styles']['align'] = $alignment . $values['settings_' . $font_element_key . '_alignment'];
    }
  }

  // Format CSS.
  foreach ($css as $selector_key => $selector_styles) {
    $output[] = $selector_styles['selector'] . '{' .  implode(';', $selector_styles['styles']) . '}';
  }

  // Output data to file.
  $titles_styles = implode("\n", $output);
  if (!empty($titles_styles)) {
    $file_name = $theme . '--titles.css';
    $filepath = "$generated_files_path/$file_name";
    file_unmanaged_save_data($titles_styles, $filepath, FILE_EXISTS_REPLACE);
  }
}
