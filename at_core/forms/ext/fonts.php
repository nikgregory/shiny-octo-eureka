<?php

/**
 * @file
 * Generate form elments for the font settings.
 */

$form['fonts'] = array(
  '#type' => 'details',
  '#title' => t('Fonts'),
  '#group' => 'at_settings',
);

// Get the font elements array, this holds options and settings for each font type
$form_elements = font_elements();

// Get the google charsets and styles
$google_charsets = google_font_charsets();
$google_styles = google_font_styles();

// Build a message for fonts depending on what is enabled
$fonts_message = t('<h3>Fonts</h3><p>First select the font type&thinsp;&mdash;&thinsp;websafe, google or custom&thinsp;&mdash;&thinsp;then select or enter the required information depending on the type. Adaptivetheme can also support any font supplied by the <a href="!module_fyf" target="_blank">@font-your-face module</a>, new settings and instructions will appear after you have installed @font-your-face.</p><p>To preview and gather required information for adding Google fonts see: <a href="!gwf" target="_blank">google.com/webfonts</a>. If you are adding extra styles or character sets these must exist for the specified font&thinsp;&mdash;&thinsp;use the <em>Quick-use</em> feature in Google fonts to check this information.</p>',
array(
  '!gwf' => 'http://www.google.com/webfonts',
  '!cfs' => 'http://coding.smashingmagazine.com/2009/09/22/complete-guide-to-css-font-stacks/',
  '!module_fyf' => 'http://drupal.org/project/fontyourface',
  )
);

// Build a list of font type options
$font_type_options = array(
  '<none>' => 'None',
  '' => 'Websafe fonts',
  'gwf' => 'Basic Google font',
  'cfs' => 'Custom font stack',
);

// Do some stuff if @font-your-face is enabled
$font_your_face_enabled = FALSE;
if (\Drupal::moduleHandler()->moduleExists('fontyourface')) {
  // add an option to the font type settings
  $font_your_face_enabled = font_your_face_fonts_enabled();
  if ($font_your_face_enabled == TRUE) {
    $fyf_type = array('fyf' => '@font-your-face');
    $font_type_options = array_merge($font_type_options, $fyf_type);
  }

  // Special message once font your face in installed
  $browse_fyf = l(t('@font-your-face library'), 'admin/appearance/fontyourface/browse', array('attributes' => array('target' => array('_blank'))));
  $settings_fyf = l(t('"add selector in theme CSS"'), 'admin/appearance/fontyourface', array('attributes' => array('target' => array('_blank'))));
  $fonts_message = t('<h3>Fonts</h3><p>First select the font type&thinsp;&mdash;&thinsp;websafe, google, custom or @font-your-face&thinsp;&mdash;&thinsp;then select or enter the required information depending on the type. To use @font-your-face you must first enable fonts in the !browse_fyf, then set those fonts to !settings_fyf. When you have done this the @font-your-face fonts will be availble here for you to apply to your content and titles. @font-your-face integration has only been tested with Google and Font Squirrel.</p><p>To preview and gather required information for adding Google fonts see: <a href="!gwf" target="_blank">google.com/webfonts</a>. If you are adding extra styles or character sets these must exist for the specified font&thinsp;&mdash;&thinsp;use the <em>Quick-use</em> feature in Google fonts to check this information.</p>',
  array(
    '!gwf' => 'http://www.google.com/webfonts',
    '!cfs' => 'http://coding.smashingmagazine.com/2009/09/22/complete-guide-to-css-font-stacks/',
    '!browse_fyf' => $browse_fyf,
    '!settings_fyf' => $settings_fyf,
    )
  );
}

// Some re-usable strings, fixing things in multiple places annoys me
$google_font_description = t('Paste the Google font name, e.g. <code>Open Sans Condensed</code>. Only add one font.');
$custom_stack_description = t("Enter a comma seperated list of fonts, with no trailing comma. Names with spaces should be wrapped in single quotes, for example <code>'Times New Roman'</code>.");

// Fonts
$form['fonts']['font_settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('Fonts'),
  '#attributes' => array('class' => array('font-element-wrapper')),
  '#weight' => -110,
);

$form['fonts']['font_settings']['description'] = array(
  '#markup' => $fonts_message,
);


