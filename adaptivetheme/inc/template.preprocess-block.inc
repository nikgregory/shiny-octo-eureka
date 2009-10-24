<?php // $Id$
// adaptivethemes.com

/**
 * @file template.preprocess-block.inc
 */

/**
 * Override or insert variables into block templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
global $theme;
$block = $vars['block'];
  
// Set the block id.
$vars['block_module_delta'] = 'block-'. $block->module .'-'. $block->delta;

// Set up variables for navigation-like blocks.
$n1 = array('user-1', 'statistics-0');
$n2 = array('menu', 'book', 'forum', 'blog', 'aggregator', 'comment');
$h1 = $block->module .'-'. $block->delta;
$h2 = $block->module;

// Special classes for blocks, emulate Drupal 7.
$classes = array();
$classes[] = 'block';
$classes[] = 'block-'. $block->module;

// Add nav class to navigation-like blocks.
if (in_array($h1, $n1)) {
  $classes[] = 'nav';
}
if (in_array($h2, $n2)) {
  $classes[] = 'nav';
}

// Optionally use additional block classes
// $classes[] = $vars['block_zebra'];        // odd, even zebra class
// $classes[] = 'block-'. $block->region;    // block-[region] class
// $classes[] = 'block-count-'. $vars['id']; // block-count-[count] class

// Add special classes to support the http://drupal.org/project/blocktheme module.
if (function_exists('blocktheme_preprocess_block') && isset($vars['blocktheme'])) {
  $classes[] = $vars['blocktheme'];
  $classes[] = $block->region .'-'. $vars['blocktheme'];
}

// Add special classes to support the http://drupal.org/project/block_class module.
if (function_exists('block_class') && block_class($block)) {
  $classes[] = block_class($block);
  $classes[] = $block->region .'-'. block_class($block);
}

// Add support for Skinr module classes http://drupal.org/project/skinr.
if (!empty($vars['skinr'])) {
  $classes[] = $vars['skinr'];
}

// Add class for block edit links if enabled.
if (theme_get_setting('block_edit_links') && user_access('administer blocks')) {
  $classes[] = 'block-edit-links';
}

// Add classes for the horizontal login block if enabled. 
if (theme_get_setting('horizontal_login_block') && $vars['block_module_delta'] == 'block-user-0') {
  $classes[] = 'at-horizontal-login clear-block';
}

// $classes is the varaible that will hold the blocks classes, and is printed in block tpl files.
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.

// Collapsible block admin blocks.
if (arg(2) == 'block') {
  $vars['block_at_admin_classes'] = implode(' .', $classes);
  drupal_add_js('misc/collapse.js', 'core', 'header', FALSE, TRUE, TRUE);
  $vars['template_files'][] = 'block-at_admin';
}

// Block edit links.
$vars['edit_links_array'] = array();
$vars['edit_links'] = '';
if (theme_get_setting('block_edit_links') && user_access('administer blocks')) {
  include_once './' . drupal_get_path('theme', 'adaptivetheme') . '/inc/template.block-editing.inc';
  phptemplate_preprocess_block_editing($vars, $hook);
}
