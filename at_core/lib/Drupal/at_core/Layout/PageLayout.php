<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\PageLayout.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Helpers\FileGlobber;

class PageLayout {

  // Theme name.
  protected $theme;

  // Active regions in a page, passed from page $variables['active_regions'].
  protected $active_regions;

  // The user selected layout, usually passed from the saved theme setting.
  protected $selected_layout;

  // Constructor.
  public function __construct($theme, $selected_layout = '', $active_regions = array()) {
    $this->theme = $theme;
    $this->selected_layout = $selected_layout;
    $this->regions = $active_regions;
    $this->layouts_path = drupal_get_path('theme', $this->theme) . '/layouts/';
  }

  // Scan for layout directories.
  public function getLayoutDirs() {
    $scan = new FileGlobber($this->layouts_path, NULL);
    $layout_directories = $scan->scanDirs();

    return $layout_directories;
  }

  // Parse layout yml files and store the arrays.
  public function parseLayoutConfig() {
    $config_data = array();
    $layouts = self::getLayoutDirs();
    foreach ($layouts as $layout) {
      $config_file = $this->layouts_path . $layout . '/' . $layout . '.layout.yml';
      $config_data[$layout] = drupal_parse_info_file($config_file);
    }

    return $config_data;
  }

  // Extract rows and regions, css files for the selected layout.
  public function buildLayoutDataArrays() {
    // Return cache data so we avoid glob/scandir on every page load, this is pretty quick.
    if ($cache = cache()->get("$this->theme:$this->selected_layout")) {
      $selected = $cache->data;
    }
    else {
      $layouts = self::parseLayoutConfig();
      if (!empty($this->selected_layout)) {
        $selected['description'] = $layouts[$this->selected_layout]['description'];
        $selected['version'] = $layouts[$this->selected_layout]['version'];
        $selected['series'] = $layouts[$this->selected_layout]['series'];
        $selected['rows'] = $layouts[$this->selected_layout]['rows'];
        $selected['css'] = $layouts[$this->selected_layout]['css'];
      }
      if (!empty($selected)) {
        cache()->set("$this->theme:$this->selected_layout", $selected);
      }
      else {
        return; // selected layout not found or not readable etc.
      }
    }

    return $selected;
  }

} // end class
