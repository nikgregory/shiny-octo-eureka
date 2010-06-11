<?php // $Id$
// adaptivethemes.com

/**
 * @file template.preprocess-comment.inc
 */

/**
 * Override or insert variables in comment templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */

global $theme, $user;

// Load the node object that the current comment is attached to.
$node = node_load($vars['comment']->nid);

// Special classes for comments, emulates Drupal 7 for forward compatibility.
$classes = array();

$classes[]  = 'comment';

//

  $classes = explode(' ', $vars['classes']);

  if (in_array('comment-by-anonymous', $classes)) {
    theme_get_setting('cleanup_comment_anonymous') ? '' : $classes = str_replace('comment-by-anonymous', '', $classes);
  }
  if (in_array('comment-by-node-author', $classes)) {
    $classes = str_replace('comment-by-node-author', 'comment-by-article-author', $classes);
    theme_get_setting('cleanup_comment_article_author') ? '' : $classes = str_replace('comment-by-article-author', '', $classes);
  }
  if (in_array('comment-by-viewer', $classes)) {
    theme_get_setting('cleanup_comment_by_viewer') ? '' : $classes = str_replace('comment-by-viewer', '', $classes);
  }
  if (in_array('comment-by-viewer', $classes)) {
    theme_get_setting('cleanup_comment_by_viewer') ? '' : $classes = str_replace('comment-by-viewer', '', $classes);
  }
  if (in_array('comment-new', $classes)) {
    theme_get_setting('cleanup_comment_new') ? '' : $classes = str_replace('comment-new', '', $classes);
  }

  //

  if (theme_get_setting('cleanup_comment_by_viewer')) {
    if ($vars['comment']->uid === $vars['user']->uid) {
      $classes[] = 'comment-by-viewer';
    }
  }
  if (theme_get_setting('cleanup_comment_new')) {
    if ($vars['comment']->new) {
      $classes[] = 'comment-new';
    }
  }
  if (theme_get_setting('cleanup_comment_zebra')) {
    $classes[] = $vars['zebra'];
  }
}

// $classes is the varaible that will hold the blocks classes, and is printed in block tpl files.
$vars['classes'] = implode(' ', $classes);

/*

  // Gather comment classes.
  if ($comment->uid === 0) {
    $variables['classes_array'][] = 'comment-by-anonymous';
  }
  else {
    // Published class is not needed. It is either 'comment-preview' or 'comment-unpublished'.
    if ($variables['status'] != 'comment-published') {
      $variables['classes_array'][] = $variables['status'];
    }
    if ($comment->uid === $variables['node']->uid) {
      $variables['classes_array'][] = 'comment-by-node-author';
    }
    if ($comment->uid === $variables['user']->uid) {
      $variables['classes_array'][] = 'comment-by-viewer';
    }
    if ($variables['new']) {
      $variables['classes_array'][] = 'comment-new';
    }

 */



















// Set title classes
$title_classes = array();
if (theme_get_setting('cleanup_headings_title_class')) {
  $title_classes[] = 'title';
}

if (theme_get_setting('cleanup_headings_namespaced_class')) {
  $title_classes[] = 'comment-title';
}

$vars['title_classes'] = implode(' ', $title_classes);

// If comment subjects are disabled, don't display them.
if (variable_get('comment_subject_field', 1) == 0) {
  $vars['title'] = '';
}

// Date & author theme settings.
if (!function_exists('submitted_by_comment_submitted')) {
  $submitted_by = t('by ') .'<em class="author">'.  theme('username', $vars['comment']) .'</em>';
  $submitted_by .= t(' - ') .'<em class="time">'.  format_date($vars['comment']->timestamp, 'small') .'</em>'; // Format date as small, medium, or large
  $vars['submitted'] = $submitted_by;
}

// Set messages if comments are unpublished.
if ($vars['comment']->status == COMMENT_NOT_PUBLISHED) {
  drupal_set_message(t('Comment #!id !title is currently unpublished', array('!id' => $vars['id'], '!title' => $vars['title'])), 'warning');
  $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
}