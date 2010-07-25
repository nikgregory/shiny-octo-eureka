<?php
// $Id$

/**
 * @file template.php
 */

// Include base theme custom functions.
include_once(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.custom-functions.inc');

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
