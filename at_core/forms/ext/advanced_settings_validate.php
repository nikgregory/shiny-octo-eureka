<?php

/**
 * Validate form values.
 */
function at_core_validate_advanced_settings(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];
}
