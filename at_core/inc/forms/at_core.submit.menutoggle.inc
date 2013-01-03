<?php

/**
 * @file
 * Save the Menu Toggle CSS to file
 */
function at_core_submit_menu_toggle($values, $theme_name, $path) {
  $smalltouch_portrait_media_query  = $values['smalltouch_portrait_media_query'];
  $smalltouch_landscape_media_query = $values['smalltouch_landscape_media_query'];
  $tablet_portrait_media_query      = $values['tablet_portrait_media_query'];
  $tablet_landscape_media_query     = $values['tablet_landscape_media_query'];
  $bigscreen_media_query            = $values['bigscreen_media_query'];
  $menu_toggle_css = array();

  $menu_toggle_css_active = '.at-menu-toggle h2{position:static;top:0}#menu-toggle{display:none}#menu-toggle ul li,#menu-toggle ul li a{display:block;float:none}';
  $menu_toggle_css_inactive = '.at-menu-toggle h2{position:absolute;top:-999999em}#menu-toggle{display:block !important}';
  
  // We need extra overrides for superfish if the module is active
  if (module_exists('superfish')) {
    $menu_toggle_superfish_css = '#menu-toggle .sf-menu{float:none;margin:0;height:auto}#menu-toggle .sf-menu ul,#menu-toggle .sf-menu li {margin:0;position:relative;top:0;display:block;float:none;clear:both;width:100%!important;left:0!important}';
  }
  else {
    $menu_toggle_superfish_css = '';
  }

  $comment = "/* Menu toggle is using the media queries from the standard, tablet and smalltouch layout settings */\n";

  // Smalltouch portrait
  if (isset($values['menu_toggle_smalltouch_portrait']) && $values['menu_toggle_smalltouch_portrait'] == 1) {
    $smalltouch_portrait_css = $menu_toggle_css_active . $menu_toggle_superfish_css;
  }
  else {
    $smalltouch_portrait_css = $menu_toggle_css_inactive;
  }
  $menu_toggle_css[] = $comment . '@media ' . $smalltouch_portrait_media_query . ' {' . "\n" . $smalltouch_portrait_css . "\n" . '}';

  // Smalltouch landscape
  if (isset($values['menu_toggle_smalltouch_landscape']) && $values['menu_toggle_smalltouch_landscape'] == 1) {
    $smalltouch_landscape_css = $menu_toggle_css_active . $menu_toggle_superfish_css;
  }
  else {
    $smalltouch_landscape_css = $menu_toggle_css_inactive;
  }
  $menu_toggle_css[] = '@media ' . $smalltouch_landscape_media_query . ' {' . "\n" . $smalltouch_landscape_css . "\n" . '}';

  // Tablet portrait
  if (isset($values['menu_toggle_tablet_portrait']) && $values['menu_toggle_tablet_portrait'] == 1) {
    $tablet_portrait_css = $menu_toggle_css_active . $menu_toggle_superfish_css;
  }
  else {
    $tablet_portrait_css = $menu_toggle_css_inactive;
  }
  $menu_toggle_css[] = '@media ' . $tablet_portrait_media_query . ' {' . "\n" . $tablet_portrait_css . "\n" . '}';

  // Tablet landscape
  if (isset($values['menu_toggle_tablet_landscape']) && $values['menu_toggle_tablet_landscape'] == 1) {
    $tablet_landscape_css = $menu_toggle_css_active . $menu_toggle_superfish_css;
  }
  else {
    $tablet_landscape_css = $menu_toggle_css_inactive;
  }
  $menu_toggle_css[] = '@media ' . $tablet_landscape_media_query . ' {' . "\n" . $tablet_landscape_css . "\n" . '}';

  // Bigscreen is always inactive
  $menu_toggle_css[] = '@media ' . $bigscreen_media_query . ' {' . "\n" . $menu_toggle_css_inactive . "\n" . '}';

  $menu_toggle_css_processed = implode("\n", $menu_toggle_css);
  $file_name = $theme_name . '.menutoggle.css';
  $filepath = $path . '/' . $file_name;
  file_unmanaged_save_data($menu_toggle_css_processed, $filepath, FILE_EXISTS_REPLACE);
}
