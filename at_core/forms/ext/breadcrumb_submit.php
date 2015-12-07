<?php

/**
 * @file
 * Save Breadcrumb CSS to file
 */

use Drupal\Component\Utility\Html;

function at_core_submit_breadcrumb($values, $generated_files_path) {
  if (!empty($values['settings_breadcrumb_separator'])) {
    $css = '.breadcrumb li:before {content: "' . Html::escape($values['settings_breadcrumb_separator']) . '"}';
  }
  if (!empty($css)) {
    $file_name = 'breadcrumb.css';
    $filepath = $generated_files_path . '/' . $file_name;
    file_unmanaged_save_data($css, $filepath, FILE_EXISTS_REPLACE);
  }
}
