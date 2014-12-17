<?php

use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;

use Drupal\Component\Utility\Xss;

/**
 * @file
 * Output formatted CSS for fonts.
 */

function at_core_submit_fonts($values, $theme, $generated_files_path) {

  // Paths
  $subtheme_path = drupal_get_path('theme', $theme);
  $generated_scripts_path = $subtheme_path . '/scripts/generated';

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

  $fileOperations = new FileOperations();
  $font_styles = array();

  foreach ($font_elements as $font_key => $font_values) {

    // Get the selectors for each element.
    $fonts[$font_key]['selectors'] = $font_values['selector'];

    // Custom selectors, reset the selectors variable if we have custom selectors.
    if ($font_key == 'custom_selectors' && !empty($values['settings_font_custom_selectors']) && !empty($values['settings_custom_selectors'])) {
      $fonts[$font_key]['selectors'] = $values['settings_custom_selectors']; // ? $values['settings_custom_selectors'] : 'ruby ruby'
    }

    // Size/Line height
    if (!empty($values['settings_font_size_' . $font_key])) {

      $px_size = $values['settings_font_size_' . $font_key];
      $rem_size = $values['settings_font_size_' . $font_key] / $base_size;

      // line-height multipliers are a bit magical, but "pretty good" defaults.
      $line_height_multiplier = $values['settings_font_lineheight_multiplier_default'];
      if ($px_size >= $values['settings_font_lineheight_multiplier_large_size']) {
        $line_height_multiplier = $values['settings_font_lineheight_multiplier_large'];
      }

      $fonts[$font_key]['size'] = 'font-size:' . ceil($px_size) . 'px; font-size:' . round($rem_size, 3) . 'rem;';
      $fonts[$font_key]['lineheight'] = 'line-height:' . ceil($px_size * $line_height_multiplier) . 'px; line-height:' . round($rem_size * $line_height_multiplier, 3) . 'rem;';
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
    foreach ($fonts as $key => $values) {
      if (isset($values['family']) || isset($values['size'])) {
        $font_style = $values['selectors'] . '{';

        if (isset($values['family'])) {
          $font_style .= $values['family'];
        }

        if (isset($values['size'])) {
          $font_style .= $values['size'];
        }

        if (isset($values['lineheight'])) {
          $font_style .= $values['lineheight'];
        }

        $font_style .= '}';
        $font_styles[] = $font_style;
      }
    }

    $output = implode("\n", $font_styles);
  }

  $output = $output ? Xss::filter($output) : '/** No fonts styles set **/';

  //$file_name = $theme . '.fonts.css';

  $file_name = 'fonts.css';
  $filepath = "$generated_files_path/$file_name";
  file_unmanaged_save_data($output, $filepath, FILE_EXISTS_REPLACE);
}
