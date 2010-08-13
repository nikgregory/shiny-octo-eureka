<?php
// $Id$

/**
 * Override or insert variables into all templates.
 */
// template_preprocess
function adaptivetheme_preprocess(&$vars, $hook) {
  $vars['theme_path'] = base_path() . path_to_theme() .'/';
}
// template_process
function adaptivetheme_process(&$vars) {
  // Provide a variable to check if the page is in the overlay.
  if (module_exists('overlay')) {
    $vars['in_overlay'] = (overlay_get_mode() == 'child');
  }
  else {
    $vars['in_overlay'] = FALSE;
  }
}

/**
 * Override or insert variables into the HTML template.
 */
// template_preprocess_html
function adaptivetheme_preprocess_html(&$vars) {
  // Skip navigation class.
  $vars['skip_nav_class'] = '';
  if (theme_get_setting('skip_navigation_display') == 'hide') {
    $vars['skip_nav_class'] = 'element-invisible';
  }
  if (theme_get_setting('skip_navigation_display') == 'show') {
    $vars['skip_nav_class'] = 'show-skip-link';
  }
  if (theme_get_setting('skip_navigation_display') == 'focus') {
    $vars['skip_nav_class'] = 'show-on-focus';
  }
  // Language specific body class.
  if (theme_get_setting('cleanup_classes_language')) {
    if (function_exists('locale')) {
      $vars['classes_array'][] = 'lang-'. $vars['language']->language;
    }
  }
  // Remove the suggestion classes.
  if (theme_get_setting('cleanup_classes_suggestions') == 0) {
    foreach($vars['classes_array'] as $i =>$class) {
      if(preg_match('/page*/', $class)) {
        unset($vars['classes_array'][$i]);
      }
    }
  }
  // Show the $page output using Krumo.
  if (module_exists('devel') && theme_get_setting('show_theme_info') == 1) {
    kpr($vars['page']);
  }
  // Layout settings - set the page width and layout method.
  if (theme_get_setting('layout_enable_settings') == 'on') {
    $vars['at_layout_width'] = '#page,.container{width:'. theme_get_setting('layout_width') .';}';
    if (isset($vars['page']['sidebar_first']) || isset($vars['page']['sidebar_second'])) {
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
      $styles = array();
      if ($method == 0) {
        $push_right = $sidebar_last_width;
        $push_left  = $sidebar_first_width;
        $pull_right = $sidebar_last_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $left . ':' . $push_left . 'px;margin-' . $right . ':' . $push_right . 'px;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $left . ':' . $push_left . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $right . ':' . $push_right . 'px; margin-' . $left . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-100%;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px;margin-' . $left . ':-' . $pull_right . 'px;}' . "\n";
      }
      if ($method == 1) {
        $content_margin = $sidebar_last_width + $sidebar_first_width;
        $push_right     = $sidebar_last_width;
        $push_left      = $sidebar_first_width;
        $left_margin    = $sidebar_last_width + $sidebar_first_width;
        $right_margin   = $sidebar_last_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $right . ':' . $content_margin . 'px;margin-'. $left . ':0;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $right . ':' . $push_left . 'px;margin-' . $left . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $right . ':' . $push_right . 'px;margin-' . $left . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-' . $left_margin . 'px;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px;margin-' . $left . ':-' . $right_margin . 'px;}' . "\n";
        $styles[] = '.sidebar-first .region-sidebar-first{width:' . $sidebar_first_width . 'px;margin-' . $left . ':-' . $sidebar_first_width . 'px;}' . "\n";
      }
      if ($method == 2) {
        $content_margin = $sidebar_last_width + $sidebar_first_width;
        $left_margin    = $sidebar_first_width;
        $right_margin   = $sidebar_last_width;
        $push_right     = $sidebar_first_width;
        $styles[] = '.two-sidebars .content-inner{margin-' . $left . ':' . $content_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-first .content-inner{margin-' . $left . ':' . $left_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.sidebar-second .content-inner{margin-' . $left . ':' . $right_margin . 'px;margin-' . $right . ':0;}' . "\n";
        $styles[] = '.region-sidebar-first{width:' . $sidebar_first_width . 'px; margin-' . $left . ': -100%;}' . "\n";
        $styles[] = '.region-sidebar-second{width:' . $sidebar_last_width . 'px; margin-' . $left . ': -100%;}' . "\n";
        $styles[] = '.two-sidebars .region-sidebar-second{width:' . $sidebar_last_width . 'px; position: relative;' . $left . ':' . $push_right . 'px;}' . "\n";
      }
      $vars['at_layout'] = implode('', $styles);
      $vars['layout'] = $vars['at_layout_width'] . $vars['at_layout'];
    }
    else {
      $vars['layout'] = $vars['at_layout_width'];
    }
    drupal_add_css($vars['layout'], array('weight' => CSS_THEME, 'type' => 'inline'));
  }
}
// template_process_html
function adaptivetheme_process_html(&$vars) {
  $classes = explode(' ', $vars['classes']);
  // .front and .not-front classes.
  if (in_array('front', $classes)) {
    theme_get_setting('cleanup_classes_front') ? '' : $classes = str_replace('front', '', $classes);
  }
  if (in_array('not-front', $classes)) {
    theme_get_setting('cleanup_classes_front') ? '' : $classes = str_replace('not-front', '', $classes);
  }
  // User status classes.
  if (in_array('logged-in', $classes)) {
    theme_get_setting('cleanup_classes_user_status') ? '' : $classes = str_replace('logged-in', '', $classes);
  }
  if (in_array('not-logged-in', $classes)) {
    theme_get_setting('cleanup_classes_user_status') ? '' : $classes = str_replace('not-logged-in', '', $classes);
  }
  // Node type class.
  if ($node = menu_get_object()) {
    $node_type_class = drupal_html_class('node-type-' . $node->type);
    if (in_array($node_type_class, $classes)) {
      theme_get_setting('cleanup_classes_node_type') ? '' : $classes = str_replace($node_type_class, '', $classes);
      $classes = str_replace('node-type-', 'article-type-', $classes);
    }
  }
  // $classes is the varaible that holds the page classes, printed in page tpl files.
  $vars['classes'] = trim(implode(' ', $classes));
}

/**
 * Override or insert variables into the page templates.
 */
// template_preprocess_page
function adaptivetheme_preprocess_page(&$vars) {
  // Set variables for the logo and site name for easy printing in templates.
  $vars['logo_alt_text'] = check_plain(variable_get('site_name', '')) .' '. t('logo');
  $vars['logo_img'] = $vars['logo'] ? '<img src="'. check_url($vars['logo']) .'" alt="'. $vars['logo_alt_text'] .'"/>' : '';
  $vars['linked_site_logo'] = $vars['logo_img'] ? l($vars['logo_img'], '<front>', array(
    'attributes' => array(
      'rel' => 'home',
      'title' => t('Home page')),
    'html' => TRUE,
    )
  ) : '';
  $vars['linked_site_name'] = $vars['site_name'] ? l($vars['site_name'], '<front>', array(
    'attributes' => array(
      'rel' => 'home',
      'title' => t('Home page')),
    )
  ) : '';
  // Set variables for the main and secondary menus.
  if (isset($vars['main_menu'])) {
    $vars['primary_navigation'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'clearfix'),
        'id' => array('primary-nav'),
      ),
      'heading' => array(
        'text' => t('Primary navigation'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_navigation'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'clearfix'),
        'id' => array('secondary-nav'),
       ),
      'heading' => array(
        'text' => t('Secondary navigation'),
        'level' => 'h2',
        'class' => array('element-invisible'),
       )
     ));
  }
  // Set variables for local tasks.
  $vars['primary_local_tasks'] = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();
  // Attribution.
  $vars['attribution'] = "<div id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></div>";
}
// template_process_html
function adaptivetheme_process_page(&$vars) {
}

