<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\PageLayout.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Helpers\FileGlobber;
use Drupal\at_core\Theme\ThemeInfo;
use Drupal\Core\Cache;
use Symfony\Component\Yaml\Parser;

class PageLayout {

  // The active theme name.
  protected $theme;

  // The currently selected layout.
  protected $layout;

  // Active regions in the current page.
  protected $active_regions;

  // Constructor.
  public function __construct($theme, $layout, $active_regions = array()) {
    $this->theme          = $theme;
    $this->layout         = $layout;
    $this->active_regions = $active_regions;
    $this->layout_path    = drupal_get_path('theme', $this->theme) . '/layout';
    $this->cid            = $theme . ':' . $layout;
  }

  // Parse a configuration file and return the contents.
  public function parseLayoutConfig($type) {
    $config_data = array();

    if ($cache = \Drupal::cache()->get($this->cid . ':' . $type)) {
      $config_data = $cache->data;
    }
    else {
      $config_file = $this->layout_path . '/' . $this->layout . '/' . $this->layout . '.' . $type . '.yml';

      $parser = new Parser();
      $config_data = $parser->parse(file_get_contents($config_file));

      if (!empty($config_data)) {
        \Drupal::cache()->set($this->cid . ':' . $type, $config_data);
      }
    }

    return $config_data;
  }

  // Extract rows and regions, css files for the selected layout.
  public function getLayoutConfig() {
    $layout_config = self::parseLayoutConfig('layout');
    return $layout_config;
  }

  // return array of CSS directories and files
  public function getCssConfig() {
    $css_config = self::parseLayoutConfig('css');
    return $css_config;
  }

  // return rows array from the layout
  public function getLayoutRows() {
    $layout_config = self::getLayoutConfig();
    return $layout_config['rows'];
  }

  // Prepare layout data for use in theme settings
  public function settingsPrepareData($key = '') {
    $layout_config = self::parseLayoutConfig('layout');

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

 // public function getTemplateSuggestions() {
    // return array of all existing template suggestions
 // }



  // Extract rows and regions, css files for the selected layout.

  public function buildLayoutDataArrays() {
    $layout = array();

    // TODO: should we use default bin or discovery etc?
    if ($cache = \Drupal::cache()->get($this->cid . ':layout' )) {
      $layout = $cache->data;
    }
    if (empty($layout)) {

      $layout_config = self::getLayoutConfig();

      //$selected_plugin = $plugins[$this->selected_plugin];
      //$css_layouts_path = $selected_plugin['css_layouts_path'];

      if (!empty($this->layout)) {
        $layout['version']         = $layout_config['version'];
        $layout['rows']            = $layout_config['rows'];
        $layout['template']        = $this->layout . '.html.twig';

        //$layout['css_layout']      = $selected_plugin['css_layouts'][$this->selected_layout]['css'];
        //$layout['css_layout_path'] = $selected_plugin['css_layouts_path'];

      }
      if (!empty($layout)) {
        \Drupal::cache()->set($this->cid, $layout);
      }
      else {
        return; // selected layout not found or not readable etc.
      }
    }

    return $layout;
  }

} // end class





















