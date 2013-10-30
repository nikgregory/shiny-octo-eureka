<?php

use Drupal\Core\Config\Config;
use Drupal\at_core\Theme\ThemeSettingsConfig;
use Drupal\at_core\Generator\Generator;
use Drupal\at_core\Layout\LayoutGenerator;

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

  // TODO: abstract all layout generation to a class.
  // Generate and save the new layout.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    // Check if this should even run.
    if ($values['layout_type_select'] != 'disable_layout_generation') {

      // Master layout selected?
      if ($values['settings_selected_layout']) {

        // Assign selected layout.
        $selected_layout = $values['settings_selected_layout'];

        // Providers.
        $selected_layout_provider = $values['selected_layout_provider'];
        $default_layout_provider = $values['default_layout_provider'];

        // Set a theme setting for the default layout provider.
        $values['settings_default_layout_provider'] = $default_layout_provider;
        $values['settings_template_suggestion_provider_page'] = $default_layout_provider;

        // Clear the selected layout cache bin.
        if ($cache = cache()->get("$selected_layout_provider:$selected_layout")) {
          cache()->delete("$selected_layout_provider:$selected_layout");
        }

        // Instantiate LayoutGenerator object.
        $generateLayout = new LayoutGenerator($selected_layout_provider, $selected_layout);

        // Check if this is a suggestion.
        $suggestion = '';
        if ($values['layout_type_select'] == 'template_suggestion') {

          $suggestion = $values['template_suggestion_name'];

          // Dynamically create a theme setting that stores the selected layout for a generated suggestion.
          $clean_suggestion = strtr($suggestion, '-', '_');
          $values["settings_template_suggestion_page__$clean_suggestion"] = $selected_layout;

          // Do the same for the template suggestion provider
          $values["settings_template_suggestion_provider_page__$clean_suggestion"] = $selected_layout_provider;

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

        $generateLayout->saveLayoutRegionsList($theme, $disable_backups);
        $generateLayout->savePageTemplate($theme, $suggestion, $disable_backups);

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
