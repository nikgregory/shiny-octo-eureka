<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 *
 * If you need to add or modify preprocess or process functions do it in your sub-theme.
 */

// Process variables for html tag
function adaptivetheme_process_html_tag(&$vars) {
  $tag = &$vars['element'];
  if ($tag['#tag'] == 'style' || $tag['#tag'] == 'script') {
    // Remove redundant type attribute and CDATA comments.
    unset($tag['#attributes']['type'], $tag['#value_prefix'], $tag['#value_suffix']);

    // Remove media="all" but leave others unaffected.
    if (isset($tag['#attributes']['media']) && $tag['#attributes']['media'] === 'all') {
      unset($tag['#attributes']['media']);
    }
  }
}

// Preprocess variables for html.tpl.php
function adaptivetheme_preprocess_html(&$vars) {

  global $language, $theme_key;

  // AT no longer uses doctype switching or $rdf_profile, these maintain backwards
  // compatibility with pre 7.x-2.1 versions of html.tpl.php
  $vars['doctype'] = '<!DOCTYPE html>' . "\n";
  $vars['rdf_profile'] = '';

  // Clean up the lang attributes, great idea Zen
  $vars['html_attributes'] = 'lang="' . $language->language . '" dir="' . $language->dir . '"';

  // add extra classes if set
  if (theme_get_setting('extra_page_classes')) {
    if (function_exists('locale')) {
      $vars['classes_array'][] = 'lang-' . $vars['language']->language;
    }

    // Classes for body element. Allows advanced theming based on context
    // (home page, node of certain type, etc.), cheers Zen.
    if (!$vars['is_front']) {
      // Add unique class for each page.
      $path = drupal_get_path_alias($_GET['q']);
      // Add unique class for each website section.
      list($section, ) = explode('/', $path, 2);
      $arg = explode('/', $_GET['q']);
      if ($arg[0] == 'node') {
        if (isset($arg[1]) && $arg[1] == 'add') {
          $section = 'node-add';
        }
        elseif (isset($arg[2]) && is_numeric($arg[1]) && ($arg[2] == 'edit' || $arg[2] == 'delete')) {
          $section = 'node-' . $arg[2];
        }
      }
      $vars['classes_array'][] = drupal_html_class('section-' . $section);
    }
    // Set classes for views and panels pages, thanks again Zen, you're amazing!
    $vars['menu_item'] = menu_get_item();
    switch ($vars['menu_item']['page_callback']) {
      case 'views_page':
        // Is this a Views page?
        $vars['classes_array'][] = 'page-views';
        break;
      case 'page_manager_page_execute':
      case 'page_manager_node_view':
      case 'page_manager_contact_site':
        // Is this a Panels page?
        $vars['classes_array'][] = 'page-panels';
        break;
    }
  }
  // strip rubbish classes if extra classes not set
  if (theme_get_setting('extra_page_classes') == 0) {
    foreach ($vars['classes_array'] as $i => $class) {
      if (preg_match('/page*/', $class)) {
        unset($vars['classes_array'][$i]);
      }
    }
  }

  // Load Styles and JS
  $theme_name = $theme_key;

  // Set the path to the directory where our CSS files are saved
  $path = "public://at_css";
  $file = '';

  // Set a variable for the mode
  $mode = theme_get_setting('production_mode');

  /**
   * Responsive Stylesheet Loader: Development Mode
   *
   * We only load these files if:
   * - 1: We're not in production mode
   * - 2: Responsiveness is not disabled
   *
   * When in production mode these files are aggregated into one file
   * and loaded using load_subtheme_responsive_styles().
   *
   * You can disable responsive capability using the theme settings - look under
   * the Global settings. Do not delete or alther this - use the theme setting!
   */
   if ($mode == FALSE) {
     if (theme_get_setting('disable_responsive_styles') == FALSE) {

      // Get the media query for each device group.
      $smartphone_portrait_media_query  = theme_get_setting('smartphone_portrait_media_query');
      $smartphone_landscape_media_query = theme_get_setting('smartphone_landscape_media_query');
      $tablet_portrait_media_query      = theme_get_setting('tablet_portrait_media_query');
      $tablet_landscape_media_query     = theme_get_setting('tablet_landscape_media_query');
      $desktop_media_query              = theme_get_setting('bigscreen_media_query');

      // Next build a keyed array of CSS file-names as key, and media query as value.
      // The file names must match the files in your themes CSS directory, if
      // you change them you must update to match.
      $files = array(
        'responsive.smartphone.portrait'  => $smartphone_portrait_media_query,
        'responsive.smartphone.landscape' => $smartphone_landscape_media_query,
        'responsive.tablet.portrait'      => $tablet_portrait_media_query,
        'responsive.tablet.landscape'     => $tablet_landscape_media_query,
        'responsive.desktop'              => $desktop_media_query,
      );

      // Add the cascading stylesheet if enabled
      if (theme_get_setting('enable_cascading_media_queries') == TRUE) {
        $cascade_media_query = theme_get_setting('cascade_media_query');
        $item = array('responsive.cascade' => $cascade_media_query);
        $files = array_merge($item, $files);
      }

      // Loop over the files array and load each CSS.
      // load_subtheme_responsive_styles() takes 3 arguements:
      // - $css_file - the name of the css file
      // - $media_query - the media query from theme settings to use for the media attribute
      // - $theme_name - to generate the path-to-theme for drupal_add_css()
      foreach ($files as $key => $value) {
        $css_file = $key . '.css';
        $media_query = $value;
        load_subtheme_responsive_styles($css_file, $media_query, $theme_name);
      }
    }
  }

  // In Production mode we only load one file for responsive styles.
  // We use a media query to prevent non media query browsers from downloading it.
  // The embedded media queries in this file take precedence always.
  if ($mode == TRUE) {
    $file = "$theme_name.responsive.styles.css";
    $media_query = 'only screen and (min-width:1px)';
    if (!empty($file)) {
      $filepath = "$path/$file";
      drupal_add_css($filepath, array(
        'preprocess' =>  variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
        'group' => CSS_THEME,
        'media' => $media_query,
        'weight' => 99,
        'every_page' => TRUE,
        )
      );
    }
  }

  // In layout.inc we built 3 layout stylesheets:
  // 1. themeName.default.layout.css - this always loads, so we just load it
  // 2. themeName.responsive.layout.css - only loads if responsive capability is enabled
  // 3. themeName.lt-ie9.layout.css - only loads if responsive capability is enabled
  //
  // Now we need to load them, but only under certain conditions:
  // a. Is the default layout the standard layout?
  // b. Is the responsive capability disabled?
  //
  // If either a or b equate to TRUE, then we are not doing mobile first
  // If a is FALSE then this is mobile first
  // If b is FALSE then we are doing responsive design

  // Load the default layout, but only if Standard layout is default, otherwise we do nothing
  if (theme_get_setting('global_default_layout') == 'standard-layout') {
    $file = "$theme_name.default.layout.css";
    if (!empty($file)) {
      $filepath = "$path/$file";
      drupal_add_css($filepath, array(
        'preprocess' => TRUE,
        'group' => CSS_THEME,
        'media' => 'screen, handheld',
        'every_page' => TRUE,
        )
      );
    }
  }
  // Next we check if we need to load the responsive layout
  if (theme_get_setting('disable_responsive_styles') == 0) {
    $file = "$theme_name.responsive.layout.css";
    if (!empty($file)) {
      $filepath = "$path/$file";
      drupal_add_css($filepath, array(
        'preprocess' => variable_get('preprocess_css', '') == 1 ? TRUE : FALSE,
        'group' => CSS_THEME,
        'media' => 'screen, handheld',
        'every_page' => TRUE,
        )
      );
    }
  }

  // If load_respondjs is enabled, load it
  if (theme_get_setting('load_respondjs') == 1) {
    drupal_add_js(drupal_get_path('theme', 'adaptivetheme') . '/js/respond.js',
      array(
        'type' => 'file',
        'scope' => 'footer',
        'group' => JS_THEME,
        'preprocess' => TRUE,
        'cache' => TRUE,
      )
    );
  }

  // The lt ie9 loads only under one very specific set of conditions:
  // 1. Responsive capabilities are ON
  // 2. Mobile first is ON
  // 3. Respond.js is OFF
  // Note this excludes IEMobile 7
  if (theme_get_setting('disable_responsive_styles') == 0) {
    if (theme_get_setting('global_default_layout') != 'standard-layout') {
      if (theme_get_setting('load_respondjs') == 0) {
        $ie_file = "$theme_name.lt-ie9.layout.css";
        if (!empty($ie_file)) {
          $ie_filepath = "$path/$ie_file";
          drupal_add_css($ie_filepath, array(
            'group' => CSS_THEME,
            'media' => 'screen',
            'browsers' => array(
              'IE' => '(lt IE 9)&(!IEMobile 7)',
              '!IE' => FALSE,
              ),
            'preprocess' => TRUE,
            )
          );
        }
      }
    }
  }

  // Load window size bookmarklet
  if (theme_get_setting('show_window_size') == 1) {
    drupal_add_js(drupal_get_path('theme', 'adaptivetheme') . '/js/window-size.js',
      array(
        'type' => 'file',
        'scope' => 'footer',
        'group' => JS_THEME,
        'preprocess' => TRUE,
        'cache' => TRUE,
      )
    );
  }

  // add a body class if the site name is hidden
  if (theme_get_setting('toggle_name') == FALSE) {
    $vars['classes_array'][] = 'site-name-hidden';
  }

  // add a body class for exposing region
  if (theme_get_setting('expose_regions') == TRUE) {
    $vars['classes_array'][] = 'debug-regions';
    $vars['classes_array'][] = 'debug-panels';
    drupal_add_css(drupal_get_path('theme', 'adaptivetheme') . '/css/debug-regions.css');
  }
}

