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

  // Check for breakpoints module.
  $breakpoints_module = \Drupal::moduleHandler()->moduleExists('breakpoint');

  if ($breakpoints_module == TRUE) {
    $breakpoint_groups = \Drupal::service('breakpoint.manager')->getGroups();
    $breakpoints = array();

    // Unset core breakpoint groups due to notices and other issues, until this
    // is resolved: SEE: https://www.drupal.org/node/2379283
    unset($breakpoint_groups['toolbar']);
    unset($breakpoint_groups['seven']);
    unset($breakpoint_groups['bartik']);

    foreach ($breakpoint_groups as $group_key => $group_values) {
      $breakpoints[$group_key] = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($group_key);
    }
  }

  $mobile_blocks_css = '';
  $mobile_breakpoint = $values['settings_mobile_blocks_breakpoint'];

  $mobile_blocks_breakpoint_group = theme_get_setting('settings.mobile_blocks_breakpoint_group', $theme) ?: 'at_core.simple';
  $mobile_blocks_breakpoints = $breakpoints[$mobile_blocks_breakpoint_group];

  foreach ($mobile_blocks_breakpoints as $mbs_key => $mbs_value) {
  $mbs_query = $mbs_value->getMediaQuery();
  $mbs_breakpoints_all[$mbs_query] = $mbs_query;
  }

  // TODO entityManager() is deprecated, but how to replace?
  $theme_blocks = \Drupal::entityManager()->getStorage('block')->loadByProperties(['theme' => $theme]);

  if (!empty($theme_blocks)) {
    foreach ($theme_blocks as $block_key => $block_values) {
      $block_id = $block_values->id();
      if (isset($values['settings_mobile_block_show_' . $block_id]) && $values['settings_mobile_block_show_' . $block_id] == 1) {
        $block_selector = '#' . Html::getUniqueId('block-' . $block_id);
        $hide_blocks[] = '  ' . $block_selector . ' {display:none}';
        $show_blocks[] = '  ' . $block_selector . ' {display:block}';
      }
      if (isset($values['settings_mobile_block_hide_' . $block_id]) && $values['settings_mobile_block_hide_' . $block_id] == 1) {
        $block_selector = '#' . Html::getUniqueId('block-' . $block_id);
        $show_blocks[] = '  ' . $block_selector . ' {display:none}';
        $hide_blocks[] = '  ' . $block_selector . ' {display:block}';
      }
    }
  }

  $output[] = '@media ' . $mobile_breakpoint . ' {' . "\n" .  implode("\n", $show_blocks) . "\n" . '}';

  if (!empty($mbs_breakpoints_all)) {
    $breakpoints_reversed = array_reverse($mbs_breakpoints_all);
    unset($breakpoints_reversed[$mobile_breakpoint]);
    foreach($breakpoints_reversed as $other_bp) {
      $output[] = '@media ' . $other_bp . ' {' . "\n" . implode("\n", $hide_blocks) . "\n" . '}';
    }
  }

  $mobile_blocks_css = implode("\n", $output);

  if (!empty($mobile_blocks_css)) {
    $file_name = 'mobile-blocks.css';
    $filepath = $generated_files_path . '/' . $file_name;
    file_unmanaged_save_data($mobile_blocks_css, $filepath, FILE_EXISTS_REPLACE);
  }
}
