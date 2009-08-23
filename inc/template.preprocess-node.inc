<?php // $Id$
// adaptivethemes.com
/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
global $user;
global $theme;
 
// Set the node id.
$vars['node_nid'] = 'node-'. $vars['node']->nid;

// Special classes for nodes. Emulates Drupal 7 node classes for forward compatibility.
$classes = array();
$classes[] = 'node';
if ($vars['promote']) {
  $classes[] = 'node-promoted';
}
if ($vars['sticky']) {
  $classes[] = 'node-sticky';
}
if (!$vars['status']) {
  $classes[] = 'node-unpublished';
}
if ($vars['teaser']) {
  // Node is displayed as teaser.
  $classes[] = 'node-teaser';
}
if (isset($vars['preview'])) {
  $classes[] = 'node-preview';
}
// Add support for Skinr module classes http://drupal.org/project/skinr
if (function_exists('node_skinr_data')) {
  $classes[] = $vars['skinr'];
}
// Class for node type: "node-type-page", "node-type-story", "node-type-my-custom-type", etc.
$classes[] = 'node-'. $vars['node']->type;
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.
  
// Add node_bottom region content.
$vars['node_bottom'] = theme('blocks', 'node_bottom');

// Set messages if node is unpublished.
if (!$vars['node']->status) {
  if ($vars['page']) {
    drupal_set_message(t('%title is currently unpublished', array('%title' => $vars['node']->title)), 'warning'); 
  }
  else {
    $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
  }
}
  
// Node Theme Settings
// Date & author
$date = t('Posted ') . format_date($vars['node']->created, 'medium');                 // Format date as small, medium, or large
$author = theme('username', $vars['node']);
$author_only_separator = t('Posted by ');
$author_date_separator = t(' by ');
$submitted_by_content_type = (theme_get_setting('submitted_by_enable_content_type') == 1) ? $vars['node']->type : 'default';
$date_setting = (theme_get_setting('submitted_by_date_'. $submitted_by_content_type) == 1);
$author_setting = (theme_get_setting('submitted_by_author_'. $submitted_by_content_type) == 1);
$author_separator = ($date_setting) ? $author_date_separator : $author_only_separator;
$date_author = ($date_setting) ? $date : '';
$date_author .= ($author_setting) ? $author_separator . $author : '';
$vars['submitted'] = $date_author;

