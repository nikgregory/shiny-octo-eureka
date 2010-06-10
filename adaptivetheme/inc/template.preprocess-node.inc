<?php // $Id$
// adaptivethemes.com

/**
 * @file template.preprocess-node.inc
 */

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */

global $theme, $user;

if (theme_get_setting('cleanup_article_id')) {
  // Set article id.
  $vars['article_id'] = 'article-'. $vars['node']->nid;
}

// Unset core node classes, trusting modules will still be able to set their own?
unset($vars['classes']);

$vars['classes_array'][] = 'article';

// Reset node classes to use article prefix.
if (theme_get_setting('cleanup_article_classes_promote')) {
  if ($vars['promote']) {
    $vars['classes_array'][] = 'article-promoted';
  }
}

if (theme_get_setting('cleanup_article_classes_sticky')) {
  if ($vars['sticky']) {
    $vars['classes_array'][] = 'article-sticky';
  }
}

if (theme_get_setting('cleanup_article_classes_teaser')) {
  if ($vars['teaser']) {
    $vars['classes_array'][] = 'article-teaser';
  }
}

if (theme_get_setting('cleanup_article_classes_preview')) {
  if (isset($vars['preview'])) {
    $vars['classes_array'][] = 'article-preview';
  }
}

if (!$vars['status']) {
  $vars['classes_array'][] = 'article-unpublished';
}

// Add support for Skinr module classes http://drupal.org/project/skinr
// if (function_exists('node_skinr_data') && !empty($vars['skinr'])) {
//  $classes[] = $vars['skinr'];
// }

// Class for content types e.g. "article-page", "article-my-content-type", etc.
if (theme_get_setting('cleanup_article_classes_type')) {
  $vars['classes_array'][] = drupal_html_class('article-' . $node->type);
}

// Language specific article class.
/*
if (theme_get_setting('cleanup_article_classes_language')) {
  if (function_exists('i18n_init')) {
    if ($vars['node']->language) {
      global $language;
      $classes[] = 'article-lang-'. $vars['node']->language;
    }
  }
}
*/

if (theme_get_setting('cleanup_article_classes_zebra')) {
  $vars['classes_array'][] = $vars['zebra'];
}

// Set title classes
if (theme_get_setting('cleanup_headings_title_class')) {
  $vars['title_attributes_array']['class'][] = 'title';
}

if (theme_get_setting('cleanup_headings_namespaced_class')) {
  $vars['title_attributes_array']['class'][] = 'article-title';
}

// Add article_aside region content.
// $vars['article_aside'] = theme('blocks', 'article_aside');

