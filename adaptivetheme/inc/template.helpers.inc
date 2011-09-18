<?php

/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */

// Helper function for theming main and secondary variables.
function _theme_menu_variables($menu, $type) {
  $output = '<div id="' . $type . '-menu-bar" class="nav"><nav class="clearfix">' . $menu . '</nav></div>';
  return $output;
}

// Load sub-theme media queries, $files is an array of css file names, $themename is the theme name.
function load_subtheme_media_queries($files, $theme_name) {
  $path_to_theme = drupal_get_path('theme', $theme_name);
  foreach ($files as $file) {
    $filepath = $path_to_theme . '/css/' . $file;
    drupal_add_css($filepath, array(
      'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
      'group' => CSS_THEME,
      'media' => 'screen',
      'every_page' => TRUE,
      )
    );
  }
}

// Load IE stylesheets, $files is a keyed array of files, keyed by conditional comment, $theme_name is the theme name.
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
      'preprocess' => FALSE,
      )
    );
  }
}

// Process the color info and return the active color scheme
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
 */
function css_browser_selector($ua = null) {
  $ua = ($ua) ? strtolower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);
  $g = 'gecko';
  $w = 'webkit';
  $s = 'safari';
  $b = array();
  // browsers
  if(!preg_match('/opera|webtv/i', $ua) && preg_match('/msie\s(\d)/', $ua, $array)) {
    $b[] = 'ie ie' . $array[1];
  } else if(strstr($ua, 'firefox/2')) {
    $b[] = $g . ' ff2';
  } else if(strstr($ua, 'firefox/3.5')) {
    $b[] = $g . ' ff3 ff3_5';
  } else if(strstr($ua, 'firefox/3')) {
    $b[] = $g . ' ff3';
  } else if(strstr($ua, 'gecko/')) {
    $b[] = $g;
  } else if(preg_match('/opera(\s|\/)(\d+)/', $ua, $array)) {
    $b[] = 'opera opera' . $array[2];
  } else if(strstr($ua, 'konqueror')) {
    $b[] = 'konqueror';
  } else if(strstr($ua, 'chrome')) {
    $b[] = $w . ' ' . $s . ' chrome';
  } else if(strstr($ua, 'iron')) {
    $b[] = $w . ' ' . $s . ' iron';
  } else if(strstr($ua, 'applewebkit/')) {
    $b[] = (preg_match('/version\/(\d+)/i', $ua, $array)) ? $w . ' ' . $s . ' ' . $s . $array[1] : $w . ' ' . $s;
  } else if(strstr($ua, 'mozilla/')) {
    $b[] = $g;
  }
  // platform
  if(strstr($ua, 'j2me')) {
    $b[] = 'mobile';
  } else if(strstr($ua, 'iphone')) {
    $b[] = 'iphone';
  } else if(strstr($ua, 'ipod')) {
    $b[] = 'ipod';
  } else if(strstr($ua, 'mac')) {
    $b[] = 'mac';
  } else if(strstr($ua, 'darwin')) {
    $b[] = 'mac';
  } else if(strstr($ua, 'webtv')) {
    $b[] = 'webtv';
  } else if(strstr($ua, 'win')) {
    $b[] = 'win';
  } else if(strstr($ua, 'freebsd')) {
    $b[] = 'freebsd';
  } else if(strstr($ua, 'x11') || strstr($ua, 'linux')) {
    $b[] = 'linux';
  }
  return join(' ', $b);
}
