<?php

/**
 * @file
 * Generate form elments for the $titles Styles settings.
 */

$form['titles'] = array(
  '#type' => 'details',
  '#title' => t('Titles'),
  '#group' => 'at_settings',
);

// Title styles
$form['titles']['titles_settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('Titles'),
  '#weight' => -100,
);

$form['titles']['titles_settings']['description'] = array(
  '#markup' => t('<h3>Title Styles</h3><p>Set case, weight and alignment for site name and slogan, page, node, block and comment titles.</p><p>Note: the <em>page title</em> style will be used on full node views.'),
);

// Array of valid title types
$titles_valid_types = array(
  'site_name',
  'site_slogan',
  'page_title',
  'node_title',
  'block_title',
  'comment_title',
);

// Get the fonts list
$font_elements = font_elements();

// Build form elements for each selector and style.
foreach ($font_elements as $font_element_key => $font_element_value) {
  $title_element = $font_element_key;
  if (in_array($title_element, $titles_valid_types)) {
    $element   = $font_element_value['element'];  // e.g. "ptf" (page_title_font), used to set array keys and body classes
    $setting   = $title_element;                  // use the key for these settings, it doesnt have "font" in it
    $container = $font_element_value['setting'];  // the theme setting used to retrieve the font values, e.g. "site_name_font"

    $setting_container = str_replace('_', '-', $container) . '-style';           // a nicer string for fielset classes
    $title             = str_replace('_', ' ', drupal_ucfirst($title_element));  // use key for titles, it doesnt have "font" in it

    // Set variables for setting case, weight and alignment.
    $setting_case       = $setting . '_case';
    $setting_weight     = $setting . '_weight';
    $setting_alignment  = $setting . '_alignment';
    //$setting_shadow     = $setting . '_shadow';

    // Fieldset wrapper for each title
    $form['titles']['titles_settings'][$setting_container]  = array(
      '#type' => 'fieldset',
      '#title' => t($title),
      '#description' => t("<strong>$title</strong>"),
      '#attributes' => array('class' => array('titles-styles-wrapper')),
    );

    // Case
    $form['titles']['titles_settings'][$setting_container]['settings_' . $setting_case] = array(
      '#type' => 'select',
      '#title' => t('Case'),
      '#default_value' => theme_get_setting('settings.' . $setting_case),
      '#options' => font_style_options('case'),
    );

    // Weight
    $form['titles']['titles_settings'][$setting_container]['settings_' . $setting_weight] = array(
      '#type' => 'select',
      '#title' => t('Weight'),
      '#default_value' => theme_get_setting('settings.' . $setting_weight),
      '#options' => font_style_options('weight'),
    );

    // Alignment
    $form['titles']['titles_settings'][$setting_container]['settings_' . $setting_alignment] = array(
      '#type' => 'select',
      '#title' => t('Alignment'),
      '#default_value' => theme_get_setting('settings.' . $setting_alignment),
      '#options' => font_style_options('alignment'),
    );

    // Text shadow
    /*
    $form['titles']['titles_settings'][$setting_container]['' . $setting_shadow . ''] = array(
      '#type' => 'select',
      '#title' => t('Shadow'),
      '#default_value' => theme_get_setting($setting_shadow),
      '#options' => font_style_options('shadow', $element),
    );
    */
  }
}







