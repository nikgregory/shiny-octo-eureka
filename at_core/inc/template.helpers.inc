<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 *
 * Provides frequently used functions etc that don't fit logically anywhere else.
 */

// AT leverages info file data if it exists to load various files and perform certain operations.
$lt = list_themes();
foreach ($lt as $key => $value) {
  if ($theme_name == $key) {
    $info = $lt[$theme_name]->info;

    // Scan for the existence of a "release" key and conditionally load google.web.fonts.inc
    if (!array_key_exists('release', $info) || ($info['release'] == '7.x-2.x')) {
      include_once($path_to_at_core . '/inc/google.web.fonts.inc');
    }
    
    // Scan for an "ie_stylesheets" key, and if it exists load the IE stylesheets
    if (array_key_exists('ie_stylesheets', $info)) {
      foreach ($info['ie_stylesheets'] as $media => $stylesheets) {
        foreach ($stylesheets as $condition => $path) {
          $ie_style['media'] = $media;
          $ie_style['condition'] = $condition;
          $ie_style['path'] = $path;
        }
        load_subtheme_conditional_styles($ie_style, $theme_name);
      }
    }
  }
}

// Helper function for theming main and secondary variables.
function _theme_menu_variables($menu, $type) {
  $output = '<div id="' . $type . '-menu-bar" class="nav clearfix"><nav id="' . $type . '-menu" class="menu-wrapper ' . $type  . '-menu-wrapper clearfix" role="navigation">' . $menu . '</nav></div>';
  return $output;
}

/**
* Load sub-theme responsive stylesheets in link elements
*
* load_subtheme_responsive_styles() takes 3 parameters:
* - $css_file - the name of the css file.
* - $media_query - the media query from theme settings to use for the media attribute.
* - $theme_name - to generate the path-to-theme for drupal_add_css().
*
* This is almost always called from adaptivetheme_preprocess_html(), only in a rare instance
* might this be called from a sub-theme (to load additional responsive stylesheets).
*/
function load_subtheme_responsive_styles($css_file, $media_query, $theme_name) {
  $path_to_theme = &drupal_static(__FUNCTION__);
  $path_to_theme = drupal_get_path('theme', $theme_name);
  $filepath = $path_to_theme . '/css/' . $css_file;
  if (file_exists($filepath)) {
    drupal_add_css($filepath, array(
      'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
      'group' => CSS_THEME,
      'media' => $media_query,
      'every_page' => TRUE,
      'weight' => 99,
      )
    );
  }
}

/**
* Load sub-theme IE stylesheets
*
* load_subtheme_conditional_styles() takes 2 parameters:
* - $ie_style - an arry containing the media, condition and file path
* - $theme_name - to generate the path-to-theme for drupal_add_css().
*/
function load_subtheme_conditional_styles($ie_style, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  $filepath = $path_to_theme . '/' . $ie_style['path'];
  drupal_add_css($filepath, array(
    'group' => CSS_THEME,
    'browsers' => array(
      'IE' => $ie_style['condition'],
      '!IE' => FALSE,
      ),
    'media' => $ie_style['media'],
    'preprocess' => TRUE,
    )
  );
}

// Load sub-theme media queries, 7.x-2.x backward compatibility
function load_subtheme_media_queries($files, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  foreach ($files as $file) {
    $filepath = $path_to_theme . '/css/' . $file;
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

// Load subtheme IE stylesheets, 7.x-2.x backward compatibility 
// $files is a keyed array of files, keyed by conditional comment, $theme_name is the theme name.
function load_subtheme_ie_styles($files, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  foreach ($files as $key => $value) {
    $filepath = $path_to_theme . '/css/' . $value;
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

// Process the color info and return the active color scheme, we use this
// to load a body class for the active color scheme, very useful for CSS
// tweaks required for a particular scheme. This is not enabled by default,
// you have to call it in template_preprocess_html()
function get_color_scheme_name($theme_name) {
  $scheme_name = '';
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

/**
 * Add classes for platforms and browsers.
 * This is very simple and can never replace something like WURFL or Mobile Smart, in the future
 * I intend to build a module to replace this. Warning, this is not overly tested...
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