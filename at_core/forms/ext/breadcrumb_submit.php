<?php

/**
 * @file
 * Save Breadcrumb CSS to file
 */
function at_core_submit_breadcrumb($values, $theme, $generated_files_path) {
  $breadcrumb_css = '';

  if (($values['settings_breadcrumb_title'] &&  $values['settings_breadcrumb_title'] === 1)) {
    $css[] = '.breadcrumb__title,.breadcrumb ol{display:inline}';
    $css[] = '.breadcrumb__title{font-size:1rem}';
  }

  if (!empty($values['settings_breadcrumb_separator'])) {
    $css[] = '.breadcrumb li:before{content: "' . t($values['settings_breadcrumb_separator']) . '"}';
  }

  $breadcrumb_css = implode("\n", $css);

  $file_name = $theme . '--breadcrumb.css';
  $filepath = $generated_files_path . '/' . $file_name;
  file_unmanaged_save_data($breadcrumb_css, $filepath, FILE_EXISTS_REPLACE);
}
