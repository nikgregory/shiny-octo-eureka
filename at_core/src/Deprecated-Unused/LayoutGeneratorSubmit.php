<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\Layouts.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Layout\LayoutGenerator;

class LayoutGeneratorSubmit {

  public function generateLayout($theme, $values) {

    $generateLayout = new LayoutGenerator();

    // Set variable for backups.
    $enable_backups = FALSE;
    if ($values['settings_enable_backups'] == 1) {
      $enable_backups = TRUE;
    }

    // Has the user entered a new template suggestion name?
    if (!empty($values['ts_name'])) {

      $suggestion = $values['ts_name'];

      // Create a setting for each new template suggestion
      $clean_suggestion = strtr($suggestion, '-', '_');
      $values["settings_suggestion_page__$clean_suggestion"] = $clean_suggestion;


      // Set a file name for messages.
      $template_file_name = 'page--' . $suggestion . '.html.twig';



      // We can do this here now, but later on if we add a system for allowing adding of rows, we have to resave all the templates in one go.
      $generateLayout->savePageTemplate($theme, $suggestion, $enable_backups);



      // Resave the info file, this might change a region name etc.
      $generateLayout->saveLayoutRegionsList($theme, $enable_backups);



      // check if the file exists and if so set a message.
      $file_path = drupal_get_path('theme', $theme) . '/templates/page/' . $template_file_name;
      if (file_exists($file_path)) {
        drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
      }
      else {
        drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
      }
    }

    $theme_breakpoints = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($theme);
    $layout_config = $values['settings_layoutconfig'];
    $css_config = $values['settings_cssconfig'];
    $compatible_layout = $values['settings_compatible_layout'];

    foreach ($values['settings_suggestions'] as $suggestion_key => $suggestions_name) {
      foreach ($theme_breakpoints as $bpid => $bpvalue) {
        foreach ($layout_config['rows'] as $row_key => $row_values) {
          $css_data[$suggestion_key][$bpvalue->getLabel()]['query'] = $bpvalue->getMediaQuery();
          if (!empty($values['settings_'. $suggestion_key .'_'. $bpvalue->getLabel() .'_'. $row_key])) {
            $css_data[$suggestion_key][$bpvalue->getLabel()]['rows'][$row_key] = $values['settings_'. $suggestion_key .'_'. $bpvalue->getLabel() .'_'. $row_key];
          }
        }
      }
    }

    $generated_files_path = $values['settings_generated_files_path'];
    $generateLayout->saveCSSLayout($theme, $css_data, $css_config, $compatible_layout, $generated_files_path);

    // Return all the values so we can merge in any changes for configuration.
    return $values;
  }
} // end class








/*
  // build css files
  $theme_breakpoints = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($theme);
  $layout_config = $values['settings_layoutconfig'];

  foreach ($values['settings_suggestions'] as $suggestion_key => $suggestions_name) {
    foreach ($theme_breakpoints as $bpid => $bpvalue) {
      foreach ($layout_config['rows'] as $row_key => $row_values) {
        $suggestion_css[$suggestion_key][$bpvalue->getLabel()]['query'] = $bpvalue->getMediaQuery();
        $suggestion_css[$suggestion_key][$bpvalue->getLabel()][$row_key] = $values['settings_'. $suggestion_key .'_'. $bpvalue->getLabel() .'_'. $row_key];





      }
    }
  }


  $output = array();



  foreach ($suggestion_css as $suggestion => $media_query) {

    foreach ($media_query as $media_query_key => $media_query_values) {

      foreach ($media_query_values as $css_key => $css_value) {


        // CSS files

        foreach ($values['settings_cssconfig'] as $cssconfig_key => $cssconfig_values) {
          if ($cssconfig_key == 'css_files_path') {
            $pathtocssfiles = drupal_get_path($theme, 'theme') . '/layout/' . $values['settings_compatible_layout'] . '' . $cssconfig_key['css_files_path'];
          }
          foreach ($cssconfig_values['css'] as $cfkey => $cfvalue) {
            $subdirpath[$suggestion][$css_key] = $cfkey;
          }
        }


        if ($css_key == 'query') {
          $output[$suggestion][] = '@media ' . $css_value . '{' . "\n";
        }
        if ($css_key !== 'query' && !empty($css_value)) {
          $output[$suggestion][] = '.page-row__' . $css_key . ' {' . "\n";

          $output[$suggestion][] = file_get_contents($pathtocssfiles . '/' . $subdirpath[$suggestion][$css_key] . '/' . $css_value . '.css');

          $output[$suggestion][] = '}' . "\n";
        }

      }


    }


  }

  kpr($output);
*/







