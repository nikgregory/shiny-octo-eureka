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
  $vars['primary_menu'] = theme('links', $vars['primary_links'], array('class' => 'primary-links clear-block'));
}
if (!empty($vars['secondary_links'])) {
  $vars['secondary_menu'] = theme('links', $vars['secondary_links'], array('class' => 'secondary-links clear-block'));
}

// Attribution.
$vars['attribution'] = "<div id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></div>"  ;

// Section class. The section class is printed on the body element and allows you to theme site sections.
// We use the path alias otherwise all nodes will be in "section-node".
$path_alias = drupal_get_path_alias($_GET['q']);
if (!$vars['is_front']) {
  list($section, ) = explode('/', $path_alias, 2);
  $vars['section_class'] = safe_string('section-'. $section) .' ';
}

// Body Classes.
$classes = explode(' ', $vars['body_classes']);

// Remove the useless page-arg(0) class.
if ($class = array_search(preg_replace('![^abcdefghijklmnopqrstuvwxyz0-9-]+!s', '', 'page-'. drupal_strtolower(arg(0))), $classes)) {
  unset($classes[$class]);
}

/** 
 * Optional Region body classes
 * Uncomment the following if you need to set a body class for each active region.
 */
/*        
if (!empty($vars['leaderboard'])) {
  $classes[] = 'leaderboard';
}
if (!empty($vars['header'])) {
  $classes[] = 'header-region';
}
if (!empty($vars['content_aside'])) {
  $classes[] = 'content-aside';
}
if (!empty($vars['secondary_content'])) {
  $classes[] = 'secondary-content';
}
if (!empty($vars['tertiary_content'])) {
  $classes[] = 'tertiary-content';
}
if (!empty($vars['footer'])) {
 $classes[] = 'footer';
}
*/

/**
 * Additional body classes to help out themers.
 */
if (!$vars['is_front']) {
  // Set classes based on Drupals internal path, e.g. page-node-1. 
  // Using the alias is fragile because path alias's can change, $normal_path is more reliable.
  $normal_path = drupal_get_normal_path($_GET['q']);
  $classes[] = safe_string('page-'. $normal_path);
  if (arg(2) == 'block') {
    $classes[] = 'page-block';
  }
  if (arg(0) == 'node') {
    if (arg(1) == 'add') {
      $classes[] = 'page-article-add'; // Add .node-add class.
    }
    elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
      $classes[] = 'page-article-'. arg(2); // Add .article-edit or .article-delete classes.
    }
  }
}
$classes_string = implode(' ', $classes); 
$vars['classes'] = str_replace('node', 'article', $classes_string);

// THEME SETTINGS
// Display mission statement on all pages.
if (theme_get_setting('mission_statement_pages') == 'all') {
  $vars['mission'] = theme_get_setting('mission', FALSE);  
}

// Layout settings - set the page width and layout method.
if (theme_get_setting('layout_enable_settings') == 'on') {
  $vars['at_layout_width'] = '#container{width:'. theme_get_setting('layout_width') .';}';
  $method = theme_get_setting('layout_method');
  $right_width = theme_get_setting('layout_sidebar_last_width');
  $left_width = theme_get_setting('layout_sidebar_first_width');
  if ($method == '0') {
    $content_margin_right = $right_width;
    $content_margin_left  = $left_width;
    $pull_right           = $right_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin:0 '. $content_margin_right .'px 0 '. $content_margin_left .'px;}';
    $styles[] = '.sidebar-left .content-inner{margin-left:'. $content_margin_left .'px; margin-right:0;}';
    $styles[] = '.sidebar-right .content-inner{margin-right:'. $content_margin_right .'px; margin-left:0;}';
    $styles[] = '#sidebar-first{width:'. $left_width .'px;margin-left:-100%;}';
    $styles[] = '#sidebar-last{width:'. $right_width .'px;margin-left:-'. $pull_right .'px;}';
  }
  if ($method == '1') {
    $content_margin       = $right_width + $left_width;
    $content_margin_right = $right_width;
    $content_margin_left  = $left_width;
    $left_margin          = $right_width + $left_width;
    $right_margin         = $right_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin-right:'. $content_margin .'px;margin-left:0;}';
    $styles[] = '.sidebar-left .content-inner{margin-right:'. $content_margin_left .'px;margin-left:0;}';
    $styles[] = '.sidebar-right .content-inner{margin-right:'. $content_margin_right .'px;margin-left:0;}';
    $styles[] = '#sidebar-first{width:'. $left_width .'px;margin-left:-'. $left_margin .'px;}';
    $styles[] = '#sidebar-last{width:'. $right_width .'px;margin-left:-'. $right_margin .'px;}';
    $styles[] = '.sidebar-left #sidebar-first{width:'. $left_width .'px;margin-left:-'. $left_width .'px;}';
  }
  if ($method == '2') {
    $content_margin = $right_width + $left_width;
    $left_margin    = $right_width + $left_width;
    $right_margin   = $right_width;
    $push_right     = $left_width;
    $styles = array();
    $styles[] = '.two-sidebars .content-inner{margin:0 0 0 '. $content_margin . 'px;}';
    $styles[] = '.sidebar-left .content-inner{margin-left:'. $left_margin .'px;margin-right:0;}';
    $styles[] = '.sidebar-right .content-inner{margin-left:'. $right_margin .'px;margin-right:0;}';
    $styles[] = '#sidebar-first{width:'. $left_width .'px; margin-left: -100%;}';
    $styles[] = '#sidebar-last{width:'. $right_width .'px; margin-left: -100%;}';
    $styles[] = '.two-sidebars #sidebar-last {width:'. $right_width .'px; position: relative; left:'. $push_right .'px;}';
  }
  $vars['at_layout'] = implode('', $styles);
  if ((theme_get_setting('at_admin_theme') == 1 && arg(0) !== 'admin') || (theme_get_setting('at_admin_theme') == 0)) {
    $vars['layout_settings'] = '<style type="text/css">'. $vars['at_layout_width'] . $vars['at_layout'] .'</style>';
  }
  if (theme_get_setting('at_admin_theme') == 1 && arg(2) == 'block') {
    $vars['layout_settings'] = '<style type="text/css">'. $vars['at_layout'] .'</style>';
  }
}

