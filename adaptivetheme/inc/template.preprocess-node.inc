<?php // $Id$
// adaptivethemes.com 62

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
global $user, $theme;

if (theme_get_setting('cleanup_article_classes_promote')) {
// Set the node id.
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

if (theme_get_setting('cleanup_article_classes_type')) {
  // Class for node type: "node-type-page", "node-type-my-custom-type", etc.
  $classes[] = $vars['node']->type .'-article';
}

// $classes is the variable that holds the node classes and is printed in node tpl files.
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.
  
// Add article_aside region content.
$vars['article_aside'] = theme('blocks', 'article_aside');

// Set message if node is unpublished.
if (!$vars['node']->status) {
  if ($vars['page']) {
    drupal_set_message(t('%title is currently unpublished', array('%title' => $vars['node']->title)), 'warning'); 
  }
  else {
    $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
  }
}
  
// Node Theme Settings.
// Date & author theme settings.
if (!function_exists('submitted_by_node_submitted')) {
  $date = t('Posted ') .'<span class="time">'. format_date($vars['node']->created, 'medium') .'</span>';
  $author = '<span class="author">'. theme('username', $vars['node']) .'</span>';
  $author_only_separator = t('Posted by ');
  $author_date_separator = t(' by ');
  $submitted_by_content_type = (theme_get_setting('submitted_by_enable_content_type') == 1) ? $vars['node']->type : 'default';
  $date_setting = (theme_get_setting('submitted_by_date_'. $submitted_by_content_type) == 1);
  $author_setting = (theme_get_setting('submitted_by_author_'. $submitted_by_content_type) == 1);
  $author_separator = ($date_setting) ? $author_date_separator : $author_only_separator;
  $date_author = ($date_setting) ? $date : '';
  $date_author .= ($author_setting) ? $author_separator . $author : '';
  $vars['submitted'] = $date_author;
}

// Taxonomy theme settings.
$taxonomy_content_type = (theme_get_setting('taxonomy_enable_content_type') == 1) ? $vars['node']->type : 'default';
$taxonomy_display = theme_get_setting('taxonomy_display_'. $taxonomy_content_type);
$taxonomy_format = theme_get_setting('taxonomy_format_'. $taxonomy_content_type);
$delimiter = filter_xss(theme_get_setting('taxonomy_delimiter_'. $taxonomy_content_type));
$heading_level = $vars['page'] ? 'h2' : 'h3';
if ((module_exists('taxonomy')) && ($taxonomy_display == 'all' || ($taxonomy_display == 'only' && $vars['page']))) {
  $vocabularies = taxonomy_get_vocabularies($vars['node']->type);
  $output = '';
  $vocab_delimiter = ''; 
  foreach ($vocabularies as $vocabulary) {
    if (theme_get_setting('taxonomy_vocab_display_'. $taxonomy_content_type .'_'. $vocabulary->vid) == 1) {
      $terms = taxonomy_node_get_terms_by_vocabulary($vars['node'], $vocabulary->vid);
      if ($terms) {
        $output .= ($taxonomy_format == 'vocab') ? '<li class="vocab vocab-'. safe_string($vocabulary->name) .'"><'. $heading_level .' class="vocab-name">'. $vocabulary->name .'</'. $heading_level .'><ul class="terms">' : '';
        $links = array();
        $term_index = 0;
        foreach ($terms as $term) {
          $term_index++;
          $term_delimiter = ($term_index == count($terms)) ? '' : $delimiter;
          $links[] = '<li>'. l($term->name, taxonomy_term_path($term), array('attributes' => array('rel' => 'tag', 'title' => strip_tags($term->description)))) . $term_delimiter .'</li>';
        }
        if ($taxonomy_format == 'list') {
          $output .= $vocab_delimiter;
          $vocab_delimiter = $delimiter;
        }
        $output .= implode("", $links);
        $output .= ($taxonomy_format == 'vocab') ? '</ul></li>' : '';
      }
    }
  }
  if ($output != '') {
    $output = '<ul class="taxonomy">'. $output .'</ul>';
  }
  $vars['terms'] = $output;
}
else {
  $vars['terms'] = '';
}
