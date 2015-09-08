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

    foreach ($this->layout_config['rows'] as $row_name => $row_data) {
      foreach ($row_data['regions'] as $region_key => $region_name) {
        if ($region_key == $region) {
          $region_row = $row_name;
          break;
        }
      }
    }

    return $region_row;
  }

  // Return row attributes
  // Does this become rowContainerAttributes(), with a wrapper attributes method of rowWrapperAttributes() ?
  public function rowAttributes() {
    $variables = array();
    $active_row_regions = array();
    $config_settings = \Drupal::config($this->theme_name . '.settings')->get('settings');

    // If rows are empty return early.
    if (empty($this->layout_config['rows'])) {
      return;
    }

    // Build array of rows with region values.
    foreach ($this->layout_config['rows'] as $row_name => $row_data) {

      // Set a bool for active regions, assume false.
      $variables[$row_name . '__regions']['active'] = FALSE;

      $i = 1;
      foreach ($row_data['regions'] as $region_key => $region_name) {
        $region_source_order[$row_name][$region_key] = $i++; // Set an increment value for each region for the .hr class (has-regions)
        $row_regions[$row_name][] = $region_key; // Build array to intersect and use for the .arc class (active region count).
      }

      // Pass on row wrapper attributes only for rows with active regions
      $active_row_regions[$row_name]['attributes'] = $row_data['attributes'];

      // Remove inactive regions.
      $active_row_regions[$row_name]['regions'] = array_intersect($row_regions[$row_name], $this->active_regions);

      // Unset inactive rows.
      if (empty($active_row_regions[$row_name]['regions'])) {
        unset($active_row_regions[$row_name]);
      }
    }

    // Set additional attributes for rows.
    foreach ($active_row_regions as $row_key => $row_values) {
      //if (!empty($row_values['regions'])) {}

      // If active regions set to true to print the row, basically a catch all condition.
      $variables[$row_key . '__regions']['active'] = TRUE;

      // Wrapper attributes.
      $variables[$row_key . '__wrapper_attributes'] = new Attribute;
      $variables[$row_key . '__wrapper_attributes']['class'] = array('l-pr', 'page__row', 'pr-' . $row_key);
      foreach ($row_values['attributes'] as $attribute_type => $attribute_values) {
        if (is_array($attribute_values)) {
          $variables[$row_key . '__wrapper_attributes'][$attribute_type] = array(implode(' ', $attribute_values));
        }
        else {
          $variables[$row_key . '__wrapper_attributes'][$attribute_type] = array($attribute_values);
        }
      }

      // Container attributes.
      $variables[$row_key . '__container_attributes'] = new Attribute;
      $variables[$row_key . '__container_attributes']['class'] = array('l-rw', 'regions', 'container', 'pr-'. str_replace('_', '-', $row_key) . '__rw');

      // Active Regions: "arc" is "active region count", this is number of active regions in this row on this page.
      $count = count($row_values['regions']);
      $variables[$row_key . '__container_attributes']['class'][] = 'arc--'. $count;

      // Match each active region with its'corrosponding source order increment.
      foreach ($row_values['regions'] as $region) {
        if (isset($region_source_order[$row_key][$region])) {
          $row_has_regions[$row_key][] = $region_source_order[$row_key][$region];
        }
      }

      // Has Regions: the "hr" class tells us which regions are active by source order (as per the layout markup yml),
      // this allows us to push layout dependant on exactly which regions are active.
      if (isset($row_has_regions[$row_key])) {
        $variables[$row_key . '__container_attributes']['class'][] =  'hr--' . implode('-', $row_has_regions[$row_key]);
      }

      // Shortcode classes.
      if (isset($config_settings['enable_extensions']) && $config_settings['enable_extensions'] === 1) {
        if (isset($config_settings['enable_shortcodes']) && $config_settings['enable_shortcodes'] === 1) {

          // Wrapper codes
          if (!empty($config_settings['page_classes_row_wrapper_' . $row_key])) {
            $shortcodes = Tags::explode($config_settings['page_classes_row_wrapper_' . $row_key]);
            foreach ($shortcodes as $class) {
              $variables[$row_key . '__wrapper_attributes']['class'][] = Html::cleanCssIdentifier($class);
            }
          }

          // Container codes
          if (!empty($config_settings['page_classes_row_container_' . $row_key])) {
            $shortcodes = Tags::explode($config_settings['page_classes_row_container_' . $row_key]);
            foreach ($shortcodes as $class) {
              $variables[$row_key . '__container_attributes']['class'][] = Html::cleanCssIdentifier($class);
            }
          }
        }
      }
    }

    return $variables;
  }
}
