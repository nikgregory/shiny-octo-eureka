<?php

/**
 * @file
 * Generate form elments for the $titles Styles settings.
 */

$form['titles'] = array(
  '#type' => 'details',
  '#title' => t('Titles'),
  '#group' => 'extension_settings',
);

$form['titles']['description'] = array(
  '#markup' => t('<h3>Title Styles</h3><p>Set title case, weight and alignment.</p><p>Semibold and light font-weight options will only work if the font supports those weights, otherwise these typically render as bold and normal respecitively.</p>'),
);

// Array of valid title types
$titles_valid_types = title_valid_type_options();

// Get the fonts list
$font_elements = font_elements();

// Build form elements for each selector and style.
foreach ($font_elements as $font_element_key => $font_element_value) {
  if (in_array($font_element_key, $titles_valid_types)) {
    // Title element
    $form['titles'][$font_element_key . '_element']  = array(
      '#type' => 'details',
      '#title' => t($font_element_value['label']),
    );
    // Case
    $form['titles'][$font_element_key . '_element']['settings_titles_' . $font_element_key . '_case'] = array(
      '#type' => 'select',
      '#title' => t('Case'),
      '#default_value' => theme_get_setting('settings.titles_' . $font_element_key . '_case'),
      '#options' => title_style_options('case'),
    );
    // Weight
    $form['titles'][$font_element_key . '_element']['settings_titles_' . $font_element_key . '_weight'] = array(
      '#type' => 'select',
      '#title' => t('Weight'),
      '#default_value' => theme_get_setting('settings.titles_' . $font_element_key . '_weight'),
      '#options' => title_style_options('weight'),
    );
    // Alignment
    $form['titles'][$font_element_key . '_element']['settings_titles_' . $font_element_key . '_alignment'] = array(
      '#type' => 'select',
      '#title' => t('Alignment'),
      '#default_value' => theme_get_setting('settings.titles_' . $font_element_key . '_alignment'),
      '#options' => title_style_options('alignment'),
    );
  }
}
