<?php

use Drupal\Component\Utility\String;
use Drupal\Component\Utility\Xss;

/**
 * @file
 * Save Breadcrumb CSS to file
 */
function at_core_submit_breadcrumb($values, $theme, $generated_files_path) {
  $breadcrumb_css = '';
  if (!empty($values['settings_breadcrumb_separator'])) {
    $css = '.breadcrumb li:before {content: "' . String::checkPlain($values['settings_breadcrumb_separator']) . '"}';
  }
  if (!empty($css)) {
    $file_name = 'breadcrumb.css';
    $filepath = $generated_files_path . '/' . $file_name;
    file_unmanaged_save_data($css, $filepath, FILE_EXISTS_REPLACE);
  }
}
