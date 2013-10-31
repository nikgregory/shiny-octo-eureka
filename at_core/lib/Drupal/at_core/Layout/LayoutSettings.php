<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutSettings.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Layout\PageLayout;

class LayoutSettings extends PageLayout {

  // Prepare layout data for use in theme settings
  public function settingsPrepareData($key = '') {
    $layout_config = self::parseLayoutConfig();
    if (!empty($key)) {
      return $layout_config[$key];
    }

    // Remove hidden layouts.
    foreach ($layout_config as $layout => $config) {
      if (isset($layout_config[$layout]['hidden']) && $layout_config[$layout]['hidden'] == TRUE) {
        unset($layout_config[$layout]);
      }
    }

    return $layout_config;
  }

  // format rows and regions into classes and attributes etc
  public function formatSelectors($key) {
    $options_data = self::settingsPrepareData($key);

    foreach ($options_data as $row => $rows) {
      if ($row == 'rows') {
        foreach ($rows as $row_key => $row_name) {

          if (isset($row_name['regions'])) {
            $region_count = count($row_name['regions']);

            // Add comment with row name and region count
            $trc_label = format_plural($region_count, '1 region', '@count regions');
            $selectors['selectors'][$row_key]['comment'] = '/* ' . ucfirst($row_key) . ' (' .  $trc_label . ') */';

            // Row class selectors.
            $selectors['selectors'][$row_key]['row'] = '.page-row-' . str_replace('_', '-', $row_key) . ' {}';
            foreach ($row_name['regions'] as $region_key => $region_names) {
              //$selectors['selectors'][$row_key]['count'] = '.total-regions-' . $region_count . ' {}';
              //$selectors['selectors'][$row_key][$region_key] =  '.' . str_replace('_', '-', $region_key) . ' {}';
            }
          }
          else {
            drupal_set_message(t('Layout formatting error: <code>regions:</code> key not declared or yml is malformed in the <code>!brokenlayout</code> layout. Check all rows have a <code>regions:</code> declaration and are properly nested and indented.', array('!brokenlayout' => $key)), 'error');
            break;
          }

          // Row attribute selectors, incl id, role and others.
          if (isset($row_name['attributes'])) {
            foreach ($row_name['attributes'] as $row_attr_key => $row_attr_value) {
              if ($row_attr_key == 'id') {$attr['id'] = '#' . $row_attr_value . ' {}';}
              if ($row_attr_key == 'role') {$attr['role'] = '[role="' . $row_attr_value . '"] {}';}
              foreach ($attr as $key => $value) {
                $selectors['selectors'][$row_key][$key] = $value;
              }
            }
          }
        }
      }
    }

    return $selectors;
  }

  // Layout Options
  public function layoutOptions() {
    $options_data = self::settingsPrepareData();
    $options = array();
    $screenshot_link_title = t('Enlarge');
    foreach ($options_data as $layout => $values) {

      // Fallback name in case name: key isset but empty for some reason.
      $layout_label = drupal_ucfirst($layout);

      // Prepare variables for the screenshot.
      $screenshot_path = base_path() . $this->layouts_path . $layout . '/' . $values['screenshot'];
      $screenshot_title = t('Screenshot for !layout_label', array('!layout_label' => $layout_label));
      $screenshot_enlarge_text = t('View larger');

      // Build the layout options
      $output = array();
      $output['name']       = isset($values['name']) ? $values['name'] : $layout_label;
      $output['desc']       = isset($values['description']) ? $values['description'] : '';
      $output['series']     = isset($values['series'])      ? $values['series'] : 'not-set';
      $output['version']    = isset($values['version'])     ? $values['version'] : '';
      $output['screenshot'] = isset($values['screenshot'])  ? '<a title="' . $screenshot_title . '" href="' . $screenshot_path . '" rel="lightbox"><img src="' . $screenshot_path . '" alt="'. $layout_label . '" /><i class="plus-icon">' . $screenshot_enlarge_text . '</i></a>' : '';

      // Build the final structure for output.
      $options[$output['series']][$layout] = $output;
    }

    return $options;
  }

}  // end class
