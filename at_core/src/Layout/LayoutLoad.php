<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutLoad.
 */

namespace Drupal\at_core\Layout;

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Html;

use Drupal\at_core\Layout\Layout;
use Drupal\at_core\Layout\LayoutCompatible;


class LayoutLoad extends Layout implements LayoutLoadInterface {

  // The active theme name.
  protected $theme_name;

  // The active regions on page load.
  protected $active_regions;


  // Constructor
  public function __construct($theme_name, $active_regions) {

    $this->theme_name = $theme_name;
    $this->active_regions = $active_regions;

    $layout_data = new LayoutCompatible($this->theme_name);
    $layout_compatible_data = $layout_data->getCompatibleLayout();

    $this->layout_config = $layout_compatible_data['layout_config'];
  }

  // Return the pages active regions
  public function activeRegions() {
    return $this->active_regions;
  }

  // Returns the row name for a region.
  public function regionAttributes($region) {
    $region_row = '';
    $config_settings = \Drupal::config($this->theme_name . '.settings')->get('settings');

    // If rows are empty return early.
    if (empty($this->layout_config['rows'])) {
      return;
    }

    foreach ($this->layout_config['rows'] as $row => $regions) {
      foreach ($regions['regions'] as $region_key => $region_name) {
        if ($region_key == $region) {
          $region_row = $row;
          break;
        }
      }
    }

    return $region_row;
  }

  // Return row attributes
  public function rowAttributes() {
    $variables = array();
    $row_region_intersect = array();
    $config_settings = \Drupal::config($this->theme_name . '.settings')->get('settings');

    // If rows are empty return early.
    if (empty($this->layout_config['rows'])) {
      return;
    }

    // Build array of rows with region and attribute values.
    foreach ($this->layout_config['rows'] as $row => $regions) {
      $rows[$row] = $regions;

      // Set an increment value for each region in a row. "rso" is "row source order".
      $i = 1;
      foreach ($regions['regions'] as $rg_key => $rg_value) {
        $rso[$row][$rg_key] = $i++;
      }
    }

    // Loop over rows and build attributes, cull out inactive regions, settings attributes for inactive
    // regions is a performance hit of massive proportions.
    foreach ($rows as $row_key => $row_values) {
      if (!empty($row_values['regions'])) {
        foreach ($row_values['regions'] as $region_key => $region_name) {
          $new_row_values[$row_key][] = $region_key;
        }
      }
      $row_region_intersect[$row_key] = array_intersect($new_row_values[$row_key], $this->active_regions);
    }

    // Set additional attributes for rows.
    foreach ($row_region_intersect as $row_region_key => $row_region_values) {

      // Set a bool for active regions, assume false.
      $variables[$row_region_key . '__regions']['active'] = FALSE;

      if (!empty($row_region_values)) {

        // Instantiate attribute object arrays per row.
        $variables[$row_region_key . '__attributes'] = new Attribute(array('class' => array()));

        // Add the row class in page generate, shift the attributes to the regions wrapper
        $variables[$row_region_key . '__attributes']['class'][] = 'l-rw';  // rw = region wrapper
        $variables[$row_region_key . '__attributes']['class'][] = 'regions';
        $variables[$row_region_key . '__attributes']['class'][] = 'pr-'. str_replace('_', '-', $row_region_key) . '__rw'; // pr = page row

        // Add theme setting defined classes if Classitis is enabled.
        if (isset($config_settings['enable_extensions']) && $config_settings['enable_extensions'] === 1) {
          if (isset($config_settings['enable_shortcodes']) && $config_settings['enable_shortcodes'] === 1) {
            if (!empty($config_settings['page_classes_row_' . $row_region_key])) {
              $shortcodes = Tags::explode($config_settings['page_classes_row_' . $row_region_key]);
              foreach ($shortcodes as $class) {
                $variables[$row_region_key . '__attributes']['class'][] = Html::getClass($class);
              }
            }
          }
        }

        // Set row attributes as defined in the layout configuration yml file.
        if (isset($row_region_values['attributes'])) {
          foreach ($row_region_values['attributes'] as $attribute => $value) {
            if (is_array($value)) {
              foreach ($value as $attr_value) {
                $variables[$row_region_key . '__attributes'][$attribute][] = $attr_value;
              }
            }
            else {
              $variables[$row_region_key . '__attributes'][$attribute] = $value;
            }
          }
        }

        if (!empty($row_region_intersect[$row_region_key])) {

          // Count how many active regions.
          $count = count($row_region_values);

          // If active regions set to true to print the row, basically a catch all condition.
          $variables[$row_region_key . '__regions']['active'] = TRUE;

          // Region count class. "arc" is "active region count"
          $variables[$row_region_key . '__attributes']['class'][] = 'arc--'. $count;

          // Active region classes.
          foreach ($row_region_values as $region_class) {
            if ($rso[$row_region_key][$region_class]) {
              $rso_count_class[$row_region_key][] = $rso[$row_region_key][$region_class];
            }
          }
        }
      }

      // rso, or "region source order" tells us which regions are actually printing based on the original source order, this
      // is a powerful class that will be used for any order columns type layout with minimal CSS and classes, think of this
      // like Drupals "two-sidebars" type class but automated for any row and any number of regions.
      // The class hr is "has regions".
      if (isset($rso_count_class[$row_region_key])) {
        $variables[$row_region_key . '__attributes']['class'][] =  'hr--' . implode('-', $rso_count_class[$row_region_key]);
      }
    }

    return $variables;
  }

}
