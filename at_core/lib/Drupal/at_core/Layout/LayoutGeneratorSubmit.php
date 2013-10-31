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

        // Do the same for the template suggestion provider.
        $values["settings_template_suggestion_provider_page__$clean_suggestion"] = $selected_layout_provider;

        // Set a file name for messages
        $template_file_name = 'page--' . $suggestion . '.html.twig';

      }
      else {
        // Save a setting for the default page temlate layout.
        $values["settings_template_suggestion_page"] = $selected_layout;

        // Set a file name for messages.
        $template_file_name = 'page.html.twig';
      }

      // If backups are disabled don't create any.
      $disable_backups = FALSE;
      if ($values['settings_disable_backups'] == 1) {
        $disable_backups = TRUE;
      }

      // Do the heavy lifting.
      $generateLayout->saveLayoutRegionsList($theme, $disable_backups);
      $generateLayout->savePageTemplate($theme, $suggestion, $disable_backups);

      // check if the file exists and if so set a message.
      $file_path = drupal_get_path('theme', $theme) . '/templates/' . $template_file_name;
      if (file_exists($file_path)) {
        drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
      }
      else {
        drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
      }
    }
  }

}  // end class
