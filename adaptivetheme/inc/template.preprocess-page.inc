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

// Set variables for the logo and site name for easy printing in templates.
$vars['logo_alt_text'] = check_plain(variable_get('site_name', '')) .' '. t('logo');
$vars['logo_img'] = $vars['logo'] ? '<img src="'. check_url($vars['logo']) .'" alt="'. $vars['logo_alt_text'] .'" title="'. t('Home page') .'"/>' : '';
$vars['linked_site_logo'] = $vars['logo_img'] ? l($vars['logo_img'], '<front>', array(
  'attributes' => array(
    'rel' => 'home'),
    'title' => t('Home page'),
    'html' => TRUE,
  )
) : '';
$vars['linked_site_name'] = $vars['site_name'] ? l($vars['site_name'], '<front>', array(
  'attributes' => array(
    'rel' => 'home'),
    'title' => t('Home page')
  )
) : '';

// Set variables for the main and secondary menus.
$vars['main_menu'] = theme('links__system_main_menu', array(
  'links' => $vars['main_menu'],
  'attributes' => array(
    'id' => 'main-menu-links',
    'class' => array(
      'links', 'clearfix')
    ),
    'heading' => t('Main menu')
  )
);
$vars['secondary_menu'] = theme('links__system_secondary_menu', array(
  'links' => $vars['secondary_menu'],
  'attributes' => array(
    'id' => 'secondary-menu-links',
    'class' => array(
      'links', 'clearfix')
    ),
    'heading' => t('Secondary menu')
  )
);

// Attribution.
$vars['attribution'] = "<div id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></div>"  ;


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
    $styles[] = '.sidebar-second .content-inner{margin-'. $right .':'. $push_right .'px; margin-'. $left .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px;margin-'. $left .':-100%;}';
    $styles[] = '#sidebar-second{width:'. $sidebar_last_width .'px;margin-'. $left .':-'. $pull_right .'px;}';
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
    $styles[] = '.sidebar-second .content-inner{margin-'. $right .':'. $push_right .'px;margin-'. $left .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px;margin-'. $left .':-'. $left_margin .'px;}';
    $styles[] = '#sidebar-second{width:'. $sidebar_last_width .'px;margin-'. $left .':-'. $right_margin .'px;}';
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
    $styles[] = '.sidebar-second .content-inner{margin-'. $left .':'. $right_margin .'px;margin-'. $right .':0;}';
    $styles[] = '#sidebar-first{width:'. $sidebar_first_width .'px; margin-'. $left .': -100%;}';
    $styles[] = '#sidebar-second{width:'. $sidebar_last_width .'px; margin-'. $left .': -100%;}';
    $styles[] = '.two-sidebars #sidebar-second {width:'. $sidebar_last_width .'px; position: relative;'. $left .':'. $push_right .'px;}';
  }
  $styles ? $vars['at_layout'] = implode('', $styles) : '';
  $vars['layout_settings'] = '<style type="text/css">'. $vars['at_layout_width'] . $vars['at_layout'] .'</style>';
}

// Show $theme_info output using krumo.
if (module_exists('devel') && theme_get_setting('show_theme_info') == 1) {
  dsm($theme_info); /* Use dsm, kpr messes up IE */
}

// Hide search theme form label
/* - double check for advanced search forms
if ($vars['search_box'] && theme_get_setting('display_search_form_label') == 0) {
  $vars['toggle_label'] = ' class="hide-label"';
}
*/
