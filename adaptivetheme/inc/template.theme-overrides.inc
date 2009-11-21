<?php // $Id$
// adaptivethemes.com

/**
 * @file template.theme-overrides.inc
 */

/**
 * Override of theme_node_form().
 */
function adaptivetheme_node_form($form) {
  if ((arg(0) == 'node' && arg(1) == 'add') || (is_numeric(arg(1)) && (arg(2) == 'edit'))) {
    if (theme_get_setting('split_node_form') == 1) {
      $buttons = '<div class="buttons">'. drupal_render($form['buttons']) .'</div>';
      $sidebar = drupal_render($form['taxonomy']);
      $main = drupal_render($form);
      return "<div class='article-form clear-block'>
        <div class='article-col-last'>{$buttons}{$sidebar}</div>
        <div class='article-col-first'><div class='main'>{$main}{$buttons}</div></div>
        </div>";
    }
    elseif (theme_get_setting('split_node_form') == 0) {
      $buttons = '<div class="buttons">'. drupal_render($form['buttons']) .'</div>';
      $main = drupal_render($form);
      return "<div class='article-form clear-block'>
        <div class='main'>{$main}{$buttons}</div>
        </div>";
    }
  }
}

/**
 * Override username theming to display/hide 'not verified' text
 */
function adaptivetheme_username($object) {
  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }
    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('attributes' => array('title' => t('View user profile.'))));
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }
    // Display or hide 'not verified' text
    if (theme_get_setting('user_notverified_display') == 1) {
      $output .= ' ('. t('not verified') .')';
    }
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }
  return $output;
}

/**
 * Set default form file input size 
 */
function adaptivetheme_file($element) {
  $element['#size'] = 60;
  return theme_file($element);
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
function adaptivetheme_breadcrumb($breadcrumb) {
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
        $title = menu_get_active_title();
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

// Override theme_button for expanding graphic buttons
function adaptivetheme_button($element) {
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'] .' '. $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'];
  }

  // Wrap visible inputs with span tags for button graphics
  if (stristr($element['#attributes']['style'], 'display: none;') || stristr($element['#attributes']['class'], 'fivestar-submit')) {
    return '<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ')  .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." />\n";
  }
  else {
    return '<span class="button-wrapper '. $element['#id'] .'"><span class="button"><input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ')  .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." /></span></span>\n";
  }
}

/**
 * Format a group of form items.
 *
 * @param $element 
 *   An associative array containing the properties of the element. 
 *
 * @return
 *   A themed HTML string representing the form item group.
 */
function adaptivetheme_fieldset($element) {
  if ($element['#collapsible']) {
    drupal_add_js('misc/collapse.js');

    if (!isset($element['#attributes']['class'])) {
      $element['#attributes']['class'] = '';
    }

    $element['#attributes']['class'] .= ' collapsible';
    if ($element['#collapsed']) {
     $element['#attributes']['class'] .= ' collapsed';
    }
  }
  // Custom fieldset CSS class from element #title.
  $css_class = 'fieldset-'. safe_string($element['#title']);

  $element['#attributes']['class'] .= (!empty($element['#attributes']['class']) ? " " : "") . $css_class;

  return '<fieldset'. drupal_attributes($element['#attributes']) .'>'. ($element['#title'] ? '<legend>'. $element['#title'] .'</legend>' : '') . ($element['#description'] ? '<div class="description">'. $element['#description'] .'</div>' : '') . $element['#children'] . $element['#value'] ."</fieldset>\n";
}

/**
 * Modify the theme settings form for our theme.
 *
 * This is incldued here to make it easier to set up the theme, so 
 * you only have one file to worry about search/repeace the theme name.
 */
function adaptivetheme_system_settings_form($form) {
  // Theme the crap out of the theme settings fieldsets.
  $form['theme_settings']['#title'] = t('Drupal core theme settings');
  $form['theme_settings']['#collapsible'] = TRUE;
  $form['theme_settings']['#collapsed']   = TRUE;
  $form['theme_settings']['#prefix'] = '<div class="theme-settings-settings-wrapper">';
  $form['theme_settings']['#suffix'] = '</div>';
  $form['logo']['#collapsible'] = TRUE;
  $form['logo']['#collapsed']   = TRUE;
  $form['logo']['#prefix'] = '<div class="logo-settings-wrapper">';
  $form['logo']['#suffix'] = '</div>';
  $form['favicon']['#collapsible'] = TRUE;
  $form['favicon']['#collapsed']   = TRUE;
  $form['favicon']['#prefix'] = '<div class="favicon-settings-wrapper">';
  $form['favicon']['#suffix'] = '</div>';
  $form['node_info']['#collapsible'] = TRUE;
  $form['node_info']['#collapsed']   = TRUE;
  $form['node_info']['#prefix'] = '<div class="node-info-settings-wrapper">';
  $form['node_info']['#suffix'] = '</div>';
  $form['theme_specific']['#title'] = t('Advanced theme settings');
  $form['theme_specific']['#collapsible'] = TRUE;
  $form['theme_specific']['#collapsed']   = TRUE;
  return drupal_render($form);
}
