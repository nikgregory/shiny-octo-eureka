<?php

/**
 * @file
 * Output formatted CSS for fonts.
 */
function at_core_submit_fonts($values, $theme, $generated_files_path) {

  // Websafe fonts.
  $websafe_fonts = websafe_fonts();

  // Elements to apply fonts to.
  $font_elements = font_elements();

  // Initialize some variables.
  $fonts = array();
  $size = '';
  $base_size = '16'; // 16px default
  $px_size = '';
  $rem_size = '';

  // Set the base font size, we need this to calculate rem sizes.
  if (!empty($values['settings_font_size_base'])) {
    $base_size = $values['settings_font_size_base'];
  }

  foreach ($font_elements as $font_key => $font_values) {

    // Get the selectors for each element.
    if ($values['settings_font_' . $font_key] !== 'none') {
      $fonts[$font_key]['selectors'] = $font_values['selector'];
    }

    // Custom selectors, reset the selectors variable if we have custom selectors.
    if ($font_key == 'custom_selectors' && !empty($values['settings_font_custom_selectors']) && !empty($values['settings_custom_selectors'])) {
      $fonts[$font_key]['selectors'] = $values['settings_custom_selectors']; // ? $values['settings_custom_selectors'] : 'ruby ruby'
    }

    // Size
    if (!empty($values['settings_font_size_' . $font_key])) {
      $px_size = $values['settings_font_size_' . $font_key] . 'px';
      $rem_size = $values['settings_font_size_' . $font_key] / $base_size . 'rem';

      $fonts[$font_key]['size'] = 'font-size:' . $px_size . ';font-size:' . $rem_size . ';';
    }

    // Websafe
    if ($values['settings_font_' . $font_key] == 'websafe') {
      $fonts[$font_key]['family'] = 'font-family:' . $websafe_fonts[$values['settings_font_websafe']] . ';';
    }

    // Customstack
    if ($values['settings_font_' . $font_key] == 'customstack') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_customstack'] . ';';
    }

    // Google
    if ($values['settings_font_' . $font_key] == 'google') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_google_' . $font_key] . ';';
    }

    // Typekit
    if ($values['settings_font_' . $font_key] == 'typekit') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_typekit_' . $font_key] . ';';
    }
  }

  // Output data to file
  if (!empty($fonts)) {
    $font_styles = array();
    foreach ($fonts as $key => $values) {
      if (isset($values['family']) || isset($values['size'])) {
        $font_style  = $values['selectors'] . '{';

        if (isset($values['family'])) {
          $font_style .= $values['family'];
        }

        if (isset($values['size'])) {
          $font_style .= $values['size'];
        }
        $font_style .= '}';
        $font_styles[] = $font_style;
      }
    }

    $output = implode("\n", $font_styles);
  }

  $output = $output ? $output : '/** No fonts styles set **/';
  $file_name = $theme . '.fonts.css';
  $filepath = "$generated_files_path/$file_name";
  file_unmanaged_save_data($output, $filepath, FILE_EXISTS_REPLACE);
}