// BASE FONT
$form['fonts']['font_settings']['base-font'] = array(
  '#type' => 'details',
  '#title' => t('Default font'),
);
$form['fonts']['font_settings']['base-font']['settings_base_font_type'] = array(
  '#type' => 'select',
  '#title' => t('Type'),
  '#options' => $font_type_options,
  '#default_value' => theme_get_setting('settings.base_font_type'),
);

// BASE FONT: Websafe font
$form['fonts']['font_settings']['base-font']['settings_base_font'] = array(
  '#type' => 'select',
  '#title' => t('Font'),
  '#default_value' => theme_get_setting('settings.base_font'),
  '#options' => str_replace("'", "", font_list('wsf', 'bf')),
  '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => ''))),
);

// BASE FONT: Google web font
$form['fonts']['font_settings']['base-font']['settings_base_font_gwf'] = array(
  '#type' => 'textfield',
  '#title' => t('Google Font Name'),
  '#default_value' => filter_xss_admin(theme_get_setting('settings.base_font_gwf')),
  '#description' => $google_font_description,
  '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => 'gwf'))),
);
$form['fonts']['font_settings']['base-font']['base_font_gwf_styles'] = array(
  '#type' => 'fieldset',
  '#title' => t('Add Google Font Styles'),
  '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => 'gwf'))),
);
$form['fonts']['font_settings']['base-font']['base_font_gwf_styles']['settings_base_font_gwf_add_styles'] = array(
  '#type' => 'checkbox',
  '#title' => t('Styles'),
  '#default_value' => theme_get_setting('settings.base_font_gwf_add_styles'),
);
foreach ($google_styles as $style_key => $style_value) {
  $form['fonts']['font_settings']['base-font']['base_font_gwf_styles']["settings_base_font_gwf_add_styles_$style_key"] = array(
    '#type' => 'checkbox',
    '#title' => t($style_value),
    '#default_value' => theme_get_setting("settings.base_font_gwf_add_styles_$style_key"),
    '#states' => array('visible' => array('input[name="settings_base_font_gwf_add_styles"]' => array('checked' => TRUE))),
  );
}
$form['fonts']['font_settings']['base-font']['base_font_gwf_charsets'] = array(
  '#type' => 'fieldset',
  '#title' => t('Add Google Font Character Sets'),
  '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => 'gwf'))),
);
$form['fonts']['font_settings']['base-font']['base_font_gwf_charsets']['settings_base_font_gwf_add_charsets'] = array(
  '#type' => 'checkbox',
  '#title' => t('Character Sets'),
  '#default_value' => theme_get_setting('settings.base_font_gwf_add_charsets'),
  '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => 'gwf'))),
);
foreach ($google_charsets as $charset_key => $charset_value) {
  $form['fonts']['font_settings']['base-font']['base_font_gwf_charsets']["settings_base_font_gwf_add_charsets_$charset_key"] = array(
    '#type' => 'checkbox',
    '#title' => t($charset_value),
    '#default_value' => theme_get_setting("settings.base_font_gwf_add_charsets_$charset_key"),
    '#states' => array('visible' => array('input[name="settings_base_font_gwf_add_charsets"]' => array('checked' => TRUE))),
  );
}

// BASE FONT: Custom font stack
$form['fonts']['font_settings']['base-font']['settings_base_font_cfs'] = array(
  '#type' => 'textfield',
  '#title' => t('Font'),
  '#default_value' => filter_xss_admin(theme_get_setting('settings.base_font_cfs')),
  '#description' => $custom_stack_description,
  '#states' => array(
    'visible' => array('select[name="settings_base_font_type"]' => array('value' => 'cfs')),
    'required' => array('select[name="settings_base_font_type"]' => array('value' => 'cfs')),
  )
);

// BASE FONT: Font your face
if ($font_your_face_enabled == TRUE) {
  $form['fonts']['font_settings']['base-font']['settings_base_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('settings.base_font_fyf'),
    '#options' => font_list('fyf', 'bf'),
    '#states' => array('visible' => array('select[name="settings_base_font_type"]' => array('value' => 'fyf'))),
  );
}

