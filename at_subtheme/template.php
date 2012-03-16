<?php
/**
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function and instance of "adaptivetheme_subtheme" to match
 *    your subthemes name, e.g. if your theme name is "footheme" then the function
 *    name will be "footheme_preprocess_hook". Tip - you can search/replace
 *    on "adaptivetheme_subtheme".
 * 2. Uncomment the required function to use.
 * 3. Read carefully, especially within adaptivetheme_subtheme_preprocess_html(), there
 *    are extra goodies you might want to leverage such as a very simple way of adding
 *    stylesheets for Internet Explorer and a browser detection script to add body classes.
 */

/**
 * Preprocess variables for the html template.
 */
function adaptivetheme_subtheme_preprocess_html(&$vars) {
  global $theme_key;
  
  /**
   * Load IE Stylesheets
   *
   * AT automates adding IE stylesheets, simply add to the array using
   * the conditional comment as the key and the stylesheet name as the value.
   * SEE: http://adaptivethemes.com/documentation/working-with-internet-explorer
   *
   * For example to add a stylesheet for IE8 only use:
   *  'IE 8' => 'ie-8.css',
   *
   * By default the sub-theme includes one IE specific stylesheet: lt-ie9.css
   * Your IE CSS files must be in the mytheme/css/ directory in your subtheme.
   */
  /* -- Delete this line to enable.
  $ie_files = array(
    '(lt IE 9)&(!IEMobile 7)' => 'lt-ie9.css',
  );
  load_subtheme_ie_styles($ie_files, $theme_key);
  // */

  // Add a class for the active theme name.
  /* -- Delete this line to enable.
  $vars['classes_array'][] = drupal_html_class($theme_key);
  // */

  // Browser/platform sniff - adds body classes such as ipad, webkit, chrome etc.
  /* -- Delete this line to enable.
  $vars['classes_array'][] = css_browser_selector();
  // */
}

/**
 * Process variables for the html template.
 */
/* -- Delete this line if you want to use this function
function adaptivetheme_subtheme_process_html(&$vars) {
}
// */


/**
 * Preprocess variables for the page templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_page(&$vars) {
}
function adaptivetheme_subtheme_process_page(&$vars) {
}
// */


/**
 * Override or insert variables into the node templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_node(&$vars) {
}
function adaptivetheme_subtheme_process_node(&$vars) {
}
// */


/**
 * Override or insert variables into the comment templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_comment(&$vars) {
}
function adaptivetheme_subtheme_process_comment(&$vars) {
}
// */


/**
 * Override or insert variables into the block templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_block(&$vars) {
}
function adaptivetheme_subtheme_process_block(&$vars) {
}
// */