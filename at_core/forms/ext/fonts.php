<?php

use Drupal\Component\Utility\String;
use Drupal\Component\Utility\Xss;

/**
 * @file
 * Generate form elments for the font settings.
 */

// Websafe fonts.
$websafe_fonts = websafe_fonts();

// Elements to apply fonts to.
$font_elements = font_elements();

// Font Options - here we must test if there are values set for each font type and populate the options list.
$font_options = array(
  'none' => t('-- none --'),
);
if (theme_get_setting('settings.base_websafe')) {
  $font_options['websafe'] = t('Websafe stack');
}
if (theme_get_setting('settings.base_google')) {
  $font_options['google'] = t('Google font');
}
if (theme_get_setting('settings.base_typekit')) {
  $font_options['typekit'] = t('Typekit');
}
if (theme_get_setting('settings.base_customstack')) {
  $font_options['customstack'] = t('Custom stack');
}

// Websafe stack message
if ($websafe = theme_get_setting('settings.font_websafe')) {
  foreach ($websafe_fonts as $websafe_font_key => $websafe_font_value) {
    if ($websafe == $websafe_font_key) {
      $selected_websafe_stack = $websafe_font_value;
    }
  }
}

// Custom stack message
if ($customstack = theme_get_setting('settings.font_customstack')) {
  $selected_customstack = $customstack;
}

$form['fonts'] = array(
  '#type' => 'details',
  '#title' => t('Fonts'),
  '#group' => 'at_settings',
);

// FONT Setup
$form['fonts']['setup'] = array(
  '#type' => 'details',
  '#title' => t('Fonts'),
);

// Help
$form['fonts']['setup']['help'] = array(
  '#type' => 'container',
  '#markup' => t('First set the fonts you want to use in your site and save the theme settings. Then apply fonts to specific elements.'),
);

// FONT Setup: Websafe font
$form['fonts']['setup']['settings_font_websafe'] = array(
  '#type' => 'select',
  '#title' => t('Websafe stack'),
  '#default_value' => theme_get_setting('settings.font_websafe'),
  '#options' => $websafe_fonts,
);

// FONT Setup: Google font
$google_font_path = check_url(theme_get_setting('settings.font_google'));
$google_font_path_fixed = str_replace('&amp;', '&', $google_font_path);

$form['fonts']['setup']['settings_font_google'] = array(
  '#type' => 'textfield',
  '#title' => t('Google fonts'),
  '#default_value' => $google_font_path_fixed,
  '#description' => t('Use the Google font wizard to select your fonts, then paste in only the URL, e.g. <code>fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext</code>'),
);

// FONT Setup: Webfont - Typekit
$form['fonts']['setup']['settings_font_typekit'] = array(
  '#type' => 'textfield',
  '#title' => t('Typekit'),
  '#default_value' => check_url(theme_get_setting('settings.font_typekit')),
  '#description' => t('Paste in Typekit script src URL, e.g. <code>use.typekit.net/okb4kwr.js</code>'),
);

// FONT Setup: Custom string
$form['fonts']['setup']['settings_font_customstack'] = array(
  '#type' => 'textfield',
  '#title' => t('Custom stack'),
  '#default_value' => Xss::filterAdmin(theme_get_setting('settings.font_customstack')),
  '#description' => t('Enter a comma seperated list of fonts. Quote font names with spaces, e.g. <code>"Times New Roman", Garamond, sans-serif</code>'),
);

// APPLY Fonts
$form['fonts']['apply'] = array(
  '#type' => 'details',
  '#title' => t('Apply Fonts'),
);