// BASE FONT: Size
$form['fonts']['font_settings']['base-font']['settings_base_font_size'] = array(
  '#type' => 'textfield',
  '#title' => t('Size'),
  '#field_suffix' => 'px',
  '#size' => 3,
  '#maxlength' => 3,
  '#description' => t('Set on the <code>html</code> element. Note: all font sizes are set in px and coverted to rem (rem with px fallback).'),
  '#default_value' => check_plain(theme_get_setting('settings.base_font_size')),
  '#attributes' => array('class' => array('font-size-wrapper')),
);



// TITLES
$form['fonts']['font_settings']['titles'] = array(
  '#type' => 'details',
  '#title' => t('Titles'),
  '#collapsible' => TRUE,
  '#collapsed' => TRUE,
);

foreach ($form_elements as $key => $value) {

  $form_type = $key;
  $font_element_type = $value['type'];
  $font_element_selector = $value['selector'];

  if ($font_element_type === 'title') {

    $element  = $value['element'];  // e.g. "bf" (base-font), this is used to set array keys
    $setting  = $value['setting'];  // the theme setting used to retrieve the font values, e.g. "site_name_font"

    $settings_container = str_replace('_', '-', $setting) . '-selector'; // a nicer string for fielset classes
    $title = str_replace('_', ' ', drupal_ucfirst($form_type)); // use the key for titles, it doesnt have "font" in it

    // Set easy reusable variables
    $settings_type = $setting . '_type';
    $settings_font = $setting . '_font';
    $settings_gwf  = $setting . '_gwf';
    $settings_cfs  = $setting . '_cfs';
    $settings_yfy  = $setting . '_fyf';
    $settings_size = $setting . '_size';

    $form['fonts']['font_settings']['titles'][$settings_container] = array(
      '#type' => 'fieldset',
      '#title' => t($title),
      '#description' => '<h3>' . t($title) . '</h3>',
    );
    $form['fonts']['font_settings']['titles'][$settings_container]['settings_' . $settings_type] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $font_type_options,
      '#default_value' => theme_get_setting('settings.' . $settings_type),
    );

    // Websafe fonts
    $form['fonts']['font_settings']['titles'][$settings_container]['settings_' . $setting] = array(
      '#type' => 'select',
      '#title' => t('Font'),
      '#default_value' => theme_get_setting('settings.' . $setting),
      '#options' => str_replace("'", "", font_list('wsf', $element)),
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => '')),
      ),
    );

    // Google web fonts
    $form['fonts']['font_settings']['titles'][$settings_container]['settings_' . $settings_gwf] = array(
      '#type' => 'textfield',
      '#title' => t('Google Font Name'),
      '#default_value' => filter_xss_admin(theme_get_setting('settings.' . $settings_gwf)),
      '#description' => $google_font_description,
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'gwf')),
      ),
    );

    $title_styles_setting = $settings_gwf . '_add_styles';

    $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_styles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Styles'),
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'gwf')),
      ),
    );

    $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $title_styles_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Styles'),
      '#default_value' => theme_get_setting('settings.' . $title_styles_setting),
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'gwf')),
      ),
    );

    foreach ($google_styles as $style_key => $style_value) {

      $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $title_styles_setting . '_' . $style_key] = array(
        '#type' => 'checkbox',
        '#title' => t($style_value),
        '#default_value' => theme_get_setting('settings.' . $title_styles_setting . '_' . $style_key),
        '#states' => array(
          'visible' => array(':input[name="settings_' . $title_styles_setting . '"]' => array('checked' => TRUE)),
        ),
      );
    }

    $title_charsets_setting = $settings_gwf . '_add_charsets';

    $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_charsets'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Character Sets'),
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'gwf')),
      ),
    );

    $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $title_charsets_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Character Sets'),
      '#default_value' => theme_get_setting('settings.' . $title_charsets_setting),
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'gwf')),
      ),
    );

    foreach ($google_charsets as $charset_key => $charset_value) {
      $form['fonts']['font_settings']['titles'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $title_charsets_setting . '_' . $charset_key] = array(
        '#type' => 'checkbox',
        '#title' => t($charset_value),
        '#default_value' => theme_get_setting('settings.' . $title_charsets_setting . '_' . $charset_key),
        '#states' => array(
          'visible' => array(':input[name="settings_' . $title_charsets_setting . '"]' => array('checked' => TRUE)),
        ),
      );
    }

    // Custom font stack
    $form['fonts']['font_settings']['titles'][$settings_container][$settings_cfs] = array(
      '#type' => 'textfield',
      '#title' => t('Font'),
      //'#default_value' => drupal_strip_dangerous_protocols(theme_get_setting('settings. ' . $settings_cfs)),

      '#default_value' => check_plain(theme_get_setting('settings. ' . $settings_cfs)),
      '#description' => $custom_stack_description,
      '#states' => array(
        'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'cfs')),
        'required' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'cfs')),
      ),
    );

    // Font your face
    if ($font_your_face_enabled == TRUE) {
      $form['fonts']['font_settings']['titles'][$settings_container]['settings_' . $settings_yfy] = array(
        '#type' => 'select',
        '#title' => t('Font'),
        '#default_value' => theme_get_setting('settings.' . $settings_yfy),
        '#options' => font_list('fyf', $element),
        '#states' => array(
          'visible' => array('select[name="settings_' . $settings_type . '"]' => array('value' => 'fyf')),
        ),
      );
    }

    // Size
    $form['fonts']['font_settings']['titles'][$settings_container]['settings_' . $settings_size] = array(
      '#type' => 'textfield',
      '#title' => t('Size'),
      '#field_suffix' => 'px',
      '#size' => 3,
      '#maxlength' => 3,
      //'#description' => t('Set on the <code>!selector</code> selector.', array('!selector' => $font_element_selector)),
      '#default_value' => check_plain(theme_get_setting('settings.' . $settings_size)),
      '#attributes' => array('class' => array('font-size-wrapper')),
    );
  }
}


