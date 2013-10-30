<?php

/**
 * @file
 * Contains \Drupal\at_core\Library\ThemeLibraries.
 */

namespace Drupal\at_core\Library;

use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\Helpers\FileGlobber;

// Methods to return libraries data
class ThemeLibraries {

  protected $theme;

  /**
   * Constructs a library.
   *
   * @param string $theme
   *  The theme name.
   */
  public function __construct($theme) {
    $this->theme = $theme;
  }

  /**
   * Scan subtheme directories for library files.
   *
   * @return array
   *  List of library files.
   */
  public function getLibraryFiles() {
    $files = array();

    $themeInfo = new ThemeSettingsInfo($this->theme);
    $baseThemes = $themeInfo->baseThemeInfo('base_themes');

    // Build paths to retrieve library files from.
    $paths[$this->theme] = drupal_get_path('theme', $this->theme) . '/libraries/';

    // For now we unset any at_core libraries, this could change in the future.
    unset($baseThemes['at_core']);

    foreach ($baseThemes as $key => $base_theme_name) {
      $paths[$key] = drupal_get_path('theme', $key) . '/libraries/';
    }

    // Set file types for the globber
    $types = array('js', 'css');

    // for each path glob files
    foreach ($paths as $theme_name => $path) {
      $glob = new FileGlobber($path, $types);
      $files[$theme_name] = $glob->globFiles();
    }

    return $files;
  }

} // end class