// Taxonomy
$taxonomy_content_type = (theme_get_setting('taxonomy_enable_content_type') == 1) ? $vars['node']->type : 'default';
$taxonomy_display = theme_get_setting('taxonomy_display_'. $taxonomy_content_type);
$taxonomy_format = theme_get_setting('taxonomy_format_'. $taxonomy_content_type);
if ((module_exists('taxonomy')) && ($taxonomy_display == 'all' || ($taxonomy_display == 'only' && $vars['page']))) {
  $vocabularies = taxonomy_get_vocabularies($vars['node']->type);
  $output = '';
  $vocab_delimiter = '';
  foreach ($vocabularies as $vocabulary) {
    if (theme_get_setting('taxonomy_vocab_display_'. $taxonomy_content_type .'_'. $vocabulary->vid) == 1) {
      $terms = taxonomy_node_get_terms_by_vocabulary($vars['node'], $vocabulary->vid);
      if ($terms) {
        $output .= ($taxonomy_format == 'vocab') ? '<li class="vocab vocab-'. $vocabulary->vid .'"><span class="vocab-name">'. $vocabulary->name .':</span> <ul class="vocab-list">' : '';
        $links = array();
        foreach ($terms as $term) {        
          $links[] = '<li class="vocab-term">'. l($term->name, taxonomy_term_path($term), array('attributes' => array('rel' => 'tag', 'title' => strip_tags($term->description)))) .'</li>';        
        }
        if ($taxonomy_format == 'list') {
          $output .= $vocab_delimiter;    // Add comma between vocabularies
          $vocab_delimiter = ', ';        // Use a comma delimiter after first displayed vocabulary
        }
        $output .= implode(", ", $links);
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
  
// Node Links
if (isset($vars['node']->links['node_read_more'])) {
  $node_content_type = (theme_get_setting('readmore_enable_content_type') == 1) ? $vars['node']->type : 'default';
  $vars['node']->links['node_read_more'] = array(
    'title' => _themesettings_link(
      theme_get_setting('readmore_prefix_'. $node_content_type),
      theme_get_setting('readmore_suffix_'. $node_content_type),
      theme_get_setting('readmore_'. $node_content_type),
      'node/'. $vars['node']->nid,
      array(
        'attributes' => array('title' => theme_get_setting('readmore_title_'. $node_content_type)), 
        'query' => NULL, 'fragment' => NULL, 'absolute' => FALSE, 'html' => TRUE
      )
    ),
    'attributes' => array('class' => 'readmore-item'),
    'html' => TRUE,
  );
}
if (isset($vars['node']->links['comment_add'])) {
  $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
  if ($vars['teaser']) {
    $vars['node']->links['comment_add'] = array(
      'title' => _themesettings_link(
        theme_get_setting('comment_add_prefix_'. $node_content_type),
        theme_get_setting('comment_add_suffix_'. $node_content_type),
        theme_get_setting('comment_add_'. $node_content_type),
        "comment/reply/". $vars['node']->nid,
        array(
          'attributes' => array('title' => theme_get_setting('comment_add_title_'. $node_content_type)), 
          'query' => NULL, 'fragment' => 'comment-form', 'absolute' => FALSE, 'html' => TRUE
        )
      ),
      'attributes' => array('class' => 'comment-add-item'),
      'html' => TRUE,
    );
  }
  else {
    $vars['node']->links['comment_add'] = array(
      'title' => _themesettings_link(
        theme_get_setting('comment_node_prefix_'. $node_content_type),
        theme_get_setting('comment_node_suffix_'. $node_content_type),
        theme_get_setting('comment_node_'. $node_content_type),
        "comment/reply/". $vars['node']->nid,
        array(
          'attributes' => array('title' => theme_get_setting('comment_node_title_'. $node_content_type)), 
          'query' => NULL, 'fragment' => 'comment-form', 'absolute' => FALSE, 'html' => TRUE
        )
      ),
      'attributes' => array('class' => 'comment-node-item'),
      'html' => TRUE,
    );
  }
}
if (isset($vars['node']->links['comment_new_comments'])) {
  $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
  $vars['node']->links['comment_new_comments'] = array(
    'title' => _themesettings_link(
      theme_get_setting('comment_new_prefix_'. $node_content_type),
      theme_get_setting('comment_new_suffix_'. $node_content_type),
      format_plural(
        comment_num_new($vars['node']->nid),
        theme_get_setting('comment_new_singular_'. $node_content_type),
        theme_get_setting('comment_new_plural_'. $node_content_type)
      ),
      "node/". $vars['node']->nid,
      array(
        'attributes' => array('title' => theme_get_setting('comment_new_title_'. $node_content_type)), 
        'query' => NULL, 'fragment' => 'new', 'absolute' => FALSE, 'html' => TRUE
      )
    ),
    'attributes' => array('class' => 'comment-new-item'),
    'html' => TRUE,
  );
}
if (isset($vars['node']->links['comment_comments'])) {
  $node_content_type = (theme_get_setting('comment_enable_content_type') == 1) ? $vars['node']->type : 'default';
  $vars['node']->links['comment_comments'] = array(
    'title' => _themesettings_link(
      theme_get_setting('comment_prefix_'. $node_content_type),
      theme_get_setting('comment_suffix_'. $node_content_type),
      format_plural(
        comment_num_all($vars['node']->nid),
        theme_get_setting('comment_singular_'. $node_content_type),
        theme_get_setting('comment_plural_'. $node_content_type)
      ),
      "node/". $vars['node']->nid,
      array(
        'attributes' => array('title' => theme_get_setting('comment_title_'. $node_content_type)), 
        'query' => NULL, 'fragment' => 'comments', 'absolute' => FALSE, 'html' => TRUE
      )
    ),
    'attributes' => array('class' => 'comment-item'),
    'html' => TRUE,
  );
}
$vars['links'] = theme('links', $vars['node']->links, array('class' => 'links inline')); 