// Build form
foreach ($font_elements as $font_element_key => $font_element_values) {

  //kpr($font_element_key);

  $form['fonts']['apply'][$font_element_key] = array(
    '#type' => 'details',
    '#title' => t($font_element_values['label']),
  );

  $form['fonts']['apply'][$font_element_key]['settings_font_' . $font_element_key] = array(
    '#type' => 'select',
    '#title' => t('Font type'),
    '#options' => $font_options,
    '#default_value' => theme_get_setting('settings.font_' . $font_element_key),
  );

  // Websafe font message
  if (isset($selected_websafe_stack) && $selected_websafe_stack !== 'none') {
    $form['fonts']['apply'][$font_element_key]['websafe_font_default'] = array(
      '#type' => 'container',
      '#markup' => t('Current Websafe stack: <code>' . $selected_websafe_stack . '</code>'),
      '#states' => array(
        'visible' => array(
          'select[name="settings_font_' . $font_element_key . '"]' => array(
            'value' => 'websafe',
          ),
        ),
      ),
    );
  }

  // Custom stack message
  if (isset($selected_customstack)) {
    $form['fonts']['apply'][$font_element_key]['customstack_font_default'] = array(
      '#type' => 'container',
      '#markup' => t('Current Custom stack: <code>' . $selected_customstack . '</code>'),
      '#states' => array(
        'visible' => array(
          'select[name="settings_font_' . $font_element_key . '"]' => array(
            'value' => 'customstack',
          ),
        ),
      ),
    );
  }

  // Google font
  $form['fonts']['apply'][$font_element_key]['settings_font_google_' . $font_element_key] = array(
    '#type' => 'textfield',
    '#title' => t('Google font name'),
    '#description' => t('Enter the name of <b>one</b> Google font you set in <em>Fonts</em>. You can find this in step 4 of the Google font wizard, e.g. <code>"Open Sans"</code>'),
    '#default_value' => theme_get_setting('settings.font_google_' . $font_element_key),
    '#states' => array(
      'visible' => array(
        'select[name="settings_font_' . $font_element_key . '"]' => array(
          'value' => 'google',
        ),
      ),
    ),
  );

  // Typekit font
  $form['fonts']['apply'][$font_element_key]['settings_font_typekit_' . $font_element_key] = array(
    '#type' => 'textfield',
    '#title' => t('Typekit font name'),
    '#description' => t('Enter the name of <b>one</b> Typekit font you set in <em>Fonts</em>. You can find this by checking the Typekit Kit Editor settings. Quote names with space, e.g. <code>"Proxima nova"</code>'),
    '#default_value' => theme_get_setting('settings.font_typekit_' . $font_element_key),
    '#states' => array(
      'visible' => array(
        'select[name="settings_font_' . $font_element_key . '"]' => array(
          'value' => 'typekit',
        ),
      ),
    ),
  );

  // Font size
  if ($font_element_key !== 'h1h4' && $font_element_key !== 'h5h6') {
    $form['fonts']['apply'][$font_element_key]['settings_font_size_' . $font_element_key] = array(
      '#type' => 'textfield',
      '#title' => t('Size'),
      '#field_suffix' => 'px (coverted to rem with px fallback)',
      '#size' => 3,
      '#maxlength' => 3,
      '#default_value' => check_plain(theme_get_setting('settings.font_size_' . $font_element_key)),
      '#attributes' => array('class' => array('font-option')),
    );
  }

  // Line-height
  /*
  if ($font_element_key == 'base') {
    $form['fonts']['apply'][$font_element_key]['settings_font_lineheight_' . $font_element_key] = array(
      '#type' => 'textfield',
      '#title' => t('Line height'),
      '#field_suffix' => 'px (coverted to rem with px fallback)',
      '#size' => 3,
      '#maxlength' => 3,
      '#default_value' => check_plain(theme_get_setting('settings.font_lineheight_' . $font_element_key)),
      '#attributes' => array('class' => array('font-option')),
    );
  }
  */

  // Custom selectors has a textarea.
  if ($font_element_key == 'custom_selectors') {
    $form['fonts']['apply']['custom_selectors']['settings_custom_selectors'] = array(
      '#type' => 'textarea',
      '#title' => t('Custom Selectors'),
      '#rows' => 3,
      '#default_value' => Xss::filterAdmin(theme_get_setting('settings.custom_selectors')),
      '#description' => t("Enter a comma seperated list of valid CSS selectors, with no trailing comma, such as <code>.node-content, .block-content</code>. Note that due to security reason you cannot use the greater than symbol (>) as a child combinator selector."),
      '#states' => array(
        'disabled' => array('select[name="settings_selectors_font_type"]' => array('value' => '<none>')),
      ),
    );
  }
}

// FONT Setup: Webfont, leave this out for now, could be one option too many and conceptually difficult
// for low tech users to grasp since it requires knowing paths and understanding how to modify them.
/*
$form['fonts']['setup']['settings_font_webfont'] = array(
  '#type' => 'textarea',
  '#title' => t('Webfonts'),
  '#default_value' => theme_get_setting('settings.font_webfont'),
  '#description' => t('Paste in webfont code, e.g. as generated by the http://www.fontsquirrel.com/tools/webfont-generator.'),
);
*/