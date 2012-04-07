<?php
/**
 * All Preprocess functions for templates and theme fucntions.
 * If you need to add or modify preprocess functions do it in your sub-theme.
 */

/**
 * Preprocess variables for html.tpl.php
 */
function adaptivetheme_preprocess_html(&$vars) {
  global $theme_key, $language;
  $theme_name = $theme_key;

  // Set some paths
  $path_to_at_core = drupal_get_path('theme', 'adaptivetheme');
  $vars['base_path'] = base_path();

  // Get the info file data
  $info = at_get_info($theme_name);

  // DOCTYPE
  // AT no longer uses doctype switching or $rdf_profile, these maintain
  // backwards compatibility with pre 7.x-2.1 versions of html.tpl.php
  $vars['doctype'] = '<!DOCTYPE html>' . "\n";
  $vars['rdf_profile'] = '';

  // Clean up the lang attributes.
  $vars['html_attributes'] = 'lang="' . $language->language . '" dir="' . $language->dir . '"';

  // Responsive Styles.
  // AT has two modes - Production and Development. Depending on the mode it
  // will load different files. Production mode assume you have finished CSS
  // development and will not need to update styles in the responsive
  // stylesheets, if you do you will need to re-save the theme settings at
  // least once to re-write your $theme_name.responsive.styles.css file which
  // is in public files.

  // Set the path to the directory where our CSS files are saved
  $path = variable_get('theme_' . $theme_name . '_files_directory');
  $file = '';

  // Development Mode
  // These files are for "design style" only, the layout is handled
  // seperately.
  //
  // We only load these files if:
  // - 1: We're not in production mode
  // - 2: Responsiveness is not disabled
  //
  // When in production mode these files are aggregated into one file
  // and loaded using load_subtheme_responsive_styles().
  //
  // You can disable responsive capability using the theme settings - look under
  // the Global settings. Do not delete or alter this - use the theme setting!

  if (theme_get_setting('disable_responsive_styles', $theme_name) === 0) {
    if (theme_get_setting('production_mode', $theme_name) === 0) {
      // Build a keyed array of CSS file-names as key, and media query as value.
      // The file names must match the files in your themes CSS directory, if
      // you change them you must update to match. theme_get_setting() caches
      // and is extremely cheap, in any case we're in development mode.
      $files = drupal_static('adaptivetheme_preprocess_html_development_responsive_styles');
      if (empty($files)) {
        $files = array(
          'responsive.smartphone.portrait'  => theme_get_setting('smartphone_portrait_media_query', $theme_name),
          'responsive.smartphone.landscape' => theme_get_setting('smartphone_landscape_media_query', $theme_name),
          'responsive.tablet.portrait'      => theme_get_setting('tablet_portrait_media_query', $theme_name),
          'responsive.tablet.landscape'     => theme_get_setting('tablet_landscape_media_query', $theme_name),
          'responsive.desktop'              => theme_get_setting('bigscreen_media_query', $theme_name),
        );

        // Add the cascading stylesheet if enabled
        if (theme_get_setting('enable_cascading_media_queries', $theme_name) === 1) {
          $cascade_media_query = theme_get_setting('cascade_media_query', $theme_name);
          $item = array('responsive.cascade' => $cascade_media_query);
          $files = array_merge($item, $files);
        }
      }

      // Loop over the files array and load each CSS file.
      // load_subtheme_responsive_styles() takes three parameters:
      // - $css_file - the name of the css file
      // - $media_query - the media query from theme settings
      // - $theme_name - to generate the path-to-theme for drupal_add_css()
      foreach ($files as $key => $value) {
        $filepath = drupal_get_path('theme', $theme_name) . '/css/' . $key . '.css';
        $media_query = $value;
        load_subtheme_responsive_styles($filepath, $media_query, $theme_name, $weight = 200);
      }
    }

    // Production Mode
    // In Production mode we only load one file for our design styles.
    // We use a media query to prevent non media query browsers from downloading it.
    // The embedded media queries in this file take precedence always.
    else {
      $filepath = $path . '/' . $theme_name . '.responsive.styles.css';
      $media_query = 'only screen'; // keyword "only" should hide this from older user agents
      load_subtheme_responsive_styles($filepath, $media_query, $theme_name, $weight = 200);
    }
  }

  // Layout
  // Each time you save the theme settings AT builds 3 layout stylesheets:
  // 1. themeName.default.layout.css
  // 2. themeName.responsive.layout.css
  // 3. themeName.lt-ie9.layout.css
  //
  // Now we need to load them, but only under certain conditions:
  // a. Is the default layout the standard layout?
  // b. Is the responsive capability disabled?
  //
  // If either a or b equate to TRUE, then we are NOT doing mobile first.
  // If a is FALSE then this is mobile first.
  // If b is FALSE then we are doing responsive design.

  // First set a boolean to test which IE stylesheet to load.
  $load_ie8_css = TRUE;

  // Load the default layout if Standard layout is default or responsive styles are disabled
  if (theme_get_setting('global_default_layout', $theme_name) === 'standard-layout' || theme_get_setting('disable_responsive_styles', $theme_name) === 1) {
    $file = $theme_name . '.default.layout.css';
    $filepath = $path . '/' . $file;
    if (file_exists($filepath)) {
      drupal_add_css($filepath, array(
        'preprocess' => TRUE,
        'group' => CSS_THEME,
        'media' => 'screen',
        'every_page' => TRUE,
        )
      );
    }
  }

  // Next - check if we need to load the responsive layout
  if (theme_get_setting('disable_responsive_styles', $theme_name) === 0) {

    // Responsiveness is not disabled, load the responsive layout
    $filepath = $path . '/' . $theme_name . '.responsive.layout.css';
    $media_query = 'only screen'; // keyword "only" should hide this from older user agents
    load_subtheme_responsive_styles($filepath, $media_query, $theme_name, $weight = 100);

    // The lt-ie9.layout.css stylesheet loads under one very specific set of conditions:
    // 1. Responsive capabilities are ON
    // 2. Mobile first is ON
    // 3. Respond.js is OFF
    // Note this excludes IEMobile 7, screw you IEMobile 7
    if (theme_get_setting('global_default_layout', $theme_name) !== 'standard-layout') {
      if (theme_get_setting('load_respondjs', $theme_name) === 0) {
        $load_ie8_css = FALSE;
        $filepath = $path . '/' . $theme_name . '.lt-ie9.layout.css';
        $ie_style['media'] = 'screen';
        $ie_style['condition'] = '(lt IE 9)&(!IEMobile 7)';
        load_subtheme_conditional_styles($filepath, $ie_style, $theme_name);
      }
    }
  }

  // The lt-ie8.layout.css file loads under the exact opposite conditions to the lt-ie9 file.
  // In other words, when lt-ie9 is loading, this is not, when it isn't, this loads. This
  // file only ever holds panels layout overrides for legacy IE browsers and is disabled by
  // default in theme settings, screw you IE7.
  if ($load_ie8_css == TRUE) {
    if (theme_get_setting('load_ltie8css', $theme_name) === 1) {
      $filepath = $path . '/' . $theme_name . '.lt-ie8.layout.css';
      $ie_style['media'] = 'screen';
      $ie_style['condition'] = 'lt IE 8';
      load_subtheme_conditional_styles($filepath, $ie_style);
    }
  }

  // General IE stylesheets from the info file
  if (array_key_exists('ie_stylesheets', $info)) {
    $ie_style = '';
    foreach ($info['ie_stylesheets'] as $media => $stylesheets) {
      // Set default value for media in case the themer forgets, all is an "assumed" value an not printed in the output.
      if (is_numeric($media)) {
        $media = 'all';
      }
      foreach ($stylesheets as $condition => $ie_styles_path) {
        $ie_style['media'] = $media;
        $ie_style['condition'] = $condition;
        $ie_style['path'] = $ie_styles_path;
      }
      $filepath = drupal_get_path('theme', $theme_name) . '/' . $ie_style['path'];
      load_subtheme_conditional_styles($filepath, $ie_style);
    }
  }

  // Build an array of polyfilling scripts
  $vars['polyfills_array'] = drupal_static('adaptivetheme_preprocess_html_polyfills_array');
  if (empty($vars['polyfills_array'])) {
    // Info file loaded conditional scripts
    $theme_path = drupal_get_path('theme', $theme_name);
    if (array_key_exists('ie_scripts', $info)) {
      foreach ($info['ie_scripts'] as $condition => $ie_scripts_path) {
        foreach ($ie_scripts_path as $key => $value) {
          $filepath = $theme_path . '/' . $value;
          $vars['polyfills_array']['info'][$condition][] = at_theme_script($filepath);
        }
      }
    }
    // AT Core Polyfills
    $polly = '';
    $polly_settings_array = array(
      'load_respondjs',
      'load_html5js',
      'load_boxsizinghtc',
      'load_pie',
      'load_selectivizr',
      'load_scalefixjs', // loaded directly by polly_wants_a_cracker(), its never returned
    );
    foreach ($polly_settings_array as $polly_setting) {
      $polly[$polly_setting] = theme_get_setting($polly_setting, $theme_name);
    }
    $backed_crackers = polly_wants_a_cracker($polly, $theme_name);
    foreach ($backed_crackers as $cupboard => $flavors) {
      foreach ($flavors as $key => $value) {
        $filepath = $path_to_at_core . '/' . $value;
        $vars['polyfills_array']['at_core'][$cupboard][] = at_theme_script($filepath);
      }
    }
  }

  // Load fonts
  if(theme_get_setting('enable_extensions', $theme_name) === 1) {
    if (theme_get_setting('enable_font_settings', $theme_name) === 1) {

      // Google fonts
      $google_fonts = drupal_static('adaptivetheme_preprocess_html_google_fonts', FALSE);
      if (empty($google_fonts)) {
        $google_fonts = load_google_fonts($theme_name);
      }
      if (!empty($google_fonts)) {
        // No protocol, we need to support https also
        drupal_add_css('//fonts.googleapis.com/css?family=' . $google_fonts, array(
          'group' => CSS_THEME,
          'type' => 'external',
          'weight' => -100,
          'preprocess' => FALSE,
          )
        );
      }

      // Load the fonts CSS from public files
      $filepath = $path . '/' . $theme_name . '.fonts.css';
      if (file_exists($filepath)) {
        drupal_add_css($filepath, array(
          'preprocess' => TRUE,
          'group' => CSS_THEME,
          'media' => 'screen',
          'every_page' => TRUE,
          )
        );
      }
    }

    // Load the custom CSS if enabled
    if (theme_get_setting('enable_custom_css', $theme_name) === 1) {
      $filepath = $path . '/' . $theme_name . '.custom.css';
      if (file_exists($filepath)) {
        drupal_add_css($filepath, array(
          'preprocess' => TRUE,
          'group' => CSS_THEME,
          'weight' => 1000,
          'media' => 'screen',
          'every_page' => TRUE,
          )
        );
      }
    }
  }

  // Skip link
  // Set the skip link target id
  $vars['skip_link_target'] = '#main-content';
  if (theme_get_setting('skip_link_target', $theme_name)) {
    $vars['skip_link_target'] = theme_get_setting('skip_link_target', $theme_name);
  }

  // Extra Classes
  if (theme_get_setting('extra_page_classes', $theme_name) === 1) {

    // Set a class based on the language
    if (function_exists('locale')) {
      $vars['classes_array'][] = 'lang-' . $vars['language']->language;
    }

    // Site name class, for multi site installs that need to target styles
    // at each site seperately (mitigates things like block-id clashes)
    if (!empty($vars['head_title_array']['name'])) {
      $vars['classes_array'][] = 'site-name-' . drupal_html_class($vars['head_title_array']['name']);
    }

    // Classes for theming based on context
    if (!$vars['is_front']) {
      // Add unique class for each page.
      $path = drupal_get_path_alias($_GET['q']);
      // Add unique class for each website section.
      list($section, ) = explode('/', $path, 2);
      $arg = explode('/', $_GET['q']);
      if ($arg[0] === 'node') {
        if (isset($arg[1]) && $arg[1] === 'add') {
          $section = 'node-add';
        }
        elseif (isset($arg[2]) && is_numeric($arg[1]) && ($arg[2] === 'edit' || $arg[2] === 'delete')) {
          $section = 'node-' . $arg[2];
        }
      }
      $vars['classes_array'][] = drupal_html_class('section-' . $section);
    }

    // Set classes for views and panels pages
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

  } // end extra classes

  // Add a body class if the site name is hidden - state driven dynamic
  // classes are very useful and AT does this a lot
  if (theme_get_setting('toggle_name', $theme_name) === 0) {
    $vars['classes_array'][] = 'site-name-hidden';
  }

  // Extensions
  if(theme_get_setting('enable_extensions', $theme_name) === 1) {

    // Add theme setting classes and files if active
    $settings_css = array();

    // Page title classes - added in html.tpl.php on body, since title_attributes_array is useless
    if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
      $page_title_settings_array = array(
        'page_title_case',
        'page_title_weight',
        'page_title_alignment',
        'page_title_shadow',
      );
      foreach ($page_title_settings_array as $page_title_setting) {
        if ($heading_settings = theme_get_setting($page_title_setting, $theme_name)) {
          $vars['classes_array'][] = $heading_settings;
        }
      }
    }

    // Headings/titles
    if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
      $settings_css[] = 'at.settings.style.headings.css';
    }

    // Images
    if(theme_get_setting('enable_image_settings', $theme_name) === 1) {
      $settings_css[] = 'at.settings.style.image.css';
      $image_settings_array = array(
        'image_caption_full',
        'image_caption_teaser',
        'image_alignment',
        'image_alignment_teaser',
      );
      foreach ($image_settings_array as $setting) {
        if ($image_setting = theme_get_setting($setting, $theme_name)) {
          $vars['classes_array'][] = $image_setting;
        }
      }
    }

    // Login block
    if (theme_get_setting('enable_loginblock_settings', $theme_name) === 1) {
      $settings_css[] = 'at.settings.style.login.css';
    }

    // Load each stylesheet to support theme setting styles
    if (!empty($settings_css)) {
      foreach ($settings_css as $css_file) {
        $filepath = $path_to_at_core . '/css/' . $css_file;
        drupal_add_css($filepath, array(
          'preprocess' => TRUE,
          'group' => CSS_THEME,
          'media' => 'screen',
          'every_page' => TRUE,
          )
        );
      }
    }

  } // end extensions

  // Do some legacy stuff for 7.x-2.x themes that have google webfonts running on the old system
  // Scan for the existence of a "release" key and conditionally load google.web.fonts.inc
  if (!array_key_exists('release', $info) || ($info['release'] == '7.x-2.x')) {
    include_once($path_to_at_core . '/inc/google.web.fonts.inc');
  }

  // Finally we do some debugging/development stuff
  // add a body class for exposing region
  if (theme_get_setting('expose_regions', $theme_name) === 1 || theme_get_setting('load_all_panels', $theme_name) === 1) {
    $vars['classes_array'][] = 'debug-regions';
    $vars['classes_array'][] = 'debug-panels';
    drupal_add_css($path_to_at_core . '/css/debug-regions.css');
  }
  // Load window size bookmarklet
  if (theme_get_setting('show_window_size', $theme_name) === 1) {
    drupal_add_js($path_to_at_core . '/js/window-size.js',
      array(
        'type' => 'file',
        'scope' => 'footer',
        'group' => JS_THEME,
        'preprocess' => TRUE,
        'cache' => TRUE,
      )
    );
  }
}

