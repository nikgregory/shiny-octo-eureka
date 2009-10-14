<?php // $Id$
// adaptivethemes.com

/**
 * @file template.php
 */

/**
 * Implementation of hook_theme
 * Register a theme function for the system_settings_form.
 */
function adaptivetheme_theme() {
  return array(
    'system_settings_form' => array(
      'arguments' => array('form' => NULL, 'key' => 'adaptivetheme'),
    ),
  );
}

/**
 * Implementation of hook_preprocess()
 * 
 * This function checks to see if a hook has a preprocess file associated with 
 * it, and if so, loads it.
 * 
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered.
 */
function adaptivetheme_preprocess(&$vars, $hook) {
  global $user;                                            // Get the current user
  $vars['is_admin'] = in_array('admin', $user->roles);     // Check for Admin, logged in
  $vars['logged_in'] = ($user->uid > 0) ? TRUE : FALSE;
  
  if (is_file(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.preprocess-'. str_replace('_', '-', $hook) .'.inc')) {
    include(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.preprocess-'. str_replace('_', '-', $hook) .'.inc');
  }
}

/**
 * Include custom functions.
 */
include_once(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.custom-functions.inc');

/**
 * Initialize theme settings
 */
if (is_null(theme_get_setting('user_notverified_display')) || theme_get_setting('rebuild_registry')) {

  // Auto-rebuild the theme registry during theme development.
  if (theme_get_setting('rebuild_registry')) {
    drupal_set_message(t('The theme registry has been rebuilt. <a href="!link">Turn off</a> this feature on production websites.', array('!link' => url('admin/build/themes/settings/'. $GLOBALS['theme']))), 'warning');
  }

  global $theme_key;
  // Get node types
  $node_types = node_get_types('names');

  /**
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the theme-settings.php file.
   */
  $defaults = array(
    'user_notverified_display'              => 1,
    'breadcrumb_display'                    => 'yes',
    'breadcrumb_separator'                  => ' &#187; ',
    'breadcrumb_home'                       => 0,
    'breadcrumb_trailing'                   => 0,
    'breadcrumb_title'                      => 0,
    'search_snippet'                        => 1,
    'search_info_type'                      => 1,
    'search_info_user'                      => 1,
    'search_info_date'                      => 1,
    'search_info_comment'                   => 1,
    'search_info_upload'                    => 1,
    'mission_statement_pages'               => 'home',
    'split_node_form'                       => 0,
    'taxonomy_display_default'              => 'only',
    'taxonomy_format_default'               => 'vocab',
    'taxonomy_delimiter_default'            => ', ',
    'taxonomy_enable_content_type'          => 0,
    'submitted_by_author_default'           => 1,
    'submitted_by_date_default'             => 1,
    'submitted_by_enable_content_type'      => 0,
    'rebuild_registry'                      => 0,
    'load_firebug_lite'                     => 0,
    'admin_user_links'                      => 1,
    'block_edit_links'                      => 1,
    'at_admin_hide_help'                    => 0,
    'layout_method'                         => '0',
    'layout_width'                          => '960px',
    'layout_sidebar_first_width'            => '240',
    'layout_sidebar_last_width'             => '240',
    'layout_enable_settings'                => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_width'                   => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_sidebars'                => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_method'                  => 'off', // set to 'on' to enable, 'off' to disable
    'equal_heights_sidebars'                => 0,
    'equal_heights_blocks'                  => 0,
    'horizontal_login_block'                => 0,
    'horizontal_login_block_overlabel'      => 0,
    'horizontal_login_block_enable'         => 'off', // set to 'on' to enable, 'off' to disable
    'color_schemes'                         => 'colors-default.css',
    'color_enable_schemes'                  => 'off',  // set to 'on' to enable, 'off' to disable
  );

  // Make the default content-type settings the same as the default theme settings,
  // so we can tell if content-type-specific settings have been altered.
  $defaults = array_merge($defaults, theme_get_settings());

  // Set the default values for content-type-specific settings
  foreach ($node_types as $type => $name) {
    $defaults["taxonomy_display_{$type}"]         = $defaults['taxonomy_display_default'];
    $defaults["taxonomy_format_{$type}"]          = $defaults['taxonomy_format_default'];
    $defaults["submitted_by_author_{$type}"]      = $defaults['submitted_by_author_default'];
    $defaults["submitted_by_date_{$type}"]        = $defaults['submitted_by_date_default'];
  }

  // Get default theme settings.
  $settings = theme_get_settings($theme_key);

  // Don't save the toggle_node_info_ variables
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {
      unset($settings['toggle_node_info_'. $type]);
    }
  }
  // Save default theme settings
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );
  // Force refresh of Drupal internals
  theme_get_setting('', TRUE);
}


// Load equalizeheights.js
if ((theme_get_setting('at_admin_theme') == 1 && arg(0) !== 'admin') || (theme_get_setting('at_admin_theme') == 0)) {
if (theme_get_setting('equal_heights_sidebars') || theme_get_setting('equal_heights_blocks')) {
  $path_to_core = path_to_theme() .'/js/core/';
  drupal_add_js($path_to_core .'jquery.equalizeheights.js', 'theme', 'header', FALSE, TRUE, TRUE);
  if (theme_get_setting('equal_heights_sidebars')) {
     drupal_add_js($path_to_core .'equalize-columns.js', 'theme', 'header', FALSE, TRUE, TRUE); 
  }
  if (theme_get_setting('equal_heights_blocks')) {
    drupal_add_js($path_to_core .'equalize-blocks.js', 'theme', 'header', FALSE, TRUE, TRUE);
  }
}
}

/** 
 * Load Firebug lite
 */
if (theme_get_setting('load_firebug_lite')) {
  $path_to_core = path_to_theme() .'/js/core/';
  drupal_add_js($path_to_core .'firebug.lite.compressed.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

/** 
 * Use horizontal login block overlabel js
 */
if (theme_get_setting('horizontal_login_block_overlabel')) {
  $path_to_core = path_to_theme() .'/js/core/';
  drupal_add_js($path_to_core .'jquery.overlabel.js', 'theme', 'header', FALSE, TRUE, TRUE);
}

/**
 * Add the color scheme stylesheet if color_enable_schemes is set to 'on'.
 * Note: you must have at minimum a color-default.css stylesheet in /css/theme/
 */
if (theme_get_setting('color_enable_schemes') == 'on') {
  drupal_add_css(drupal_get_path('theme', 'adaptivetheme') .'/css/theme/'. get_at_colors(), 'theme');
}

/**
 * Override of theme_node_form().
 */
function adaptivetheme_node_form($form) {
  if ((arg(0) == 'node' && arg(1) == 'add') || (is_numeric(arg(1)) && (arg(2) == 'edit'))) {
    if (theme_get_setting('split_node_form') == 1) {
      $buttons = '<div class="buttons">'. drupal_render($form['buttons']) .'</div>';
      $sidebar = drupal_render($form['taxonomy']);
      $main = drupal_render($form);
      return "<div class='node-form clear-block'>
        <div class='node-col-last'>{$buttons}{$sidebar}</div>
        <div class='node-col-first'><div class='main'>{$main}{$buttons}</div></div>
        </div>";
    }
    elseif (theme_get_setting('split_node_form') == 0) {
      $buttons = '<div class="buttons">'. drupal_render($form['buttons']) .'</div>';
      $main = drupal_render($form);
      return "<div class='node-form clear-block'>
        <div class='main'>{$main}{$buttons}</div>
        </div>";
    }
  }
}

/**
 * Modify search results based on theme settings
 */
function adaptivetheme_preprocess_search_result(&$variables) {
  static $search_zebra = 'even';
  $search_zebra = ($search_zebra == 'even') ? 'odd' : 'even';
  $variables['search_zebra'] = $search_zebra;
  
  $result = $variables['result'];
  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);

  // Check for existence. User search does not include snippets.
  $variables['snippet'] = '';
  if (isset($result['snippet']) && theme_get_setting('search_snippet')) {
    $variables['snippet'] = $result['snippet'];
  }
  
  $info = array();
  if (!empty($result['type']) && theme_get_setting('search_info_type')) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user']) && theme_get_setting('search_info_user')) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date']) && theme_get_setting('search_info_date')) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    // $info = array_merge($info, $result['extra']);  Drupal bug?  [extra] array not keyed with 'comment' & 'upload'
    if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
      $info['comment'] = $result['extra'][0];
    }
    if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
      $info['upload'] = $result['extra'][1];
    }
  }

  // Provide separated and grouped meta information.
  $variables['info_split'] = $info;
  $variables['info'] = implode(' - ', $info);

  // Provide alternate search result template.
  $variables['template_files'][] = 'search-result-'. $variables['type'];
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
      $breadcrumb_separator = theme_get_setting('breadcrumb_separator');
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
    return '<span class="button-wrapper"><span class="button"><span><input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ')  .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." /></span></span></span>\n";
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
