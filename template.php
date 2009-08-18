<?php // $Id$
// adaptivethemes.com

/**
 * @file template.php
 */

/**
 * Include dependant settings and function.
 */
include_once './'. drupal_get_path('theme', 'adaptivetheme') .'/inc/template.custom-functions.inc';
include_once './'. drupal_get_path('theme', 'adaptivetheme') .'/inc/template.theme-settings.inc';
include_once './'. drupal_get_path('theme', 'adaptivetheme') .'/inc/template.theme-functions.inc';';

/**
 * Implement HOOK_theme
 * - Add conditional stylesheets:
 *   For more information: http://msdn.microsoft.com/en-us/library/ms537512.aspx
 */
function adaptivetheme_theme(&$existing, $type, $theme, $path) {
  
  // Compute the conditional stylesheets.
  if (!module_exists('conditional_styles')) {
    include_once './'. drupal_get_path('theme', 'adaptivetheme') .'/inc/template.conditional-styles.inc';
    // _conditional_styles_theme() only needs to be run once.
    if ($theme == 'adaptivetheme') {
      _conditional_styles_theme($existing, $type, $theme, $path);
    }
  }  
  $templates = drupal_find_theme_functions($existing, array('phptemplate', $theme));
  $templates += drupal_find_theme_templates($existing, '.tpl.php', $path);
  return $templates;
}


/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered.
 */
function adaptivetheme_preprocess(&$vars, $hook) {
  global $user;                                            // Get the current user
  $vars['is_admin'] = in_array('admin', $user->roles);     // Check for Admin, logged in
  $vars['logged_in'] = ($user->uid > 0) ? TRUE : FALSE;
}


/**
 * Override or insert variables into page templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
function adaptivetheme_preprocess_page(&$vars, $hook) {
  global $theme;

  // Don't display empty help from node_help().
  if ($vars['help'] == "<div class=\"help\"> \n</div>") {
    $vars['help'] = '';
  }
  
  // Remove sidebars if disabled e.g., for Panels
  if (!$vars['show_blocks']) {
    $vars['left'] = '';
    $vars['right'] = '';
  }

  // Add conditional stylesheets (for Internt Explorer).
  if (!module_exists('conditional_styles')) {
    $vars['styles'] .= $vars['conditional_styles'] = variable_get('conditional_styles_'. $GLOBALS['theme'], '');
  }

  // Set variables for the logo and site_name.
  if (!empty($vars['logo'])) {
    // Return the site_name even when site_name is disabled in theme settings.
    $vars['logo_alt_text'] = variable_get('site_name', '');
    $vars['linked_site_logo'] = '<a href="'. $vars['front_page'] .'" title="'. t('Home page') .'" rel="home"><img src="'. $vars['logo'] .'" alt="'. $vars['logo_alt_text'] .' '. t('logo') .'" /></a>';
  }
  if (!empty($vars['site_name'])) {
    $vars['linked_site_name'] = '<a href="'. $vars['front_page'] .'" title="'. t('Home page') .'" rel="home">'. $vars['site_name'] .'</a>';
  }

  // Set variables for the primary and secondary links.
  if (!empty($vars['primary_links'])) {
    $vars['primary_menu'] = theme('links', $vars['primary_links'], array('class' => 'primary-links clear-block'));
  }
  if (!empty($vars['secondary_links'])) {
    $vars['secondary_menu'] = theme('links', $vars['secondary_links'], array('class' => 'secondary-links clear-block'));
  }
  
  // Admin welcome message with date for the admin theme.
  global $user;
  $welcome = t('Welcome') .' '. $user->name;
  $conjunction = ', '. t('it\'s') .' ';
  $todays_date = date("l, F d Y" , time()); 
  $vars['admin_welcome'] = $welcome . $conjunction . $todays_date;

  // Attribution.
  $vars['attribution'] = "<div id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></div>"  ;

  // Section class. The section class is printed on the body element and allows you to theme site sections.
  // We use the path alias otherwise all nodes will be in "section-node".
  $path_alias = drupal_get_path_alias($_GET['q']);
  if (!$vars['is_front']) {
    list($section, ) = explode('/', $path_alias, 2);
    $vars['section_class'] = safe_string('section-'. $section);
  }

  // Body Classes. In Genesis these are printed on the #container wrapper div, not on the body.
  $classes = explode(' ', $vars['body_classes']);

  // Remove the useless page-arg(0) class.
  if ($class = array_search(preg_replace('![^abcdefghijklmnopqrstuvwxyz0-9-]+!s', '', 'page-'. drupal_strtolower(arg(0))), $classes)) {
    unset($classes[$class]);
  }

 /** 
  * Optional Region body classes
  * Uncomment the following if you need to set a body class for each active region.
  */
  /*        
  if (!empty($vars['leaderboard'])) {
    $classes[] = 'leaderboard';
  }
  if (!empty($vars['header'])) {
    $classes[] = 'header-blocks';
  }
  if (!empty($vars['secondary_content'])) {
    $classes[] = 'secondary-content';
  }
  if (!empty($vars['tertiary_content'])) {
    $classes[] = 'tertiary-content';
  }
  if (!empty($vars['footer'])) {
    $classes[] = 'footer';
  }
  */

  /**
   * Additional body classes to help out themers.
   */
  if (!$vars['is_front']) {
    // Set classes based on Drupals internal path, e.g. page-node-1. 
    // Using the alias is fragile because path alias's can change, $normal_path is more reliable.
    $normal_path = drupal_get_normal_path($_GET['q']);
    $classes[] = safe_string('page-'. $normal_path);
    if (arg(2) == 'block') {
      $classes[] = 'page-block';
    }
    if (arg(0) == 'node') {
      if (arg(1) == 'add') {
        $classes[] = 'page-node-add'; // Add .node-add class.
      }
      elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
        $classes[] = 'page-node-'. arg(2); // Add .node-edit or .node-delete classes.
      }
    }
  }
  $vars['classes'] = implode(' ', $classes); // Concatenate with spaces.
}


