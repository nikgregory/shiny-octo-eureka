<?php

/**
 * Build font family CSS.
 *
 * @param $element
 * @param $selector
 * @param $font_values
 */
function at_build_font_families($element, $selector, $font_values) {
  $output = '';

  // Format values as valid CSS
  $font_styles = array();
  if (!empty($font_values)) {
    if (!empty($font_values['font_size'])) {
      $font_styles[] = 'font-size:' . $font_values['font_size'] . 'px;';
      $font_styles[] = 'font-size:'. $font_values['font_size'] / 16 . 'rem;';
    }
    $font_styles[] = $font_values['font_style']  ? 'font-style:'  . $font_values['font_style']  . ';' : '';
    $font_styles[] = $font_values['font_weight'] ? 'font-weight:' . $font_values['font_weight'] . ';' : '';
    $font_styles[] = $font_values['font_family'] ? 'font-family:' . $font_values['font_family']       : '';

  }
  $font_styles = implode('', $font_styles);

  $css = array();
  switch ($element) {
    case $element:
      if ($font_styles) {
        $css[] = $selector . '{' . $font_styles . '}';
      }
    break;
  }

  $output = implode("\n", $css);
  return $output;
}

/**
 * @file
 * Font Elements Config Array.
 *
 * The fonts array is used by the fonts sub-system to generate forms,
 * build stylesheets and load font styles.
 */
function font_elements() {
  $elements_array = &drupal_static(__FUNCTION__, array());
  if (empty($elements_array)) {
    $elements_array = array(
      'base' => array(
        'type' => 'default',
        'subtype' => 'global',
        'element' => 'bf',
        'selector' => 'html,.ui-widget', // add the jQuery UI widget selector
        'setting' => 'base_font',
      ),
      'site_name' => array(
        'type' => 'title',
        'subtype' => 'branding',
        'element' => 'sn',
        'selector' => '.site__name',
        'setting' => 'site_name_font',
      ),
      'site_slogan' => array(
        'type' => 'title',
        'subtype' => 'branding',
        'element' => 'ss',
        'selector' => '.site__slogan',
        'setting' => 'site_slogan_font',
      ),
      'page_title' => array(
        'type' => 'title',
        'subtype' => 'global',
        'element' => 'pt',
        'selector' => '.view-mode--full .node__title,.page__title',
        'setting' => 'page_title_font',
      ),
      'node_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'nt',
        'selector' => '.node__title',
        'setting' => 'node_title_font',
      ),
      'comment_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'ct',
        'selector' => '.comment__title',
        'setting' => 'comment_title_font',
      ),
      'block_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'bt',
        'selector' => '.block__title',
        'setting' => 'block_title_font',
      ),
      'content_headings_h1h4' => array(
        'type' => 'heading',
        'subtype' => 'heading_group',
        'element' => 'h1h4',
        'selector' => 'h1,h2,h3,h4',
        'setting' => 'content_headings_h1h4_font',
      ),
      'content_headings_h5h6' => array(
        'type' => 'heading',
        'subtype' => 'heading_group',
        'element' => 'h5h6',
        'selector' => 'h5,h6',
        'setting' => 'content_headings_h5h6_font',
      ),
      'content_headings_h1' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h1',
        'selector' => 'h1',
        'setting' => 'h1_font',
      ),
      'content_headings_h2' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h2',
        'selector' => 'h2',
        'setting' => 'h2_font',
      ),
      'content_headings_h3' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h3',
        'selector' => 'h3',
        'setting' => 'h3_font',
      ),
      'content_headings_h4' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h4',
        'selector' => 'h4',
        'setting' => 'h4_font',
      ),
      'content_headings_h5' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h5',
        'selector' => 'h5',
        'setting' => 'h5_font',
      ),
      'content_headings_h6' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h6',
        'selector' => 'h6',
        'setting' => 'h6_font',
      ),
      'main_menu' => array(
        'type' => 'menu',
        'subtype' => 'menu_variable',
        'element' => 'mm',
        'selector' => '#menu-bar .menu-wrapper,#primary-menu-bar .menu-wrapper',
        'setting' => 'main_menu_font',
      ),
      'secondary_menu' => array(
        'type' => 'menu',
        'subtype' => 'menu_variable',
        'element' => 'sm',
        'selector' => '#secondary-menu-bar .menu-wrapper,#menu-bar #block-system-user-menu',
        'setting' => 'secondary_menu_font',
      ),
      'block_menus' => array(
        'type' => 'menu',
        'subtype' => 'menu_blocks',
        'element' => 'bm',
        'selector' => 'nav.block .block-content',
        'setting' => 'block_menu_font',
      ),
      'selectors' => array(
        'type' => 'custom',
        'subtype' => '',
        'element' => 'css',
        'selector' => 'custom_css',
        'setting' => 'selectors_font',
      ),
    );
  }

  return $elements_array;
}

