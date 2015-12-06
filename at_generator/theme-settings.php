<?php

/**
 * @file
 * Generator theme settings.
 */

/**
 * Implementation of hook_form_system_theme_settings_alter()
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function at_generator_form_system_theme_settings_alter(&$form, &$form_state) {

  // Common paths.
  $at_generator_path = drupal_get_path('theme', 'at_generator');

  // Attach libraries.
  $form['#attached']['library'][] = 'at_generator/at.generator';

  // Set form type vertical tabs.
  $form['generator'] = array('#type' => 'vertical_tabs');

  // Generator form.
  include_once($at_generator_path . '/forms/generator.php');

  // Hide form items.
  $form['theme_settings']['#attributes']['class'] = array('visually-hidden');
  $form['logo']['#attributes']['class'] = array('visually-hidden');
  $form['favicon']['#attributes']['class'] = array('visually-hidden');

  // Modify the submit.
  $form['actions']['submit']['#value'] = t('Generate theme');
  $form['#validate'][] = 'at_generator_validate_generator';
  $form['#submit'][] = 'at_generator_submit_generator';

  include_once($at_generator_path . '/forms/generator_validate.php');
  include_once($at_generator_path . '/forms/generator_submit.php');
}
