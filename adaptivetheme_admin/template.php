<?php
/**
 * Override or insert variables into page templates.
 */
function adaptivetheme_admin_preprocess_page(&$vars) {
  global $user;
  $vars['datetime_rfc'] = '';
  $vars['datetime_iso'] = '';
  $vars['datetime_rfc'] = date("r" , time()); // RFC2822 date format
  $vars['datetime_iso'] = date("c" , time()); // ISO 8601 date format
}

/**
* Reset theme button styles for admin to prevent issues with
* Views and other modules
*/
function adaptivetheme_admin_button($vars) {
  $element = $vars['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}
