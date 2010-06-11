<?php // $Id$
// adaptivethemes.com

/**
 * @file template.theme-overrides.inc
 */

/**
 * Returns HTML for a breadcrumb trail.
 *
 * @param $variables
 *   An associative array containing:
 *   - breadcrumb: An array containing the breadcrumb links.
 */
/* TODO: waiting on http://drupal.org/node/576290
function adaptivetheme_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  // Determine if we are to display the breadcrumb.
  $show_breadcrumb = theme_get_setting('breadcrumb_display');
  if ($show_breadcrumb == 'yes' || $show_breadcrumb == 'admin' && arg(0) == 'admin') {

    // Optionally get rid of the homepage link.
    $show_breadcrumb_home = theme_get_setting('breadcrumb_home');
    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }

    // Return the breadcrumb with separators.
    if (!empty($breadcrumb)) {
      $breadcrumb_separator = filter_xss(theme_get_setting('breadcrumb_separator'));
      $trailing_separator = $title = '';
      if (theme_get_setting('breadcrumb_title')) {
        $trailing_separator = $breadcrumb_separator;
        $title = drupal_get_title();
      }
      elseif (theme_get_setting('breadcrumb_trailing')) {
        $trailing_separator = $breadcrumb_separator;
      }
      return implode($breadcrumb_separator, $breadcrumb) . $trailing_separator . $title;
    }
  }
  // Otherwise, return an empty string.
  return '';
}
*/

/**
 * Theme a form button.
 */
function adaptivetheme_button($variables) {
  $element = $variables['element'];
  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  // Wrap visible inputs with span tags for button graphics
  return '<span class="button '. $element['#id'] .'"><input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." /></span>\n";
}

/**
 * Returns HTML for a list or nested list of items.
 *
 * @param $variables
 *   An associative array containing:
 *   - items: An array of items to be displayed in the list. If an item is a
 *     string, then it is used as is. If an item is an array, then the "data"
 *     element of the array is used as the contents of the list item. If an item
 *     is an array with a "children" element, those children are displayed in a
 *     nested list. All other elements are treated as attributes of the list
 *     item element.
 *   - title: The title of the list.
 *   - type: The type of list to return (e.g. "ul", "ol").
 *   - attributes: The attributes applied to the list element.
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
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if (theme_get_setting('cleanup_item_list_zebra')) {
        if ($i & 1) {
          $attributes['class'] = empty($attributes['class']) ? 'odd' : ($attributes['class'] .' odd');
        }
        else {
          $attributes['class'] = empty($attributes['class']) ? 'even' : ($attributes['class'] .' even');
        }
      }
      if (theme_get_setting('cleanup_item_list_first_last')) {
        if ($i == 0) {
          $attributes['class'] = empty($attributes['class']) ? 'first' : ($attributes['class'] .' first');
        }
        if ($i == $num_items - 1) {
          $attributes['class'] = empty($attributes['class']) ? 'last' : ($attributes['class'] .' last');
        }
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}

// If DHTML module is installed dont override theme_menu_tree and theme_menu_link.
if (!function_exists('dhtml_menu_init')) {
  /**
   * Generate the HTML output for a menu tree
   */
  function adaptivetheme_menu_tree($variables) {
    if (theme_get_setting('cleanup_menu_menu_class')) {
      return '<ul class="menu">' . $variables['tree'] . '</ul>';
    }
    else{
      return '<ul>' . $variables['tree'] . '</ul>';
    }
  }

  function adaptivetheme_menu_link(array $variables) {
    $element = $variables['element'];
    $sub_menu = '';

    if ($element['#below']) {
      $sub_menu = drupal_render($element['#below']);
    }

    // add a class based on the anchor text for granular theming.
    if (theme_get_setting('cleanup_menu_title_class')) {
      $element['#attributes']['class'][] = drupal_html_class($element['#title']);
    }

    // wrap <span>'s around the anchor text
    if (theme_get_setting('theme_menu_link_spans')) {
      $element['#title'] = '<span>' . $element['#title'] . '</span>';
      $element['#localized_options']['html'] = TRUE;
    }

    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    if (theme_get_setting('cleanup_menu_link_classes') == 0) {
      return '<li>' . $output . $sub_menu . "</li>\n";
    }
    else {
      return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
    }
  }

} // end DHTML menu check.

// Main menu links
function adaptivetheme_links__system_main_menu($variables) {
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {

      if (theme_get_setting('cleanup_main_mlid_class')) {
        $class = array($key);
      }

      // Add first, last and active classes to the list of links to help out themers.
      if (theme_get_setting('cleanup_main_firstlast_classes')) {
        if ($i == 1) {
          $class[] = 'first';
        }
        if ($i == $num_links) {
          $class[] = 'last';
        }
      }
      if (theme_get_setting('cleanup_main_active_classes')) {
        if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
            && (empty($link['language']) || $link['language']->language == $language_url->language)) {
          $class[] = 'active';
        }
      }
      if (theme_get_setting('cleanup_main_mlid_class') || theme_get_setting('cleanup_main_firstlast_classes') || theme_get_setting('cleanup_main_active_classes')) {
        $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';
      }
      else {
        $output .= '<li>';
      }

      if (isset($link['href'])) {
        // wrap <span>'s around the anchor text
        if (theme_get_setting('theme_main_menu_spans')) {
          $link['title'] = '<span>' . $link['title'] . '</span>';
          $link['html'] = TRUE;
        }
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

// Secondary menu links
function adaptivetheme_links__system_secondary_menu($variables) {
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {

      if (theme_get_setting('cleanup_sec_mlid_class')) {
        $class = array($key);
      }

      // Add first, last and active classes to the list of links to help out themers.
      if (theme_get_setting('cleanup_sec_firstlast_classes')) {
        if ($i == 1) {
          $class[] = 'first';
        }
        if ($i == $num_links) {
          $class[] = 'last';
        }
      }
      if (theme_get_setting('cleanup_sec_active_classes')) {
        if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
            && (empty($link['language']) || $link['language']->language == $language_url->language)) {
          $class[] = 'active';
        }
      }
      if (theme_get_setting('cleanup_main_sec_class') || theme_get_setting('cleanup_sec_firstlast_classes') || theme_get_setting('cleanup_sec_active_classes')) {
        $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';
      }
      else {
        $output .= '<li>';
      }

      if (isset($link['href'])) {
        // wrap <span>'s around the anchor text
        if (theme_get_setting('theme_sec_menu_spans')) {
          $link['title'] = '<span>' . $link['title'] . '</span>';
          $link['html'] = TRUE;
        }
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}







