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

// Special classes for nodes. Emulates Drupal 7 node classes for forward compatibility.
$classes = array();

$classes[] = 'article';

if (theme_get_setting('cleanup_article_classes_promote')) {
  if ($vars['promote']) {
    $classes[] = 'article-promoted';
  }
}

if (theme_get_setting('cleanup_article_classes_sticky')) {
  if ($vars['sticky']) {
    $classes[] = 'article-sticky';
  }
}

if (theme_get_setting('cleanup_article_classes_teaser')) {
  if ($vars['teaser']) {
    $classes[] = 'article-teaser';
  }
}

if (theme_get_setting('cleanup_article_classes_preview')) {
  if (isset($vars['preview'])) {
    $classes[] = 'article-preview';
  }
}

if (!$vars['status']) {
  $classes[] = 'article-unpublished';
}

// Add support for Skinr module classes http://drupal.org/project/skinr
if (function_exists('node_skinr_data') && !empty($vars['skinr'])) {
  $classes[] = $vars['skinr'];
}

// Class for content types e.g. "article-page", "article-my-content-type", etc.
if (theme_get_setting('cleanup_article_classes_type')) {
  $classes[] = $vars['node']->type .'-article';
}

// Language specific article class.
if (theme_get_setting('cleanup_article_classes_language')) {
  if (function_exists('i18n_init')) {
    if ($vars['node']->language) {
      global $language;
      $classes[] = 'article-lang-'. $vars['node']->language;
    }
  }
}

if (theme_get_setting('cleanup_article_classes_zebra')) {
  $classes[] = $vars['zebra'];
}

// $classes is the variable that holds the node classes and is printed in node tpl files.
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.

// Set title classes
$title_classes = array();

if (theme_get_setting('cleanup_headings_title_class')) {
  $title_classes[] = 'title';
}

if (theme_get_setting('cleanup_headings_namespaced_class')) {
  $title_classes[] = 'article-title';
}

$vars['title_classes'] = implode(' ', $title_classes);

// Add article_aside region content.
$vars['article_aside'] = theme('blocks', 'article_aside');

// Support node tpl suggestions per node nid.
$vars['template_files'][] = 'node-'.$vars['node']->nid;

// Set message if node is unpublished.
if (!$vars['node']->status) {
  if ($vars['page']) {
    drupal_set_message(t('%title is currently unpublished', array('%title' => $vars['node']->title)), 'warning');
  }
  else {
    $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
  }
}
