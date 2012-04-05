<?php
/**
 * Provides frequently used functions that don't fit logically anywhere else.
 */
global $theme_key;
$theme_name = $theme_key;
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');


/**
 * Return the info file data for a particular theme, usually the active theme.
 * Simple wrapper function for list_themes().
 *
 * @param $theme_name
 */
function at_get_info($theme_name) {
  $info = drupal_static(__FUNCTION__, array());
  if (empty($info)) {
    $lt = list_themes();
    foreach ($lt as $key => $value) {
      if ($theme_name == $key) {
        $info = $lt[$theme_name]->info;
      }
    }
  }

  return $info;
}

/**
 * Load Sub-theme Responsive Stylesheets.
 * Wrapper function for drupal_add_css() that takes advantage of Drupals's
 * CSS aggregation logic to trick Drupal into always loading responsive
 * stylesheets in link elements.
 *
 * This is almost always called from adaptivetheme_preprocess_html(), only in
 * a rare instance might this be called from a sub-theme (to load additional
 * responsive stylesheets).
 *
 * @param $filepath, path to the CSS file.
 * @param $media_query, the media query from theme settings.
 * @param $theme_name, usually the active theme.
 * @param $weight, optional.
 */
function load_subtheme_responsive_styles($filepath, $media_query, $theme_name, $weight = 0) {
  if (file_exists($filepath)) {
    drupal_add_css($filepath, array(
      'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
      'group' => CSS_THEME,
      'media' => $media_query,
      'every_page' => TRUE,
      'weight' => $weight,
      )
    );
  }
}

/**
 * Load Sub-theme IE Stylesheets.
 * Wrapper function for drupal_add_css() that makes use the 'browser' option
 * to load stylesheets for Internet Explorer.
 *
 * @param $filepath, path to the file.
 * @param $ie_style, an arry containing the media attribute value and the IE
 * conditional comment.
 * @param $weight, optional.
 */
function load_subtheme_conditional_styles($filepath, $ie_style, $weight = 0) {
  if (file_exists($filepath)) {
    drupal_add_css($filepath, array(
      'group' => CSS_THEME,
      'browsers' => array(
        'IE' => $ie_style['condition'],
        '!IE' => FALSE,
        ),
      'media' => $ie_style['media'],
      'preprocess' => TRUE,
      'weight' => $weight,
      )
    );
  }
}

/**
 * Return a themed script.
 * Since Drupal 7 does not (yet) support the 'browser' option in drupal_add_js()
 * Adaptivetheme provides a way to load scripts inside conditional comments.
 * This function wraps a file in script elements and returns a string.
 *
 * @param $filepath, path to the file.
 */
function at_theme_script($filepath) {
  $script = '';

  // We need the default query string for cache control finger printing
  $query_string = variable_get('css_js_query_string', '0');

  if (file_exists($filepath)) {
    $file = file_create_url($filepath);
    $script = '<script src="' . $file . '?' . $query_string . '"></script>';
  }

  return $script;
}

/**
 * Return themed scripts in Conditional Comments.
 * Since Drupal 7 does not (yet) support the 'browser' option in drupal_add_js()
 * Adaptivetheme provides a way to load scripts inside conditional comments.
 * This function will return a string for printing into a template, its
 * akin to a real theme_function but its not.
 *
 * @param $ie_scripts, an array of themed scripts.
 */
function at_theme_conditional_scripts($ie_scripts) {
  $themed_scripts = drupal_static(__FUNCTION__, array());
  if (empty($themed_scripts)) {
    $cc_scripts = array();

    foreach ($ie_scripts as $conditional_comment => $conditional_scripts) {
      $cc_scripts[] = '<!--[if ' . $conditional_comment . ']>' . "\n"  . $conditional_scripts . "\n" . '<![endif]-->' . "\n";
    }
    $themed_scripts = implode("\n", $cc_scripts);
  }

  return $themed_scripts;
}

/**
 * Polyfills.
 * This function does two seperate operations. First it attaches a condition
 * to each Polyfill which can be either an IE conditional comment or 'all'.
 * Polyfills with 'all' are loaded immediatly via drupal_add_js(), those with
 * an IE CC are returned for further processing. This function is hard coded
 * to support only those scripts supplied by the core theme, if you need to load
 * a script for IE use the info file feature.
 *
 * @param $polly
 * @param $theme_name
 */