// CUSTOM SELECTORS
$form['fonts']['font_settings']['css'] = array(
  '#type' => 'details',
  '#title' => t('Custom Selectors'),
  '#collapsible' => TRUE,
  '#collapsed' => TRUE,
);

$form['fonts']['font_settings']['css']['settings_selectors_font_type'] = array(
  '#type' => 'select',
  '#title' => t('Type'),
  '#options' => $font_type_options,
  '#default_value' => theme_get_setting('settings.selectors_font_type')
);

// Websafe fonts
$form['fonts']['font_settings']['css']['settings_selectors_font'] = array(
  '#type' => 'select',
  '#title' => t('Font'),
  '#default_value' => theme_get_setting('settings.selectors_font'),
  '#options' => font_list('wsf', 'css'),
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => '')),
  ),
);

// Google fonts
$form['fonts']['font_settings']['css']['settings_selectors_font_gwf'] = array(
  '#type' => 'textfield',
  '#title' => t('Google Font Name'),
  '#default_value' => filter_xss_admin(theme_get_setting('settings.selectors_font_gwf')),
  '#description' => $google_font_description,
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'gwf')),
  ),
);

$css_styles_setting = 'selectors_font_add_styles';

$form['fonts']['font_settings']['css']['selectors_font_gwf_styles'] = array(
  '#type' => 'fieldset',
  '#title' => t('Add Google Font Styles'),
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'gwf')),
  ),
);

$form['fonts']['font_settings']['css']['selectors_font_gwf_styles']['settings_' . $css_styles_setting] = array(
  '#type' => 'checkbox',
  '#title' => t('Styles'),
  '#default_value' => theme_get_setting('settings.' . $css_styles_setting),
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'gwf')),
  ),
);

foreach ($google_styles as $style_key => $style_value) {
  $form['fonts']['font_settings']['css']['selectors_font_gwf_styles']['settings_' . $css_styles_setting . '_' . $style_key] = array(
    '#type' => 'checkbox',
    '#title' => t($style_value),
    '#default_value' => theme_get_setting('settings.' . $css_styles_setting . '_' . $style_key),
    '#states' => array(
      'visible' => array(':input[name="settings_' . $css_styles_setting . '"]' => array('checked' => TRUE)),
    ),
  );
}

$css_charsets_setting = 'selectors_font_add_charsets';

$form['fonts']['font_settings']['css']['selectors_font_gwf_charsets'] = array(
  '#type' => 'fieldset',
  '#title' => t('Add Google Font Character Sets'),
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'gwf')),
  ),
);

$form['fonts']['font_settings']['css']['selectors_font_gwf_charsets']['settings_' . $css_charsets_setting] = array(
  '#type' => 'checkbox',
  '#title' => t('Character Sets'),
  '#default_value' => theme_get_setting('settings.' . $css_charsets_setting),
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'gwf')),
  ),
);

