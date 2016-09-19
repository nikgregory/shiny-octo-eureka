<?php

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_page_alter(array &$suggestions, array $variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Add content type suggestions, e.g. page--node--article.html.twig.
  if ($node = \Drupal::routeMatch()->getParameter('node')) {
    if (is_object($node)) {
      array_splice($suggestions, 1, 0, 'page__node__' . $node->getType());
    }
    // If no page--front template is set, inherit the page--node-[id] template
    // when a node/nid is set as the homepage.
    if (\Drupal::service('path.matcher')->isFrontPage() == TRUE) {
      array_splice($suggestions, 2, 0, 'page__node__' . $node->id());
    }
  }

  // Show template suggestions.
  if (\Drupal::currentUser()->hasPermission('administer themes')) {
    if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
      if (isset($config['enable_devel']) && $config['enable_devel'] === 1) {
        if (isset($config['show_page_suggestions']) && $config['show_page_suggestions'] === 1) {
          $formatted_suggestions = array();
          if ($suggestions) {
            foreach ($suggestions as $suggestion_name) {
              if ($suggestion_name === 'page__') {
                $suggestion_name = str_replace('_', '', $suggestion_name);
              }
              $formatted_suggestions[] = str_replace('_', '-', $suggestion_name);
            }
            $these_suggestions = array('#theme' => 'item_list', '#items' => $formatted_suggestions,);
            drupal_set_message(t('Template suggestions for this page:<br>@these_suggestions <p>To stop showing suggestions switch them off in your themes Appearance settings > Developer tools.</p>', array('@these_suggestions' => \Drupal::service('renderer')->renderPlain($these_suggestions))), 'status');
          }
        }
      }
    }
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter() for row templates.
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_row_alter(array &$suggestions, array $variables) {
  if (isset($variables['regions']['#row'])) {
    $row = $variables['regions']['#row'];
    $suggestions[] = 'row__' . $row;

    // Add content type suggestions, e.g. page--node--article.html.twig.
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if (is_object($node)) {
        array_splice($suggestions, 1, 0, 'row__' . $row  . '__node_' . $node->getType());
      }
      // Row front.
      if (\Drupal::service('path.matcher')->isFrontPage() == TRUE) {
        array_splice($suggestions, 2, 0, 'row__' . $row . '__front');
      }
    }
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter() for field templates.
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_field_alter(array &$suggestions, array $variables) {
  // Add the entity reference type as a field template suggestion.
  if (isset($variables['element']['#items']) && is_object($variables['element']['#items'])) {
    $target_type = $variables['element']['#items']->getSetting('target_type') ?: NULL;
    if ($target_type !== NULL) {
      array_splice($suggestions, 1, 0, 'field__entity_reference_type__' . $target_type);
    }
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter() for form templates.
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_block_alter(array &$suggestions, array $variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Block suggestions for custom block bundles.
  if (isset($variables['elements']['content']['#block_content'])) {
    array_splice($suggestions, 1, 0, 'block__bundle__' . $variables['elements']['content']['#block_content']->bundle());
  }

  // Use a custom block suggestion for responsive menus.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_responsive_menus']) && $config['enable_responsive_menus'] === 1) {
      if (isset($config['responsive_menu_block'])) {
        if (isset($variables['elements']['#id']) && $variables['elements']['#id'] === $config['responsive_menu_block']) {
          $suggestions[] = 'block__responsive_menu';
        }
      }
    }
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 * TODO: keep on on https://drupal.org/node/2247677
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_user_alter(array &$suggestions, array $variables) {
  $suggestions[] = 'user__' . $variables['elements']['#view_mode'];
}

/**
 * Implements hook_theme_suggestions_HOOK_alter() for form templates.
 * @param array $suggestions
 * @param array $variables
 */
function at_core_theme_suggestions_form_alter(array &$suggestions, array $variables) {
  if ($variables['element']['#form_id'] === 'search_block_form') {
    $suggestions[] = 'form__search_block_form';
  }
}
