<?php // $Id$
// adaptivethemes.com

/**
 * @file template.preprocess-comment-wrapper.inc
 */
  
global $theme;

$classes = array();
// Add support for Skinr module classes http://drupal.org/project/skinr
if (function_exists('node_skinr_data') && !empty($vars['skinr'])) {
  $classes[] = $vars['skinr'];
}
 
$classes = array();
 
// Class for node types: "forum-comments", "blog-comments", etc.
$classes[] = $vars['node']->type .'-comments';

// $classes is the variable that holds the node classes and is printed in node tpl files.
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.