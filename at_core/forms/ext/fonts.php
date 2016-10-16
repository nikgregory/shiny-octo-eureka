<?php

/**
 * Generate form elements for the font settings.
 */

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;

// Elements to apply fonts to.
$font_elements = font_elements();
$websafe_options = [];

// Websafe stacks and select options.
if ($settings_font_websafe = theme_get_setting('settings.font_websafe')) {
  $websafe_fonts = $settings_font_websafe;
}
else {
  $websafe_fonts = implode("\n", websafe_fonts());
}
if (!empty($websafe_fonts)) {
  $websafe_options = explode(PHP_EOL, $websafe_fonts);
}

// Font Options - here we must test if there are values set for each font type
// and populate the options list.
$font_options = [
  'none' => t('-- none --'),
];

if (!empty($websafe_fonts)) {
  $font_options['websafe'] = t('Websafe fonts');
}
if (theme_get_setting('settings.font_google')) {
  $font_options['google'] = t('Google font');
}
if (theme_get_setting('settings.font_typekit')) {
  $font_options['typekit'] = t('Typekit');
}

$form['fonts'] = [
  '#type' => 'details',
  '#title' => t('Fonts'),
  '#group' => 'extension_settings',
];

// Font Setup
$form['fonts']['setup'] = [
  '#type' => 'details',
  '#title' => t('Fonts Setup'),
];

// Help
$form['fonts']['setup']['help'] = [
  '#type' => 'container',
  '#markup' => t('First set the fonts you want to use in your site and save the Extension settings. Then apply fonts to specific elements.'),
];

// Websafe stacks
$form['fonts']['setup']['settings_font_websafe'] = [
  '#type' => 'textarea',
  '#title' => t('Websafe font stacks'),
  '#rows' => 10,
  '#default_value' => $websafe_fonts,
  '#description' => t('Enter one font stack per line. Separate fonts with a comma, quote font names with spaces, e.g <code>"Times New Roman", Times, serif;</code>.'),
];

// Font Setup: Google font
$form['fonts']['setup']['settings_font_google'] = [
  '#type' => 'textarea',
  '#rows' => 3,
  //'#maxlength' => 1024,
  '#title' => t('Google fonts'),
  '#default_value' => Xss::filter(theme_get_setting('settings.font_google')),
  '#description' => [
    '#theme' => 'item_list',
    '#list_type' => 'ol',
    '#attributes' => ['class' => ['google-font-wizard-desc', 'web-font-desc']],
    '#items' => [
      t('Use the <a href=":google_font_wizard" target="_blank">Google font wizard</a> to select your fonts.', [':google_font_wizard' => 'https://fonts.google.com/']),
      t('Click the "Use" button, then copy/paste the URL from the <em>Standard</em> method, e.g. <code>https://fonts.googleapis.com/css?family=Open+Sans</code>'),
      t('Note: always use <code>https</code>, even with <code>http</code> and mixed content websites.'),
    ],
  ],
];

// Font Setup: Typekit
$form['fonts']['setup']['settings_font_typekit'] = [
  '#type' => 'textfield',
  '#title' => t('Typekit ID'),
  '#default_value' => Html::escape(theme_get_setting('settings.font_typekit')),
  '#description' => [
    '#theme' => 'item_list',
    '#list_type' => 'ol',
    '#attributes' => ['class' => ['typekit-font-desc', 'web-font-desc']],
    '#items' => [
      t('Locate the <em>Embed Code</em> details for your kit.'),
      t('Copy/paste the ID, e.g. <code>okb4kwr</code>.'),
    ],
  ],
];

// Fallback
$form['fonts']['setup']['settings_font_fallback'] = [
  '#type' => 'select',
  '#title' => t('Fallback font family'),
  '#options' => [
    'sans_serif' => t('Sans-serif'),
    'serif' => t('Serif'),
    'monospace' => t('Monospace'),
    'cursive' => t('Cursive'),
    'fantasy' => t('Fantasy'),
  ],
  '#default_value' => theme_get_setting('settings.font_fallback') ? theme_get_setting('settings.font_fallback') : 'sans_serif',
  '#description' => t('In the event a font does not load use a generic fallback.'),
];

$form['fonts']['setup']['line_height'] = [
  '#type' => 'details',
  '#title' => t('Line height'),
  '#description' => t('Normally this value will be between 1.0 and 3.0.'),
];

$form['fonts']['setup']['line_height']['settings_font_line_height_multiplier_default'] = [
  '#type' => 'number',
  '#title' => t('Base'),
  '#max-length' => 4,
  '#step' => 0.001,
  '#default_value' => Html::escape(theme_get_setting('settings.font_line_height_multiplier_default')),
  '#attributes' => [
    'min' => 1,
    'max' => 10,
    'step' => 0.001,
    'class' => ['font-option']
  ],
];

$form['fonts']['setup']['line_height']['settings_font_line_height_multiplier_large'] = [
  '#type' => 'number',
  '#title' => t('Headings'),
  '#max-length' => 4,
  '#step' => 0.001,
  '#description' => t('Headings usually require a smaller line-height.'),
  '#default_value' => Html::escape(theme_get_setting('settings.font_line_height_multiplier_large')),
  '#attributes' => [
    'min' => 1,
    'max' => 10,
    'step' => 0.001,
    'class' => ['font-option']
  ],
];

