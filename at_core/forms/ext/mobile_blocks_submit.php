<?php

/**
 * @file
 * Save Mobile Blocks CSS to file.
 */

use Drupal\Component\Utility\Html;

/**
 * Submit Mobile Blocks settings.
 * @param $values
 * @param $theme
 * @param $generated_files_path
 */
function at_core_submit_mobile_blocks($values, $theme, $generated_files_path) {
  $mobile_blocks_css = array();
  // TODO entityManager() is deprecated, but how to replace?
  $theme_blocks = \Drupal::entityManager()->getStorage('block')->loadByProperties(['theme' => $theme]);

  if (!empty($theme_blocks)) {
    foreach ($theme_blocks as $block_key => $block_values) {
      $block_id = $block_values->id();
      if (isset($values['settings_mobile_block_show_' . $block_id]) && $values['settings_mobile_block_show_' . $block_id] == 1) {
        $block_selector = '#' . Html::getUniqueId('block-' . $block_id);
        $mobile_blocks_css[] = $block_selector . ' {display:none}' . "\n";
        $mobile_blocks_css[] = '.is-mobile ' . $block_selector . ' {display:block}' . "\n";
      }
      if (isset($values['settings_mobile_block_hide_' . $block_id]) && $values['settings_mobile_block_hide_' . $block_id] == 1) {
        $block_selector = '#' . Html::getUniqueId('block-' . $block_id);
        $mobile_blocks_css[] = '.is-mobile ' . $block_selector . ' {display:none}' . "\n";
        $mobile_blocks_css[] = $block_selector . ' {display:block}' . "\n";
      }
    }
  }

  if (!empty($mobile_blocks_css)) {
    $file_name = 'mobile-blocks.css';
    $filepath = $generated_files_path . '/' . $file_name;
    file_unmanaged_save_data($mobile_blocks_css, $filepath, FILE_EXISTS_REPLACE);
  }
}
