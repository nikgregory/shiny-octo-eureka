<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\Layouts.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Layout\LayoutGenerator;

class LayoutGeneratorSubmit {

  public function generateLayout($theme, $values) {

    // Master layout selected?
    $values_selected_layout = '';
    if ($values['settings_master_layout']) {
      $values_selected_layout = $values['settings_selected_layout_' . $values['settings_master_layout']];
    }

    if (!empty($values_selected_layout)) {

      // Selected layout.
      $selected_layout = $values_selected_layout; //$values['settings_selected_layout'];

      // Providers.
      $selected_provider = $values['selected_layout_provider'];
      $default_provider  = $values['default_layout_provider'];

      // Plugins
      $selected_plugin = $values['selected_layout_plugin'];
      $default_plugin  = $values['default_layout_plugin'];

      // Set a theme setting for the default page layout provider and plugin.
      $values['settings_default_layout_provider'] = $default_provider;
      $values['settings_template_suggestion_provider_page'] = $default_provider;
      $values["settings_template_suggestion_plugin_page"] = $selected_plugin;

      // Clear the selected layout cache bin.
      if ($cache = \Drupal::cache()->get("$selected_provider:$selected_layout")) {
        \Drupal::cache()->delete("$selected_provider:$selected_layout");
      }

      // Instantiate LayoutGenerator object.
      $generateLayout = new LayoutGenerator($selected_provider, $selected_plugin, $selected_layout);

      // Check if this is a suggestion.
      $suggestion = '';
      if ($values['layout_type_select'] == 'template_suggestion') {

        $suggestion = $values['template_suggestion_name'];

        // Dynamically create a theme setting that stores the selected layout for a generated suggestion.
        $clean_suggestion = strtr($suggestion, '-', '_');
        $values["settings_template_suggestion_page__$clean_suggestion"] = $selected_layout;

        // Do the same for the template suggestion provider and plugin.
        $values["settings_template_suggestion_provider_page__$clean_suggestion"] = $selected_provider;
        $values["settings_template_suggestion_plugin_page__$clean_suggestion"] = $selected_plugin;

        // Set a file name for messages.
        $template_file_name = 'page--' . $suggestion . '.html.twig';
      }
      else {
        // Not a suggestion, set the root template name for default page layout message.
        $template_file_name = 'page.html.twig';
      }

      // Set variable for backups.
      $enable_backups = FALSE;
      if ($values['settings_enable_backups'] == 1 &&
          $values['layout_type_select'] != 'disable_layout_generation') {
        $enable_backups = TRUE;
      }

      // Do the heavy lifting.
      $generateLayout->saveLayoutRegionsList($theme, $enable_backups);
      $generateLayout->savePageTemplate($theme, $suggestion, $enable_backups);

      // check if the file exists and if so set a message.
      $file_path = drupal_get_path('theme', $theme) . '/templates/' . $template_file_name;
      if (file_exists($file_path)) {
        drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
      }
      else {
        drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
      }
    }

    // Return values so they are merged and written into configuration.
    return $values;
  }

}  // end class
