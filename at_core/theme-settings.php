<?php

/**
 * @file
 * Custom theme settings.
 */

use Drupal\Component\Utility\Html;
use Drupal\at_core\Theme\ThemeInfo;
use Drupal\at_core\Layout\LayoutGenerator;
use Drupal\at_core\File\DirectoryOperations;

/**
 * Implementation of hook_form_system_theme_settings_alter()
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 *
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function at_core_form_system_theme_settings_alter(&$form, &$form_state) {
  // Set the theme name.
  $build_info = $form_state->getBuildInfo();
  $theme = $build_info['args'][0];

  // Instantiate our Theme info object.
  $themeInfo = new ThemeInfo($theme);
  $getThemeInfo = $themeInfo->getThemeInfo('info');

  // Get this themes config settings
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Common paths.
  $at_core_path  = drupal_get_path('theme', 'at_core');
  $subtheme_path = drupal_get_path('theme', $theme);
  $generated_files_path = NULL;

  // Path to save generated CSS files. We don't want this happening for at_core or the generator.
  if (isset($getThemeInfo['subtheme type']) && $getThemeInfo['subtheme type'] === 'adaptive_subtheme') {
    $directoryOperations = new DirectoryOperations();
    $generated_files_path = $directoryOperations->directoryPrepare($backup_file_path = array($subtheme_path, 'styles/css/generated'));
  }

  // Get the active themes regions so we can use this in
  // various other places.
  $theme_regions = system_region_list($theme, $show = REGIONS_VISIBLE);

  // Active themes active blocks
  $theme_blocks = entity_load_multiple_by_properties('block', ['theme' => $theme]);

  // Check for breakpoints module and set a warning and a flag to disable much
  // of the theme settings if its not available.
  $breakpoints_module = \Drupal::moduleHandler()->moduleExists('breakpoint');

  if ($breakpoints_module == TRUE) {
    $breakpoint_groups = \Drupal::service('breakpoint.manager')->getGroups();
    $breakpoints = array();

    // Unset core breakpoint groups due to notices and other issues, until this
    // is resolved: SEE: https://www.drupal.org/node/2379283
    unset($breakpoint_groups['toolbar']);
    unset($breakpoint_groups['seven']);
    unset($breakpoint_groups['bartik']);

    // Set breakpoint options, we use these in layout and other extensions like
    // Responsive menus.
    foreach ($breakpoint_groups as $group_key => $group_values) {
      $breakpoints[$group_key] = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($group_key);
    }

    foreach($breakpoints as $group => $breakpoint_values)  {
      if ($breakpoint_values !== array()) {
        $breakpoint_options[$group] = $group;
      }
    }
  }
  else {
    drupal_set_message(t('Adaptivetheme requires the <b>Breakpoint module</b>. Open the <a href="!extendpage" target="_blank">Extend</a> page and enable Breakpoint.', array('!extendpage' => base_path() . 'admin/modules')), 'warning');
  }

  // Get node types (bundles).
  $node_types = \Drupal\node\Entity\NodeType::loadMultiple();

  // View or "Display modes".
  // TODO entityManager() is deprecated, but how to replace?
  $node_view_modes = \Drupal::entityManager()->getViewModes('node');

  // Unset unwanted view modes
  unset($node_view_modes['rss']);
  unset($node_view_modes['search_index']);
  unset($node_view_modes['search_result']);

  // Set a class on the form for the current admin theme, note if this is set to
  // "Default theme" the result is always 0.
  $system_theme_config = \Drupal::config('system.theme');
  $admin_theme = $system_theme_config->get('admin');
  if (!empty($admin_theme)) {
    $admin_theme_class = 'admin-theme--' . Html::cleanCssIdentifier($admin_theme);
    $form['#attributes'] = array('class' => array($admin_theme_class));
  }

  // Attached required CSS and JS.
  $form['#attached']['library'][] = 'at_core/at.appearance_settings';

  // AT Core
  if ($theme == 'at_core') {
    $form['at_core']['message'] = array(
      '#type' => 'container',
      '#markup' => t('AT Core has no configuration and cannot be used as a front end theme - it is a base them only. Use the <b>AT Theme Generator</b> to generate or clone a theme to get started.'),
    );

    // Hide form items.
    $form['theme_settings']['#attributes']['class'] = array('visually-hidden');
    $form['logo']['#attributes']['class'] = array('visually-hidden');
    $form['favicon']['#attributes']['class'] = array('visually-hidden');
    $form['actions']['#attributes']['class'] = array('visually-hidden');
  }

  // AT Subtheme
  if (isset($getThemeInfo['subtheme type'])) {

    if ($getThemeInfo['subtheme type'] !== 'adaptive_generator') {

      // Pass in the generated files path to values and settings.
      $form['at']['settings_generated_files_path'] = array(
        '#type' => 'hidden',
        '#value' => $generated_files_path,
      );

      // Extension settings.
      require_once($at_core_path . '/forms/ext/extension_settings.php');

      // Layouts.
      require_once($at_core_path . '/forms/layout/layouts.php');

      // Basic settings - move into details wrapper and collapse.
      $form['basic_settings'] = array(
        '#type' => 'details',
        '#title' => t('Basic Settings'),
        '#open' => FALSE,
      );

      $form['theme_settings']['#open'] = FALSE;
      $form['theme_settings']['#group'] = 'basic_settings';
      $form['logo']['#open'] = FALSE;
      $form['logo']['#group'] = 'basic_settings';
      $form['favicon']['#open'] = FALSE;
      $form['favicon']['#group'] = 'basic_settings';

      // Buttons don't work with #group, move it the hard way.
      $form['actions']['#type'] = $form['basic_settings']['actions']['#type'] = 'actions';
      $form['actions']['submit']['#type'] = $form['basic_settings']['actions']['submit']['#type'] = 'submit';
      $form['actions']['submit']['#value'] = $form['basic_settings']['actions']['submit']['#value'] = t('Save basic settings');
      $form['actions']['submit']['#button_type'] = $form['basic_settings']['actions']['submit']['#button_type'] = 'primary';
      unset($form['actions']);
    }
  }

  // Modify the color scheme form.
  if (\Drupal::moduleHandler()->moduleExists('color')) {
    include_once($at_core_path . '/forms/color/color_submit.php');
    if (isset($build_info['args'][0]) && ($theme = $build_info['args'][0]) && color_get_info($theme) && function_exists('gd_info')) {
      $form['#process'][] = 'at_core_make_collapsible';
    }
  }
}

// Helper function to modify the color scheme form.
function at_core_make_collapsible($form) {
  $form['color']['#open'] = FALSE;
  $form['color']['actions'] = array(
    '#type' => 'actions',
    '#attributes' => array('class' => array('submit--color-scheme')),
  );
  $form['color']['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save color scheme'),
    '#button_type' => 'primary',
    '#weight' => 100,
  );
  $form['color']['actions']['log'] = array(
    '#type' => 'submit',
    '#value' => t('Log color scheme'),
    '#submit'=> array('at_core_log_color_scheme'),
    '#weight' => 101,
    '#access' => FALSE,
  );

  // Magic user Obi Wan gets special Jedi powers.
  // TODO getUsername() is deprecated, no idea how to replace it.
  $user = \Drupal::currentUser();
  if (in_array('administrator', $user->getRoles()) && $user->getUsername() == 'Obi Wan') {
    $form['color']['actions']['log']['#access'] = TRUE;
  }

  return $form;
}