/**
 * Preprocess variables for page.tpl.php
 */
function adaptivetheme_preprocess_page(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  // Set up logo element
  if (theme_get_setting('toggle_logo', $theme_name) === 1) {
    $vars['site_logo'] = drupal_static('adaptivetheme_preprocess_page_site_logo');
    if (empty($vars['site_logo'])) {
      $logo_path = check_url($vars['logo']);
      $logo_alt = check_plain(variable_get('site_name', 'Site logo'));
      $logo_vars = array('path' => $logo_path, 'alt' => $logo_alt, 'attributes' => array('class' => 'site-logo'));
      $logo_img = theme('image', $logo_vars);
      $vars['site_logo'] = $logo_img ? l($logo_img, '<front>', array(
        'attributes' => array(
          'title' => t('Home page')
        ),
        'html' => TRUE,
        )
      ) : '';
    }
    // Maintain backwards compatibility with 7.x-2.x sub-themes
    $vars['logo_img'] = isset($logo_img) ? $logo_img : '';
    $vars['linked_site_logo'] = $vars['site_logo'];
  }
  else {
    $vars['site_logo'] = '';
  }

  // Site name
  $vars['site_name'] = &drupal_static('adaptivetheme_preprocess_page_site_name');
  if (empty($vars['site_name'])) {
    $sitename = variable_get('site_name', 'Drupal');
    $vars['site_name'] = l($sitename, '<front>', array('attributes' => array('title' => t('Home page'))));
  }

  // Hide the site name if toggled off, don't remove it
  if (theme_get_setting('toggle_name', $theme_name) === 0) {
    $vars['visibility'] = 'element-invisible';
    $vars['hide_site_name'] = TRUE;
  }
  else {
    $vars['visibility'] = '';
    $vars['hide_site_name'] = FALSE;
  }


  // Headings Classes
  // oh oh, whats this, a theme using the page $classes_array, unheard of!
  // This is all about scope - its easy to set these on body, but thats the wrong scope
  // imo. AT wants to target the right scope always and not abuse the body element. This
  // is not as DRY as it could be, particulary for blocks/nodes/comments etc but it does
  // open the door for more granularity (i.e. target one block or block type).
  if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
    $heading_settings_array = &drupal_static('adaptivetheme_preprocess_page_heading_settings');
    if (empty($heading_settings_array)) {
      $heading_settings_array = array();
      if ($vars['hide_site_name'] == FALSE) {
        $heading_settings_array[] = 'site_name_case';
        $heading_settings_array[] = 'site_name_weight';
        $heading_settings_array[] = 'site_name_alignment';
        $heading_settings_array[] = 'site_name_shadow';
      }
      if ($vars['site_slogan']) {
        $heading_settings_array[] = 'site_slogan_case';
        $heading_settings_array[] = 'site_slogan_weight';
        $heading_settings_array[] = 'site_slogan_alignment';
        $heading_settings_array[] = 'site_slogan_shadow';
      }
    }
    foreach ($heading_settings_array as $setting) {
      if ($heading_setting = theme_get_setting($setting, $theme_name)) {
        $vars['classes_array'][] = $heading_setting;
      }
    }
  }

  // Build a variable for the main menu
  if (isset($vars['main_menu'])) {
    $vars['primary_navigation'] = theme('links', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('menu', 'primary-menu', 'clearfix'),
       ),
      'heading' => array(
        'text' => t('Main menu'),
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
  if (theme_get_setting('feed_icons_hide', $theme_name) === 1) {
    $vars['feed_icons'] = '';
  }

  // Remove block system main, just from the front page
  if (drupal_is_front_page()) {
    if (theme_get_setting('unset_block_system_main_front', $theme_name) === 1) {
      $vars['page']['content']['system_main'] = ''; // Brutal but effective
    }
  }

  // Attribution variable used in admin theme and some others
  $vars['attribution'] = "<small class=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";

  // Work-around a stupid bug in Drupal 7
  if (arg(0) === 'user' && arg(1) === 'login') {
    drupal_set_title(t('User login'));
  }
  if (arg(0) === 'user' && arg(1) === 'password') {
    drupal_set_title(t('Request new password'));
  }
  if (arg(0) === 'user' && arg(1) === 'register') {
    drupal_set_title(t('Create new account'));
  }
}

/**
 * Preprocess variables for region.tpl.php
 */
function adaptivetheme_preprocess_region(&$vars) {

  // Add a "sidebar" class to sidebar regions
  if (strpos($vars['region'], 'sidebar_') === 0) {
    $vars['classes_array'][] = 'sidebar';
  }

  // Add classes to conditional stack regions in Gpanel regions, in the Panels layout plugins these are hard coded.
  $conditional_stack_regions = &drupal_static(__FUNCTION__, array());
  if (empty($conditional_stack_regions)) {
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
  }
  if (in_array($vars['region'], $conditional_stack_regions)){
    $vars['classes_array'][] = 'region-conditional-stack';
  }
}

/**
 * Preprocess variables for block.tpl.php
 */
function adaptivetheme_preprocess_block(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  // The menu bar region gets special treatment for the block template
  if ($vars['block']->region === 'menu_bar') {
    // Hide titles, very unlikey to want it show, ever
    $vars['title_attributes_array']['class'][] = 'element-invisible';
    $vars['classes_array'][] = 'menu-wrapper menu-bar-wrapper clearfix';
  }

  // Add extra classes if required
  if (theme_get_setting('extra_block_classes', $theme_name) === 1) {

    // Zebra
    $vars['classes_array'][] = $vars['block_zebra'];

    // Position?
    if ($vars['block_id'] === 1) {
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

  // Add title style classes, these are loaded via the classes_arry and not title_attributes_array
  // so we can style the whole block depending on the title styles.
  if (isset($vars['block']->subject)) {
    if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
      $settings_array = &drupal_static('adaptivetheme_preprocess_block_heading_settings');
      if (empty($settings_array)) {
        $settings_array = array(
          'block_title_case',
          'block_title_weight',
          'block_title_alignment',
          'block_title_shadow',
        );
      }
      foreach ($settings_array as $setting) {
        if ($heading_setting = theme_get_setting($setting, $theme_name)) {
          $vars['classes_array'][] = $heading_setting;
        }
      }
    }
  }

  // Add classes to theme the horizontal block option
  if (theme_get_setting('horizontal_login_block', $theme_name) === 1 && $vars['block']->module === 'user' && $vars['block']->delta === 'login') {
    $vars['classes_array'][] = 'lb-h';
  }
  if (theme_get_setting('slider_login_block', $theme_name) === 1 && $vars['block']->module === 'user' && $vars['block']->delta === 'login') {
    $vars['classes_array'][] = 'lb-s';
  }

  // Give our block titles and content some additional class
  $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['content_attributes_array']['class'][] = 'block-content';
  $vars['content_attributes_array']['class'][] = 'content';

  // Add Aria Roles via attributes
  switch ($vars['block']->module) {
    case 'system':
      switch ($vars['block']->delta) {
        case 'main':
          // Note: the "main" role goes in the page.tpl, not here.
          break;
        case 'help':
        case 'powered-by':
          $vars['attributes_array']['role'] = 'complementary';
          break;
        default:
          // Any other "system" block is a menu block.
          $vars['attributes_array']['role'] = 'navigation';
          break;
      }
      break;
    case 'menu':
    case 'menu_block':
    case 'blog':
    case 'book':
    case 'comment':
    case 'forum':
    case 'shortcut':
    case 'statistics':
      $vars['attributes_array']['role'] = 'navigation';
      break;
    case 'search':
      $vars['attributes_array']['role'] = 'search';
      break;
    case 'help':
    case 'aggregator':
    case 'locale':
    case 'poll':
    case 'profile':
      $vars['attributes_array']['role'] = 'complementary';
      break;
    case 'node':
      switch ($vars['block']->delta) {
        case 'syndicate':
          $vars['attributes_array']['role'] = 'complementary';
          break;
        case 'recent':
          $vars['attributes_array']['role'] = 'navigation';
          break;
      }
      break;
    case 'user':
      switch ($vars['block']->delta) {
        case 'login':
          $vars['attributes_array']['role'] = 'form';
          break;
        case 'new':
        case 'online':
          $vars['attributes_array']['role'] = 'complementary';
          break;
      }
      break;
  }
}

/**
 * Preprocess variables for node.tpl.php
 */
function adaptivetheme_preprocess_node(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  // Extra classes if required
  if (theme_get_setting('extra_article_classes', $theme_name) === 1) {

    // Zebra
    $vars['classes_array'][] = $vars['zebra'];

    // Langauge
    if (module_exists('translation')) {
      if ($vars['node']->language) {
        $vars['classes_array'][] = 'node-lang-' . $vars['node']->language;
      }
    }

    // User picture?
    if ($vars['display_submitted'] && !empty($vars['picture'])) {
      $vars['classes_array'][] = 'node-with-picture';
    }

    // Class for each view mode, core assumes we only need to target teasers but neglects custom view modes or full
    if ($vars['view_mode'] !== 'teaser') {
      $vars['classes_array'][] = drupal_html_class('node-' . $vars['view_mode']);
    }
  }

  // Add title style classes, as per blocks these are added to classes_array not title_attraibutes_array
  // so we can style the entire node based on how the title wants to be handled (such as alignmment)
  if (isset($vars['title'])) {
    if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
      $settings_array = &drupal_static('adaptivetheme_preprocess_node_heading_settings');
      if (empty($settings_array)) {
        $settings_array = array(
          'node_title_case',
          'node_title_weight',
          'node_title_alignment',
          'node_title_shadow',
        );
      }
      foreach ($settings_array as $setting) {
        if ($heading_setting = theme_get_setting($setting, $theme_name)) {
          $vars['classes_array'][] = $heading_setting;
        }
      }
    }
  }

  // ARIA Role
  $vars['attributes_array']['role'][] = 'article';

  // Title and content classes
  $vars['title_attributes_array']['class'][] = 'node-title';
  $vars['content_attributes_array']['class'][] = 'node-content';

  // Build the submitted by and time elements
  $vars['datetime'] = format_date($vars['created'], 'custom', 'Y-m-d\TH:i:s\Z'); // PHP 'c' format is not proper ISO8601!
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
    $vars['unpublished'] = '<p class="unpublished">' . t('Unpublished') . '</p>';
  }
}