foreach ($google_charsets as $charset_key => $charset_value) {
  $form['fonts']['font_settings']['css']['selectors_font_gwf_charsets']['settings_' . $css_charsets_setting . '_' . $charset_key] = array(
    '#type' => 'checkbox',
    '#title' => t($charset_value),
    '#default_value' => theme_get_setting('settings.' . $css_charsets_setting . '_' . $charset_key),
    '#states' => array(
      'visible' => array(':input[name="settings_' . $css_charsets_setting . '"]' => array('checked' => TRUE)),
    ),
  );
}

// Custom font stacks
$form['fonts']['font_settings']['css']['settings_selectors_font_cfs'] = array(
  '#type' => 'textfield',
  '#title' => t('Font'),
  '#default_value' => filter_xss_admin(theme_get_setting('settings.selectors_font_cfs')),
  '#description' => $custom_stack_description,
  '#states' => array(
    'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'cfs')),
    'required' => array('select[name="settings_selectors_font_type"]' => array('value' => 'cfs')),
  )
);

// Font your face
if ($font_your_face_enabled == TRUE) {
  $form['fonts']['font_settings']['css']['settings_selectors_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('settings.selectors_font_fyf'),
    '#options' => font_list('fyf', 'css'),
    '#states' => array(
      'visible' => array('select[name="settings_selectors_font_type"]' => array('value' => 'fyf')),
    ),
  );
}

// CSS selectors
$form['fonts']['font_settings']['css']['settings_selectors_css'] = array(
  '#type' => 'textarea',
  '#title' => t('CSS Selectors'),
  '#rows' => 3,
  '#default_value' => filter_xss_admin(theme_get_setting('settings.selectors_css')),
  '#description' => t("Enter a comma seperated list of valid CSS selectors, with no trailing comma, such as <code>.node-content, .block-content</code>. Note that due to security reason you cannot use the greater than symbol (>) as a child combinator selector."),
  '#states' => array(
    'disabled' => array('select[name="settings_selectors_font_type"]' => array('value' => '<none>')),
  ),
);
















