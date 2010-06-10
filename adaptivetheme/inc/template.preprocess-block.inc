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
// Set the block html id.
if (theme_get_setting('cleanup_block_block_module_delta')) {
  $vars['block_html_id'] = ' id="' . $vars['block_html_id'] .'"';
}
else {
  unset($vars['block_html_id']);
}

// Special classes for blocks.
$vars['classes_array'][] = 'block';

if (theme_get_setting('cleanup_block_classes_module')) {
  $vars['classes_array'][] = drupal_html_class('block-' . $vars['block']->module);
}

if (theme_get_setting('cleanup_block_classes_zebra')) {
  $vars['classes_array'][] = $vars['block_zebra']; // odd, even zebra class
}

if (theme_get_setting('cleanup_block_classes_region')) {
  $vars['classes_array'][] = 'block-region-'. drupal_html_class($vars['block']->region); // block-[region] class
}

if (theme_get_setting('cleanup_block_classes_count')) {
  $vars['classes_array'][] = 'block-count-'. $vars['block_id']; // block-count-[count] class
}

// Add classes for the horizontal login block if enabled.
if (theme_get_setting('horizontal_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'user-login') {
  $vars['classes_array'][] = 'at-horizontal-login clearfix';
}

// Hide search label on the search block
if ($vars['block']->module == 'search' && $vars['block']->delta == 'search-form') {
  if (theme_get_setting('display_search_form_label') == 0) {
    $vars['classes_array'][] = 'hide-label';
  }
}

// Set title classes.
if (theme_get_setting('cleanup_headings_title_class')) {
  $vars['title_attributes_array']['class'][] = 'title';
}
if (theme_get_setting('cleanup_headings_namespaced_class')) {
  $vars['title_attributes_array']['class'][] = 'block-title';
}

// Add the content class to block content wrapper.
$vars['content_attributes_array']['class'][] = 'content';