/**
 * Return lists of font names keyed by element.
 *
 * @param $type
 * @param $element
 */
function font_list($type, $element) {
  $font_list = array();

  // websafe font list
  if ($type === 'wsf') {
    $wsf_fonts = websafe_fonts_list($element);
    if (!empty($wsf_fonts)) {
      $font_list = $wsf_fonts;
    }
  }
  // Google font list
  if ($type === 'gwf') {
    $gwf_fonts = google_fonts_list($element);
    if (!empty($gwf_fonts)) {
      $font_list = $gwf_fonts;
    }
  }
  // @font-your-face font list
  if ($type === 'fyf') {
    $fyf_fonts = font_your_face_fonts_list($element);
    if (!empty($fyf_fonts)) {
      $font_list = $fyf_fonts;
    }
  }

  return array_unique($font_list);
}

/**
 * Websafe fonts list.
 * These stacks are my own and I spent years working with these, testing and
 * checking platform and browser support. The work very well accross platform.
 * I love all these fonts and its no accident that 6 of the 10 stacks start
 * with a Microsoft font. Whose says MS can't design a good font?
 *
 * Note, if you are going to *borrow* these and use them in your own theme, book,
 * etc then do me the courtesy of attribution; you know who you are.
 *
 * @param $element
 */
function websafe_fonts_list($element) {
  $fonts = &drupal_static(__FUNCTION__, array());
  if (empty($fonts)) {
    $fonts = array(
      "$element-sss" => t("'Trebuchet MS', 'Helvetica Neue', Arial, Helvetica, sans-serif"),
      "$element-ssl" => t("Verdana, Geneva, 'DejaVu Sans', Arial, Helvetica, sans-serif"),
      "$element-a"   => t("Arial, Helvetica, sans-serif"),
      "$element-cc"  => t("Calibri, Candara, Arial, Helvetica, sans-serif"),
      "$element-m"   => t("'Segoe UI', 'Myriad Pro', Myriad, Arial, Helvetica, sans-serif"),
      "$element-l"   => t("'Lucida Sans Unicode', 'Lucida Sans', 'Lucida Grande', Verdana, Geneva, sans-serif"),
      "$element-i"   => t("Impact, Haettenschweiler, 'Franklin Gothic Bold', Charcoal, 'Helvetica Inserat', 'Bitstream Vera Sans Bold', 'Arial Black', sans-serif"),
      "$element-ss"  => t("Garamond, Perpetua, 'Times New Roman', serif"),
      "$element-sl"  => t("Georgia, Baskerville, Palatino, 'Palatino Linotype', 'Book Antiqua', 'Times New Roman', serif"),
      "$element-ms"  => t("Consolas, Monaco, 'Courier New', Courier, monospace, sans-serif"),
    );
  }

  return $fonts;
}

/**
 * @font-your-face.
 * This requires the @font-your-face module to be enabled and at least one font
 * enabled and set to "add selector in theme CSS". Adaptivetheme will pull the
 * enabled fonts and allow them to be used for styling elements in the site via
 * each sub-themes appearance settings.
 *
 * @pararm $element
 */
function font_your_face_fonts_list($element) {
  $fonts = &drupal_static(__FUNCTION__, array());
  if (empty($fonts)) {
    $font_your_face_enabled = font_your_face_fonts_enabled();
    if ($font_your_face_enabled == TRUE) {
      $enabled_fonts = fontyourface_get_fonts('enabled = 1');
      if (!empty($enabled_fonts)) {
        foreach ($enabled_fonts as $font) {
          // <none> is "-- add selector in theme CSS --" in fontyourface settings
          if ($font->css_selector === '<none>') {
            $key = drupal_html_class("$element-$font->name");
            $value = $font->name;
            $fonts[$key] = $value;
          }
        }
      }
    }
  }

  return $fonts;
}

/**
 * Check if there are available fonts, return boolean.
 * There are a number of places in forms and other functions we need to know
 * if any font-your-face fonts are enabled and set to <none> and therefor
 * available for use in the theme settings.
 */
function font_your_face_fonts_enabled() {
  $fonts_enabled = FALSE;
  if (\Drupal::moduleHandler()->moduleExists('fontyourface')) {
    $enabled_fonts = fontyourface_get_fonts('enabled = 1');
    if (!empty($enabled_fonts)) {
      foreach ($enabled_fonts as $font) {
        if ($font->css_selector === '<none>') {
          $fonts[] = $font;
        }
      }
    }
    if (!empty($fonts)) {
      $fonts_enabled = TRUE;
    }
  }

  return $fonts_enabled;
}


