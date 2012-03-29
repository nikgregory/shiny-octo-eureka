<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 *
 * If you need to add or modify theme functions do it in your sub-theme.
 */

/**
 * Implements hook_theme().
 */
function adaptivetheme_theme($existing, $type, $theme, $path) {
  return array(
    'menubar' => array(
      'render element' => 'element',
    ),
  );
}

/**
 * Adativetheme implementation to display a menubar.
 *
 * The contents is normally one of Drupals magic menu variables, 
 * such as the Main menu or User menu (secondary menu), but could
 * be any menu you wish to wrap in navigation menu type markup
 * and classes.
 *
 * Available variables:
 * - $menubar_id: CSS id for theming the menubar
 * - $menu: Holds the themed menu (normally using theme_links())
 * - the usual $classes, $attributes, $content attributes etc
 *
 * @see adaptivetheme_preprocess_menubar()
 * @see adaptivetheme_process_menubar()
 */
function adaptivetheme_menubar($vars) {
  $output = '';

  $output .= '<div id="' . $vars['menubar_id'] . '" class="' . $vars['classes'] . '"' . $vars['attributes'] . '>';
  $output .= '<nav ' . $vars['content_attributes'] . '>';
  $output .= $vars['menu'];
  $output .= '</nav></div>';

  return $output;
}

/**
 * Returns HTML for a breadcrumb trail.
 */
function adaptivetheme_breadcrumb($vars) {
  global $theme_name;

  $breadcrumb = $vars['breadcrumb'];
  $show_breadcrumb = theme_get_setting('breadcrumb_display', $theme_name);

  if ($show_breadcrumb == 'yes') {
    $show_breadcrumb_home = theme_get_setting('breadcrumb_home', $theme_name);

    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }

    if (!empty($breadcrumb)) {
      $heading = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
      $separator = filter_xss_admin(theme_get_setting('breadcrumb_separator', $theme_name));

      $class = 'crumb';
      end($breadcrumb);
      $last = key($breadcrumb);

      $output = '';
      foreach ($breadcrumb as $key => $val) {
        if ($key == $last) {
          $class = 'crumb crumb-last';
        }
        if ($key == 0) {
          $output .= '<li class="' . $class . ' crumb-first">' . $val . '</li>';
        }
        else {
          $output .= '<li class="' . $class . '"><span>' . $separator . '</span>' . $val . '</li>';
        }
      }
      return '<div id="breadcrumb" class="clearfix"><nav class="breadcrumb-wrapper clearfix" role="navigation">' . $heading . '<ol id="crumbs" class="clearfix">' . $output . '</ol></nav></div>';
    }
  }

  return '';
}

/**
 * Returns HTML for status and/or error messages, grouped by type.
 */
function adaptivetheme_status_messages($vars) {
  $display = $vars['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div id=\"messages\"><div class=\"messages $type\">";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>";
    }
    if (count($messages) > 1) {
      $output .= " <ul>";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>";
      }
      $output .= " </ul>";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div></div>";
  }
  return $output;
}


/**
 * Returns HTML for a list or nested list of items.
 */
function adaptivetheme_item_list($vars) {
  global $theme_name;
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

      if (theme_get_setting('extra_item_list_classes', $theme_name) === 1) {
        if ($i & 1) {
          $attributes['class'][] = 'odd';
        }
        else {
          $attributes['class'][] = 'even';
        }
        if ($i == 0) {
          $attributes['class'][] = 'first';
        }
        if ($i == $num_items - 1) {
          $attributes['class'][] = 'last';
        }
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>"; // no new line!
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
    global $theme_name;

    $element = $vars['element'];
    $sub_menu = '';

    if ($element['#below']) {
      $sub_menu = drupal_render($element['#below']);
    }

    if (theme_get_setting('extra_menu_classes', $theme_name) === 1 && !empty($element['#original_link'])) {
      if (!empty($element['#original_link']['depth'])) {
        $element['#attributes']['class'][] = 'menu-depth-' . $element['#original_link']['depth'];
      }
      if (!empty($element['#original_link']['mlid'])) {
        $element['#attributes']['class'][] = 'menu-item-' . $element['#original_link']['mlid'];
      }
    }

    if (theme_get_setting('menu_item_span_elements', $theme_name) === 1 && !empty($element['#title'])) {
      $element['#title'] = '<span>' . $element['#title'] . '</span>';
      $element['#localized_options']['html'] = TRUE;
    }

    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>";
  }

}

