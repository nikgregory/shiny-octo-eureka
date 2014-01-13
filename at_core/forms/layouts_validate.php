<?php

use Drupal\at_core\Layout\LayoutGenerator;
use Drupal\at_core\Layout\LayoutSettings;

/**
 * Validate form values.
 * TODO: form_set_error is deprecated, but I am not sure how to use setErrorByName(), SEE https://drupal.org/node/2145007
 */
function at_core_validate_layouts(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  //kpr($values);

  // Validate Layout Generator.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    // Check if this should even run.
    if ($values['layout_type_select'] != 'disable_layout_generation') {

      // Is a layout selected?
      $plugin = $values['settings_master_layout'];
      if ($selected_layout = $values['settings_selected_layout_' . $plugin]) {

        // Selected provider and plugin.
        $selected_provider = $form['layouts']['layout_select']['variants_' . $plugin]['settings_selected_layout_' . $plugin]['#options'][$selected_layout]['provider']['data'];
        $selected_plugin = $form['layouts']['layout_select']['variants_' . $plugin]['settings_selected_layout_' . $plugin]['#options'][$selected_layout]['plugin']['data'];

        // Default provider and plugin.
        $default_layout = $values['settings_template_suggestion_page'];
        $default_plugin = $form['layouts']['layout_select']['variants_' . $plugin]['settings_selected_layout_' . $plugin]['#options'][$default_layout]['plugin']['data'];
        $default_provider = $form['layouts']['layout_select']['variants_' . $plugin]['settings_selected_layout_' . $plugin]['#options'][$default_layout]['provider']['data'];

        // Pass data into form values, we need these during submit.
        $form_state['values']['selected_layout_plugin'] = $selected_plugin;
        $form_state['values']['selected_layout_provider'] = $selected_provider;

        $form_state['values']['default_layout_plugin'] = $default_plugin;
        $form_state['values']['default_layout_provider'] = $default_provider;

        // Reset the suggestion_page selected layout if we are processing the default layout.
        if ($values['layout_type_select'] == 'default_layout') {
          $form_state['values']['settings_template_suggestion_page'] = $selected_layout;
        }

        // Validate suggestions if template_suggestion is selected.
        if ($values['layout_type_select'] == 'template_suggestion') {

          // Did the user enter a template suggestion?
          if (empty($values['template_suggestion_name'])) {
            form_set_error('template_suggestion_name', $form_state, t("No suggestion was provided."));
          }

          if ($selected_plugin !== $default_plugin) {
            form_set_error('settings_selected_layout', $form_state, t("Template suggestion layout must belong to the same <b>Plugin</b> as the default layout."));
          }
        }

        // Check if content region is defined - Drupal requires themes to declare
        // a "content" region, we must check if one is included in the layout.
        $generateLayout = new LayoutGenerator($selected_provider, $selected_plugin, $selected_layout);
        $regions_list = $generateLayout->formatLayoutRegions();

        if (array_key_exists('content', $regions_list)) {
          $content_exists = '';
        }
        else {
          $layout_file_name = strtolower($selected_plugin) . '.layout.yml';
          form_set_error('', $form_state, t("The <code>content</code> region does not exist and is a required region for Drupal. <code>!layout_file_name</code> must define at least one region with the machine name <code>content</code>, for example <code>content: 'Main Content'</code>. Update your layout and clear the site cache before trying again.", array('!layout_file_name' => $layout_file_name)));
        }
      }

      if (isset($values['settings_max_width_enable']) && $values['settings_max_width_enable'] === 1) {
        if (empty($values['settings_max_width_value'])) {
          form_set_error('settings_max_width_value', $form_state, t("No value entered for the max-width setting."));
        }

      }

    }
  }

  // Validate Theme Generator.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $machine_name  = $values['generate']['generate_machine_name'];
    $path   = drupal_get_path('theme', 'at_core');
    $target = $path . '/../../' . $machine_name;

    $subtheme_type    = $values['generate']['generate_type'];
    $skin_base_theme  = $values['generate']['generate_skin_base'];
    $clone_source     = $values['generate']['generate_clone_source'];

    if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal' || $subtheme_type == 'at_skin') {
      $source = $path . '/../at_starterkits/' . $subtheme_type;
    }
    else if ($subtheme_type == 'at_clone') {
      $clone_source_theme = drupal_get_path('theme', $clone_source);
      $source = $clone_source_theme;
    }

    // Check if directories and files exist and are readable/writable etc.
    if (!file_exists($source) && !is_readable($source)) {
      form_set_error('', $form_state, t('The Starterkit or base theme (if you are generating a Skin) can not be found or is not readable - check permissions or perhaps you moved things around?'));
    }
    if (!is_writable(dirname($target))) {
      form_set_error('', $form_state, t('The target directory is not writable, please check permissions on the <code>/themes/</code> directory where Adaptivetheme is located.'));
    }
  }
}
