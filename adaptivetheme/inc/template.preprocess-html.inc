<?php
// $Id$

// Unset body classes by default, modules can still add them later.
unset($vars['classes']);

// Section class - use the path alias otherwise all nodes will be in "section-node".
if (theme_get_setting('cleanup_classes_section')) {
  $path_alias = drupal_get_path_alias($_GET['q']);
  if (!$vars['is_front']) {
    list($section, ) = explode('/', $path_alias, 2);
    $vars['classes_array'][] = drupal_html_class('section-' . $section);
  }
}

/**
 * Compile a list of classes that are going to be applied to the body element.
 * This allows advanced theming based on context (home page, node of certain type, etc.).
 */
// Add information about the number of sidebars.
if (!empty($vars['page']['sidebar_first']) && !empty($vars['page']['sidebar_second'])) {
  $vars['classes_array'][] = 'two-sidebars';
}
elseif (!empty($vars['page']['sidebar_first'])) {
  $vars['classes_array'][] = 'one-sidebar sidebar-first';
}
elseif (!empty($vars['page']['sidebar_second'])) {
  $vars['classes_array'][] = 'one-sidebar sidebar-second';
}
else {
  $vars['classes_array'][] = 'no-sidebars';
}

// Rebuild the classes conditionally depending on the theme settings.
//Add a class that tells us whether we're on the front page or not.
if (theme_get_setting('cleanup_classes_front')) {
  $vars['classes_array'][] = $vars['is_front'] ? 'front' : 'not-front';
}
// Add a class that tells us whether the page is viewed by an authenticated user or not.
if (theme_get_setting('cleanup_classes_user_status')) {
  $vars['classes_array'][] = $vars['logged_in'] ? 'logged-in' : 'not-logged-in';
}

// Add classes for node types
if (theme_get_setting('cleanup_classes_node_type')) {
  if ($suggestions = theme_get_suggestions(arg(), 'page', '-')) {
    foreach ($suggestions as $suggestion) {
      if ($suggestion != 'page-front') {
        // Add current suggestion to page classes to make it possible to theme
        // the page depending on the current page type (e.g. node, admin, user,
        // etc.) as well as more specific data like node-12 or node-edit.
        $vars['classes_array'][] = drupal_html_class($suggestion);
      }
    }
  }

  // If on an individual node page, add the node type to body classes.
  if ($node = menu_get_object()) {
    $vars['classes_array'][] = drupal_html_class('page-type-' . $node->type);
  }
}

if (!$vars['is_front']) {
  // Replacement for Drupals page-arg(0) class, instead we print page-$normal_page.
  if (theme_get_setting('cleanup_classes_normal_path')) {
    $normal_path = drupal_get_normal_path($_GET['q']);
    $vars['classes_array'][] = drupal_html_class('page-'. $normal_path);
  }
  // Add extra classes for node add, edit and delete pages.
  if (theme_get_setting('cleanup_classes_add_edit_delete')) {
    if (arg(0) == 'node') {
      if (arg(1) == 'add') {
        $vars['classes_array'][] = 'article-add'; // Add .node-add class.
      }
      elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
        $vars['classes_array'][] = 'article-'. arg(2);
      }
    }
  }
}

// Language specific body class.
/*
if (theme_get_setting('cleanup_classes_language')) {
  if (function_exists('locale')) {
    $classes[] = 'lang-'. $language->language;
  }
}
*/
