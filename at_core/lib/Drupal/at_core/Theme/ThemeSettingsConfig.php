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
  public function settingsConvertToConfig(array $values, Config $config) {
    $config = \Drupal::config($values['config_key']);
    foreach ($values as $key => $value) {
      if (substr($key, 0, 9) == 'settings_') {
        $config->set('settings.' . drupal_substr($key, 9), $value);
      }
    }
    // Clear template suggestion settings from configuration when suggestions are deleted via the UI.
    if (isset($values['delete_suggestions'])) {
      if ($values['delete_suggestions'] == 1 && !empty($values['delete_suggestions_table'])) {
        foreach ($values['delete_suggestions_table'] as $config_key => $config_value) {
           $suggestion = drupal_substr($config_key, 20);
           $suggestion_provider = 'settings.template_suggestion_provider_' . $suggestion;
           $suggestion_plugin = 'settings.template_suggestion_plugin_' . $suggestion;
           $config->clear('settings.' . $config_key);
           $config->clear($suggestion_provider);
           $config->clear($suggestion_plugin);
        }
      }
    }
    return $config;
  }

} // end class
