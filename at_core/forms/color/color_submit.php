<?php

/**
 * @file
 * Saves a parsable inc file with the full color info array for the active theme.
 *
 * If a custom color scheme has been created in the UI it is injected into the
 * schemes array and saved. You must rename the Custom scheme and give it a
 * unique array key before using the generated file in your theme.
 *
 * Note that color module validates the input of the color form and this is not
 * run if there is a problem, e.g. the user inputting non hexadecimal CSS color
 * strings, which color module validates to avoid XSS.
 */
function at_core_submit_color(&$form, &$form_state) {

  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];

  $palette = $values['palette'];

  $indent = str_pad(' ', 6);
  $lines = explode("\n", var_export($palette, TRUE));

  array_shift($lines);

  $message  = "    'PaletteName' => array(\n";
  $message .= $indent . "'title' => t('PaletteName'),\n";
  $message .= $indent . "'colors' => array(\n";
  $last_line = $indent . array_pop($lines) . ',';

  foreach ($lines as $line) {
    if (strpos($line, ' => ') !== FALSE) {
      $parts = explode(' => ', $line);
      $message .= $indent . $parts[0] . str_pad(' ', (46 - strlen($line))) . '=> ' . $parts[1];
    } else {
      $message .=  "$indent  $line";
    }
    $message .=  "\n";
  }

  $message .= "$last_line\n";
  $message .= "    ),\n";
  $message = '<pre>' . $message . '</pre>';

  //watchdog('Custom color palette', $message);
  \Drupal::logger($theme)->notice($message);
}
