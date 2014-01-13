<?php

/**
 * Validate form values.
 * TODO: form_set_error is deprecated, but I am not sure how to use setErrorByName(), SEE https://drupal.org/node/2145007
 */
function at_core_validate_advanced_settings(&$form, &$form_state) {
  $theme  = $form_state['build_info']['args'][0];
  $values = $form_state['values'];

}
