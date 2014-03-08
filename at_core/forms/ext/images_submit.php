<?php

/**
 * @file
 * Generate image styles.
 */
function at_core_submit_images($values, $theme, $generated_files_path) {
  $images_styles = array();

  // Get node types (bundles).
  $node_types = node_type_get_types();

  // View or "Display modes", the search display mode is still problematic so we will exclude it for now,
  // please see: https://drupal.org/node/1166114
  $node_view_modes = entity_get_view_modes('node');

  // Unset unwanted view modes
  unset($node_view_modes['rss']);
  unset($node_view_modes['search_index']);
  unset($node_view_modes['search_result']);

  // Extract values and build bundle data arrays.
  $bundle_data = array();
  foreach ($node_types as $nt) {

    $node_type = $nt->type;
    $node_type_selector = '.node--' . $node_type;

    foreach ($node_view_modes as $display_mode) {

      $display_mode_id = str_replace('.', '_', $display_mode['id']);
      $display_mode_selector = '.view-mode--' . substr($display_mode['id'], 5);

      if (isset($values['settings_image_alignment_' . $node_type . '_' .  $display_mode_id])) {
        $bundle_data[$node_type]['selector'] = $node_type_selector;
        $bundle_data[$node_type][$display_mode_id]['align'] = (string) $values['settings_image_alignment_' . $node_type . '_' .  $display_mode_id];
        $bundle_data[$node_type][$display_mode_id]['selector'] = (string) $display_mode_selector;
      }
    }
  }

  // Format CSS.
  if (!empty($bundle_data)) {
    $view_mode_selector = '';
    $declaration = '';
    foreach ($bundle_data as $bundle_key => $bundle_values) {
      foreach ($bundle_values as $view_mode_key => $view_mode_values) {
        if (isset($view_mode_values['selector'])) {
          $view_mode_selector = $view_mode_values['selector'];
        }
        if (isset($view_mode_values['align'])) {
          $declaration = 'float:' . $view_mode_values['align'];
          if ($view_mode_values['align'] == 'center') {
            $declaration = 'margin-left:auto;margin-right:auto;text-align:center';
          }
        }
        $css[$bundle_key][$view_mode_key] = $bundle_values['selector'] . $view_mode_selector . ' .field-type--image{' .  $declaration . '}';
      }
      unset($css[$bundle_key]['selector']);
    }
    foreach ($css as $bundle_type => $bundle_type_styles) {
      $output[$bundle_type] = implode("\n", $bundle_type_styles);
      $output['figcaption_style'] = 'figcaption{margin-left:auto;margin-right:auto}';
    }

    // Output data to file.
    $image_styles = implode("\n", $output);
    if (!empty($image_styles)) {
      $file_name = $theme . '--images.css';
      $filepath = "$generated_files_path/$file_name";
      file_unmanaged_save_data($image_styles, $filepath, FILE_EXISTS_REPLACE);
    }
  }
}
