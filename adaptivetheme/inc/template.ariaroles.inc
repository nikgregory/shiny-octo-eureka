<?php
/**
 * Implements hook_preprocess_block().
 */
function adaptivetheme_preprocess_block(&$variables) {
  if ($variables['block']->module == 'aggregator') {
    $variables['attributes_array']['role'] = 'complementary';
  }
  if ($variables['block']->module == 'blog') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']-> module == 'book') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']->module == 'comment') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']->module == 'forum') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']->module == 'help') {
    $variables['attributes_array']['role'] = 'complementary';
  }
  if ($variables['block']->module == 'locale') {
    $variables['attributes_array']['role'] = 'complementary';
  }
  if ($variables['block']->module == 'menu') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']->module == 'poll') {
    $variables['attributes_array']['role'] = 'complementary';
  }
  if ($variables['block']->module == 'profile') {
    $variables['attributes_array']['role'] = 'complementary';
  }
  if ($variables['block']->module == 'search') {
    $variables['attributes_array']['role'] = 'search';
  }
  if ($variables['block']->module == 'shortcut') {
    $variables['attributes_array']['role'] = 'navigation';
  }
  if ($variables['block']->module == 'statistics') {
    $variables['attributes_array']['role'] = 'navigation';
  }

  // System
  if ($variables['block']->module == 'system') {
    switch ($variables['block']->delta) {
      case 'main':
        $variables['attributes_array']['role'] = 'main';
        break;

      case 'powered-by':
        $variables['attributes_array']['role'] = 'complementary';
        break;

      case 'help':
        $variables['attributes_array']['role'] = 'complementary';
        break;

      // System menu blocks should get the same class as menu module blocks.
      default:
      if (in_array($variables['block']->delta, array_keys(menu_list_system_menus()))) {
        $variables['attributes_array']['role'] = 'navigation';
        $variables['classes_array'][] = 'block-menu';
      }
    }
  }
  
  // Node
  if ($variables['block']->module == 'node') {
    switch ($variables['block']->delta) {
      case 'syndicate':
        $variables['attributes_array']['role'] = 'complementary';
        break;

      case 'recent':
        $variables['attributes_array']['role'] = 'navigation';
        break;
    }
  }

}
