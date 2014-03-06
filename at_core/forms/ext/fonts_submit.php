<?php

/**
 * @file
 * Extract font data from form values, send for processing and print
 * returned data in a CSS file.
 */
function at_core_submit_fonts($values, $theme, $generated_files_path) {

  // $font_styles_data holds all data for the stylesheet
  $font_styles_data = array();

  // Get the font elements array.
  $font_elements = font_elements();

  // Check if fontyourface is enabled, doing this in the loop will be expensive
  $font_your_face_enabled = FALSE;
  if (function_exists('font_your_face_fonts_enabled')) {
    $font_your_face_enabled = font_your_face_fonts_enabled();
  }

  // Find the right font for each element
  foreach ($font_elements as $key => $value) {

    // Each item in $font_elements has 3 key value pairs
    $element  = 'settings_' . $value['element']  ? 'settings_' . $value['element']  : '';  // a key to use later
    $selector = 'settings_' . $value['selector'] ? 'settings_' . $value['selector'] : '';  // the selector to use when building the CSS
    $setting  = 'settings_' . $value['setting']  ? 'settings_' . $value['setting']  : '';  // the theme setting used to retrieve the font values

    // Deal with the custom CSS selectors.
    if ($selector == 'settings_custom_css' && !empty($values['settings_selectors_css'])) {
      $selector = filter_xss('settings_' . $values['settings_selectors_css']); // sanitize user entered data
    }
    if ($selector == 'settings_custom_css' && empty($values['settings_selectors_css'])) {
      $selector = 'settings_ruby ruby'; // Valid but highly unlikely to ever match anything
    }

    // Get the font type if isset, not all font settings have a type
    if (isset($values[$setting . '_type'])) {
      $font_type = $values[$setting . '_type'];
    }
    else {
      $font_type = '<none>'; // this is an individual "in-content" heading
    }

    // Get the font size if isset, not all font settings have size
    if (isset($values[$setting . '_size'])) {
      $font_size = check_plain($values[$setting . '_size']);
    }
    else {
      $font_size = ''; // set a fallback for rem conversions.
    }

    // Get the font value (the font name or family) for the right font type if isset,
    // not all font settings have a value
    if (isset($values[$setting . (!empty($font_type) ? '_' . $font_type : '')])) {
      $font_value = $values[$setting . (!empty($font_type) ? '_' . $font_type : '')];
    }

    // Initialize the $font_values array variables
    $font_values['font_family'] = '';
    $font_values['font_size']   = '';
    $font_values['font_style']  = '';
    $font_values['font_weight'] = '';

    // Some Content Headings have no type or family, we add these first,
    // these are the h1 to h6 settings that only have a size
    /*
    if ($font_type === '<none>') {
      $font_values['font_size'] = $font_size;

      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }
    */

    // Websafe Fonts
    if ($font_type === '') {
      // Get a list of websafe fonts
      $websafe_fonts = websafe_fonts_list($element);
      // Loop over the websafe fonts list and get a match
      foreach ($websafe_fonts as $k => $v) {
        if ($k == $font_value) {
          $font_family = $v;
        }
      }
      $font_values['font_family'] = $font_family;
      $font_values['font_size']   = $font_size;

      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Custom Font stacks (user entered data)
    if ($font_type === 'cfs') {
      $font_values['font_family'] = drupal_strip_dangerous_protocols($font_value); // sanitize user entered data
      $font_values['font_size']   = $font_size;
      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Google Fonts (user entered data)
    if ($font_type === 'gwf') {
      $font_value = "'" . $font_value . "'";
      $font_values['font_family'] = filter_xss_admin($font_value); // sanitize user entered data
      $font_values['font_size']   = $font_size;

      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Font Your Face
    if ($font_your_face_enabled === TRUE) {
      if ($font_type === 'fyf') {
        // pull the font list, we need to iterate over it
        $fyf_fonts = font_your_face_fonts_list($element); // this is a keyed array
        // loop over fyf_fonts list and get a match and retrive the font name
        foreach ($fyf_fonts as $k => $v) {
          if ($k == $font_value) {
            $font_value = $v;
          }
        }
        // Get the font objects from font-your-face, we need additional data out
        // of each object
        $enabled_fonts = fontyourface_get_fonts('enabled = 1');
        foreach ($enabled_fonts as $font) {
          // we need to know if the $font_value matches a $font->name
          if ($font_value == $font->name) {
            // Now we need a buch of variables to get the font family, style and weight
            $font_values['font_family'] = $font->css_family ? $font->css_family : '';
            $font_values['font_style']  = $font->css_style  ? $font->css_style  : '';
            $font_values['font_weight'] = $font->css_weight ? $font->css_weight : '';
          }
        }
        // Load the font size
        $font_values['font_size'] = $font_size;
        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
      }
    }
  }

  // Output data to file
  if (!empty($font_styles_data)) {
    foreach ($font_styles_data as $font_style) {
      $font_styles_data_clean_styles[] = substr($font_style, 9);
    }
    $font_styles = implode("\n", $font_styles_data_clean_styles);
    $font_styles = preg_replace('/^[ \t]*[\r\n]+/m', '', $font_styles);
    $file_name = $theme . '--fonts.css';
    $filepath = "$generated_files_path/$file_name";
    file_unmanaged_save_data($font_styles, $filepath, FILE_EXISTS_REPLACE);
  }
}