/**
 * Preprocess variables for comment.tpl.php
 */
function adaptivetheme_preprocess_comment(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  // Extra comment classes if required
  if (theme_get_setting('extra_comment_classes', $theme_name) === 1) {

    // Zebra
    $vars['classes_array'][] = $vars['zebra'];

    // Position?
    if ($vars['id'] === 1) {
      $vars['classes_array'][] = 'first';
    }
    if ($vars['id'] === $vars['node']->comment_count) {
      $vars['classes_array'][] = 'last';
    }

    // Title hidden?
    if (theme_get_setting('comments_hide_title', $theme_name) === 1) {
      $vars['classes_array'][] = 'comment-title-hidden';
    }

    // User picture?
    if (isset($vars['picture'])) {
      $vars['classes_array'][] = 'comment-with-picture';
    }

    // Signature?
    if (isset($vars['signature'])) {
      $vars['classes_array'][] = 'comment-with-signature';
    }
  }

  // Classes for comment title
  $vars['title_attributes_array']['class'][] = 'comment-title';

  // Title hidden?
  if (theme_get_setting('comments_hide_title', $theme_name) === 1) {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }

  // Add title style classes
  if (theme_get_setting('comments_hide_title', $theme_name) !== 1) {
    if(theme_get_setting('enable_heading_settings', $theme_name) === 1) {
      $settings_array = &drupal_static('adaptivetheme_preprocess_comment_heading_settings');
      if (empty($settings_array)) {
        $settings_array = array(
          'comment_title_case',
          'comment_title_weight',
          'comment_title_alignment',
          'comment_title_shadow',
        );
      }
      foreach ($settings_array as $setting) {
        if ($heading_setting = theme_get_setting($setting, $theme_name)) {
          $vars['classes_array'][] = $heading_setting;
        }
      }
    }
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
  if ($vars['status'] === 'comment-unpublished') {
    $vars['unpublished'] = '<p class="unpublished">' . t('Unpublished') . '</p>';
  }
}

/**
 * Preprocess variables for the search block form.
 */
function adaptivetheme_preprocess_search_block_form(&$vars) {
  // Changes the search form to use the "search" input element attribute (HTML5)
  // We have to replace the string because FAPI don't know what type=search is, i.e.
  // no way we can do this in a form alter hook.
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

/**
 * Preprocess variables for field.tpl.php
 */
function adaptivetheme_preprocess_field(&$vars) {
  global $theme_key;
  $theme_name = $theme_key;

  $element = $vars['element'];

  // add a view mode class to fields
  $vars['classes_array'][] = 'view-mode-' . $element['#view_mode'];

  // provide an variable we can use to test the view mode inside templates, I found
  // $element['#view_mode'] impossible to get to inside the template, which may be a
  // lack of understanding of render arrays and how we can access variables, or that
  // Drupal theme layer is so horribly inconsistant it drives me insane.
  $vars['field_view_mode'] = $element['#view_mode'] ? $element['#view_mode'] : '';

  // enable captioning capability for image fields
  if ($element['#field_type'] === 'image') {
    $vars['image_caption_teaser'] = FALSE;
    $vars['image_caption_full'] = FALSE;
    if (theme_get_setting('image_caption_teaser', $theme_name) === 1) {
      $vars['image_caption_teaser'] = TRUE;
    }
    if (theme_get_setting('image_caption_full', $theme_name) === 1) {
      $vars['image_caption_full'] = TRUE;
    }
  }
}

/**
 * Preprocess variables for aggregator-item.tpl.php
 */
function adaptivetheme_preprocess_aggregator_item(&$vars) {
  $item = $vars['item'];
  // We want the same highly accurate time stamp feature as nodes and comments
  $vars['datetime'] = format_date($item->timestamp, 'custom', 'Y-m-d\TH:i:s\Z');

  // Give our aggregator items some class
  $vars['classes_array'][] = 'feed-item clearfix';
  $vars['title_attributes_array']['class'][] = 'title feed-item-title';
  $vars['content_attributes_array']['class'][] = 'content feed-item-content';
}

/**
 * Preprocess variables for maintenance-page.tpl.php
 */
function adaptivetheme_preprocess_maintenance_page(&$vars) {
  $vars['attribution'] = "<small id=\"attribution\"><a href=\"http://adaptivethemes.com\">Premium Drupal Themes</a></small>";
}

/**
 * Preprocess variables for adaptivtheme_menubar()
 */
function adaptivetheme_preprocess_menubar(&$vars) {
  $type = $vars['type'];
  $vars['menubar_id'] = $type . '-menu-bar';
  $vars['classes_array'][] = 'nav clearfix';
  $vars['content_attributes_array']['role'][] = 'navigation';
  $vars['content_attributes_array']['class'][] = $type .'-menu-wrapper menu-wrapper clearfix';
  $vars['theme_hook_suggestions'][] = 'menubar__' . $type;
}
