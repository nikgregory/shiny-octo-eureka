<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeInfo;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Generator\Generator;
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

  $form['atsettings'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -200,
  );

  // AT Core
  if ($theme == 'at_core') {

    // Generator.
    include_once($at_core_path . '/forms/generator.php');

    // Help (at_core).
    include_once($at_core_path . '/forms/help_core.php');

    // Hide form items.
    $form['theme_settings']['#attributes']['class'] = array('visually-hidden');
    $form['logo']['#attributes']['class'] = array('visually-hidden');
    $form['favicon']['#attributes']['class'] = array('visually-hidden');

    // Modify the submit label.
    $form['actions']['submit']['#value'] = t('Submit');
  }

  // Subtheme Settings
  else {

    // Layouts.
    include_once($at_core_path . '/forms/layouts.php');

    // Libraries.
    include_once($at_core_path . '/forms/libraries.php');

    // Development.
    include_once($at_core_path . '/forms/devel.php');

    // Extentions
    //include_once($at_core_path . '/forms/extensions.php');

    // Help (sub-theme).
    include_once($at_core_path . '/forms/help_subtheme.php');

    // Collapse infrequently used forms.
    $form['theme_settings']['#collapsible'] = TRUE;
    $form['theme_settings']['#collapsed'] = TRUE;
    $form['logo']['#collapsible'] = TRUE;
    $form['logo']['#collapsed'] = TRUE;
    $form['favicon']['#collapsible'] = TRUE;
    $form['favicon']['#collapsed'] = TRUE;
  }

  $form['#validate'][] = 'at_core_settings_form_validate';
  $form['#submit'][] = 'at_core_settings_form_submit';
}

// Include validation and submit handlers.
include_once(drupal_get_path('theme', 'at_core') . '/forms/settings_form_validate.php');
include_once(drupal_get_path('theme', 'at_core') . '/forms/settings_form_submit.php');
