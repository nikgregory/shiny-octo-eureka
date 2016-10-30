<?php

/**
 * Custom theme settings.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\at_core\Theme\ThemeInfo;
use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;

/**
 * Implementation of hook_form_system_theme_settings_alter()
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function at_core_form_system_theme_settings_alter(&$form, FormStateInterface $form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // Set the theme name.
  $build_info = $form_state->getBuildInfo();

  $active_theme = \Drupal::theme()->getActiveTheme();
  $theme = $active_theme->getName();
  $theme_extension = $active_theme->getExtension();

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
  if (isset($getThemeInfo['subtheme type']) && ($getThemeInfo['subtheme type'] === 'adaptive_subtheme')) {
    $directoryOperations = new DirectoryOperations();
    $generated_files_path = $directoryOperations->directoryPrepare($backup_file_path = [$subtheme_path, 'styles/css/generated']);
  }

  // Get the active themes regions so we can use this in
  // various other places.
  $theme_regions = system_region_list($theme, $show = REGIONS_VISIBLE);

  // Active themes active blocks
  $block_module = \Drupal::moduleHandler()->moduleExists('block');
  if ($block_module === TRUE) {
    $theme_blocks = \Drupal::entityTypeManager()->getStorage('block')->loadByProperties(['theme' => $theme]);
  }
  else {
    $theme_blocks = NULL;
  }

  // Check for breakpoints module and set a warning and a flag to disable much
  // of the theme settings if its not available.
  $breakpoints_module = \Drupal::moduleHandler()->moduleExists('breakpoint');
  if ($breakpoints_module === TRUE) {
    $breakpoint_groups = \Drupal::service('breakpoint.manager')->getGroups();
    $breakpoints = [];

    // Set breakpoint options, we use these in layout and other extensions like
    // Responsive menus.
    foreach ($breakpoint_groups as $group_key => $group_values) {
      $breakpoints[$group_key] = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($group_key);
    }

    foreach($breakpoints as $group => $breakpoint_values)  {
      if ($breakpoint_values !== []) {
        $breakpoint_options[$group] = $group;
      }
    }
  }
  else {
    drupal_set_message(t('This theme requires the <b>Breakpoint module</b> to be installed. Go to the <a href="@extendpage" target="_blank">Modules</a> page and install Breakpoint. You cannot set the layout or use this themes custom settings until Breakpoint is installed.', ['@extendpage' => base_path() . 'admin/modules']), 'error');
  }

  // Entity types / view modes.
  $entity_types = [];
  $view_modes = [];

  // Get node types.
  $node_module = \Drupal::moduleHandler()->moduleExists('node');
  if ($node_module === TRUE) {
    $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
    $entity_types['node'] = $node_types;
    $view_modes['node'] = \Drupal::service('entity_display.repository')->getViewModes('node');

    // Unset unwanted view modes
    unset($view_modes['node']['rss']);
    unset($view_modes['node']['search_index']);
    unset($view_modes['node']['search_result']);
  }

  // Get comment types.
  $comment_module = \Drupal::moduleHandler()->moduleExists('comment');
  if ($comment_module === TRUE) {
    $comment_types = \Drupal\comment\Entity\CommentType::loadMultiple();
    $entity_types['comment'] = $comment_types;
    $view_modes['comment'] = \Drupal::service('entity_display.repository')->getViewModes('comment');
  }

  // Get block types.
  $block_content_module = \Drupal::moduleHandler()->moduleExists('block_content');
  if ($block_content_module === TRUE) {
    $block_content_types = \Drupal\block_content\Entity\BlockContentType::loadMultiple();
    $entity_types['block_content'] = $block_content_types;
    $view_modes['block_content'] = \Drupal::service('entity_display.repository')->getViewModes('block_content');
  }

  // Get paragraph types.
  $paragraphs_module = \Drupal::moduleHandler()->moduleExists('paragraphs');
  if ($paragraphs_module === TRUE) {
    $paragraph_types = \Drupal\paragraphs\Entity\ParagraphsType::loadMultiple();
    $entity_types['paragraphs'] = $paragraph_types;
    $view_modes['paragraphs'] = \Drupal::service('entity_display.repository')->getViewModes('paragraph');
  }

  // $getAllViewModes = \Drupal::service('entity_display.repository')->getAllViewModes();

//  // Get user types.
//  $user_module = \Drupal::moduleHandler()->moduleExists('user');
//  if ($user_module === TRUE) {
//    $user_types = \Drupal\user\Entity\User::loadMultiple();
//    $entity_types['user'] = $user_types;
//    $view_modes['user'] = \Drupal::service('entity_display.repository')->getViewModes('user');
//  }
//
//  // Get taxonomy term types.
//  $taxonomy_module = \Drupal::moduleHandler()->moduleExists('taxonomy');
//  if ($taxonomy_module === TRUE) {
//    $taxonomy_types = \Drupal\taxonomy\Entity\Term::loadMultiple();
//    $entity_types['taxonomy_term'] = $taxonomy_types;
//    $view_modes['taxonomy_term'] = \Drupal::service('entity_display.repository')->getViewModes('taxonomy_term');
//  }

  // Get Image types.
  $image_module = \Drupal::moduleHandler()->moduleExists('image');
  if ($image_module === TRUE) {
    $image_styles = \Drupal\image\Entity\ImageStyle::loadMultiple();
  }

  // Possible future extensions will use this.
  //$entity_types = \Drupal::entityTypeManager()->getDefinitions();

  // Set a class on the form for the current admin theme, note if this is set to
  // "Default theme" the result is always 0.
  $system_theme_config = \Drupal::config('system.theme');
  $admin_theme = $system_theme_config->get('admin');
  if (!empty($admin_theme)) {
    $admin_theme_class = 'admin-theme--' . Html::cleanCssIdentifier($admin_theme);
    $form['#attributes'] = ['class' => [$admin_theme_class]];
  }

  // Attached required CSS and JS.
  $form['#attached']['library'][] = 'at_core/at.appearance_settings';

  // Display a rude message if AT Tools is missing...
  $at_tools_module = \Drupal::moduleHandler()->moduleExists('at_tools');
  if ($at_tools_module === FALSE) {
    drupal_set_message(t('Please install the <a href="@at_tools_href" target="_blank">AT Tools</a> module for Drupal 8. Your theme may not operate correctly without this module installed.', ['@at_tools_href' => 'https://www.drupal.org/project/at_tools']), 'warning');
  }

  // AT Core
  if ($theme === 'at_core') {
    $form['at_core']['message'] = [
      '#type' => 'container',
      '#markup' => t('AT Core has no configuration and cannot be used as a front end theme - it is a base them only. Use the <b>AT Theme Generator</b> to generate or clone a theme to get started.'),
    ];

    // Hide form items.
    $form['theme_settings']['#attributes']['class'] = ['visually-hidden'];
    $form['logo']['#attributes']['class'] = ['visually-hidden'];
    $form['favicon']['#attributes']['class'] = ['visually-hidden'];
    $form['actions']['#attributes']['class'] = ['visually-hidden'];
  }

  // AT Subtheme
  if (isset($getThemeInfo['subtheme type'])) {

    // BC layer for older themes.
    if ($getThemeInfo['subtheme type'] !== 'adaptive_generator') {

      // Pass in the generated files path to values and settings.
      $form['at']['settings_generated_files_path'] = [
        '#type' => 'hidden',
        '#value' => $generated_files_path,
      ];

      // Check for breakpoint module, a lot of errors without it, this is brutal.
      if ($breakpoints_module == TRUE) {
        if ($getThemeInfo['subtheme type'] === 'adaptive_subtheme') {
          require_once($at_core_path . '/forms/ext/extension_settings.php');
          require_once($at_core_path . '/forms/layout/layouts.php');
        }
        elseif ($getThemeInfo['subtheme type'] === 'adaptive_skin') {
          require_once($at_core_path . '/forms/ext/extension_settings_skin.php');
        }
      }

      // Basic settings - move into details wrapper and collapse.
      $form['basic_settings'] = [
        '#type' => 'details',
        '#title' => t('Basic Settings'),
      ];

      // If it's skin type, set details to open.
      if ($getThemeInfo['subtheme type'] === 'adaptive_skin') {
        $form['basic_settings']['#open'] = TRUE;
      }

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
      $form['#process'][] = 'at_core_color_form';
    }
    // TODO This should only happen after color form submit. We need this to
    // stop 404 errors for the map URL for rewritten color stylesheets.
    $color_paths = \Drupal::config('color.theme.' . $theme)->get('stylesheets');
    if (!empty($color_paths)) {
      $fileOperations = new FileOperations();
      foreach ($color_paths as $color_path) {
        $map_string = '/*# sourceMappingURL=maps/' . str_replace('.css', '.css.map', basename($color_path)) . ' */';
        $fileOperations->fileStrReplace("$color_path", $map_string, '');
      }
    }
  }
}

/**
 * Helper function to modify the color scheme form.
 *
 * @param $form
 * @return array $form
 */
function at_core_color_form($form) {
  $form['color']['#open'] = FALSE;
  $form['color']['actions'] = [
    '#type' => 'actions',
    '#attributes' => ['class' => ['submit--color-scheme']],
  ];
  $form['color']['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Save color scheme'),
    '#button_type' => 'primary',
    '#submit'=> ['at_color_scheme_form_submit'],
    '#weight' => 100,
  ];
  $form['color']['actions']['log'] = [
    '#type' => 'submit',
    '#value' => t('Log color scheme'),
    '#submit'=> ['at_core_log_color_scheme'],
    '#weight' => 101,
    '#access' => FALSE,
  ];

  // Magic user Obi Wan gets special Jedi powers.
  $user = \Drupal::currentUser();
  if (in_array('administrator', $user->getRoles()) && $user->getAccountName() == 'Obi Wan') {
    $form['color']['actions']['log']['#access'] = TRUE;
  }

  return $form;
}
