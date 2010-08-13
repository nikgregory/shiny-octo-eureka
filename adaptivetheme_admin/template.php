<?php
// $Id$

/**
 * @file template.php
 */

/**
 * Override or insert variables into page templates.
 */
function adaptivetheme_admin_process_page(&$vars) {
  global $user;
  // Welcome message with date for the admin theme.
  if ($vars['logged_in']) {
    $vars['time_date'] = date("r" , time());
  }
}
