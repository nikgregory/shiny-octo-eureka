<?php

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;

/**
 * Because I'm such a lazy kint().
 * @param $var
 */
function kt($var) {
  if (function_exists('kint')) {
    return kint($var);
  }
}

/**
 * Clean away twig debugging output, it breaks some stuff.
 * @param $variable
 * @return mixed
 */
function _at_core_cleanup_twig_debug_output($variable) {
  $output = preg_replace('/[ \t]+/', ' ', preg_replace('/<!--(.|\s)*?-->/', '', $variable));
  return preg_replace(array('/\r/', '/\n/'), '', $output);
}

/**
 * Return values for the breadcrumb title placeholder.
 * @param $page_title
 * @return array
 */
function _at_core_breadcrumb_title_placeholder($page_title) {
  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
  $array = array(
    '#theme' => 'page_title__breadcrumb',
    '#title' => $title,
  );

  return $array;
}

/**
 * Helper function for setting classes on menu links.
 * @param $i
 * @return mixed
 */
function _at_core_menu_link_classes($i) {
  if (isset($i['url'])) {
    $attr = $i['url']->getOption('attributes') ?: [];
    $ac = isset($attr['class']) ? implode($attr['class']) : '';
    $mlc = array('class' => array('menu__link', $ac));
    return $i['url']->setOption('attributes', array_merge_recursive($attr, $mlc));
  }
}

/**
 * Helper function for layout plugin attributes.
 * @param $variables
 * @return mixed
 */
function _at_core_layout_plugin_attributes($variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');
  $html_element = 'div';
  $classes = array();
  $role = NULL;
  $bundle = NULL;
  $entity_type = NULL;
  $view_mode = NULL;
  $id = NULL;;

  // Entity and bundle.
  if (isset($variables['content']['#entity_type'])) {
    $entity_type = $variables['content']['#entity_type'];
    $bundle = $variables['content']['#bundle'];
    $view_mode = $variables['content']['#view_mode'];
    $id = $variables['content']['#'. $entity_type]->id();

    $entity_classes = !empty($variables['content']['#ds_configuration']['layout']['entity_classes']) ? $variables['content']['#ds_configuration']['layout']['entity_classes'] : '';
    if ($entity_classes !== 'no_classes') {
      $classes[] = $entity_type;
      $classes[] = $view_mode ? Html::cleanCssIdentifier($entity_type . '--view-mode-' . $view_mode) : '';
      $classes[] = $bundle ? Html::cleanCssIdentifier($entity_type . '--type-' . $bundle) : '';
      $classes[] = $id ? Html::cleanCssIdentifier($entity_type . '--id-' . $id) : '';
    }

    if (in_array($entity_type, ['node', 'comment'])) {
      $html_element = 'article';
      $role = 'article';
    }

    if ($entity_type == 'comment') {
      $classes[] = 'js-comment';
      $classes[] = $variables['content']['#comment']->getStatus() != 1 ? 'unpublished' : '';
      if ($variables['content']['#comment']->getOwnerId() === $variables['content']['#comment']->getCommentedEntity()->getOwnerId()) {
        $classes[] = 'by-' . $variables['content']['#comment']->getCommentedEntity()->getEntityTypeId() . '-author';
      }
    }
  }

  // Panels
  if (isset($variables['content']['#page_variant'])) {
    $classes[] = 'panels-page';
    $classes[] = 'panels-page--' . Html::cleanCssIdentifier($variables['content']['#page_variant']->get('page'));
  }

  // Shortcodes for blocks.
  if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1 && isset($variables['content']['#block_content'])) {
    if ($bid = $variables['content']['#block_content']->id()) {
      if (!empty($config['block_classes_' . $bid])) {
        $shortcodes = Tags::explode($config['block_classes_' . $bid]);
        foreach ($shortcodes as $class) {
          $classes[] = Html::cleanCssIdentifier($class);
        }
      }
    }
  }

  // Global classes
  $classes[] = 'atl';
  $classes[] = 'atl--' . Html::cleanCssIdentifier($variables['layout']['id']);

  $attributes['classes'] = $classes;
  $attributes['html_element'] = $html_element;
  if (!empty($role)) {
    $attributes['role'] = $role;
  }

  return $attributes;
}