function polly_wants_a_cracker($polly, $theme_name) {
  global $path_to_at_core;

  $baked_crackers = drupal_static(__FUNCTION__, array());
  if (empty($baked_crackers)) {
    if (in_array(1, $polly)) {

      $crackers = array();

      // Respond.js
      if ($polly['load_respondjs'] === 1 && theme_get_setting('disable_responsive_styles', $theme_name) === 0) {
        $crackers['ie']['lt IE 9'][] = 'js/respond.js';
      }
      // HTML5 Shiv
      if ($polly['load_html5js'] === 1) {
        $crackers['ie']['lt IE 9'][] = 'js/html5.js';
      }
      // Boxsizing.htc
      if ($polly['load_boxsizinghtc'] === 1) {
        $crackers['ie']['lt IE 9'][] = 'js/boxsizing.htc';
      }
      // Scalefix.js
      if ($polly['load_scalefixjs'] === 1) {
        $crackers['all'][] = 'js/scalefix.js';
      }

      // Load Polyfills
      if (!empty($crackers)) {

        // We need the default query string for cache control finger printing
        $query_string = variable_get('css_js_query_string', '0');

        // "all" - no conditional comment needed, use drupal_add_js()
         if (isset($crackers['all'])) {
          foreach ($crackers['all'] as $script) {
            drupal_add_js($path_to_at_core . '/' . $script,
              array(
                'type' => 'file',
                'scope' => 'header',
                'group' => JS_THEME,
                'preprocess' => TRUE,
                'cache' => TRUE,
              )
            );
          }
        }
        if ($crackers['ie']) {
          $baked_crackers = $crackers['ie'];
        }
      }
    }
  }

  return $baked_crackers;
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
function load_google_fonts($theme_name) {
  global $path_to_at_core;

  $google_fonts = drupal_static(__FUNCTION__, array());
  if (empty($google_fonts)) {

    // Include the font lists, we need these for the font names
    include_once($path_to_at_core . '/inc/font.lists.inc');

    $used_fonts = array();
    $font_elements = font_elements();
    foreach ($font_elements as $key => $value) {
      $setting = $value['setting'];
      $element = $value['element'];
      // Check if any elements are set to use a google font
      if (theme_get_setting($setting . '_type', $theme_name) === 'gwf') {
        // Get the theme setting
        $setting = theme_get_setting($setting . '_gwf', $theme_name);
        // strip the element prefix
        $font = str_replace($element . '-', '', $setting);
        // replace hyphens with spaces, now we have a lower case font name
        $used_fonts[] = str_replace('-', ' ', $font);
      }
    }
    if (!empty($used_fonts)) {
      // Get the full list of font names, Google fonts are case sensitive, and
      // you cannot just uppercase/ucfirst, font names are not consistant
      $google_fonts = google_font_names();
      // Populate a variable with lower cased font names
      $google_fonts_lower_case = unserialize(strtolower(serialize($google_fonts)));
      // Now key the array with our lower case font names, and set that to a variable
      $google_fonts_lower_case_keys = array_combine($google_fonts_lower_case, $google_fonts);
      $used_google_fonts = array();
      // Now we can find a match, comparing our lowercase $used_fonts with the lower case keys
      foreach ($google_fonts_lower_case_keys as $k => $v) {
        if (in_array($k, $used_fonts)) {
          $used_google_fonts[] = $v; // at last, we have the right font name/s
        }
      }
      // The same font can be used multiple times, but we only need to load it once
      $array = array_unique($used_google_fonts);
      // Implode the array, seperate font names with a pipe
      $google_fonts = trim(implode('|', $array));
    }
  }

  return $google_fonts;
}

/**
 * Process the color info and return the active color scheme. We use this to
 * load a body class for the active color scheme, very useful for CSS tweaks
 * required for a particular scheme. This is not enabled by default, you have to
 * call it in template_preprocess_html().
 *
 * @param $theme_name
 */
function get_color_scheme_name($theme_name) {
  $scheme_name = drupal_static(__FUNCTION__, array());
  if (empty($scheme_name)) {
    if (module_exists('color')) {
      $info = color_get_info($theme_name);
      $info['schemes'][''] = array('title' => t('Custom'), 'colors' => array());
      $schemes = array();
      foreach ($info['schemes'] as $key => $scheme) {
        $schemes[$key] = $scheme['colors'];
      }
      $current_scheme = variable_get('color_' . $theme_name . '_palette', array());
      foreach ($schemes as $key => $scheme) {
        if ($current_scheme == $scheme) {
          $scheme_name = $key;
          break;
        }
      }
      if (empty($scheme_name)) {
        if (empty($current_scheme)) {
          $scheme_name = 'default';
        }
        else {
          $scheme_name = 'custom';
        }
      }
      return $scheme_name;
    }
  }
}

/**
 * Add classes for platforms and browsers.
 * This is very simple and can never replace something like WURFL or Mobile
 * Smart, in the future I intend to build a module to replace this.
 *
 * @param null $ua
 */
function css_browser_selector($ua = NULL) {
  if ($ua == NULL && !isset($_SERVER['HTTP_USER_AGENT'])) {
    return;
  }
  $ua = ($ua) ? strtolower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);

  $g = 'gecko';
  $w = 'webkit';
  $s = 'safari';
  $o = 'opera';

  $b = array();
  if(!preg_match('/opera|webtv/i', $ua) && preg_match('/msie\s(\d)/', $ua, $array)) {
    $b[] = 'ie ie' . $array[1];
  }
  elseif (strstr($ua, 'firefox')) {
    $b[] = $g . ' firefox';
  }
  elseif (strstr($ua, 'gecko/')) {
    $b[] = $g;
  }
  elseif (preg_match('/opera(\s|\/)(\d+)/', $ua, $array)) {
    $b[] = $o;
  }
  elseif (strstr($ua, 'konqueror')) {
    $b[] = 'konqueror';
  }
  elseif (strstr($ua, 'chrome')) {
    $b[] = $w . ' chrome';
  }
  elseif (strstr($ua, 'applewebkit/')) {
    $b[] = (preg_match('/version\/(\d+)/i', $ua, $array)) ? $w . ' ' . $s . ' ' . $s . $array[1] : $w . ' ' . $s;
  }
  elseif (strstr($ua, 'mozilla/')) {
    $b[] = $g;
  }
  if (strstr($ua, 'j2me')) {
    $b[] = 'mobile';
  }
  elseif (strstr($ua, 'iphone')) {
    $b[] = 'iphone';
  }
  elseif (strstr($ua, 'ipod')) {
    $b[] = 'ipod';
  }
  elseif (strstr($ua, 'ipad')) {
    $b[] = 'ipad';
  }
  elseif (strstr($ua, 'android')) {
    $b[] = 'android';
  }
  elseif (strstr($ua, 'googletv')) {
    $b[] = 'googletv';
  }
  elseif (strstr($ua, 'xoom')) {
    $b[] = 'xoom';
  }
  elseif (strstr($ua, 'nuvifone')) {
    $b[] = 'nuvifone';
  }
  elseif (strstr($ua, 'symbian')) {
    $b[] = 'symbian';
  }
  elseif (strstr($ua, 'blackberry')) {
    $b[] = 'blackberry';
  }
  elseif (strstr($ua, 'windows phone os 7')) {
    $b[] = 'windows-phone-7';
  }
  elseif (strstr($ua, 'windows ce')) {
    $b[] = 'windows-ce';
  }
  elseif (strstr($ua, 'iemobile')) {
    $b[] = 'iemobile';
  }
  elseif (strstr($ua, 'palm')) {
    $b[] = 'palm';
  }
  elseif (strstr($ua, 'webos')) {
    $b[] = 'webos';
  }
  elseif (strstr($ua, 'kindle')) {
    $b[] = 'kindle';
  }
  elseif (strstr($ua, 'mac')) {
    $b[] = 'mac';
  }
  elseif (strstr($ua, 'darwin')) {
    $b[] = 'mac';
  }
  elseif (strstr($ua, 'webtv')) {
    $b[] = 'webtv';
  }
  elseif (strstr($ua, 'win')) {
    $b[] = 'win';
  }
  elseif (strstr($ua, 'freebsd')) {
    $b[] = 'freebsd';
  }
  elseif (strstr($ua, 'x11') || strstr($ua, 'linux')) {
    $b[] = 'linux';
  }
  return join(' ', $b);
}

