<?php

/**
 * @file
 * Contains \Drupal\at_core\Theme\ThemeSettingsConfig.
 */

namespace Drupal\at_core\Theme;

use Drupal\Core\Config\Config;
use Drupal\Component\Utility\Unicode;

class ThemeSettingsConfig {

  /**
   * Set config for theme settings, core seems to have forgotten themes can
   * have custom settings that you probably very much need in config.
   */
  public function settingsConvertToConfig(array $values, Config $config) {
    foreach ($values as $key => $value) {
      // Save settings as config
      if (substr($key, 0, 9) == 'settings_') {
        $config_key = Unicode::substr($key, 9);
        $config->set('settings.' . $config_key, $value)->save();
      }
      // Delete suggestions config settings. We do not remove all the suggestions settings
      // because later on if the suggestion is recreated there will be settings for it already,
      // which is kind of nice for the user should they accidentally delete a suggestion.
      if (substr($key, 0, 18) == 'delete_suggestion_') {
        $delete_suggestion_key = 'settings.suggestion_' . Unicode::substr($key, 18);
        if ($value == 1) {
          $config->clear($delete_suggestion_key, $value)->save();
        }
      }
    }
  }
}
