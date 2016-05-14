<?php

/**
 * @file
 * Save Mobile Blocks CSS to file.
 */

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
    foreach ($breakpoint_groups as $group_key => $group_values) {
      $breakpoints[$group_key] = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($group_key);
    }
  }

  $mobile_blocks_breakpoint_group = theme_get_setting('settings.mobile_blocks_breakpoint_group', $theme) ?: 'at_core.simple';
  $mobile_blocks_breakpoints = $breakpoints[$mobile_blocks_breakpoint_group];

  // TODO entityManager() is deprecated, but how to replace?
  $theme_blocks = \Drupal::entityManager()->getStorage('block')->loadByProperties(['theme' => $theme]);

  if (!empty($theme_blocks)) {
    foreach ($mobile_blocks_breakpoints as $mbs_key => $mbs_value) {
      $mbs_query = $mbs_value->getMediaQuery();
      $mbs_breakpoints_all[$mbs_query] = $mbs_query;
      $mbs_label = strtolower($mbs_value->getLabel());

      foreach ($theme_blocks as $block_key => $block_values) {
        $block_id = $block_values->id();
        $block_selector[$mbs_label] = '#block-' . $block_id;

        if (isset($values['settings_mobile_block_show_' . 'bp' . $mbs_label . '_' . $block_id]) && $values['settings_mobile_block_show_' . 'bp' . $mbs_label . '_' . $block_id] == 1) {
          $show_blocks[$mbs_label][] = '  ' . $block_selector[$mbs_label] . ' {display:block}';
        }
        if (isset($values['settings_mobile_block_hide_' . 'bp' . $mbs_label . '_' . $block_id]) && $values['settings_mobile_block_hide_' . 'bp' . $mbs_label . '_' . $block_id] == 1) {
          $hide_blocks[$mbs_label][] = '  ' . $block_selector[$mbs_label] . ' {display:none}';
        }
      }

      $hide_blocks[$mbs_label] = implode("\n", $hide_blocks[$mbs_label]);
      $show_blocks[$mbs_label] = implode("\n", $show_blocks[$mbs_label]);
      $output[$mbs_label][] = '@media ' . $mbs_query . ' {' . "\n" . $hide_blocks[$mbs_label] . "\n" . $show_blocks[$mbs_label] . "\n" . '}';
      $mobile_blocks_css[] = implode("\n", $output[$mbs_label]);
    }

    $mobile_blocks_css = implode("\n", $mobile_blocks_css);
    if (!empty($mobile_blocks_css)) {
      $file_name = 'mobile-blocks.css';
      $filepath = $generated_files_path . '/' . $file_name;
      file_unmanaged_save_data($mobile_blocks_css, $filepath, FILE_EXISTS_REPLACE);
    }
  }
}
