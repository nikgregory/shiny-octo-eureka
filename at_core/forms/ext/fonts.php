<?php

use Drupal\Component\Utility\Html;
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

if (theme_get_setting('settings.font_websafe')) {
  $font_options['websafe'] = t('Websafe stack');
}
if (theme_get_setting('settings.font_google')) {
  $font_options['google'] = t('Google font');
}
if (theme_get_setting('settings.font_typekit')) {
  $font_options['typekit'] = t('Typekit');
}
if (theme_get_setting('settings.font_customstack')) {
  $font_options['customstack'] = t('Custom stack');
}

// Websafe stack message
if ($websafe = theme_get_setting('settings.font_websafe')) {
  foreach ($websafe_fonts as $websafe_font_key => $websafe_font_value) {
    if ($websafe == $websafe_font_key) {
      $selected_websafe_stack = Html::escape($websafe_font_value);
    }
  }
}

// Custom stack message
if ($customstack = theme_get_setting('settings.font_customstack')) {
  $selected_customstack = Html::escape($customstack);
}

$form['fonts'] = array(
  '#type' => 'details',
  '#title' => t('Fonts'),
  '#group' => 'extension_settings',
);

// FONT Setup
$form['fonts']['setup'] = array(
  '#type' => 'details',
  '#title' => t('Fonts Setup'),
);

// Help
$form['fonts']['setup']['help'] = array(
  '#type' => 'container',
  '#markup' => t('First set the fonts you want to use in your site and save the Extension settings. Then apply fonts to specific elements.'),
);

// FONT Setup: Websafe font
$form['fonts']['setup']['settings_font_websafe'] = array(
  '#type' => 'select',
  '#title' => t('Websafe stack'),
  '#default_value' => Xss::filter(theme_get_setting('settings.font_websafe')),
  '#options' => $websafe_fonts,
);

// FONT Setup: Google font
$form['fonts']['setup']['settings_font_google'] = array(
  '#type' => 'textfield',
  '#title' => t('Google fonts'),
  '#default_value' => Xss::filter(theme_get_setting('settings.font_google')),
  '#description' => t('<ol><li>Use the <a href="@google_font_wizard" target="_blank">Google font wizard</a> to select your fonts.</li><li>Click the "Use" button, then copy/paste the URL from the <em>Standard</em> method, e.g. <code>http://fonts.googleapis.com/css?family=Open+Sans</code></li></ol>', array('@google_font_wizard' => 'http://www.google.com/fonts')),
);

// FONT Setup: Webfont - Typekit
$form['fonts']['setup']['settings_font_typekit'] = array(
  '#type' => 'textfield',
  '#title' => t('Typekit ID'),
  '#default_value' => Html::escape(theme_get_setting('settings.font_typekit')),
  '#description' => t('<ol><li>Locate the <em>Embed Code</em> details for your kit and find this line: <em>If you\'re using a plugin or service that asks for a Typekit Kit ID, use this: okb4kwr</em>.</li><li>Copy/paste the ID, e.g. <code>okb4kwr</code>.</li></ol>'),
);

// FONT Setup: Custom string
$form['fonts']['setup']['settings_font_customstack'] = array(
  '#type' => 'textfield',
  '#title' => t('Custom stack'),
  '#default_value' => Xss::filter(theme_get_setting('settings.font_customstack')),
  '#description' => t('Enter a comma seperated list of fonts. Quote font names with spaces, e.g. <code>"Times New Roman", Garamond, sans-serif</code>'),
);

$form['fonts']['setup']['lineheight'] = array(
  '#type' => 'details',
  '#title' => t('Line height mulipliers'),
  '#description' => t('Multipliers are used to calculate the line-height for each font size. Normally this value will be between 1.0 and 3.0.'),
);

$form['fonts']['setup']['lineheight']['settings_font_lineheight_multiplier_default'] = array(
  '#type' => 'number',
  '#title' => t('Default'),
  '#max-lenght' => 3,
  '#step' => 0.1,
  '#default_value' => Html::escape(theme_get_setting('settings.font_lineheight_multiplier_default')),
  '#attributes' => array(
    'min' => 1,
    'max' => 10,
    'step' => 0.1,
    'class' => array('font-option')
  ),
);

$form['fonts']['setup']['lineheight']['settings_font_lineheight_multiplier_large'] = array(
  '#type' => 'number',
  '#title' => t('Large font multiplier'),
  '#max-lenght' => 3,
  '#step' => 0.1,
  '#description' => t('Large fonts usually require a smaller multiplier.'),
  '#default_value' => Html::escape(theme_get_setting('settings.font_lineheight_multiplier_large')),
  '#attributes' => array(
    'min' => 1,
    'max' => 10,
    'step' => 0.1,
    'class' => array('font-option')
  ),
);

$form['fonts']['setup']['lineheight']['settings_font_lineheight_multiplier_large_size'] = array(
  '#type' => 'number',
  '#title' => t('Large font size'),
  '#field_suffix' => 'px',
  '#max-lenght' => 2,
  '#description' => t('What is considerd a large font?'),
  '#default_value' => Html::escape(theme_get_setting('settings.font_lineheight_multiplier_large_size')),
  '#attributes' => array(
    'min' => 1,
    'max' => 99,
    'step' => 1,
    'class' => array('font-option')
  ),
);

// APPLY Fonts
$form['fonts']['apply'] = array(
  '#type' => 'details',
  '#title' => t('Apply Fonts'),
);

// Build form
foreach ($font_elements as $font_element_key => $font_element_values) {

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
    '#default_value' => Xss::filter(theme_get_setting('settings.font_google_' . $font_element_key)),
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
    '#default_value' => Xss::filter(theme_get_setting('settings.font_typekit_' . $font_element_key)),
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
      '#type' => 'number',
      '#title' => t('Size'),
      '#field_suffix' => 'px <small>(coverts to rem)</small>',
      '#default_value' => Html::escape(theme_get_setting('settings.font_size_' . $font_element_key)),
      '#attributes' => array(
        'min' => 0,
        'max' => 999,
        'step' => 1,
        'class' => array('font-option')
      ),
    );
  }

  // Custom selectors has a textarea.
  if ($font_element_key === 'custom_selectors') {
    $form['fonts']['apply']['custom_selectors']['settings_custom_selectors'] = array(
      '#type' => 'textarea',
      '#title' => t('Custom Selectors'),
      '#rows' => 3,
      '#default_value' => Xss::filter(theme_get_setting('settings.custom_selectors')),
      '#description' => t("Enter a comma seperated list of valid CSS selectors, with no trailing comma, such as <code>.node-content, .block-content</code>. Note that due to security reason you cannot use the greater than symbol (>) as a child combinator selector."),
      '#states' => array(
        'disabled' => array('select[name="settings_selectors_font_type"]' => array('value' => '<none>')),
      ),
    );
  }
}
