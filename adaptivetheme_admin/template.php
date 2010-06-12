<?php // $Id$
// adaptivethemes.com admin

/**
 * @file template.php
 */

// Include base theme custom functions.
include_once(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.custom-functions.inc');


/**
 * Add the color scheme stylesheet if color_enable_schemes is set to 'on'.
 * Note: you must have at minimum a color-default.css stylesheet in /css/theme/
 */
if (theme_get_setting('color_enable_schemes') == 'on') {
  drupal_add_css(drupal_get_path('theme', 'adaptivetheme_admin') .'/css/theme/'. get_at_colors(), 'theme');
}

/**
 * Override or insert variables into page templates.
 */
function adaptivetheme_admin_process_page(&$vars, $hook) {
  global $user;
  // Welcome message with date for the admin theme.
  if ($vars['logged_in']) {
    $vars['time_date'] = date("r" , time());
  }
}
