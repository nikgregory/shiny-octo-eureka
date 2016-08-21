<?php

/**
 * Implements hook_theme().
 */
function at_core_theme() {
  $path = drupal_get_path('theme', 'at_core');
  return array(
    // Define the row template/hook.
    'row' => array(
      'variables' => array(
        'regions' => NULL
      ),
      'template' => 'row',
      'path' => $path . '/templates/layout',
    ),
    // Ensure suggestions are picked up in the base theme first.
    'row__header' => array(
      'base hook' => 'row',
      'path' => $path . '/templates/layout',
    ),
    'row__footer' => array(
      'base hook' => 'row',
      'path' => $path . '/templates/layout',
    ),
    // Template suggestion for the breadcrumb page title placeholder.
    'page_title__breadcrumb' => array(
      'base hook' => 'page_title',
      'path' => $path . '/templates/content',
    ),
  );
}
