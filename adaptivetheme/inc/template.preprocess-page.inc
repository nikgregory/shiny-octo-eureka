<?php // $Id$
// adaptivethemes.com

/**
 * @file template.preprocess-page.inc
 */

/**
 * Override or insert variables into page templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called.
 */

global $theme, $theme_info, $user;

// Don't display empty help from node_help().
if ($vars['help'] == "<div class=\"help\"> \n</div>") {
  $vars['help'] = '';
}

// Remove sidebars if disabled e.g., for Panels.
if (!$vars['show_blocks']) {
  $vars['left'] = '';
  $vars['right'] = '';
}

// Skip navigation class.
if (theme_get_setting('skip_navigation_display') == 'hide') {
  $vars['skip_nav_class'] = 'element-invisible';
}
if (theme_get_setting('skip_navigation_display') == 'show') {
  $vars['skip_nav_class'] = 'show';
}
if (theme_get_setting('skip_navigation_display') == 'focus') {
  $vars['skip_nav_class'] = 'show-on-focus';
}

// Set variables for the logo and site_name.
if (!empty($vars['logo'])) {
  // Return the site_name even when site_name is disabled in theme settings.
  $vars['logo_alt_text'] = check_plain(variable_get('site_name', '')) .' '. t('logo');
  $vars['linked_site_logo'] = '<a href="'. $vars['front_page'] .'" title="'. t('Home page') .'" rel="home"><img src="'. $vars['logo'] .'" alt="'. $vars['logo_alt_text'] .'" /></a>';
}
if (!empty($vars['site_name'])) {
  $vars['linked_site_name'] = '<a href="'. $vars['front_page'] .'" title="'. t('Home page') .'" rel="home">'. $vars['site_name'] .'</a>';
}

// Set variables for the primary and secondary links.
if (!empty($vars['primary_links'])) {
  $vars['primary_menu'] = theme('links', $vars['primary_links'], array('class' => 'primary-links clearfix'));
}
if (!empty($vars['secondary_links'])) {
  $vars['secondary_menu'] = theme('links', $vars['secondary_links'], array('class' => 'secondary-links clearfix'));
}

// Attribution.
$vars['attribution'] = "<div id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></div>"  ;

// Body Classes.
$vars['layout'] = 'none';
if (!empty($vars['left'])) {
  $vars['layout'] = 'first';
}
if (!empty($vars['right'])) {
  $vars['layout'] = ($vars['layout'] == 'first') ? 'both' : 'last';
}

$classes = array();

// Section class. The section class is printed on the body element and allows you to theme site sections.
// We use the path alias otherwise all nodes will be in "section-node".
if (theme_get_setting('cleanup_classes_section')) {
  $path_alias = drupal_get_path_alias($_GET['q']);
  if (!$vars['is_front']) {
    list($section, ) = explode('/', $path_alias, 2);
    $classes[] = safe_string('section-'. $section) .' ';
  }
}

if (theme_get_setting('cleanup_classes_front')) {
  $classes[] = $vars['is_front'] ? 'front' : 'not-front';
}

if (theme_get_setting('cleanup_classes_user_status')) {
  $classes[] = $vars['logged_in'] ? 'logged-in' : 'not-logged-in';
}

if (theme_get_setting('cleanup_classes_arg_one')) {
  $classes[] = preg_replace('![^abcdefghijklmnopqrstuvwxyz0-9-_]+!s', '', 'page-'. safe_string(arg(0)));
}

if (theme_get_setting('cleanup_classes_node_type')) {
  if (isset($vars['node']) && $vars['node']->type) {
    $classes[] = 'article-type-'. safe_string($vars['node']->type);
  }
}

if (!$vars['is_front']) {
  if (theme_get_setting('cleanup_classes_normal_path')) {
    $normal_path = drupal_get_normal_path($_GET['q']);
    $classes[] = safe_string('page-'. $normal_path);
  }
  if (arg(2) == 'block') {
    $classes[] = 'page-block';
  }
  if (theme_get_setting('cleanup_classes_add_edit_delete')) {
    if (arg(0) == 'node') {
      if (arg(1) == 'add') {
        $classes[] = 'article-add'; // Add .node-add class.
      }
      elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
        $classes[] = 'article-'. arg(2);
      }
    }
  }
}

// Add information about the number of sidebars.
if ($vars['layout'] == 'both') {
  $classes[] = 'two-sidebars';
}
elseif ($vars['layout'] == 'none') {
  $classes[] = 'no-sidebars';
}
else {
  $classes[] = 'one-sidebar sidebar-'. $vars['layout'];
}

// Support for Context module body classes.
if (module_exists('context')) { // use module_exists, its slower but I cant be sure all fucntions will survive development.
  foreach (context_active_contexts() as $context) {
    if (!empty($context->theme_section['class'])) {
     $classes[] = $context->theme_section['class'];
    }
  }
}

// Language specific body class.
if (theme_get_setting('cleanup_classes_language')) {
  if (function_exists('locale')) {
    global $language;
    $classes[] = 'lang-'. $language->language;
  }
}

// $classes is the varaible that will hold the page classes, and is printed in page tpl files.
$vars['classes'] = implode(' ', $classes); // Concatenate with spaces.

