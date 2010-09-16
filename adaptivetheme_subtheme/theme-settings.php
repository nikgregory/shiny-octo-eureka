<?php // $Id$
// adaptivethemes.com st

/**
 * @file theme-settings.php
 */

// Include the definition of adaptivetheme_settings() and adaptivetheme_theme_get_default_settings().
include_once(drupal_get_path('theme', 'adaptivetheme') .'/theme-settings.php');

/**
* Implementation of themehook_settings() function.
*
* @param $saved_settings
*   An array of saved settings for this theme.
* @return
*   A form array.
*/
function adaptivetheme_subtheme_settings($saved_settings) {

  // Get the default values from the .info file.
  $defaults = adaptivetheme_theme_get_default_settings('adaptivetheme_subtheme');

  // Merge the saved variables and their default values.
  $settings = array_merge($defaults, $saved_settings);

  // Create the form using Forms API: http://api.drupal.org/api/6
  $form = array();

  // You can add settings here - the example uses the style_schemes settings.
  // Style schemes
  if ($settings['styles_enable_schemes'] == 'on') {
    $form['style'] = array(
      '#type' => 'fieldset',
      '#title' => t('Style settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#weight' => 90,
      '#description'   => t('Use these settings to modify the style - such as the color scheme. If no stylesheet is selected the default styles will apply.'),
    );
    $form['style']['style_schemes'] = array(
      '#type' => 'select',
      '#title' => t('Styles'),
      '#default_value' => $settings['style_schemes'],
      '#options' => array(
        'style-default.css' => t('Default Style'),
      ),
    );
    $form['style']['style_enable_schemes'] = array(
      '#type' => 'hidden',
      '#value' => $settings['style_enable_schemes'],
    );
  } // endif schemes

  // Add the base theme's settings.
  $form += adaptivetheme_settings($saved_settings, $defaults);

  // Return the form
  return $form;
}