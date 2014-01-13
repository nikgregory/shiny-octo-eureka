<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutAttributes.
 */

namespace Drupal\at_core\Layout;

use Drupal\Core\Template\Attribute;
use Drupal\at_core\Layout\PageLayout;

class LayoutAttributes extends PageLayout {

  // Return row attributes
  public function rowAttributes() {
    $variables = array();
    $row_region_intersect = array();

    $layout_data = self::buildLayoutDataArrays();

    // If rows are empty return early.
    if (empty($layout_data['rows'])) {
      return;
    }

    // Discard CSS key and build array of rows with region and attribute values.
    foreach ($layout_data['rows'] as $row => $regions) {
      $rows[$row] = $regions;

      // Set an increment value for each region in a row. "rso" is "row source order".
      $i = 1;
      foreach ($regions['regions'] as $rg_key => $rg_value) {
        $rso[$row][$rg_key] = $i++;
      }
    }

    foreach ($rows as $row_key => $row_values) {
      // Instantiate attribute object arrays per row.
      $variables[$row_key . '__attributes'] = new Attribute(array('class' => array()));
      $variables[$row_key . '__attributes']['class'][] = 'page-row__'. str_replace('_', '-', $row_key);

      // Set row attributes as defined in the layout configuration yml file.
      if (isset($row_values['attributes'])) {
        foreach ($row_values['attributes'] as $attribute => $value) {
          if (is_array($value)) {
            foreach ($value as $attr_value) {
              $variables[$row_key . '__attributes'][$attribute][] = $attr_value;
            }
          }
          else {
            $variables[$row_key . '__attributes'][$attribute] = $value;
          }
        }
      }

      // Count total number of regions per row.
      $total_region_count[$row_key]['total_count'] = count($row_values['regions']);

      // Intersect all-regions with active-regions; only set attributes for visible active-regions.
      if (!empty($row_values['regions'])) {
        foreach ($row_values['regions'] as $region_key => $region_name) {
          $new_row_values[$row_key][] = $region_key;
        }
      }

      $row_region_intersect[$row_key] = array_intersect($new_row_values[$row_key], $this->regions);
    }

    // Set additional attributes for rows.
    foreach ($row_region_intersect as $row_region_key => $row_region_values) {

      // Set a boolean for active regions, assume false.
      $variables[$row_region_key . '__regions']['active'] = FALSE;

      if (!empty($row_region_intersect[$row_region_key])) {

        // Count how many active regions.
        $count = count($row_region_values);

        // If active regions set to true to print the row, basically a catch all condition.
        $variables[$row_region_key . '__regions']['active'] = TRUE;

        // Total count class.
        $variables[$row_region_key . '__attributes']['class'][] = 'total-count--' . $total_region_count[$row_region_key]['total_count'];

        // Region count class.
        $variables[$row_region_key . '__attributes']['class'][] = 'active-count--'. $count;

        // Active region classes.
        foreach ($row_region_values as $region_class) {

          if ($rso[$row_region_key][$region_class]) {
            $rso_count_class[$row_region_key][] = $rso[$row_region_key][$region_class];
          }

          //$variables[$row_region_key . '__attributes']['class'][] = str_replace('_', '-', $region_class);
        }
      }

      // rso, or "region source order" tells us which regions are actually printing based on the original source order, this
      // is a powerful class that will be used for any order columns type layout with minimal CSS and classes, think of this
      // like Drupals "two-sidebars" type class but automated for any row and an any number of regions.
      if (isset($rso_count_class[$row_region_key])) {
        $variables[$row_region_key . '__attributes']['class'][] =  'active-regions--' . implode('-', $rso_count_class[$row_region_key]);
      }

    }

    return $variables;
  }

}  // end class