/*
  // Set variable for backups.
  $enable_backups = FALSE;
  if ($values['settings_enable_backups'] == 1 && $values['layout_type_select'] != 'disable_layout_generation') {
    $enable_backups = TRUE;
  }
*/

  // Do the heavy lifting.
  //$generateLayout->saveLayoutRegionsList($theme, $enable_backups);
  //$generateLayout->savePageTemplate($theme, $template_file_name, $enable_backups);

/*
  // check if the file exists and if so set a message.
  $file_path = drupal_get_path('theme', $theme) . '/templates/page/' . $template_file_name;
  if (file_exists($file_path)) {
    drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
  }
  else {
    drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
  }
*/


/*


  return $values;
*/
























  /*
  This probably means that here we need to build an array that holds:

  1. suggestions
  2. breakpoints
  3. rows with the CSS file setting

  This will get saved into configuration and pulled in preprocess - but how should this work and what does the array look like?


  $settings = array(
    'page__front' => array(
      breakpoints => array(
        'mobile' => array(
          'row_header' => 'none',
        ),
        'mobile_tablet' => array(
          'row_header' => 'two-3-9',
        ),
      ),
    ),
  );


  we can check if the config array key exists, i.e. loop config, if key isset then config->get(setting.key.etc)

  Pretty much the only stuff to keep here is the suggestion vs page checks and saving the template + backups etc.

  All the variable building needs to be removed and replaced with the above concepts.


  BUT - what if we build a CSS file with breakpoints and CSS includes (file_get_contents) and write CSS files to generated_css for each suggestion, then we just have to
  load those files per suggestion in preprocess.

  I think I favour the seperate file approach, its more lazy loaded layout and avoids one huge download.

  OR, we could add a class per suggestion on the body element, write that into the CSS file (one big file), but that file could get really big?

  */


    //kpr($values);



/*
      // Instantiate LayoutGenerator object.
      $generateLayout = new LayoutGenerator($selected_provider, $selected_plugin, $selected_layout);

      // Check if this is a suggestion.
      $suggestion = '';

      if ($values['layout_type_select'] == 'template_suggestion') {

        if ($selected_plugin !== $values['settings_master_layout'])  {
          $form_state->setErrorByName('settings_master_layout', t('Suggestion plugin does not match the default plugin.'));
        }

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
      if ($values['settings_enable_backups'] == 1 && $values['layout_type_select'] != 'disable_layout_generation') {
        $enable_backups = TRUE;
      }

      // Do the heavy lifting.
      $generateLayout->saveLayoutRegionsList($theme, $enable_backups);
      $generateLayout->savePageTemplate($theme, $suggestion, $enable_backups);

      // check if the file exists and if so set a message.
      $file_path = drupal_get_path('theme', $theme) . '/templates/page/' . $template_file_name;
      if (file_exists($file_path)) {
        drupal_set_message(t('Success - template file has been saved to <code>!file_path</code>.', array('!file_path' => $file_path)), 'status');
      }
      else {
        drupal_set_message(t('The template file could not be saved to <code>!file_path</code>, check permissions and try again.', array('!file_path' => $file_path)), 'error');
      }
    }

    // Return values so they are merged and written into configuration.
    return $values;
    */