// HEADINGS
/*
$form['fonts']['font_settings']['headings'] = array(
  '#type' => 'details',
  '#title' => t('Headings'),
  '#collapsible' => TRUE,
  '#collapsed' => TRUE,
);

foreach ($form_elements as $key => $value) {

  $form_type = $key;
  $font_element_subtype = $value['subtype'];

  // we have to handle subgroups seperatly
  if ($font_element_subtype === 'heading_group') {

    // e.g. "bf" (base-font), this is used to set array keys
    $element  = $value['element'];

    // the theme setting used to retrieve the font values, e.g. "site_name_font"
    $setting  = $value['setting'];

    // a nicer string for fielset classes
    $settings_container = str_replace('_', '-', $setting) . '-selector';

    // Custom titles
    if ($element === 'h1h4') {
      $title = 'h1 to h4';
    }
    else {
      $title = 'h5 and h6';
    }

    // Set easy reusable variables
    $settings_type = $setting . '_type';
    $settings_font = $setting . '_font';
    $settings_gwf  = $setting . '_gwf';
    $settings_cfs  = $setting . '_cfs';
    $settings_yfy  = $setting . '_fyf';

    $form['fonts']['font_settings']['headings'][$settings_container] = array(
      '#type' => 'fieldset',
      '#title' => t($title),
    );
    $form['fonts']['font_settings']['headings'][$settings_container]['settings_' . $settings_type] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $font_type_options,
      '#default_value' => theme_get_setting('settings.' . $settings_type),
    );
    $form['fonts']['font_settings']['headings'][$settings_container]['settings_' . $setting] = array(
      '#type' => 'select',
      '#title' => t('Font'),
      '#default_value' => theme_get_setting('settings.' . $setting),
      '#options' => str_replace("'", "", font_list('wsf', $element)),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => ''))),
    );

    // Google web fonts
    $form['fonts']['font_settings']['headings'][$settings_container]['settings_' . $settings_gwf] = array(
      '#type' => 'textfield',
      '#title' => t('Google Font Name'),
      '#default_value' => filter_xss_admin(theme_get_setting('settings.' . $settings_gwf)),
      '#description' => $google_font_description,
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $headings_styles_setting = $settings_gwf . '_add_styles';
    $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_styles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Styles'),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $headings_styles_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Styles'),
      '#default_value' => theme_get_setting('settings.' . $headings_styles_setting),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    foreach ($google_styles as $style_key => $style_value) {
      $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $headings_styles_setting . '_' . $style_key] = array(
        '#type' => 'checkbox',
        '#title' => t($style_value),
        '#default_value' => theme_get_setting('settings.' . $headings_styles_setting . '_' . $style_key),
        '#states' => array('visible' => array(":input[name=settings_$headings_styles_setting]" => array('checked' => TRUE))),
      );
    }
    $headings_charsets_setting = $settings_gwf . '_add_charsets';
    $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_charsets'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Character Sets'),
      '#states' => array('visible' => array('select[name=' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $headings_charsets_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Character Sets'),
      '#default_value' => theme_get_setting('settings.' . $headings_charsets_setting),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    foreach ($google_charsets as $charset_key => $charset_value) {
      $form['fonts']['font_settings']['headings'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $headings_charsets_setting . '_' . $charset_key] = array(
        '#type' => 'checkbox',
        '#title' => t($charset_value),
        '#default_value' => theme_get_setting('settings.' . $headings_charsets_setting . '_' . $charset_key),
        '#states' => array('visible' => array(":input[name=settings_$headings_charsets_setting]" => array('checked' => TRUE))),
      );
    }

    // Custom font stacks
    $form['fonts']['font_settings']['headings'][$settings_container]['settings_' . $settings_cfs] = array(
      '#type' => 'textfield',
      '#title' => t('Font'),
      '#default_value' => filter_xss_admin(theme_get_setting('settings.' . $settings_cfs)),
      '#description' => $custom_stack_description,
      '#states' => array(
        'visible' => array('select[name=' . $settings_type . ']' => array('value' => 'cfs')),
        'required' => array('select[name=' . $settings_type . ']' => array('value' => 'cfs')),
      ),
    );

    // Font your face
    if ($font_your_face_enabled == TRUE) {
      $form['fonts']['font_settings']['headings'][$settings_container]['settings_' . $settings_yfy] = array(
        '#type' => 'select',
        '#title' => t('Font'),
        '#default_value' => theme_get_setting('settings.' . $settings_yfy),
        '#options' => font_list('fyf', $element),
        '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'fyf'))),
      );
    }
  }
}

// Size for in-content headings
$form['fonts']['font_settings']['headings']['sizes'] = array(
  '#type' => 'fieldset',
  '#title' => t('Size'),
  '#attributes' => array('class' => array('font-element-wrapper'))
);

foreach ($form_elements as $key => $value) {

  $form_type = $key;
  $font_element_subtype = $value['subtype'];

  // we have to handle subgroups seperatly
  if ($font_element_subtype === 'heading_level') {

    // e.g. "bf" (base-font), this is used to set array keys
    $element  = $value['element'];

    // the theme setting used to retrieve the font values, e.g. "site_name_font"
    $setting  = $value['setting'];

    // Set easy reusable variables
    $settings_type = $setting . '_type';
    $settings_size = $setting . '_size';

    $form['fonts']['font_settings']['headings']['sizes']['settings_' . $settings_size] = array(
      '#type' => 'select',
      '#title' => t($element), // element as title is fine in this context
      '#options' => $font_sizes_em,
      '#default_value' => theme_get_setting('settings.' . $settings_size),
      '#attributes' => array('class' => array('font-size-wrapper')),
     );
  }
}
*/


