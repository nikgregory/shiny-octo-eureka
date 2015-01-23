<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\Layout
 */

namespace Drupal\at_core\Layout;

use Drupal\Core\Cache;
use Symfony\Component\Yaml\Parser;

use Drupal\at_core\Theme\ThemeInfo;
use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\File\ParseFile;

class Layout implements LayoutInterface {

  // The active theme name.
  protected $theme_name;

  // This themes layout name.
  protected $layout_name;

  // Constructor
  public function __construct($theme_name, $layout_name) {
    $this->theme_name = $theme_name;
    $this->layout_name = $layout_name;
    $this->layout_path = drupal_get_path('theme', $this->theme_name) . '/layout/' . $this->layout_name;
    $this->layout_cid = $this->theme_name . ':' . $this->layout_name;
  }

  // Returns layout configuration of a type (normally markup or css yml config).
  // looks for cached config first, if none we parse the respective yml file.
  public function getLayoutConfig($type) {
    $config_data = array();

    if ($cache = \Drupal::cache()->get($this->layout_cid . ':' . $type)) {
      $config_data = $cache->data;
    }
    else {
      $config_file = $this->layout_path . '/' . $this->layout_name . '.' . $type . '.yml';

      if (file_exists($config_file)) {
        $parser = new Parser();
        $config_data = $parser->parse(file_get_contents($config_file));
      }

      if (!empty($config_data)) {
        \Drupal::cache()->set($this->layout_cid . ':' . $type, $config_data);
      }
    }

    return $config_data;
  }

  // Returns a layout markup object.
  public function getLayoutMarkup() {
    $layout_markup = $this->getLayoutConfig(LayoutInterface::LayoutMarkup);

    return $layout_markup;
  }

  // Returns a layout CSS object.
  public function getLayoutCSS() {
    $layout_css = $this->getLayoutConfig(LayoutInterface::LayoutCSS);

    return $layout_css;
  }

}
