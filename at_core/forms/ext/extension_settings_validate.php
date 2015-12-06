<?php

/**
 * @file
 * Validate form values.
 * @param $form
 * @param $form_state
 */
function at_core_validate_extension_settings(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];

  //if ($values['settings_responsive_menu_default_breakpoint'] == $values['settings_responsive_menu_responsive_breakpoint']) {
   //$form_state->setErrorByName('settings_responsive_menu_responsive_breakpoint', t("Responsive menu breakpoints must be different, double check both the default and responsive style breakpoint options."));
  //}
}
