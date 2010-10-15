<?php
// $Id$

/**
 * Returns HTML for a breadcrumb trail.
 */
function adaptivetheme_breadcrumb($vars) {
  $breadcrumb = $vars['breadcrumb'];
  $show_breadcrumb = theme_get_setting('breadcrumb_display');
  if ($show_breadcrumb == 'yes') {
    $show_breadcrumb_home = theme_get_setting('breadcrumb_home');
    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }
    if (!empty($breadcrumb)) {
      $heading = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
      $separator = filter_xss(theme_get_setting('breadcrumb_separator'));
      $output = '';
      foreach ($breadcrumb as $key => $val) {
        if ($key == 0) {
          $output .= '<li class="crumb">' . $val . '</li>';
        }
        else {
          $output .= '<li class="crumb"><span>' . $separator . '</span>' . $val . '</li>';
        }
      }
      return $heading . '<ol id="crumbs">' . $output . '</ol>';
    }
  }
  return '';
}

/**
 * Theme a form button.
 */
function adaptivetheme_button($vars) {
  $element = $vars['element'];
  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  return '<span class="button '. $element['#id'] .'"><input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." /></span>\n";
}

/**
 * Returns HTML for a list or nested list of items.
 */
function adaptivetheme_item_list($vars) {
  $items = $vars['items'];
  $title = $vars['title'];
  $type = $vars['type'];
  $attributes = $vars['attributes'];
  $output = '<div class="item-list">';
  if (isset($title)) {
    $output .= '<h3>' . $title . '</h3>';
  }
  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if (theme_get_setting('extra_item_list_classes')) {
        if ($i & 1) {
          $attributes['class'] = empty($attributes['class']) ? 'odd' : ($attributes['class'] .' odd');
        }
        else {
          $attributes['class'] = empty($attributes['class']) ? 'even' : ($attributes['class'] .' even');
        }
      }
      if (theme_get_setting('extra_item_list_classes')) {
        if ($i == 0) {
          $attributes['class'] = empty($attributes['class']) ? 'first' : ($attributes['class'] .' first');
        }
        if ($i == $num_items - 1) {
          $attributes['class'] = empty($attributes['class']) ? 'last' : ($attributes['class'] .' last');
        }
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}

if (!function_exists('dhtml_menu_init')) {

  // theme_menu_tree
  function adaptivetheme_menu_tree($vars) {
    return '<ul class="menu clearfix">' . $vars['tree'] . '</ul>';
  }

  // theme_menu_link
  function adaptivetheme_menu_link(array $vars) {
    $element = $vars['element'];
    $sub_menu = '';
    if ($element['#below']) {
      $sub_menu = drupal_render($element['#below']);
    }
    if (theme_get_setting('extra_menu_classes')) {
      $element['#attributes']['class'][] = drupal_html_class($element['#title']);
    }
    if (theme_get_setting('menu_item_span_elements')) {
      $element['#title'] = '<span>' . $element['#title'] . '</span>';
      $element['#localized_options']['html'] = TRUE;
    }
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    if (theme_get_setting('extra_menu_classes') == 0) {
      return '<li>' . $output . $sub_menu . "</li>";
    }
    else {
      return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>";
    }
  }

}
