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
            $selectors['selectors'][$row_key]['row'] = '.page-row__' . str_replace('_', '-', $row_key) . ' {}';
            foreach ($row_name['regions'] as $region_key => $region_names) {
              //$selectors['selectors'][$row_key]['count'] = '.total-regions-' . $region_count . ' {}';
              $selectors['selectors'][$row_key][$region_key] =  '.region__' . str_replace('_', '-', $region_key) . ' {}';
            }
          }
          else {
            drupal_set_message(t('Layout formatting error: <code>regions:</code> key not declared or yml is malformed in the <code>!brokenlayout</code> layout. Check all rows have a <code>regions:</code> declaration and are properly nested and indented.', array('!brokenlayout' => $key)), 'error');
            break;
          }

          // Row attribute selectors, incl id, role and others.
          if (isset($row_name['attributes'])) {
            //kpr($row_name['attributes']);
            foreach ($row_name['attributes'] as $row_attr_key => $row_attr_value) {
              if ($row_attr_key == 'id') {
                $selectors['selectors'][$row_key]['id'] = '#' . $row_attr_value . ' {}';
              }
              if ($row_attr_key == 'role') {
                //$selectors['selectors'][$row_key]['role'] = '[role="' . $row_attr_value . '"] {}';
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

    foreach ($options_data as $layout_plugin => $values) {
      $plugin_label = drupal_ucfirst($values['name']);
      $version = $values['version'];

      foreach ($values['css_layouts'] as $css_layout => $css_layout_values) {
        $css_layout_name = drupal_ucfirst($css_layout);
        $screenshot_path = base_path() . $this->plugin_path . $layout_plugin . '/' . $values['css_layouts_path'] . '/' . $css_layout . '/' . $css_layout_values['screenshot'];

        $screenshot_title = t('Screenshot for !css_layout_name', array('!css_layout_name' => $css_layout_name));
        $screenshot_enlarge_text = t('View larger');

        $output = array();
        $output['name']    = $css_layout_name;
        $output['plugin']  = $plugin_label;
        $output['desc']    = isset($css_layout_values['description']) ? $css_layout_values['description'] : '';
        $output['version'] = $version;
        $output['screenshot'] = isset($css_layout_values['screenshot'])  ? '<a title="' . $screenshot_title . '" href="' . $screenshot_path . '" rel="lightbox"><img src="' . $screenshot_path . '" alt="'. $css_layout_name . '" /><i class="plus-icon">' . $screenshot_enlarge_text . '</i></a>' : '';

        $options[$layout_plugin][$css_layout] = $output;
      }
    }

    return $options;
  }

}  // end class
