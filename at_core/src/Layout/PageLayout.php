<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\PageLayout.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Helpers\FileGlobber;
use Drupal\Core\Cache;
use Symfony\Component\Yaml\Parser;

class PageLayout {

  // The active theme name.
  protected $theme;

  // Active regions in the current page.
  protected $active_regions;

  // The currently selected layout.
  protected $selected_layout;

  // The plugin the selected layout belongs to.
  protected $selected_plugin;

  // Constructor.
  public function __construct($theme, $selected_plugin = '', $selected_layout = '', $active_regions = array()) {
    $this->theme = $theme;
    $this->selected_plugin = $selected_plugin;
    $this->selected_layout = $selected_layout;
    $this->regions = $active_regions;
    $this->plugin_path = drupal_get_path('theme', $this->theme) . '/layouts/';
  }

  // Scan for layout directories.
  public function getLayoutDirs() {
    $scan = new FileGlobber($this->plugin_path, NULL);
    $layout_plugins = $scan->scanDirs();

    return $layout_plugins;
  }

  // Scan for layout for CSS layouts.
  // TODO: can this be merged with getLayoutDirs() into a more generic method?
  public function getCSSLayoutDirs($css_layouts_path) {
    $scan = new FileGlobber($css_layouts_path, NULL);
    $css_layouts = $scan->scanDirs();

    return $css_layouts;
  }

  // Parse layout yml files and store the arrays.
  public function parseLayoutConfig() {
    $config_data = array();
    $layout_plugins = self::getLayoutDirs();

    foreach ($layout_plugins as $plugin) {
      // First parse the main layout yml file
      $config_file = $this->plugin_path . $plugin . '/' . $plugin . '.layout.yml';

      $parser = new Parser();
      $config_data[$plugin] = $parser->parse(file_get_contents($config_file));

      // Get the CSS layouts path from the main layout yml file.
      if ($config_data[$plugin]['css_layouts_path']) {
        $css_layouts_path = $this->plugin_path . $plugin . '/' . $config_data[$plugin]['css_layouts_path'];

        // We need to scan directories to get all CSS layouts.
        $css_layouts = self::getCSSLayoutDirs($css_layouts_path);

        // Now parse each CSS layouts yml and add them the configuration array.
        foreach ($css_layouts as $css_layout) {
          $css_config_file = $css_layouts_path . '/' . $css_layout . '/' . $plugin . '.variant.' . $css_layout . '.yml';

          $config_data[$plugin]['css_layouts'][$css_layout] = $parser->parse(file_get_contents($css_config_file));
        }
      }
    }

    return $config_data;
  }

  // Extract rows and regions, css files for the selected layout.
  public function buildLayoutDataArrays() {
    // Return cache data so we avoid glob/scandir on every page load, this is pretty quick.
    if ($cache = \Drupal::cache()->get("$this->theme:$this->selected_layout")) {
      $layout = $cache->data;
    }

    else {
      $plugins = self::parseLayoutConfig();
      $selected_plugin = $plugins[$this->selected_plugin];
      $css_layouts_path = $selected_plugin['css_layouts_path'];

      $layout = array();
      if (!empty($this->selected_layout)) {
        $layout['version'] = $selected_plugin['version'];
        $layout['rows']    = $selected_plugin['rows'];
        $layout['css_layout']      = $selected_plugin['css_layouts'][$this->selected_layout]['css'];
        $layout['css_layout_path'] = $selected_plugin['css_layouts_path'];
      }
      if (!empty($layout)) {
        \Drupal::cache()->set("$this->theme:$this->selected_layout", $layout);
      }
      else {
        return; // selected layout not found or not readable etc.
      }
    }

    return $layout;
  }

} // end class
