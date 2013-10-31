<?php

use Drupal\at_core\Layout\LayoutGenerator;
use Drupal\at_core\Layout\LayoutSettings;

/**
 * Validate form values.
 */
function at_core_settings_form_validate(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

  //dsm($form['layouts']);
  //dsm($values);

  // Validate Layout Generator.
  if (isset($values['settings_layouts_enable']) && $values['settings_layouts_enable'] == 1) {

    // Check if this should even run.
    if ($values['layout_type_select'] != 'disable_layout_generation') {

      // Is a layout selected?
      if ($selected_layout = $values['settings_selected_layout']) {

        // get the layout for the default page template.
        $default_layout = $values['settings_template_suggestion_page'];

        // Get the providers for both selected and default layouts, the can be different.
        $selected_layout_provider = $form['layouts']['select']['layout']['settings_selected_layout']['#options'][$selected_layout]['provider']['data'];

        $default_layout_provider = $form['layouts']['select']['layout']['settings_selected_layout']['#options'][$default_layout]['provider']['data'];

        // Get the selected layout data
        $selected_layout_config = new LayoutSettings($selected_layout_provider);
        $selected_layout_provider_layouts = $selected_layout_config->settingsPrepareData();

        // Get the default layout data
        $default_layout_config = new LayoutSettings($default_layout_provider);
        $default_layout_provider_layouts = $default_layout_config->settingsPrepareData();

        // Get the selected and default layouts series, we need to compare them if this is a suggestion
        $selected_series = $selected_layout_provider_layouts[$selected_layout]['series'];
        $default_series = $default_layout_provider_layouts[$default_layout]['series'];

        // Pass in some form values we will use in submit
        $form_state['values']['selected_layout_provider'] = $selected_layout_provider;
        $form_state['values']['default_layout_provider'] = $default_layout_provider;

        // Validate suggestions if template_suggestion is selected.
        if ($values['layout_type_select'] == 'template_suggestion') {

          // Did the user enter a template suggestion?
          if (empty($values['template_suggestion_name'])) {
            form_set_error('template_suggestion_name', t("No suggestion was provided."));
          }

          if ($selected_series !== $default_series) {
            form_set_error('settings_selected_layout', t("Template suggestion layout must belong to the same <b>Series</b> as the default layout."));
          }
        }

        // Check if content region is defined - Drupal requires themes to declare
        // a "content" region, we must check if one is included in the layout.
        $generateLayout = new LayoutGenerator($selected_layout_provider, $selected_layout);
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
      form_set_error('', t('The Starterkit or base theme (if you are generating a Skin) can not be found or is not readable - check permissions or perhaps you moved things around?'));
    }
    if (!is_writable(dirname($target))) {
      form_set_error('', t('The target directory is not writable, please check permissions on the <code>/themes/</code> directory where Adaptivetheme is located.'));
    }
  }
}

