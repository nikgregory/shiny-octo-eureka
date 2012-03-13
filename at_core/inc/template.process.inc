<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 *
 * If you need to add or modify process functions do it in your sub-theme.
 */

/**
 * Process variables for html.tpl.php
 */
function adaptivetheme_process_html(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;
  // Load the panels test, more debugging tools...
  if (theme_get_setting('load_all_panels', $theme_name) === 1) {
    if (drupal_is_front_page()) {
       $panels_test = file_get_contents(drupal_get_path('theme', 'adaptivetheme') . '/layouts/panels_test.html');
       $vars['page'] = $panels_test;
    }
  }
}

/**
 * Process variables for the html tag
 */
function adaptivetheme_process_html_tag(&$vars) {
  $tag = &$vars['element'];
  if ($tag['#tag'] === 'style' || $tag['#tag'] === 'script') {
    // Remove redundant type attribute and CDATA comments.
    unset($tag['#attributes']['type'], $tag['#value_prefix'], $tag['#value_suffix']);

    // Remove media="all" but leave others unaffected.
    if (isset($tag['#attributes']['media']) && $tag['#attributes']['media'] === 'all') {
      unset($tag['#attributes']['media']);
    }
  }
}

/**
 * Process variables for page.tpl.php
 */
function adaptivetheme_process_page(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  // Call our helper function to wrap menus in extra markup
  if (!empty($vars['primary_navigation'])) {
    $vars['primary_navigation'] = _theme_menu_variables($vars['primary_navigation'], 'primary');
  }
  if (!empty($vars['secondary_navigation'])) {
    $vars['secondary_navigation'] = _theme_menu_variables($vars['secondary_navigation'], 'secondary');
  }
  
  // Generate the wrapper element for the main content
  $vars['tag'] = $vars['title'] ? 'section' : 'div';
  
  // Force full width wrapper template suggestion if enabled
  if (theme_get_setting('page_full_width_wrappers', $theme_name) === 1) {
    array_unshift($vars['theme_hook_suggestions'], 'page__full_width_wrappers');
  }
}

/**
 * Process variables for region.tpl.php
 */
function adaptivetheme_process_region(&$vars) {
  // Initialize and populate the outer wrapper variables
  $vars['outer_prefix'] = '<div class="' . $vars['classes'] . '">';
  $vars['outer_suffix'] = '</div>';

  // Initialize and populate the inner wrapper variables
  $vars['inner_prefix'] = '<div class="region-inner clearfix">';
  $vars['inner_suffix'] = '</div>';

  // Some regions need different or no markup
  // Use a region template with no wrappers for the main content
  if ($vars['region'] === 'content') {
    $vars['outer_prefix'] = '';
    $vars['outer_suffix'] = '';
    $vars['inner_prefix'] = '';
    $vars['inner_suffix'] = '';
  }
  // Menu bar needs an id, nav class and no inner wrapper
  if ($vars['region'] === 'menu_bar') {
    $vars['outer_prefix'] = '<div id="menu-bar" class="nav clearfix">';
    $vars['inner_prefix'] = '';
    $vars['inner_suffix'] = '';
  }
}

/**
 * Process variables for block.tpl.php
 */
function adaptivetheme_process_block(&$vars) {
  // Generate the wrapper element, if there's a title use section
  $vars['block']->subject ? $vars['tag'] = 'section' : $vars['tag'] = 'div';

  // Search is never a section, its just a div
  if ($vars['block_html_id'] === 'block-search-form') {
    $vars['tag'] = 'div';
  }

  // Use a $title variable instead of $block->subject
  $vars['title'] = $vars['block']->subject;

  // Wrap the content variable in a div with attributes
  $vars['content_processed'] = '<div' . $vars['content_attributes'] . '>' . $vars['content'] . '</div>';

  // Initialize and populate the inner wrapper variables
  $vars['inner_prefix'] = '<div class="block-inner clearfix">';
  $vars['inner_suffix'] = '</div>';

  // Use nav element for menu blocks and provide a suggestion for all of them
  $nav_blocks = array('navigation', 'main-menu', 'management', 'user-menu');
  if (in_array($vars['block']->delta, $nav_blocks)) {
    $vars['tag'] = 'nav';
    array_unshift($vars['theme_hook_suggestions'], 'block__menu');
  }
  $nav_modules = array('superfish', 'nice_menus');
  if (in_array($vars['block']->module, $nav_modules)) {
    $vars['tag'] = 'nav';
    array_unshift($vars['theme_hook_suggestions'], 'block__menu');
  }

  // The menu bar region gets special treatment for the block template
  if ($vars['block']->region === 'menu_bar') {
    // They are always menu blocks, right?
    $vars['tag'] = 'nav';
    $vars['content_processed'] = $vars['content'];
  }

  // Now we know all the block $tag's, we can generate our wrapper
  $vars['outer_prefix'] = '<' . $vars['tag'] . ' id="' . $vars['block_html_id'] . '" class="' . $vars['classes'] . '" ' . $vars['attributes'] . '>';
  $vars['outer_suffix'] = '</' . $vars['tag'] . '>';

  // Wait, some blocks look like shit with wrappers, blow them away...
  if ($vars['block_html_id'] === 'block-system-main') {
    $vars['outer_prefix'] = '';
    $vars['outer_suffix'] = '';
    $vars['inner_prefix'] = '';
    $vars['inner_suffix'] = '';
    $vars['content_processed'] = $vars['content'];
  }
  if ($vars['block']->module === 'panels_mini') {
    $vars['inner_prefix'] = '';
    $vars['inner_suffix'] = '';
  }

  // Provide additional suggestions so the block__menu suggestion can be overridden easily
  $vars['theme_hook_suggestions'][] = 'block__' . $vars['block']->region . '__' . $vars['block']->module;
  $vars['theme_hook_suggestions'][] = 'block__' . $vars['block']->region . '__' . $vars['block']->delta;
}

/**
 * Process variables for node.tpl.php
 */
function adaptivetheme_process_node(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;
  // Strip default drupal classes if not required
  if (theme_get_setting('extra_article_classes', $theme_name) === 0) {
    $classes = explode(' ', $vars['classes']);
    if (in_array('node-sticky', $classes)) {
      $classes = str_replace('node-sticky', '', $classes);
    }
    if (in_array('node-promoted', $classes)) {
      $classes = str_replace('node-promoted', '', $classes);
    }
    if (in_array('node-teaser', $classes)) {
      $classes = str_replace('node-teaser', '', $classes);
    }
    if (in_array('node-preview', $classes)) {
      $classes = str_replace('node-preview', '', $classes);
    }
    $vars['classes'] = trim(implode(' ', $classes));
  }
}

/**
 * Process variables for comment.tpl.php
 */
function adaptivetheme_process_comment(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;
  // Strip default drupal classes if not required
  if (theme_get_setting('extra_comment_classes', $theme_name) === 0) {
    $classes = explode(' ', $vars['classes']);
    if (in_array('comment-by-anonymous', $classes)) {
      $classes = str_replace('comment-by-anonymous', '', $classes);
    }
    if (in_array('comment-by-node-author', $classes)) {
      $classes = str_replace('comment-by-node-author', '', $classes);
    }
    if (in_array('comment-by-viewer', $classes)) {
      $classes = str_replace('comment-by-viewer', '', $classes);
    }
    if (in_array('comment-new', $classes)) {
      $classes = str_replace('comment-new', '', $classes);
    }
    $vars['classes'] = trim(implode(' ', $classes));
  }
}
