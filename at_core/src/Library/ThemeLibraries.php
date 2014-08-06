<?php

/**
 * @file
 * Contains \Drupal\at_core\Library\ThemeLibraries.
 */

namespace Drupal\at_core\Library;

use Drupal\at_core\Theme\ThemeSettingsInfo;

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
   *  List of libraries for all base themes for the current theme.
   */
  public function getThemeTrailLibraries() {
    $theme_trail_libraries = array();

    $themeInfo = new ThemeSettingsInfo($this->theme);
    $baseThemes = $themeInfo->baseThemeInfo('base_themes');
    $library_discovery = \Drupal::service('library.discovery');

    $theme_libraries[$this->theme] = $this->theme . 'libraries.yml';

    foreach ($baseThemes as $key => $base_theme_name) {
      $theme_libraries[$key] = $key . '.libraries.yml';;
    }

    foreach ($theme_libraries as $key => $library_yml) {
      $path = drupal_get_path('theme', $key);
      if (file_exists("$path/$library_yml")) {
        $theme_trail_libraries[$key] = $library_discovery->getLibrariesByExtension($key);
      }
    }

    // All base themes and the current theme that declare libraries.
    return $theme_trail_libraries;
  }

} // end class
