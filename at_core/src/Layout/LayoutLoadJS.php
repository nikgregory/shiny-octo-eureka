<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutLoad
 */

namespace Drupal\at_core\Layout;

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Html;

class LayoutLoadJS extends Layout {

  // The active theme name.
  protected $theme_name;


  /**
   * LayoutInterface constructor.
   * @param $theme_name
   * @param $active_regions
   */
  public function __construct($theme_name) {
    $this->theme_name = $theme_name;
    $layout_data = new LayoutCompatible($this->theme_name);
    $layout_compatible_data = $layout_data->getCompatibleLayout();
    $this->layout_config = $layout_compatible_data['layout_config'];
  }

  /**
   * {@inheritdoc}
   */
  public function rowAttributes() {
    $variables = array();
    $config_settings = \Drupal::config($this->theme_name . '.settings')->get('settings');

    // If rows are empty return early.
    if (empty($this->layout_config['rows'])) {
      return NULL;
    }

    // Set additional attributes for rows.
    foreach ($this->layout_config['rows'] as $row_key => $row_values) {

      // If active regions set to true, print the row.
      //$variables[$row_key]['has_regions'] = TRUE;

      $row_key_class = str_replace('_', '-', $row_key);

      // Wrapper attributes.
      $variables[$row_key]['wrapper_attributes'] = new Attribute;
      $variables[$row_key]['wrapper_attributes']['class'] = array('l-pr', 'page__row', 'pr-' . $row_key_class);

      // Wrapper attributes set in the layout yml file.
      foreach ($row_values['attributes'] as $attribute_type => $attribute_values) {
        if (is_array($attribute_values)) {
          $variables[$row_key]['wrapper_attributes'][$attribute_type] = array(implode(' ', $attribute_values));
        }
        else {
          $variables[$row_key]['wrapper_attributes'][$attribute_type] = array($attribute_values);
        }
      }

      // Set class multiple
      if (count($row_values['regions']) > 1) {
        $variables[$row_key]['wrapper_attributes']['class'][] = 'regions-multiple';
      }

      // Container attributes.
      $variables[$row_key]['container_attributes'] = new Attribute;
      $variables[$row_key]['container_attributes']['class'] = array('l-rw', 'regions', 'container', 'pr-'. $row_key_class . '__rw');
      $variables[$row_key]['container_attributes']['data-at-regions'] = '';

      // Shortcode classes.
      if (isset($config_settings['enable_extensions']) && $config_settings['enable_extensions'] === 1) {
        if (isset($config_settings['enable_shortcodes']) && $config_settings['enable_shortcodes'] === 1) {

          // Wrapper codes
          if (!empty($config_settings['page_classes_row_wrapper_' . $row_key])) {
            $wrapper_codes = Tags::explode($config_settings['page_classes_row_wrapper_' . $row_key]);
            foreach ($wrapper_codes as $wrapper_class) {
              $variables[$row_key]['wrapper_attributes']['class'][] = Html::cleanCssIdentifier($wrapper_class);
            }
          }

          // Container codes
          if (!empty($config_settings['page_classes_row_container_' . $row_key])) {
            $container_codes = Tags::explode($config_settings['page_classes_row_container_' . $row_key]);
            foreach ($container_codes as $container_class) {
              $variables[$row_key]['container_attributes']['class'][] = Html::cleanCssIdentifier($container_class);
            }
          }
        }
      }
    }

    return $variables;
  }
}
