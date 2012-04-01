<?php
/**
 * Implimentation of a Page Layout Plugin for Adaptivetheme
 *
 * This function looks like a Panels layout plugin, but its not, its 
 * designed to work only with AT pluggable layout sytem and provides
 * a "page layout". 
 *
 * Standard three column layout. The "grail" refers to the holy search for the ideal
 * CSS only layout that occured in the late 1990's/early 2000's. Yes, I was around
 * back then and participated in that grail quest.
 *
 * Usage:
 * The naming convetion here is very important, the function name must match the method name
 * and the array key. Do not add anything else to it. In the layout function simply append 
 * "_layout", this gets called when the user submits the theme settings form.
 *
 * TODO: allow form #states to be defined (UX issue), and possibly for templates to be loaded (TX issue).
 * Template loading is hard and not something I had considered because of one simple fact - the plugins never
 * run in the front end of the site, they are entirely an admin thing, adding templates would mean they have to 
 * be called by the front end theme, not a big deal really, but requires special attention to performance.
 */
function three_col_grail() {                     // function name must be the same as the method, and the array key (therefor use underscores etc and be a valid PHP funtion name)
  $page_layout['three_col_grail'] = array(       // array key
    'title'     => t('Three Column Holy Grail'), // needed for the UI, not optional
    'category'  => t('AT Core layout'),          // this could get used but is currently not, include something but its not critical
    'method'    => 'three_col_grail',            // method - must match the function name and the key, definitly not optional!
    'type'      => 'page_layout',                // tell the system this is a page_layout, not optional!
    'icon'      => 'three-col-grail.png',        // not optional, not currently used, is a TODO and will happen
    'admin_css' => 'three-col-grail.css',        // optional, not currently used, maybe could be probably not
    'device_groups' => array(                    // define which device groups this layout can work with, can be one or more of
      'bigscreen',                               // bigscreen, tablet_landscape, tablet_portrait, smartphone_landscape. What you
      'tablet_landscape',                        // enter here will dictate the device groups it shows for in theme settings.
     ),                                          // device_groups is not optional, you must put something there!
  );
  return $page_layout;
}

function three_col_grail_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = ''; 
  $styles = array();

  // Define your custom layout.
  // This does not have to be anything like this, but it must return a string of CSS, thats about it, 
  // and only has 3 bits of data to work with that come from the theme settings (what the user entered
  // in the UI), of course you can just make up your own data if that works for your layout, see the
  // one_col_stack for such an implimentation.
  
  // Parameters:
  // - $sidebar_first - a numeric value, like 200 
  // - $sidebar_second - a numeric value, like 181
  // - $sidebar_unit - a value unit, one of px, em or %
  
  // Remember, if you are building a sub-theme you have full control over the theme settings form
  // via your sub-themes hook_form_system_theme_settings_alter() in theme-settings.php

  // Set variables for language direction. In thoery a layout plugin could be RTL compatible.
  $left = 'left';
  $right = 'right';
  
  // Set vars for your sidebars, this can be very different, and entirely depends on your layout.
  $sidebar_second = $sidebar_second . $sidebar_unit;
  $sidebar_first  = $sidebar_first . $sidebar_unit;
  
  // Push and pull values if required, AT is a content source ordered layout and uses a negative margin layout system.
  $push_right = $sidebar_second;
  $push_left  = $sidebar_first;
  $pull_right = $sidebar_second;
  
  // Add to the $styles[] array, at_build_page_layout() will return the styles to the submit function for printing to file.
  $styles[] = '#content-column,.content-column,div.sidebar {float:left;clear:none}';
  $styles[] = '.two-sidebars .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':' . $push_right . '}';
  $styles[] = '.sidebar-first .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}';
  $styles[] = '.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . ';margin-' . $left . ':0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . ';margin-' . $left . ':-100%}';
  $styles[] = '.region-sidebar-second {width:' . $sidebar_second . ';margin-' . $left . ':-' . $pull_right . '}';

  $output = implode('', $styles);
  return $output;
}