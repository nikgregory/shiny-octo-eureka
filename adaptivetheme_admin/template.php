<?php
// $Id$

/**
 * Override or insert variables into page templates.
 */
function adaptivetheme_admin_process_page(&$vars) {
  global $user;
  // Welcome message with date for the admin theme.
  if ($vars['logged_in']) {
    $vars['datetime_rfc'] = date("r" , time()); // RFC2822 date format
    $vars['datetime_iso'] = date("c" , time()); // ISO 8601 date format
  }
}