// Check whether help is disabled.
if (theme_get_setting('at_admin_hide_help')) {
  unset($vars['help']);
}

// Admin user links.
if (theme_get_setting('admin_user_links')) {
  if (!function_exists(admin_menu_perm)) {
    $admin_user_links[] = l(t('Create content'), 'node/add');
    $admin_user_links[] = user_access('administer nodes') ? l(t('Manage content'), 'admin/content/node') : '';
    $admin_user_links[] = user_access('administer blocks') ? l(t('Blocks'), 'admin/build/block') : '';
    $admin_user_links[] = user_access('administer menu') ? l(t('Menus'), 'admin/build/menu') : '';
    $admin_user_links[] = user_access('administer users') ? l(t('Users'), 'admin/user/user') : '';
    $admin_user_links[] = user_access('access administration pages') ? l(t('Admin'), 'admin') : '';
  }
  $admin_user_links[] = l(t('My Account'), 'user/'. $user->uid);
  if (!function_exists(admin_menu_perm)) {
    $admin_user_links[] = l(t('Logout'), 'logout');
  }
  $vars['admin_user_menu'] = $admin_user_links;
  $vars['admin_user_links'] = theme('item_list', $admin_user_links);
}

/**
 * From Studio Theme 6.x-2.x
 * Check for stylesheets to be placed at the top of the stack or conditional
 * Internet Explorer styles in the .info file and add them to the $styles
 * variable.
 */
$top_styles = array();
$ie_styles = array();

/**
 * If there is more than one active theme, check all base themes for
 * stylesheets.
 */
if (count($themes_active) > 1) {
  foreach ($base_theme_info as $name => $info) {
    if (isset($info->info['top stylesheets'])) {
      $top_styles[$name] = $info->info['top stylesheets'];
    }
    if (isset($info->info['ie stylesheets'])) {
      $ie_styles[$name] = $info->info['ie stylesheets'];
    }
  }
}

/**
 * Check the current theme for stylesheets.
 */
if (isset($theme_info->info['top stylesheets'])) {
  $top_styles[$theme_info->name] = $theme_info->info['top stylesheets'];
}
if (isset($theme_info->info['ie stylesheets'])) {
  $ie_styles[$theme_info->name] = $theme_info->info['ie stylesheets'];
}

/**
 * If there is at least one entry in the $top_styles array, process it.
 */
if (count($top_styles) >= 1) {
  // Format the array into a format readable by drupal_get_css().
  $vars['top_css'] = array();
  foreach ($top_styles as $name => $theme_styles) {
    $path = drupal_get_path('theme', $name);
    foreach ($theme_styles as $media => $styles) {
      foreach ($styles as $style){
        $vars['top_css'][$media]['featured'][$path . '/' . $style] = TRUE;
      }
      // Add the new styles to the top of the $vars['css'] array.
      array_unshift($vars['css'][$media], $vars['top_css'][$media]['featured']);
    }
  }
  // Run $vars['css'] through drupal_get_css and replace the $styles variable.
  $vars['styles'] = drupal_get_css($vars['css']);
}

/**
 * If there is at least one entry in the $ie_styles array, process it.
 */
if (count($ie_styles) >= 1) {
  // Format the array into a format readable by drupal_get_css().
  $vars['ie_css'] = array();
  foreach ($ie_styles as $name => $theme_styles) {
    $path = drupal_get_path('theme', $name);
    foreach ($theme_styles as $version => $media) {
      foreach ($media as $type => $styles) {
        foreach ($styles as $style) {
          $vars['ie_css'][$version][$type]['theme'][$path . '/' . $style] = TRUE;
        }
      }
    }
  }
  // Append the stylesheets to $styles, grouped by IE conditional.
  foreach ($vars['ie_css'] as $version => $styles) {
    $vars['styles'] .= '<!--[if ' . $version . ']>' . "\n" . drupal_get_css($styles) . '<![endif]-->' . "\n";
  }
}