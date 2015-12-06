<?php

/**
 * Validate form values.
 */
function at_generator_validate_generator(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];

  // Validate Theme Generator.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_core') {
    $machine_name  = $values['generate']['generate_machine_name'];
    $path   = drupal_get_path('theme', 'at_core');
    $target = $path . '/../../' . $machine_name;

    $subtheme_type    = $values['generate']['generate_type'];
    //$skin_base_theme  = $values['generate']['generate_skin_base'];
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
      $form_state->setErrorByName('', t('The Starterkit or base theme (if you are generating a Skin) can not be found or is not readable - check permissions or perhaps you moved things around?'));
    }
    if (!is_writable(dirname($target))) {
      $form_state->setErrorByName('', t('The target directory is not writable, please check permissions on the <code>/themes/</code> directory where Adaptivetheme is located.'));
    }
  }
}
