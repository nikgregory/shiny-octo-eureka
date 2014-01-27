<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Theme\ThemeGeneratorSubmit;

/**
 * Form submit handler for the theme settings form.
 */
function at_core_submit_generator(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  // Generate a new theme.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $generateTheme = new ThemeGeneratorSubmit();
    $generateTheme->generateTheme($values);
  }

  //drupal_theme_rebuild();
  //system_list_reset();

  drupal_flush_all_caches();

  // Messages
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    drupal_set_message(t('You generated a theme - you may need to clear the cache for Drupal to see your new theme. Go to the Performance Settings page in Configuration (admin/config/development/performance) and clear the cache.'), 'warning');
  }
}