/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
function adaptivetheme_preprocess_node(&$vars, $hook) {
  global $user;
  
  // Set the node id.
  $vars['node_id'] = 'node-'. $vars['node']->nid;

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
}


/**
 * Override or insert variables in comment templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
function adaptivetheme_preprocess_comment(&$vars, $hook) {
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
    if ($vars['comment']->uid == 0) {
      $classes[] = 'comment-by-anonymous';
    }
    if ($vars['comment']->uid === $vars['node']->uid) {
      $classes[] = 'comment-by-node-author';
    }
    if ($vars['comment']->uid === $vars['user']->uid) {
      $classes[] = 'comment-by-viewer';
    }
    if ($vars['comment']->new) {
      $classes[] = 'comment-new';
    }
    $classes[] = $vars['zebra'];
  }
  $vars['classes'] = implode(' ', $classes);

  // If comment subjects are disabled, don't display them.
  if (variable_get('comment_subject_field', 1) == 0) {
    $vars['title'] = '';
  }
  
  // Date & author
  $submitted_by = t('by ') .'<span class="comment-name">'.  theme('username', $vars['comment']) .'</span>';
  $submitted_by .= t(' - ') .'<span class="comment-date">'.  format_date($vars['comment']->timestamp, 'small') .'</span>';     // Format date as small, medium, or large
  $vars['submitted'] = $submitted_by;

  // Set messages if comment is unpublished.
  if ($vars['comment']->status == COMMENT_NOT_PUBLISHED) {
    drupal_set_message(t('Comment #!id !title is currently unpublished', array('!id' => $vars['id'], '!title' => $vars['title'])), 'warning');
    $vars['unpublished'] = '<span class="unpublished">'. t('Unpublished') .'</span>';
 }
}


/**
 * Override or insert variables into block templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */
function adaptivetheme_preprocess_block(&$vars, $hook) {
  $block = $vars['block'];
  
  // Set the block id.
  $vars['block_id'] = 'block-'. $block->module .'-'. $block->delta;

  // Special classes for blocks, emulate Drupal 7.
  // Set up variables for navigation-like blocks.
  $n1 = array('user-1', 'statistics-0');
  $n2 = array('menu', 'book', 'forum', 'blog', 'aggregator', 'comment');
  $h1 = $block->module .'-'. $block->delta;
  $h2 = $block->module;

  // Special classes for blocks
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
  //$classes[] = $vars['block_zebra'];        // odd, even zebra class
  //$classes[] = 'block-'. $block->region;    // block-[region] class
  //$classes[] = 'block-count-'. $vars['id']; // block-count-[count] class

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
  // Add support for Skinr module classes http://drupal.org/project/skinr
  if (function_exists('block_skinr_data')) {
    $classes[] = $vars['skinr'];
  }
  if (theme_get_setting('block_edit_links') && user_access('administer blocks')) {
    $classes[] = 'block-edit-links';
  }
  
  $vars['classes'] = implode(' ', $classes);
}
