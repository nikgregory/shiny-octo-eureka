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
global $user;

// Special classes for comments, emulates Drupal 7 for forward compatibility.
// Load the node object that the current comment is attached to.
$node = node_load($vars['comment']->nid);
$classes = array();
$classes[]  = 'comment';
if ($vars['status'] != 'comment-published') {
  $classes[] = $vars['status'];
}
else {
  if (theme_get_setting('cleanup_comment_anonymous')) {
    if ($vars['comment']->uid == 0) {
      $classes[] = 'comment-by-anonymous';
    }
  }
  if (theme_get_setting('cleanup_comment_article_author')) {
    if ($vars['comment']->uid === $vars['node']->uid) {
      $classes[] = 'comment-by-article-author';
    }
  }
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
$vars['classes'] = implode(' ', $classes);



// If comment subjects are disabled, don't display them.
if (variable_get('comment_subject_field', 1) == 0) {
  $vars['title'] = '';
}
  
// Date & author theme settings.
if (!function_exists('submitted_by_comment_submitted')) {
  $submitted_by = t('by ') .'<span class="author">'.  theme('username', $vars['comment']) .'</span>';
  $submitted_by .= t(' - ') .'<span class="time">'.  format_date($vars['comment']->timestamp, 'small') .'</span>'; // Format date as small, medium, or large
  $vars['submitted'] = $submitted_by;
}

// Set messages if comments are unpublished.
if ($vars['comment']->status == COMMENT_NOT_PUBLISHED) {
  drupal_set_message(t('Comment #!id !title is currently unpublished', array('!id' => $vars['id'], '!title' => $vars['title'])), 'warning');
  $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
}
