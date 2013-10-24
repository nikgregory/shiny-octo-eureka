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
  // Provides theme generator and basic help for generating themes.
  //------------------------------------------------------------------------
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

  // Subtheme Settings Only
  // Here we provide the layout generator, libraries, development settings
  // and help documents to guide the themer.
  //------------------------------------------------------------------------
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

/**
 * Validate form values.
 */
function at_core_settings_form_validate(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  // Validate Layout Generator.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    // Check if this should even run.
    if ($values['layout_type_select'] != 'disable_layout_generation') {

      // Is a master layout selected?
      if ($selected_layout = $values['settings_layout_master_layout']) {

        // Validate suggestions if template_suggestion is selected.
        if ($values['layout_type_select'] == 'template_suggestion') {

          // Did the user enter a template suggestion?
          if (empty($values['template_suggestion_name'])) {
            form_set_error('template_suggestion_name', t("No suggestion was provided."));
          }

          // Check suggestion and page.html.twig series are a match
          $layout_config = new LayoutSettings($theme);
          $layouts = $layout_config->settingsPrepareData();

          // get the master layout for page.
          $page_layout = $values['settings_template_suggestion_page'];

          $default_series = $layouts[$page_layout]['series'];
          $selected_series = $layouts[$selected_layout]['series'];

          if ($selected_series !== $default_series) {
            form_set_error('settings_layout_master_layout', t("Template suggestion layout must belong to the same <b>Series</b> as the default layout."));
          }
        }

        // Check if content region is defined - Drupal requires themes to declare a "content" region, we must check if one is included in the layout.
        $generateLayout = new LayoutGenerator($theme, $selected_layout);
        $regions_list = $generateLayout->formatLayoutRegions();
        if (array_key_exists('content', $regions_list)) {
          $content_exists = '';
        }
        else {
          $layout_file_name = strtolower($selected_layout) . '.layout.yml';
          form_set_error('', t("The <code>content</code> region does not exist and is a required region for Drupal. <code>!layout_file_name</code> must define at least one region with the machine name <code>content</code>, for example <code>content: 'Main Content'</code>. Update your layout and clear the site cache before trying again.", array('!layout_file_name' => $layout_file_name)));
        }
      }
    }
  }

  // Validate Theme Generator.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $machine_name  = $values['generate']['generate_machine_name'];
    $path   = drupal_get_path('theme', 'at_core');
    $target = $path . '/../../' . $machine_name;
    $subtheme_type = $values['generate']['generate_type'];
    $base_theme    = $values['generate']['generate_base'];
    if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {
      $source = $path . '/../at_starterkits/' . $subtheme_type;
    }
    else if ($subtheme_type == 'at_skin') {
      $skin_base_theme_path = drupal_get_path('theme', $base_theme);
      $source = $skin_base_theme_path;
    }

    // Check if directories and files exist and are readable/writable etc.
    if (!file_exists($source) && !is_readable($source)) {
      form_set_error('', t('The Starterkit or base theme (if you are generating a Skin) can not be found or is not readable - check permissions or perhaps you moved things around?'));
    }
    if (!is_writable(dirname($target))) {
      form_set_error('', t('The target directory is not writable, please check permissions on the <code>/themes/</code> directory where Adaptivetheme is located.'));
    }
  }
}

/**
 * Form submit handler for the theme settings form.
 */
function at_core_settings_form_submit(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  // Generate a new theme.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $generateSubtheme = new Generator();
    $generateSubtheme->generateSubtheme($values);
  }

  // Generate and save the new layout.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    // Check if this should even run.
    if ($values['layout_type_select'] != 'disable_layout_generation') {

      // Master layout selected?
      if ($values['settings_layout_master_layout']) {

        // Assign selected layout.
        $selected_layout = $values['settings_layout_master_layout'];

        // Clear the selected layout cache bin.
        if ($cache = cache()->get("$theme:$selected_layout")) {
          cache()->delete("$theme:$selected_layout");
        }

        // Instantiate LayoutGenerator object.
        $generateLayout = new LayoutGenerator($theme, $selected_layout);

        // Check if this is a suggestion.
        $suggestion = '';
        if ($values['layout_type_select'] == 'template_suggestion') {

          $suggestion = $values['template_suggestion_name'];

          // Dynamically create a theme setting that stores the selected layout for a generated suggestion.
          $clean_suggestion = strtr($suggestion, '-', '_');
          $values["settings_template_suggestion_page__$clean_suggestion"] = $selected_layout;

          // Set a file name for messages
          $template_file_name = 'page--' . $suggestion . '.html.twig';

        }
        else {
          // Save a setting for the default page temlate layout.
          $values["settings_template_suggestion_page"] = $selected_layout;

          // Set a file name for messages
          $template_file_name = 'page.html.twig';
        }

        // If backups are disabled don't create any.
        $disable_backups = FALSE;
        if ($values['settings_disable_backups'] == 1) {
          $disable_backups = TRUE;
        }

        $generateLayout->saveLayoutRegionsList($disable_backups);
        $generateLayout->savePageTemplate($suggestion, $disable_backups);

        // check if the file exists and if so set a message, we really need to give the user some feedback at this stage.
        $file_path = drupal_get_path('theme', $theme) . '/templates/' . $template_file_name;
        if (file_exists($file_path)) {
          drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
        }
        else {
          drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
        }
      }
    }
  }

  // Save custom settings to the settings configuration.
  $config = config($theme . '_settings');
  $convertToConfig = new ThemeSettingsConfig();
  $convertToConfig->settingsConvertToConfig($values, $config)->save();

  drupal_theme_rebuild();

  // Been trying to call drupal_flush_all_caches here but nothing seems to work other than going to the
  // Performance settings page and actually firing it there.
  if ((!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') || (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1 && $values['layout_type_select'] != 'disable_layout_generation')) {
    drupal_set_message(t('You generated a theme or layout - you must clear the cache for Drupal to see new theme data (such as a new theme, templates and new block regions if generating a new layout). Go to the Performance Settings page in Configuration (admin/config/development/performance) and clear the cache.'), 'warning');
  }
}