// theme links
function adaptivetheme_links($vars) {
  global $language_url, $theme_name;

  $links = $vars['links'];
  $attributes = $vars['attributes'];
  $heading = $vars['heading'];

  $output = '';
  if (count($links) > 0) {
    $output = '';
    if (!empty($heading)) {
      if (is_string($heading)) {
        $heading = array(
          'text' => $heading,
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . filter_xss_admin($heading['text']) . '</' . $heading['level'] . '>'; // Core uses check_plain(), I think this is over the top, basic markup is OK
    }
    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {

      if (theme_get_setting('extra_menu_classes', $theme_name) === 1) {
        $class = array($key);
        if ($i == 1) {
          $class[] = 'first';
        }
        if ($i == $num_links) {
          $class[] = 'last';
        }
        if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
            && (empty($link['language']) || $link['language']->language == $language_url->language)) {
          $class[] = 'active';
        }
        $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';
      }

      else {
        $output .= '<li>';
      }
      if (isset($link['href'])) {
        if (theme_get_setting('menu_item_span_elements', $theme_name) === 1) {
          $link['title'] = '<span>' . $link['title'] . '</span>';
          $link['html'] = TRUE;
        }
        $output .= l($link['title'], $link['href'], $link);
      }

      elseif (!empty($link['title'])) {
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
      $output .= "</li>";
    }
    $output .= '</ul>';
  }

  return $output;
}

/**
 * Modify the output of theme_field to use H2 for field lables.
 * There is a mirror image of this code as a template file in the
 * templates directory, see field.tpl.php.txt (example file to use
 * for template suggestions).
 */
function adaptivetheme_field($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<h2 class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</h2>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $vars['content_attributes'] . '>';
  foreach ($vars['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level wrapper element.
  $tag = $vars['label_hidden'] ? 'div' : 'section';
  $output = "<$tag class=\"" . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . "</$tag>";

  return $output;
}

// Taxonomy Fields
// Output taxonomy term fields as unordered lists.
function adaptivetheme_field__taxonomy_term_reference($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<h2 class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</h2>';
  }

  // Render the items.
  $output .= '<ul class="field-items"' . $vars['content_attributes'] . '>';
  foreach ($vars['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<li class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }

  $output .= '</ul>';
  // Render the top-level wrapper element.
  $tag = $vars['label_hidden'] ? 'div' : 'section';
  $output = "<$tag class=\"" . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . "</$tag>";

  return $output;
}

// Image Fields
// Output image fields as figure with figcaption for captioning.
function adaptivetheme_field__image($vars) {
  global $theme_name;

  // Check if image settings are enabled, otherwise captions might print unexpectedly
  $image_settings_enabled = theme_get_setting('enable_image_settings', $theme_name) === 1 ? TRUE : FALSE;
  $caption_full = theme_get_setting('image_caption_full', $theme_name) === 1 ? TRUE : FALSE;
  $caption_teaser = theme_get_setting('image_caption_teaser', $theme_name) === 1 ? TRUE : FALSE;

  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<h2 class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</h2>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $vars['content_attributes'] . '>';

  foreach ($vars['items'] as $delta => $item) {

    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<figure class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>';
    $output .= drupal_render($item);

    if (isset($item['#item']['title'])) {
      if ($vars['field_view_mode'] == 'full') {
        if ($caption_full == TRUE && $image_settings_enabled == TRUE) {
          $output .= '<figcaption class="caption full-caption">' . $item['#item']['title'] . '</figcaption>';
        }
      }
      if ($vars['field_view_mode'] == 'teaser') {
        if ($caption_teaser == TRUE && $image_settings_enabled == TRUE) {
          $output .= '<figcaption class="caption teaser-caption">' . $item['#item']['title'] . '</figcaption>';
        }
      }
    }

    $output .= '</figure>';
  }

  $output .= '</div>';

  // Render the top-level wrapper element.
  $tag = $vars['label_hidden'] ? 'div' : 'section';
  $output = "<$tag class=\"" . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . "</$tag>";

  return $output;
}

/*Speed tests
  function adaptivetheme_block($vars) {
  $output = '';
  $output .= $vars['outer_prefix'] . $vars['inner_prefix'];
  $output .= render($vars['title_prefix']);
  if ($vars['title']) {
    $output .= '<h2' . $vars['title_attributes'] . '>' . $vars['title'] . '</h2>';
  }
  $output .= render($vars['title_suffix']);
  $output .= $vars['content_processed'];
  $output .= $vars['inner_suffix'] . $vars['outer_suffix'];
  return $output;
}

 function adaptivetheme_region($vars) {
  $output = '';
  $output .= $vars['outer_prefix'] . $vars['inner_prefix'] . $vars['content'] . $vars['inner_suffix'] . $vars['outer_suffix'];
  return $output;
}
*/