/**
 * Override or insert variables into the node templates.
 */
// template_preprocess_node
function adaptivetheme_preprocess_node(&$vars) {
  global $theme, $user;
  $vars['article_id'] = '';
  if (theme_get_setting('cleanup_article_id')) {
    $vars['attributes_array']['id'][] = 'article-'. $vars['node']->nid;
  }
  /* Add support for Skinr module classes http://drupal.org/project/skinr */
  if (module_exists('skinr') && !empty($vars['skinr'])) {
    $classes[] = $vars['skinr'];
  }
  // Language specific article class.
  if (theme_get_setting('cleanup_article_classes_language')) {
    if (module_exists('translation')) {
      if ($vars['node']->language) {
        global $language;
        $classes[] = 'article-lang-'. $vars['node']->language;
      }
    }
  }
  if (theme_get_setting('cleanup_article_classes_zebra')) {
    $vars['classes_array'][] = $vars['zebra'];
  }
  // Set title classes
  if (theme_get_setting('cleanup_headings_title_class')) {
    $vars['title_attributes_array']['class'][] = 'title';
  }
  if (theme_get_setting('cleanup_headings_type_class')) {
    $vars['title_attributes_array']['class'][] = 'article-title';
  }
  // Set content wrapper classes
  if (theme_get_setting('cleanup_content_class')) {
    $vars['content_attributes_array']['class'][] = 'content';
  }
  if (theme_get_setting('cleanup_content_type_class')) {
    $vars['content_attributes_array']['class'][] = 'article-content';
  }
}
// template_process_node
function adaptivetheme_process_node(&$vars) {
  $classes = explode(' ', $vars['classes']);
  $classes = str_replace('node', 'article', $classes);
  if (in_array('article-sticky', $classes)) {
    theme_get_setting('cleanup_article_classes_sticky') ? '' : $classes = str_replace('article-sticky', '', $classes);
  }
  if (in_array('article-promoted', $classes)) {
    theme_get_setting('cleanup_article_classes_promote') ? '' : $classes = str_replace('article-promoted', '', $classes);
  }
  if (in_array('article-teaser', $classes)) {
    theme_get_setting('cleanup_article_classes_teaser') ? '' : $classes = str_replace('article-teaser', '', $classes);
  }
  if (in_array('article-preview', $classes)) {
    theme_get_setting('cleanup_article_classes_preview') ? '' : $classes = str_replace('article-preview', '', $classes);
  }
  if (in_array('article-' . $vars['node']->type, $classes)) {
    theme_get_setting('cleanup_article_classes_type') ? '' : $classes = str_replace('article-' . $vars['node']->type, '', $classes);
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

/**
 * Override or insert variables into the comment templates.
 */
// template_preprocess_comment
function adaptivetheme_preprocess_comment(&$vars) {
  if (theme_get_setting('cleanup_comment_zebra')) {
    $vars['classes_array'][] = $vars['zebra'];
  }
  // Set title classes
  if (theme_get_setting('cleanup_headings_title_class')) {
    $vars['title_attributes_array']['class'][] = 'title';
  }
  if (theme_get_setting('cleanup_headings_type_class')) {
    $vars['title_attributes_array']['class'][] = 'comment-title';
  }
  // Set content wrapper classes
  if (theme_get_setting('cleanup_content_class')) {
    $vars['content_attributes_array']['class'][] = 'content';
  }
  if (theme_get_setting('cleanup_content_type_class')) {
    $vars['content_attributes_array']['class'][] = 'comment-content';
  }
  // Permalinks - add rel='bookmark' to permalinks
  $uri = entity_uri('comment', $vars['comment']);
  $uri['options'] += array('attributes' => array('rel' => 'bookmark'));
  $vars['title'] = l($vars['comment']->subject, $uri['path'], $uri['options']);
  $vars['permalink'] = l(t('Permalink'), $uri['path'], $uri['options']);
  // Use the $created variable for the permalink, aka Wordpress/Twitter style.
  $vars['created'] = '<span class="date-time permalink">' . l($vars['created'], $uri['path'], $uri['options']) . '</span>';
}

// template_process_comment
function adaptivetheme_process_comment(&$vars) {
  $classes = explode(' ', $vars['classes']);
  if (in_array('comment-by-anonymous', $classes)) {
    theme_get_setting('cleanup_comment_anonymous') ? '' : $classes = str_replace('comment-by-anonymous', '', $classes);
  }
  if (in_array('comment-by-node-author', $classes)) {
    $classes = str_replace('comment-by-node-author', 'comment-by-article-author', $classes);
    theme_get_setting('cleanup_comment_article_author') ? '' : $classes = str_replace('comment-by-article-author', '', $classes);
  }
  if (in_array('comment-by-viewer', $classes)) {
    theme_get_setting('cleanup_comment_by_viewer') ? '' : $classes = str_replace('comment-by-viewer', '', $classes);
  }
  if (in_array('comment-new', $classes)) {
    theme_get_setting('cleanup_comment_new') ? '' : $classes = str_replace('comment-new', '', $classes);
  }
  $vars['classes'] = trim(implode(' ', $classes));
  // Unset the permalink variable generated by core.
  if (theme_get_setting('comment_permalink_cid') == 0) {
    $vars['permalink'] = '';
  }
}

/**
 * Override or insert variables into the block templates.
 */
// template_preprocess_block
function adaptivetheme_preprocess_block(&$vars) {
  // Add the block_html_id to the attributes array.
  if (theme_get_setting('cleanup_block_block_module_delta')) {
   $vars['attributes_array']['id'][] = $vars['block_html_id'];
  }
  if (theme_get_setting('cleanup_block_classes_zebra')) {
    $vars['classes_array'][] = 'block-' . $vars['block_zebra'];
  }
  if (theme_get_setting('cleanup_block_classes_region')) {
    $vars['classes_array'][] = 'block-count-'. $vars['id'];
  }
  if (theme_get_setting('cleanup_block_classes_count')) {
    $vars['classes_array'][] = drupal_html_class('block-region-' . $vars['block']->region);
  }
  // Add classes for the horizontal login block if enabled.
  if (theme_get_setting('horizontal_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['classes_array'][] = 'at-horizontal-login clearfix';
  }
  // Set title classes.
  if (theme_get_setting('cleanup_headings_title_class')) {
    $vars['title_attributes_array']['class'][] = 'title';
  }
  if (theme_get_setting('cleanup_headings_type_class')) {
    $vars['title_attributes_array']['class'][] = 'block-title';
  }
  // Set content wrapper classes
  if (theme_get_setting('cleanup_content_class')) {
    $vars['content_attributes_array']['class'][] = 'content';
  }
  if (theme_get_setting('cleanup_content_type_class')) {
    $vars['content_attributes_array']['class'][] = 'block-content';
  }
  // Remove "block" class from "Main page content" block
  if ($vars['block']->module == 'system' && $vars['block']->delta == 'main') {
    foreach ($vars['classes_array'] as $key => $val) {
      if ($val == 'block') {
        unset($vars['classes_array'][$key]);
      }
    }
  }
}
// process block
function adaptivetheme_process_block(&$vars) {
  $classes = explode(' ', $vars['classes']);
  if (in_array('block-' . $vars['block']->module, $classes)) {
    theme_get_setting('cleanup_block_classes_module') ? '' : $classes = str_replace('block-' . $vars['block']->module, '', $classes);
  }
  $vars['classes'] = trim(implode(' ', $classes));
}

/**
 *  Modify search results based on theme settings.
 */
// template_preprocess_search_result
function adaptivetheme_preprocess_search_result(&$vars) {
  $result = $vars['result'];
  $vars['url'] = check_url($result['link']);
  $vars['title'] = check_plain($result['title']);
  // Check for snippets - user search does not include snippets.
  $vars['snippet'] = '';
  if (!empty($result['snippet']) && theme_get_setting('search_snippet')) {
    $vars['snippet'] = $result['snippet'];
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
    if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
      $info['comment'] = $result['extra'][0];
    }
    if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
      $info['upload'] = $result['extra'][1];
    }
  }
  // Provide separated and grouped meta information.
  $vars['info_split'] = $info;
  $vars['info'] = implode(' - ', $info);
  // Provide alternate search result template.
  $vars['template_files'][] = 'search-result-'. $vars['type'];
  // info_separator
  $vars['info_separator'] = filter_xss(theme_get_setting('search_info_separator'));
}

/**
 * Theme preprocess function for theme_field() and field.tpl.php.
 */
// template_preprocess_field
function adaptivetheme_preprocess_field(&$vars, $hook) {
  // Add specific suggestions that can override the default implementation.
  array_unshift($vars['theme_hook_suggestions'], 'field__' . $vars['element']['#field_type']);
}