/**
 * Google font charsets.
 */
function google_font_charsets() {
  $charset_array = &drupal_static(__FUNCTION__, array());
  if (empty($charset_array)) {
    $charset_array = array(
      'latin' => t('Latin'),
      'latin-ext' => t('Latin extended'),
      'greek' => t('Greek'),
      'greek-ext' => t('Greek extended'),
      'cyrillic' => t('Cyrillic'),
      'cyrillic-ext' => t('Cyrillic extended'),
      'khmer' => t('Khmer'),
      'vietnamese' => t('Vietnamese'),
    );
  }

  return $charset_array;
}

/**
 * Google font styles.
 */
function google_font_styles() {
  $styles_array = &drupal_static(__FUNCTION__, array());
  if (empty($styles_array)) {
    $styles_array = array(
      '300' => t('Book 300'),
      '300italic' => t('Book 300 Italic'),
      '400' => t('Normal 400'),
      '400italic' => t('Normal 400 Italic'),
      '700' => t('Bold 700'),
      '700italic' => t('Bold 700 Italic'),
    );
  }

  return $styles_array;
}

/**
 * Reusable style options.
 *
 * @param $style
 * @param $element
 */
function font_style_options($style) {
  $options = &drupal_static(__FUNCTION__, array());
  switch ($style) {
    case ('case'):
      $options = array(
        'none'       => t('Normal'),
        'uppercase'  => t('Upper case'),
        'lowercase'  => t('Lower case'),
        'capitalize' => t('Capitalize'),
        'small-caps' => t('Small caps'),
        'inherit'    => t('Inherit'),
      );
    break;
    case ('weight'):
      $options = array(
        '400' => t('Normal'),
        '700' => t('Bold'),
      );
    break;
    case ('alignment'):
      $options = array(
        'left'   => t('Left'),
        'right'  => t('Right'),
        'center' => t('Centered'),
      );
    break;
  }

  return $options;
}

/**
 * Google Font loader.
 * Adaptivetheme can load websafe fonts, Google fonts, custom font stacks and
 * integrate with @font-your-face module. All can be configured in the "Fonts"
 * theme settings. Here we only need to load Google webfonts. Websafe and custom
 * fonts are stored in a generated CSS file (in public files) and
 * @font-your-face module takes care of loading its own fonts.
 *
 * @param $theme_name
 */
function at_core_load_google_fonts($theme) {
  $google_font_string = &drupal_static(__FUNCTION__);
  if (empty($google_font_string)) {

    $used_fonts = array();
    $font_elements = font_elements();
    $charsets = google_font_charsets();
    $styles = google_font_styles();
    $used_styles = array();
    $used_charsets = array();

    foreach ($font_elements as $key => $value) {
      $setting = 'settings.' . $value['setting'];
      if (at_core_theme_get_setting($setting . '_type', $theme) == 'gwf') {
        $used_settings[] = $setting;
        $font_name = at_core_theme_get_setting($setting . '_gwf', $theme);
        $used_font = str_replace(' ', '+', $font_name);
        $used_fonts[] = $used_font;
      }
    }

    if (!empty($used_fonts)) {
      $used_fonts = array_unique($used_fonts);
      $google_fonts = implode('|', $used_fonts);

      foreach ($used_settings as $used_setting) {
        // Extended styles
        if (at_core_theme_get_setting($used_setting . '_add_styles', $theme) == 1) {
          foreach ($styles as $styles_key => $styles_value) {
            if (at_core_theme_get_setting($used_setting . '_add_styles_' . $styles_key, $theme)) {
              $used_styles[] = $styles_key;
            }
          }
        }
        // Extended charsets
        if (at_core_theme_get_setting($used_setting . '_add_charsets', $theme) == 1) {
          foreach ($charsets as $charset_key => $charset_value) {
            if (at_core_theme_get_setting($used_setting . '_add_charsets_' . $charset_key, $theme)) {
              $used_charsets[] = $charset_key;
            }
          }
        }
      }

      if (!empty($used_styles)) {
        $styles_array = array_unique($used_styles);
        $google_styles = ':' . implode(',', $styles_array);
      }
      else {
        $google_styles = '';
      }
      if (!empty($used_charsets)) {
        $charset_array = array_unique($used_charsets);
        $google_charsets = '&subset=' . implode(',', $charset_array);
      }
      else {
        $google_charsets = '';
      }

      // Build the final string
      $google_font_string = '//fonts.googleapis.com/css?family=' . $google_fonts . $google_styles . $google_charsets;
    }
    else {
      $google_font_string = '';
    }
  }

  return $google_font_string;
}
