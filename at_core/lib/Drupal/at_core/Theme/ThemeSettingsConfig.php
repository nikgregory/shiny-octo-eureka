<?php

/**
 * @file
 * Contains \Drupal\at_core\Theme\ThemeSettingsConfig.
 */

namespace Drupal\at_core\Theme;

use Drupal\Core\Config\Config;

class ThemeSettingsConfig {

  /**
   * Set config for theme settings, core seems to have forgotten themes can
   * have custom settings that you probably very much need in config.
   */
  public function settingsConvertToConfig(array $theme_settings, Config $config) {
    $config = config($theme_settings['config_key']);
    foreach ($theme_settings as $key => $value) {
      if (substr($key, 0, 9) == 'settings_') {
        $config->set('settings.' . drupal_substr($key, 9), $value);
      }
    }
    return $config;
  }

} // end class