// MENUS
/*
$form['fonts']['font_settings']['menus'] = array(
  '#type' => 'details',
  '#title' => t('Menus'),
  '#collapsible' => TRUE,
  '#collapsed' => TRUE,
);

foreach ($form_elements as $key => $value) {

  $form_type = $key;
  $font_element_type = $value['type'];

  if ($font_element_type === 'menu') {

    // e.g. "bf" (base-font), this is used to set array keys
    $element  = $value['element'];

    // the theme setting used to retrieve the font values, e.g. "site_name_font"
    $setting  = $value['setting'];

    // a nicer string for fielset classes
    $settings_container = str_replace('_', '-', $setting) . '-selector';

    // use the key for titles, it doesnt have "font" in it
    $title = str_replace('_', ' ', drupal_ucfirst($form_type));

    // Set easy reusable variables
    $settings_type = $setting . '_type';
    $settings_font = $setting . '_font';
    $settings_gwf  = $setting . '_gwf';
    $settings_cfs  = $setting . '_cfs';
    $settings_yfy  = $setting . '_fyf';
    $settings_size = $setting . '_size';

    $form['fonts']['font_settings']['menus'][$settings_container] = array(
      '#type' => 'fieldset',
      '#title' => t($title),
    );
    $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $settings_type] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#options' => $font_type_options,
      '#default_value' => theme_get_setting('settings.' . $settings_type),
    );
    $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $setting] = array(
      '#type' => 'select',
      '#title' => t('Font'),
      '#default_value' => theme_get_setting('settings.' . $setting),
      '#options' => str_replace("'", "", font_list('wsf', $element)),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => ''))),
    );

    // Google web fonts
    $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $settings_gwf] = array(
      '#type' => 'textfield',
      '#title' => t('Google Font Name'),
      '#default_value' => filter_xss_admin(theme_get_setting('settings.' . $settings_gwf)),
      '#description' => $google_font_description,
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $menus_styles_setting = $settings_gwf . '_add_styles';
    $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_styles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Styles'),
      '#states' => array('visible' => array('select[name=' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $menus_styles_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Styles'),
      '#default_value' => theme_get_setting('settings.' . $menus_styles_setting),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    foreach ($google_styles as $style_key => $style_value) {
      $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_styles']['settings_' . $menus_styles_setting . '_' . $style_key] = array(
        '#type' => 'checkbox',
        '#title' => t($style_value),
        '#default_value' => theme_get_setting('settings.' . $menus_styles_setting . '_' . $style_key),
        '#states' => array('visible' => array(":input[name=settings_$menus_styles_setting]" => array('checked' => TRUE))),
      );
    }
    $menus_charsets_setting = $settings_gwf . '_add_charsets';
    $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_charsets'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add Google Font Character Sets'),
      '#states' => array('visible' => array('select[name=' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $menus_charsets_setting] = array(
      '#type' => 'checkbox',
      '#title' => t('Character Sets'),
      '#default_value' => theme_get_setting('settings.' . $menus_charsets_setting),
      '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'gwf'))),
    );
    foreach ($google_charsets as $charset_key => $charset_value) {
      $form['fonts']['font_settings']['menus'][$settings_container][$settings_container . '_gwf_charsets']['settings_' . $menus_charsets_setting . '_' . $charset_key] = array(
        '#type' => 'checkbox',
        '#title' => t($charset_value),
        '#default_value' => theme_get_setting('settings.' . $menus_charsets_setting . '_' . $charset_key),
        '#states' => array('visible' => array(":input[name=settings_$menus_charsets_setting]" => array('checked' => TRUE))),
      );
    }

    // Custom font stacks
    $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $settings_cfs] = array(
      '#type' => 'textfield',
      '#title' => t('Font'),
      '#default_value' => filter_xss_admin(theme_get_setting('settings.' . $settings_cfs)),
      '#description' => $custom_stack_description,
      '#states' => array(
        'visible' => array('select[name=' . $settings_type . ']' => array('value' => 'cfs')),
        'required' => array('select[name=' . $settings_type . ']' => array('value' => 'cfs')),
      ),
    );

    // Font your face
    if ($font_your_face_enabled == TRUE) {
      $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $settings_yfy] = array(
        '#type' => 'select',
        '#title' => t('Font'),
        '#default_value' => theme_get_setting('settings.' . $settings_yfy),
        '#options' => font_list('fyf', $element),
        '#states' => array('visible' => array('select[name=settings_' . $settings_type . ']' => array('value' => 'fyf'))),
      );
    }

    // Size
    $form['fonts']['font_settings']['menus'][$settings_container]['settings_' . $settings_size] = array(
      '#type' => 'select',
      '#title' => t('Size'),
      '#options' => $font_sizes_em,
      '#default_value' => theme_get_setting('settings.' . $settings_size),
      '#attributes' => array('class' => array('font-size-wrapper')),
    );
  }
}
*/