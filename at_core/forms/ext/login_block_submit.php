<?php

/**
 * @file
 * Save custom CSS to file
 */
function at_core_submit_login_block($values, $theme, $generated_files_path) {
  $login_block_css = '';

  // Set the heading font size.
  $font_size_px = '16px;';
  $font_size_rem = '1rem;';

  // Override if fonts extension is on and a default font size is set.
  if (isset($values['settings_enable_fonts']) && $values['settings_enable_fonts'] === 1) {
    if (!empty($values['settings_base_font_size'])) {
      $font_size_px = $values['settings_base_font_size'] . 'px;';
      //$font_size_rem = $values['settings_base_font_size'] / $values['settings_base_font_size'] . 'rem;';
    }
  }

  $login_block_css = ".block-login--horizontal .block__title {
  font-size:$font_size_px;
  font-size:$font_size_rem;
}
.block-login--horizontal .block__title,
.block-login--horizontal > div,
.block-login--horizontal .user-login-form,
.block-login--horizontal .user-login-form > div,
.block-login--horizontal .form-item,
.block-login--horizontal .form-actions {
  display:inline;
}";

  $file_name = $theme . '--login-block.css';
  $filepath = $generated_files_path . '/' . $file_name;
  file_unmanaged_save_data($login_block_css, $filepath, FILE_EXISTS_REPLACE);
}
