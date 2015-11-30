<?php

use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Config\Config;

/**
 * @file
 * Output formatted CSS for fonts.
 * TODO: validate font elements, are they set and not empty?
 */

function at_core_submit_fonts($values, $theme, $generated_files_path) {
  // Config, we need to save some config directly from this form.
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Paths.
  $subtheme_path = drupal_get_path('theme', $theme);
  $generated_scripts_path = $subtheme_path . '/scripts/generated';

  // Websafe fonts.
  $websafe_fonts = $values['websafe_options'];

  // Elements to apply fonts to.
  $font_elements = font_elements();

  // Fallback family
  $fallback_font_family = str_replace('_', '-', $values['settings_font_fallback']);

  // Initialize some variables.
  $fonts = array();
  $size = '';
  $base_size = '16'; // 16px default
  $px_size = '';
  $rem_size = '';

  // Inject config settings for webfonts.
  $values['settings_font_use_googlefonts'] = FALSE;
  $values['settings_font_use_typekit'] = FALSE;

  $fileOperations = new FileOperations();
  $font_styles = array();

  foreach ($font_elements as $font_key => $font_values) {

    // Get the selectors for each element.
    $fonts[$font_key]['selectors'] = $font_values['selector'];

    // Reset the selectors variable if we have custom selectors.
    if ($font_key == 'custom_selectors' && !empty($values['settings_font_custom_selectors']) && !empty($values['settings_custom_selectors'])) {
      $fonts[$font_key]['selectors'] = $values['settings_custom_selectors']; // ? $values['settings_custom_selectors'] : 'ruby ruby'
    }

    // Size/Line height.
    if (!empty($values['settings_font_size_' . $font_key])) {

      $px_size = $values['settings_font_size_' . $font_key];
      $rem_size = $values['settings_font_size_' . $font_key] / $base_size;

      // line-height multipliers are a bit magical, but "pretty good" defaults.
      $line_height_multiplier = $values['settings_font_lineheight_multiplier_default'];
      if ($px_size >= $values['settings_font_lineheight_multiplier_large_size']) {
        $line_height_multiplier = $values['settings_font_lineheight_multiplier_large'];
      }

      $fonts[$font_key]['size'] = ' font-size: ' . ceil($px_size) . 'px; font-size: ' . round($rem_size, 3) . 'rem;';
      $fonts[$font_key]['lineheight'] = ' line-height: ' . ceil($px_size * $line_height_multiplier) . 'px; line-height: ' . round($rem_size * $line_height_multiplier, 3) . 'rem;';
    }

    // Set font family for each key.
    if (isset($values['settings_font_' . $font_key])) {

      // Websafe.
      if ($values['settings_font_' . $font_key] == 'websafe') {
        if (isset($values['settings_font_websafe_' . $font_key])) {
          if (!empty($websafe_fonts[$values['settings_font_websafe_' . $font_key]])) {
            $fonts[$font_key]['family'] = 'font-family: ' . trim($websafe_fonts[$values['settings_font_websafe_' . $font_key]]) . ';';
          }
          else {
            $fonts[$font_key]['family'] = 'font-family: inherit;';
          }
        }
        else {
          $fonts[$font_key]['family'] = 'font-family: inherit;';
        }
      }

      // Google.
      if ($values['settings_font_' . $font_key] == 'google') {
        if (isset($values['settings_font_google_' . $font_key])) {
          $fonts[$font_key]['family'] = 'font-family: ' . $values['settings_font_google_' . $font_key] . ', ' . $fallback_font_family . ';';
          $values['settings_font_use_googlefonts'] = TRUE;
        }
        else {
          $fonts[$font_key]['family'] = 'font-family: inherit;';
        }
      }

      // Typekit.
      if ($values['settings_font_' . $font_key] == 'typekit') {
        if (!empty($values['settings_font_typekit_' . $font_key])) {
          $fonts[$font_key]['family'] = 'font-family: ' . $values['settings_font_typekit_' . $font_key] . ', ' . $fallback_font_family . ';';
          $values['settings_font_use_typekit'] = TRUE;
        }
        else {
          $fonts[$font_key]['family'] = 'font-family: inherit;';
        }
      }
    }

    // Font smoothing.
    if (isset($values['settings_font_smoothing_' . $font_key]) && $values['settings_font_smoothing_' . $font_key] == 1) {
      $fonts[$font_key]['smoothing'] = ' -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;';
    }
  }

  // Output data to file.
  if (!empty($fonts)) {
    foreach ($fonts as $font_key => $font_values) {
      if (isset($font_values['family']) || isset($font_values['size'])) {
        $font_style = $font_values['selectors'] . ' { ';

        if (isset($font_values['family'])) {
          $font_style .= $font_values['family'];
        }

        if (isset($font_values['size'])) {
          $font_style .= $font_values['size'];
        }

        if (isset($font_values['lineheight'])) {
          $font_style .= $font_values['lineheight'];
        }

        if (isset($font_values['smoothing'])) {
          $font_style .= 	$font_values['smoothing'];
        }

        $font_style .= ' }';
        $font_styles[] = $font_style;
      }
    }

    $output = implode("\n", $font_styles);
  }

  $output = $output ? Xss::filter($output) : '/** No fonts styles set **/';

  $file_name = 'fonts.css';
  $filepath = "$generated_files_path/$file_name";
  file_unmanaged_save_data($output, $filepath, FILE_EXISTS_REPLACE);

  // Return modified values to convert to config.
  return $values;
}