// Apply Fonts
$form['fonts']['apply'] = [
  '#type' => 'details',
  '#title' => t('Apply Fonts'),
];

// Build form
foreach ($font_elements as $font_element_key => $font_element_values) {

  $form['fonts']['apply'][$font_element_key] = [
    '#type' => 'details',
    '#title' => t($font_element_values['label']),
  ];

  $form['fonts']['apply'][$font_element_key]['settings_font_' . $font_element_key] = [
    '#type' => 'select',
    '#title' => t('Font type'),
    '#options' => $font_options,
    '#default_value' => theme_get_setting('settings.font_' . $font_element_key),
  ];

  // Websafe fonts.
  if (isset($font_options['websafe'])) {
    $websafe_font_element_key_setting = theme_get_setting('settings.font_websafe_' . $font_element_key);
    $form['fonts']['apply'][$font_element_key]['settings_font_websafe_' . $font_element_key] = [
      '#type' => 'select',
      '#title' => t('Select a font stack to apply to this element.'),
      '#options' => $websafe_options,
      '#default_value' => isset($websafe_font_element_key_setting) ? $websafe_font_element_key_setting : 0,
      '#states' => [
        'visible' => [
          'select[name="settings_font_' . $font_element_key . '"]' => [
            'value' => 'websafe',
          ],
        ],
      ],
    ];
  }

  // Google font.
  if (isset($font_options['google'])) {
    $form['fonts']['apply'][$font_element_key]['settings_font_google_' . $font_element_key] = [
      '#type' => 'textfield',
      '#title' => t('Google font name'),
      '#description' => t('Enter the name of <b>one</b> Google font you set in <em>Fonts</em>. You can find this in step 4 of the Google font wizard. Quote names with a space e.g. <code>"Open Sans"</code>'),
      '#default_value' => Xss::filter(theme_get_setting('settings.font_google_' . $font_element_key)),
      '#states' => [
        'visible' => [
          'select[name="settings_font_' . $font_element_key . '"]' => [
            'value' => 'google',
          ],
        ],
      ],
    ];
  }

  // Typekit font.
  if (isset($font_options['typekit'])) {
    $form['fonts']['apply'][$font_element_key]['settings_font_typekit_' . $font_element_key] = [
      '#type' => 'textfield',
      '#title' => t('Typekit font name'),
      '#description' => t('Enter the name of <b>one</b> Typekit font you set in <em>Fonts</em>. You can find the correct name to use by checking the kits selectors "Using fonts in CSS". Quote names with a space, e.g. <code>"Proxima nova"</code>'),
      '#default_value' => Xss::filter(theme_get_setting('settings.font_typekit_' . $font_element_key)),
      '#states' => [
        'visible' => [
          'select[name="settings_font_' . $font_element_key . '"]' => [
            'value' => 'typekit',
          ],
        ],
      ],
    ];
  }

  // Font size
  if ($font_element_key !== 'h1h4' && $font_element_key !== 'h5h6') {
    $form['fonts']['apply'][$font_element_key]['settings_font_size_' . $font_element_key] = [
      '#type' => 'number',
      '#title' => t('Size'),
      '#field_suffix' => 'px <small>(coverts to rem)</small>',
      '#default_value' => Html::escape(theme_get_setting('settings.font_size_' . $font_element_key)),
      '#attributes' => [
        'min' => 0,
        'max' => 999,
        'step' => 1,
        'class' => ['font-option']
      ],
    ];
  }
  
  // Font smoothing
  $form['fonts']['apply'][$font_element_key]['settings_font_smoothing_' . $font_element_key] = [
    '#type' => 'checkbox',
    '#title' => t('Apply font smoothing'),
    '#description' => t('Font smoothing only works in Mac OS X and may improve legibility, in particular for Google and Typekit fonts.'),
    '#default_value' => theme_get_setting('settings.font_smoothing_' . $font_element_key),
  ];

  // Custom selectors has a textarea.
  if ($font_element_key === 'custom_selectors') {
    $form['fonts']['apply']['custom_selectors']['settings_custom_selectors'] = [
      '#type' => 'textarea',
      '#title' => t('Custom Selectors'),
      '#rows' => 3,
      '#default_value' => Xss::filter(theme_get_setting('settings.custom_selectors')),
      '#description' => t("Enter a comma separated list of valid CSS selectors, with no trailing comma, such as <code>.node__content, .block__content </code>. Note that due to security reason you cannot use the greater than symbol (>) as a child combinator selector."),
      '#states' => [
        'disabled' => ['select[name="settings_selectors_font_type"]' => ['value' => '<none>']],
      ],
    ];
  }

  // Show the selectors this applies to.
  $form['fonts']['apply'][$font_element_key]['selector'] = [
    '#type' => 'container',
    '#markup' => t('Applies to: @selectors' , ['@selectors' => $font_element_values['selector']]),
    '#attributes' => ['class' => ['font-selector-list']],
  ];
}