// Process variables for html.tpl.php
function adaptivetheme_process_html(&$vars) {
  // Load the panels test
  if (theme_get_setting('load_all_panels') == TRUE) {
    if (drupal_is_front_page()) {
       $panels_test = file_get_contents(drupal_get_path('theme', 'adaptivetheme') . '/layouts/panels_test.html');
       $vars['page'] = $panels_test;
    }
  }
}

// Preprocess variables for page.tpl.php
function adaptivetheme_preprocess_page(&$vars) {
  global $theme_key;

  // Set up logo element
  $vars['logo_alt_text'] = check_plain(variable_get('site_name', '')) . ' ' . t('logo');
  $vars['logo_img'] = $vars['logo'] ? '<img src="' . check_url($vars['logo']) . '" alt="' . $vars['logo_alt_text'] . '"/>' : '';
  $vars['linked_site_logo'] = $vars['logo_img'] ? l($vars['logo_img'], '<front>', array(
    'attributes' => array(
      'title' => t('Home page')
    ),
    'html' => TRUE,
    )
  ) : '';
  // Hide site name if toggled off
  if (theme_get_setting('toggle_name') == FALSE) {
    $vars['visibility'] = 'element-invisible';
    $vars['hide_site_name'] = TRUE;
  }
  else {
    $vars['visibility'] = '';
    $vars['hide_site_name'] = FALSE;
  }
  $sitename = filter_xss_admin(variable_get('site_name', 'Drupal')); /* Convert entitles if used, cunning... */
  $vars['site_name'] = $sitename ? l($sitename, '<front>', array(
    'attributes' => array(
      'title' => t('Home page')),
    )
  ) : '';

  // Build a variable for the main menu
  if (isset($vars['main_menu'])) {
    $text = t('Main menu');
    if (module_exists('block')) {
      $text = block_get_blocks_by_region('menu_bar') ? t('Navigation') : t('Main menu');
    }
    $vars['primary_navigation'] = theme('links', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('menu', 'primary-menu', 'clearfix'),
       ),
      'heading' => array(
        'text' => $text,
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }

  // Build a variable for the secondary menu
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_navigation'] = theme('links', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('menu', 'secondary-menu', 'clearfix'),
      ),
      'heading' => array(
        'text' => t('Secondary navigation'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }

  // Build variables for Primary and Secondary local tasks
  $vars['primary_local_tasks'] = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();

  // Add back the $search_box var to D7
  if (module_exists('search')) {
    $search_box = drupal_get_form('search_form');
    $vars['search_box'] = '<div id="search-box">' . drupal_render($search_box) . '</div>';
  }

  // Remove the infernal feed icons
  if (theme_get_setting('feed_icons_hide') == TRUE) {
    $vars['feed_icons'] = '';
  }

  // Remove block system main, just from the front page
  if (drupal_is_front_page()) {
    if (theme_get_setting('unset_block_system_main_front') == TRUE) {
      $vars['page']['content']['system_main'] = '';
    }
  }

  // Attribution variable used in admin theme and some others
  $vars['attribution'] = "<small id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";

  // Work-around a stupid bug in Drupal 7
  if (arg(0) == 'user' && arg(1) == 'login') {
    drupal_set_title(t('User login'));
  }
  if (arg(0) == 'user' && arg(1) == 'password') {
    drupal_set_title(t('Request new password'));
  }
  if (arg(0) == 'user' && arg(1) == 'register') {
    drupal_set_title(t('Create new account'));
  }

}

// Process variables for page.tpl.php
function adaptivetheme_process_page(&$vars) {
  // Call our helper function to wrap menus in extra markup
  if (!empty($vars['primary_navigation'])) {
    $vars['primary_navigation'] = _theme_menu_variables($vars['primary_navigation'], 'primary');
  }
  if (!empty($vars['secondary_navigation'])) {
    $vars['secondary_navigation'] = _theme_menu_variables($vars['secondary_navigation'], 'secondary');
  }
}

// Preprocess variables for node.tpl.php
function adaptivetheme_preprocess_node(&$vars) {
  global $theme, $user;

  // Extra classes if required
  if (theme_get_setting('extra_article_classes')) {
    // Zebra
    $vars['classes_array'][] = $vars['zebra'];
    // Langauge
    if (module_exists('translation')) {
      if ($vars['node']->language) {
        global $language;
        $vars['classes_array'][] = 'node-lang-' . $vars['node']->language;
      }
    }
    // User picture?
    if ($vars['display_submitted'] && !empty($vars['picture'])) {
      $vars['classes_array'][] = 'node-with-picture';
    }
    // Class for each view mode, core assumes we only need to target teasers but neglects custom view modes or full
    if ($vars['view_mode'] != 'teaser') {
      $vars['classes_array'][] = drupal_html_class('node-' . $vars['view_mode']);
    }
  }

  // ARIA Role
  $vars['attributes_array']['role'][] = 'article';

  // Title and content classes
  $vars['title_attributes_array']['class'][] = 'node-title';
  $vars['content_attributes_array']['class'][] = 'node-content';

  // Build the submitted by and time elements
  $vars['datetime'] = format_date($vars['created'], 'custom', 'Y-m-d\TH:i:s\Z'); // PHP 'c' format is not proper ISO8601, we need to build it
  if (variable_get('node_submitted_' . $vars['node']->type, TRUE)) {
    $vars['submitted'] = t('Submitted by !username on !datetime',
      array(
        '!username' => $vars['name'],
        '!datetime' => '<time datetime="' . $vars['datetime'] . '" pubdate="pubdate">' . $vars['date'] . '</time>',
      )
    );
  }
  else {
    $vars['submitted'] = '';
  }

  // Unpublised?
  $vars['unpublished'] = '';
  if (!$vars['status']) {
    $vars['unpublished'] = '<div class="unpublished">' . t('Unpublished') . '</div>';
  }
}

// Process variables for node.tpl.php
function adaptivetheme_process_node(&$vars) {
  // Strip default drupal classes if not required  
  if (theme_get_setting('extra_article_classes') == 0) {
    $classes = explode(' ', $vars['classes']);
    if (in_array('node-sticky', $classes)) {
      $classes = str_replace('node-sticky', '', $classes);
    }
    if (in_array('node-promoted', $classes)) {
      $classes = str_replace('node-promoted', '', $classes);
    }
    if (in_array('node-teaser', $classes)) {
      $classes = str_replace('node-teaser', '', $classes);
    }
    if (in_array('node-preview', $classes)) {
      $classes = str_replace('node-preview', '', $classes);
    }
    $vars['classes'] = trim(implode(' ', $classes));
  }
}

// Preprocess variables for comment.tpl.php
function adaptivetheme_preprocess_comment(&$vars) {

  // Extra comment classes if required
  if (theme_get_setting('extra_comment_classes')) {
    // Zebra
    $vars['classes_array'][] = $vars['zebra'];
    // Position?
    if ($vars['id'] == 1) {
      $vars['classes_array'][] = 'first';
    }
    if ($vars['id'] == $vars['node']->comment_count) {
      $vars['classes_array'][] = 'last';
    }
    // Title hidden?
    if (theme_get_setting('comments_hide_title')) {
      $vars['classes_array'][] = 'comment-title-hidden';
    }
    // User picture?
    if ($vars['picture']) {
      $vars['classes_array'][] = 'comment-with-picture';
    }
    // Signature?
    if ($vars['signature']) {
      $vars['classes_array'][] = 'comment-with-signature';
    }
  }

  // Classes for comment title
  $vars['title_attributes_array']['class'][] = 'comment-title';
  // Title hidden?
  if (theme_get_setting('comments_hide_title')) {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }

  // Classes for comment content
  $vars['content_attributes_array']['class'][] = 'comment-content';

  // Build the submitted by and time elements
  $uri = entity_uri('comment', $vars['comment']);
  $uri['options'] += array('attributes' => array('rel' => 'bookmark'));
  $vars['title'] = l($vars['comment']->subject, $uri['path'], $uri['options']);
  $vars['permalink'] = l(t('Permalink'), $uri['path'], $uri['options']); // Permalinks are embedded in the time element, aka Wordpress
  $vars['created'] = '<span class="date-time permalink">' . l($vars['created'], $uri['path'], $uri['options']) . '</span>';
  $vars['datetime'] = format_date($vars['comment']->created, 'custom', 'Y-m-d\TH:i:s\Z'); // Generate the timestamp, PHP "c" format is wrong
  $vars['submitted'] = t('Submitted by !username on !datetime',
    array(
      '!username' => $vars['author'],
      '!datetime' => '<time datetime="' . $vars['datetime'] . '" pubdate="pubdate">' . $vars['created'] . '</time>',
    )
  );

  // Unpublished?
  $vars['unpublished'] = '';
  if ($vars['status'] == 'comment-unpublished') {
    $vars['unpublished'] = '<div class="unpublished">' . t('Unpublished') . '</div>';
  }
}

// Process variables for comment.tpl.php
function adaptivetheme_process_comment(&$vars) {
  // Strip default drupal classes if not required
  if (theme_get_setting('extra_comment_classes') == 0) {
    $classes = explode(' ', $vars['classes']);
    if (in_array('comment-by-anonymous', $classes)) {
      $classes = str_replace('comment-by-anonymous', '', $classes);
    }
    if (in_array('comment-by-node-author', $classes)) {
      $classes = str_replace('comment-by-node-author', '', $classes);
    }
    if (in_array('comment-by-viewer', $classes)) {
      $classes = str_replace('comment-by-viewer', '', $classes);
    }
    if (in_array('comment-new', $classes)) {
      $classes = str_replace('comment-new', '', $classes);
    }
    $vars['classes'] = trim(implode(' ', $classes));
  }
}

// Preprocess variables for block.tpl.php
function adaptivetheme_preprocess_block(&$vars) {

  // Generate the wrapper element, if there's a title use section
  $vars['block']->subject ? $vars['tag'] = 'section' : $vars['tag'] = 'div';

  // Add extra classes if required
  if (theme_get_setting('extra_block_classes')) {
    // Zebra
    $vars['classes_array'][] = $vars['block_zebra'];
    // Position?
    if ($vars['block_id'] == 1) {
      $vars['classes_array'][] = 'first';
    }
    if (isset($vars['block']->last_in_region)) {
      $vars['classes_array'][] = 'last';
    }
    // Count
    $vars['classes_array'][] = 'block-count-' . $vars['id'];
    // Region
    $vars['classes_array'][] = drupal_html_class('block-region-' . $vars['block']->region);
    // Delta
    $vars['classes_array'][] = drupal_html_class('block-' . $vars['block']->delta);
  }

  // Add classes to theme the horizontal block option
  if (theme_get_setting('horizontal_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['classes_array'][] = 'lb-h';
  }
  if (theme_get_setting('slider_login_block') && $vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['classes_array'][] = 'lb-s';
  }

  // Give our block titles and content some class
  $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['content_attributes_array']['class'][] = 'block-content';
  $vars['content_attributes_array']['class'][] = 'content';

  // Use nav element for menu blocks and provide a powerfull suggestion for all of them
  $nav_blocks = array('navigation', 'main-menu', 'management', 'user-menu');
  if (in_array($vars['block']->delta, $nav_blocks)) {
    $vars['tag'] = 'nav';
    array_unshift($vars['theme_hook_suggestions'], 'block__menu');
  }
  $nav_modules = array('superfish', 'nice_menus');
  if (in_array($vars['block']->module, $nav_modules)) {
    $vars['tag'] = 'nav';
    array_unshift($vars['theme_hook_suggestions'], 'block__menu');
  }

  // Hide the title for blocks in the menu bar
  if ($vars['block']->region == 'menu_bar') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
    $vars['classes_array'][] = 'menu-wrapper';
  }

  // Provide additional suggestions so the block__menu suggestion can be overridden easily
  $vars['theme_hook_suggestions'][] = 'block__' . $vars['block']->region . '__' . $vars['block']->module;
  $vars['theme_hook_suggestions'][] = 'block__' . $vars['block']->region . '__' . $vars['block']->delta;

  //dsm($vars['theme_hook_suggestions']); // debug suggestions

  // Add ARIA Roles to blocks, thank-you to Jacine http://drupal.org/user/88931 for cleaning up this code :)
  $roles = array(
    'complementary' => array(
      'aggregator',
      'help',
      'locale',
      'poll',
      'profile',
      'node' => array('syndicate'),
      'system' => array('powered-by', 'help'),
      'user' => array('new', 'online'),
    ),
    'navigation' => array(
      'blog',
      'book',
      'comment',
      'forum',
      'menu',
      'menu_block',
      'node' => array('recent'),
      'shortcut',
      'statistics',
      'system' => array_keys(menu_list_system_menus()),
      'superfish',
      'nice_menus',
    ),
    'search' => array(
      'search',
    ),
    'form' => array(
      'user' => array('login'),
    ),
  );
  foreach ($roles as $role => $module) {
    if (!is_array($role) && in_array($vars['block']->module, $module)) {
      $vars['attributes_array']['role'] = "$role";
    }
    elseif (is_array($role)) {
      foreach ($role as $module => $delta) {
        if ($vars['block']->module == $module && in_array($vars['block']->delta, $delta)) {
          $vars['attributes_array']['role'] = "$role";
        }
      }
    }
  }

}

// Changes the search form to use the "search" input element of HTML5 (from the Boron theme)
function adaptivetheme_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

// Modify search results based on theme settings
function adaptivetheme_preprocess_search_result(&$vars) {
  $result = $vars['result'];
  $vars['url'] = check_url($result['link']);
  $vars['title'] = check_plain($result['title']);
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
  $vars['info_split'] = $info;
  $vars['info'] = implode(' - ', $info);
  $vars['info_separator'] = filter_xss(theme_get_setting('search_info_separator'));
}

//Override or insert vars into the field template.
function adaptivetheme_preprocess_field(&$vars) {
  $element = $vars['element'];

  // add a view mode class to fields
  $vars['classes_array'][] = 'view-mode-' . $element['#view_mode'];

  // provide an variable we can use to test the view mode inside templates
  $vars['field_view_mode'] = $element['#view_mode'] ? $element['#view_mode'] : '';

  // enable captioning capability for image fields
  if ($element['#field_type'] == 'image') {
    $vars['image_caption_teaser'] = FALSE;
    $vars['image_caption_full'] = FALSE;
    if (theme_get_setting('image_caption_teaser') == 1) {
      $vars['image_caption_teaser'] = TRUE;
    }
    if (theme_get_setting('image_caption_full') == 1) {
      $vars['image_caption_full'] = TRUE;
    }
  }
}

// Preprocess variables for region.tpl.php
function adaptivetheme_preprocess_region(&$vars, $hook) {
  if (strpos($vars['region'], 'sidebar_') === 0) {
    $vars['classes_array'][] = 'sidebar';
  }

  //  if (substr($vars['region'], -4, 4) == '_top') {
  //    $vars['classes_array'][] = 'conditional-stack-top';
  //  }
  //  if (substr($vars['region'], -7, 7) == '_bottom') {
  //    $vars['classes_array'][] = 'conditional-stack-bottom';
  //  }

  // Add classes to conditional stack regions
  $conditional_stack_regions = array(
    'three_25_25_50_top',
    'three_25_25_50_bottom',
    'three_25_50_25_top',
    'three_25_50_25_bottom',
    'three_50_25_25_top',
    'three_50_25_25_bottom',
    'three_33_top',
    'three_33_bottom',
    'two_50_top',
    'two_50_bottom',
    'two_33_66_top',
    'two_33_66_bottom',
    'two_66_33_top',
    'two_66_33_bottom',
    'two_brick_top',
    'two_brick_middle',
    'two_brick_bottom',
    'threecol_inset_left_top',
    'threecol_inset_left_bottom',
    'threecol_inset_right_top',
    'threecol_inset_right_bottom',
  );
  if (in_array($vars['region'], $conditional_stack_regions)){
    $vars['classes_array'][] = 'region-conditional-stack';
  }

}

// Process variables for aggregator-item.tpl.php
function adaptivetheme_preprocess_aggregator_item(&$vars) {
  $item = $vars['item'];
  $vars['datetime'] = format_date($item->timestamp, 'custom', 'Y-m-d\TH:i:s\Z');
}

// Process variables for maintenance-page.tpl.php
function adaptivetheme_preprocess_maintenance_page(&$vars) {
  $vars['attribution'] = "<small id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";
}