// Might use one day, crufty and can live here for now
function minify_css($css) {
	$css = preg_replace( '#\s+#', ' ', $css );
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = str_replace( '; ', ';', $css );
	$css = str_replace( ': ', ':', $css );
	$css = str_replace( ' {', '{', $css );
	$css = str_replace( '{ ', '{', $css );
	$css = str_replace( ', ', ',', $css );
	$css = str_replace( '} ', '}', $css );
	$css = str_replace( ';}', '}', $css );
	return trim( $css );
}

//
// Functions to maintain 7.x-2.x backward compatibility, DO NOT use these for 7.x-3.x or greater.
//

// Helper function for theming main and secondary variables, 7.x-2.x backward compatibility
function _theme_menu_variables($menu, $type) {
  $output = '<div id="' . $type . '-menu-bar" class="nav"><nav class="clearfix">' . $menu . '</nav></div>';
  return $output;
}

// Load sub-theme media queries, 7.x-2.x backward compatibility
function load_subtheme_media_queries($files, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  foreach ($files as $file) {
    $filepath = $path_to_theme . '/css/' . $file;
    if (file_exists($filepath)) {
      drupal_add_css($filepath, array(
        'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
        'group' => CSS_THEME,
        'media' => 'all',
        'every_page' => TRUE,
        'weight' => 99,
        )
      );
    }
  }
}

// Load subtheme IE stylesheets, 7.x-2.x backward compatibility
function load_subtheme_ie_styles($files, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  foreach ($files as $key => $value) {
    $filepath = $path_to_theme . '/css/' . $value;
    if (file_exists($filepath)) {
      drupal_add_css($filepath, array(
        'group' => CSS_THEME,
        'browsers' => array(
          'IE' => $key,
          '!IE' => FALSE,
          ),
        'media' => 'screen',
        'preprocess' => TRUE,
        )
      );
    }
  }
}
