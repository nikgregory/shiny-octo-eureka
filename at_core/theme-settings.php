<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeInfo;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Layout\LayoutGenerator;
use Drupal\at_core\Layout\LayoutSettings;

/**
 * Implimentation of hook_form_system_theme_settings_alter()
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 *
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function at_core_form_system_theme_settings_alter(&$form, &$form_state) {

  // Set the theme name.
  $theme = $form_state['build_info']['args'][0];

  // Instantiate our Theme info object.
  $themeInfo = new ThemeInfo($theme);
  $getThemeInfo = ($themeInfo->getThemeInfo('info'));

  // File and theme paths.
  $machinenamejs = drupal_add_library('system', 'drupal.machine-name');
  $at_core_path  = drupal_get_path('theme', 'at_core');
  $subtheme_path = drupal_get_path('theme', $theme);

  // Attach some CSS to style appearance settings pages.
  $form['#attached']['css'] = array(
    $at_core_path . '/stylesheets/css/appearance.css',
    $at_core_path . '/stylesheets/css/slimbox2/slimbox2.css',
  );

  // Attach our simple lightbox effect for screenshots
  $form['#attached']['js'] = array(
    $at_core_path . '/scripts/slimbox2/slimbox2.js',
  );

  // AT Core
  if ($theme == 'at_core') {

    $form['atsettings'] = array(
      '#type' => 'vertical_tabs',
    );

    // Generator.
    include_once($at_core_path . '/forms/generator.php');

    // Help (at_core).
    include_once($at_core_path . '/forms/help_core.php');

    // Hide form items.
    $form['theme_settings']['#attributes']['class'] = array('visually-hidden');
    $form['logo']['#attributes']['class'] = array('visually-hidden');
    $form['favicon']['#attributes']['class'] = array('visually-hidden');

    // Modify the submit.
    $form['actions']['submit']['#value'] = t('Submit');
    $form['actions']['submit']['#validate'][] = 'at_core_validate_generator';
    $form['actions']['submit']['#submit'][] = 'at_core_submit_generator';

    include_once(drupal_get_path('theme', 'at_core') . '/forms/generator_validate.php');
    include_once(drupal_get_path('theme', 'at_core') . '/forms/generator_submit.php');
  }

  // AT Subtheme
  else {

    // Layouts.
    include_once($at_core_path . '/forms/layouts.php');

    // Advanced settings.
    $form['advanced_settings'] = array(
      '#type' => 'details',
      '#title' => t('Advanced settings'),
      '#weight' => -199,
      '#collapsed' => TRUE,
    );

    $form['advanced_settings']['at_settings'] = array(
      '#type' => 'vertical_tabs',
      '#weight' => -200,
    );

    // Extensions
    include_once($at_core_path . '/forms/ext/extensions.php');

    // Extensions master toggle.
    if ($form['ext']['ext_settings']['settings_enable_extensions']['#default_value'] == 1) {

      // Include fonts.inc by default.
      include_once($at_core_path . '/forms/ext/fonts.inc');

      $extensions_array = array(
        'fonts',
        'titles',
        'images',
        'touch_icons',
        'libraries',
        'custom_css',
        'markup_overrides'
      );

      foreach ($extensions_array as $extension) {
        $form_state_value = isset($form_state['values']["settings_enable_$extension"]);
        $form_value = $form['ext']['ext_settings']['enable_ext']["settings_enable_$extension"]['#default_value'];
        if (($form_state_value && $form_state_value == 1) ||
           (!$form_state_value && $form_value == 1)) {
          include_once($at_core_path . '/forms/ext/' . $extension . '.php');
        }
      }
    }

    // Development.
    include_once($at_core_path . '/forms/devel.php');

    // Help (sub-theme). TODO: rethink where help goes.
    // include_once($at_core_path . '/forms/help_subtheme.php');

    // Submit button for advanced settings.
    $form['advanced_settings']['actions'] = array(
      '#type' => 'actions',
      '#attributes' => array('class' => array('submit--advanced-settings')),
    );
    $form['advanced_settings']['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save advanced settings'),
      '#validate'=> array('at_core_validate_advanced_settings'),
      '#submit'=> array('at_core_submit_advanced_settings'),
      '#attributes' => array('class' => array('button--primary')),
      '#weight' => -10000,
    );

    // Submit handlers for the advanced settings.
    include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/advanced_settings_validate.php');
    include_once(drupal_get_path('theme', 'at_core') . '/forms/ext/advanced_settings_submit.php');

    // Basic settings - move into details wrapper and collapse.
    $form['basic_settings'] = array(
      '#type' => 'details',
      '#title' => 'Basic Settings',
      '#collapsed' => TRUE,
      '#weight' => -197,
    );
    $form['theme_settings']['#collapsible'] = TRUE;
    $form['theme_settings']['#collapsed'] = TRUE;
    $form['theme_settings']['#group'] = 'basic_settings';
    $form['logo']['#collapsible'] = TRUE;
    $form['logo']['#collapsed'] = TRUE;
    $form['logo']['#group'] = 'basic_settings';
    $form['favicon']['#collapsible'] = TRUE;
    $form['favicon']['#collapsed'] = TRUE;
    $form['favicon']['#group'] = 'basic_settings';
    // buttons don't work with #group, move it the hard way.
    $form['actions']['#type'] = $form['basic_settings']['actions']['#type'] = 'actions';
    $form['actions']['submit']['#type'] = $form['basic_settings']['actions']['submit']['#type'] = 'submit';
    $form['actions']['submit']['#value'] = $form['basic_settings']['actions']['submit']['#value'] = t('Save basic settings');
    $form['actions']['submit']['#button_type'] = $form['basic_settings']['actions']['submit']['#button_type'] = 'primary';
     unset($form['actions']);
  }
}
