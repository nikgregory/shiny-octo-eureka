<?php

/**
 * Validate form values.
 * TODO: form_set_error is deprecated, but I am not sure how to use setErrorByName(), SEE https://drupal.org/node/2145007
 */
function at_core_validate_generator(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

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
