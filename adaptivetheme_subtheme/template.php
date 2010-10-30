<?php
// $Id$

/**
 * Add the Style Schemes if enabled.
 * NOTE: You must make changes in your subthemes theme-settings.php file
 * also to enable Style Schemes.
 */

/* -- Delete this line if you want to enable Style Schemes

// DONT TOUCH THIS FUNCTION!
function get_at_styles() {
  $scheme = theme_get_setting('style_schemes');
  if (!$scheme) {
    $scheme = 'style-default.css';
  }
  if (isset($_COOKIE["atstyles"])) {
    $scheme = $_COOKIE["atstyles"];
  }
  return $scheme;
}

// ONLY MODIFY THIS - change 'pixture_reloaded' to your theme name:
if (theme_get_setting('style_enable_schemes') == 'on') {
  drupal_add_css(drupal_get_path('theme', 'pixture_reloaded') .'/css/schemes/'. get_at_styles(), 'theme');
}

// */


/**
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function to match your subthemes name,
 *    e.g. if you name your theme "themeName" then the function
 *    name will be "themeName_preprocess_hook".
 * 2. Uncomment the required function to use.
 */

/**
 * Override or insert variables into all templates.
 */
/* -- Delete this line if you want to use these functions
function pixture_reloaded_preprocess(&$vars, $hook) {
}
function pixture_reloaded_process(&$vars, $hook) {
}
// */

/**
 * Override or insert variables into the html templates.
 */

function pixture_reloaded_preprocess_html(&$vars) {
  // Uncomment the folowing line to add a conditional stylesheet for IE 7 or less.
  // drupal_add_css(path_to_theme() . '/css/ie/ie-lte-7.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));


}
function pixture_reloaded_process_html(&$vars) {
}
// */

/**
 * Override or insert variables into the page templates.
 */
/* -- Delete this line if you want to use these functions
function pixture_reloaded_preprocess_page(&$vars) {
}
function pixture_reloaded_process_page(&$vars) {
}
// */

/**
 * Override or insert variables into the node templates.
 */
/* -- Delete this line if you want to use these functions
function pixture_reloaded_preprocess_node(&$vars) {
}
function pixture_reloaded_process_node(&$vars) {
}
// */

/**
 * Override or insert variables into the comment templates.
 */
/* -- Delete this line if you want to use these functions
function pixture_reloaded_preprocess_comment(&$vars) {
}
function pixture_reloaded_process_comment(&$vars) {
}
// */

/**
 * Override or insert variables into the block templates.
 */
/* -- Delete this line if you want to use these functions
function pixture_reloaded_preprocess_block(&$vars) {
}
function pixture_reloaded_process_block(&$vars) {
}
// */