// Layout settings - set the page width and layout method.
if (theme_get_setting('layout_enable_settings') == 'on') {
  $vars['at_layout_width'] = '#container{width:'. theme_get_setting('layout_width') .';}';
  $method = theme_get_setting('layout_method');
  $sidebar_last_width = theme_get_setting('layout_sidebar_last_width');
  $sidebar_first_width = theme_get_setting('layout_sidebar_first_width');
  if ($vars['language']->dir == 'ltr') {
    $left = 'left';
    $right = 'right';
  }
  if ($vars['language']->dir == 'rtl') {
    $left = 'right';
    $right = 'left';
  }
  if ($method == '0') {
    $push_right = $sidebar_last_width;
    $push_left  = $sidebar_first_width;
    $pull_right = $sidebar_last_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin-'. $left .':'. $push_left .'px; margin-'. $right .':'. $push_right .'px;}';
    $styles[] = '.sidebar-first .content-inner{margin-'. $left .':'. $push_left .'px; margin-'. $right .':0;}';
    $styles[] = '.sidebar-last .content-inner{margin-'. $right .':'. $push_right .'px; margin-'. $left .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px;margin-'. $left .':-100%;}';
    $styles[] = '#sidebar-last{width:'. $sidebar_last_width .'px;margin-'. $left .':-'. $pull_right .'px;}';
  }
  if ($method == '1') {
    $content_margin = $sidebar_last_width + $sidebar_first_width;
    $push_right     = $sidebar_last_width;
    $push_left      = $sidebar_first_width;
    $left_margin    = $sidebar_last_width + $sidebar_first_width;
    $right_margin   = $sidebar_last_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin-'. $right .':'. $content_margin .'px;margin-'. $left .':0;}';
    $styles[] = '.sidebar-first .content-inner{margin-'. $right .':'. $push_left .'px;margin-'. $left .':0;}';
    $styles[] = '.sidebar-last .content-inner{margin-'. $right .':'. $push_right .'px;margin-'. $left .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px;margin-'. $left .':-'. $left_margin .'px;}';
    $styles[] = '#sidebar-last{width:'. $sidebar_last_width .'px;margin-'. $left .':-'. $right_margin .'px;}';
    $styles[] = '.sidebar-first #sidebar-first{width:'. $sidebar_first_width .'px;margin-'. $left .':-'. $sidebar_first_width .'px;}';
  }
  if ($method == '2') {
    $content_margin = $sidebar_last_width + $sidebar_first_width;
    $left_margin    = $sidebar_first_width;
    $right_margin   = $sidebar_last_width;
    $push_right     = $sidebar_first_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin-'. $left .':'. $content_margin .'px;margin-'. $right .':0;}';
    $styles[] = '.sidebar-first .content-inner{margin-'. $left .':'. $left_margin .'px;margin-'. $right .':0;}';
    $styles[] = '.sidebar-last .content-inner{margin-'. $left .':'. $right_margin .'px;margin-'. $right .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px; margin-'. $left .': -100%;}';
    $styles[] = '#sidebar-last{width:'. $sidebar_last_width .'px; margin-'. $left .': -100%;}';
    $styles[] = '.two-sidebars #sidebar-last {width:'. $sidebar_last_width .'px; position: relative;'. $left .':'. $push_right .'px;}';
  }
  $vars['at_layout'] = implode('', $styles);
  if ((theme_get_setting('at_admin_theme') == 1 && arg(0) !== 'admin') || (theme_get_setting('at_admin_theme') == 0)) {
    $vars['layout_settings'] = '<style type="text/css">'. $vars['at_layout_width'] . $vars['at_layout'] .'</style>';
  }
  if (theme_get_setting('at_admin_theme') == 1 && arg(2) == 'block') {
    $vars['layout_settings'] = '<style type="text/css">'. $vars['at_layout'] .'</style>';
  }
}

// Display mission statement on all pages.
if (theme_get_setting('mission_statement_pages') == 'all') {
  $vars['mission'] = theme_get_setting('mission', FALSE);
}

// Show $theme_info output using krumo.
if (module_exists('devel') && theme_get_setting('show_theme_info') == 1) {
  kpr($theme_info);
}

// Check whether help is disabled.
if (theme_get_setting('at_admin_hide_help')) {
  unset($vars['help']);
}

// Admin user links.
if ($vars['logged_in'] && theme_get_setting('at_user_menu') == 1) {
  if (!function_exists(admin_menu_perm)) {
    $admin_user_links[] = l(t('Create content'), 'node/add');
    $admin_user_links[] = user_access('administer nodes') ? l(t('Manage content'), 'admin/content/node') : '';
    $admin_user_links[] = user_access('administer blocks') ? l(t('Blocks'), 'admin/build/block') : '';
    $admin_user_links[] = user_access('administer menu') ? l(t('Menus'), 'admin/build/menu') : '';
    $admin_user_links[] = user_access('administer users') ? l(t('Users'), 'admin/user/user') : '';
    $admin_user_links[] = user_access('access administration pages') ? l(t('Admin'), 'admin') : '';
  }
  if ($vars['logged_in']) {
    $admin_user_links[] = l(t('My Account'), 'user/'. $user->uid);
  }
  if (!function_exists(admin_menu_perm)) {
    $admin_user_links[] = l(t('Logout'), 'logout');
  }
  $vars['admin_user_menu'] = $admin_user_links;
  $vars['admin_user_links'] = theme('item_list', $admin_user_links);
}