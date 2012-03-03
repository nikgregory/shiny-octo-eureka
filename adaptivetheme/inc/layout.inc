<?php
// Validate some stuff: set form errors if the user accidentally does not enter a required value, we need
// to tell them where to look because these settings could be invisible! None of these fields
// are actually *required, we need to validate this ourselves and set helpful messages. IMO
// this is actually a bug in the form_state states system, if a form item is required AND
// is invisible, then it should be forced visible when the error is returned.
function at_theme_settings_validate($form, &$form_state) {
  $values = $form_state['values'];

  // Validate max_width values seperatly, they need a condition so the errors are actually visible 
  // on the page and not buried in tabs.
  if ($values['bigscreen_set_max_width'] == 1) {
    if (empty($values['bigscreen_max_width']['#default_value'])) {
      form_set_error('bigscreen_max_width', t('Standard layout max-width is empty - you forgot to enter a value for the max width!'));
    }
  }
  if ($values['tablet_landscape_set_max_width'] == 1) {
    if (empty($values['tablet_landscape_max_width']['#default_value'])) {
      form_set_error('tablet_landscape_max_width', t('Tablet landscape layout max-width is empty - you forgot to enter a value for the max width!'));
    }
  }
  if ($values['tablet_portrait_set_max_width'] == 1) {
    if (empty($values['tablet_portrait_max_width']['#default_value'])) {
      form_set_error('tablet_portrait_max_width', t('Tablet portrait max-width is empty - you forgot to enter a value for the max width!'));
    }
  }
  if ($values['smartphone_landscape_set_max_width'] == 1) {
    if (empty($values['smartphone_landscape_max_width']['#default_value'])) {
      form_set_error('smartphone_landscape_max_width', t('Smartphone landscape max-width is empty - you forgot to enter a value for the max width!'));
    }
  }
}

// Custom submit function to generate and save the layout CSS embedded in media queries, or not, as the case may be...
function at_theme_settings_submit($form, &$form_state) {

  // Setup some variables we're going to use a lot
  $values = $form_state['values'];
  $theme_name = $form_state['build_info']['args'][0];
  $is_default_layout = $values['global_default_layout'];
  $path_to_panels_css = drupal_get_path('theme', 'adaptivetheme') . '/layouts/css/';
  $path_to_responsive_css = drupal_get_path('theme', $theme_name) . '/css/';

  // Initialize the panels layout variable, this will by and large hold the panels layouts
  $panels_layout = '';
  // $layouts will hold all the page level layouts
  $layouts = array();
  // $responsive_styles will hold all the styles from the responsive stylesheets, wrapped in media queries.
  $responsive_styles = array();
  
  // If the cascade setting is true, load up these styles first
  if ($values['enable_cascading_media_queries'] == TRUE) {
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.cascade.css', FALSE);
    $responsive_styles[] = $responsive_css . "\n";
  }

  // Smartphone layout - portrait, we only need the media query values
  if ($values['smartphone_portrait_media_query']) {
    $device_group   = 'smartphone';
    $sidebar_first  = 100;
    $sidebar_second = 100;
    $media_query    = $values['smartphone_portrait_media_query'];
    $method         = 'one-col-stack';
    $sidebar_unit   = '%';
    $page_unit      = '%';
    $layout         = at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit);
    $comment        = "/* Smartphone portrait $method */\n";
    $width          = "\n" . '.container {width:100%}';
    $panels_layout  = '.at-panel .region {display:block;}';

    $styles = implode("\n", $layout) . $width . "\n" . $panels_layout;

    // If mobile first then smartphone portrait needs to do nothing, else its
    // problematic to set the layout later due to the cascade and inheritance
    if ($is_default_layout == 'smartphone-portrait') {
      $default_layout = '/* Smartphone portrait is default, hooray for doing mobile first! */';
      $css = "/* Smartphone portrait is the default layout, hooray for doing mobile first! */";
    }
    else {
      $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    }
    
    // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }
  // Smartphone layout - landscape
  if ($values['smartphone_landscape_layout']) {
    $device_group   = 'smartphone';
    $sidebar_first  = $values['smartphone_landscape_sidebar_first'];
    $sidebar_second = $values['smartphone_landscape_sidebar_second'];
    $media_query    = $values['smartphone_landscape_media_query'];
    $page_width     = $values['smartphone_landscape_page_width'];
    $method         = $values['smartphone_landscape_layout'];
    $sidebar_unit   = $values['smartphone_landscape_sidebar_unit'];
    $page_unit      = $values['smartphone_landscape_page_unit'];
    $layout         = at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit);
    $comment        = "/* Smartphone landscape $method */\n";
    $width          = "\n" . '.container {width:' . $page_width . $page_unit . '}';
    if ($values['smartphone_landscape_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['smartphone_landscape_max_width'];
      $max_width_unit = $values['smartphone_landscape_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width: ' . $max_width . $max_width_unit . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width: ' . $page_width . $page_unit . '}';
      }
    }
    // Panels layouts
    $panel_settings = array(
      'smartphone_landscape_two_50',
      'smartphone_landscape_two_33_66',
      'smartphone_landscape_two_66_33',
      'smartphone_landscape_two_brick',
      'smartphone_landscape_three_3x33',
      'smartphone_landscape_three_25_50_25',
      'smartphone_landscape_three_25_25_50',
      'smartphone_landscape_three_50_25_25',
      'smartphone_landscape_four_4x25',
      'smartphone_landscape_five_5x20',
      'smartphone_landscape_six_6x16',
      'smartphone_landscape_inset_left',
      'smartphone_landscape_inset_right',
    );
    $css_files = array();
    foreach ($panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }
    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] == 1) {
      foreach ($panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] =  drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }
    // These panel regions can float, add a single declaration to support floating regions
    $float = '.at-panel .region {display:inline;float:left}';
    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    
    // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';
    
    $layouts[] = $css;
  }
  // Tablet layout - portrait
  if ($values['tablet_portrait_layout']) {
    $device_group   = 'tablet_portrait';
    $sidebar_first  = $values['tablet_portrait_sidebar_first'];
    $sidebar_second = $values['tablet_portrait_sidebar_second'];
    $media_query    = $values['tablet_portrait_media_query'];
    $page_width     = $values['tablet_portrait_page_width'];
    $method         = $values['tablet_portrait_layout'];
    $sidebar_unit   = $values['tablet_portrait_sidebar_unit'];
    $page_unit      = $values['tablet_portrait_page_unit'];
    $layout         = at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit);
    $comment        = "/* Tablet portrait $method */\n";
    $width          = "\n" . '.container {width:' . $page_width . $page_unit . '}';
    if ($values['tablet_portrait_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['tablet_portrait_max_width'];
      $max_width_unit = $values['tablet_portrait_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $max_width . $max_width_unit . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $page_width . $page_unit . '}';
      }
    }
    // Panels layouts
    $panel_settings = array(
      'tablet_portrait_two_50',
      'tablet_portrait_two_33_66',
      'tablet_portrait_two_66_33',
      'tablet_portrait_two_brick',
      'tablet_portrait_three_3x33',
      'tablet_portrait_three_25_50_25',
      'tablet_portrait_three_25_25_50',
      'tablet_portrait_three_50_25_25',
      'tablet_portrait_four_4x25',
      'tablet_portrait_five_5x20',
      'tablet_portrait_six_6x16',
      'tablet_portrait_inset_left',
      'tablet_portrait_inset_right',
    );
    $css_files = array();
    foreach ($panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }
    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] == 1) {
      foreach ($panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] =  drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }
    // These panel regions can float, add a single declaration to support floating regions
    $float = '.at-panel .region {display:inline;float:left}';
    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    
     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';
    
    $layouts[] = $css;
  }
  // Tablet layout - landscape
  if ($values['tablet_landscape_layout']) {
    $device_group   = 'tablet_landscape';
    $sidebar_first  = $values['tablet_landscape_sidebar_first'];
    $sidebar_second = $values['tablet_landscape_sidebar_second'];
    $media_query    = $values['tablet_landscape_media_query'];
    $page_width     = $values['tablet_landscape_page_width'];
    $method         = $values['tablet_landscape_layout'];
    $sidebar_unit   = $values['tablet_landscape_sidebar_unit'];
    $page_unit      = $values['tablet_landscape_page_unit'];
    $layout         = at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit);
    $comment        = "/* Tablet landscape $method */\n";
    $width          = "\n" . '.container {width:' . $page_width . $page_unit . '}';
    if ($values['tablet_landscape_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['tablet_landscape_max_width'];
      $max_width_unit = $values['tablet_landscape_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $max_width . $max_width_unit . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $page_width . $page_unit . '}';
      }
    }
    // panels layouts
    $panel_settings = array(
      'tablet_landscape_two_50',
      'tablet_landscape_two_33_66',
      'tablet_landscape_two_66_33',
      'tablet_landscape_two_brick',
      'tablet_landscape_three_3x33',
      'tablet_landscape_three_25_50_25',
      'tablet_landscape_three_25_25_50',
      'tablet_landscape_three_50_25_25',
      'tablet_landscape_four_4x25',
      'tablet_landscape_five_5x20',
      'tablet_landscape_six_6x16',
      'tablet_landscape_inset_left',
      'tablet_landscape_inset_right',
    );
    $css_files = array();
    foreach ($panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }
    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] == 1) {
      foreach ($panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] =  drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }
    
    // These panel regions can float, add a single declaration to support floating regions
    $float = '.at-panel .region {display:inline;float:left}';
    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    
     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';
    
    $layouts[] = $css;
  }
  // Standard bigscreen layout
  if ($values['bigscreen_layout']) {
    $device_group   = 'bigscreen';
    $sidebar_first  = $values['bigscreen_sidebar_first'];
    $sidebar_second = $values['bigscreen_sidebar_second'];
    $media_query    = $values['bigscreen_media_query'];
    $page_width     = $values['bigscreen_page_width'];
    $method         = $values['bigscreen_layout'];
    $sidebar_unit   = $values['bigscreen_sidebar_unit'];
    $page_unit      = $values['bigscreen_page_unit'];
    $layout         = at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit);
    $comment        = "/* Standard layout $method */\n";
    $standard_layout_comment = "/* Standard layout $method */";
    $width          = "\n" . '.container {width:'. $page_width . $page_unit . '}';

    if ($values['bigscreen_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['bigscreen_max_width'];
      $max_width_unit = $values['bigscreen_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $max_width . $max_width_unit . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $page_width . $page_unit .';max-width:' . $page_width . $page_unit . '}';
      }
    }

    // Everything gets much more serious with the standard layout, it dictates by and large 
    // what is written into each file.
    // We can't forget about respond.js, it sticks its mucky hand in here as well.

    // Load $panels_layout, we always need to load this somewhere
    $panels_layouts = drupal_load_stylesheet($path_to_panels_css . 'default.css', FALSE);

    // Get the lte ie7 panels layout overrides, we always need to load these somewhere also
    $ie_panels_layouts = drupal_load_stylesheet($path_to_panels_css . '/ie/' . 'ie_defaults.css', FALSE);

    // Now build a $styles variable with the correct layout, width and default panels layouts
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;

    // Next we need to know if respond.js is loading or not, so we can setup variables for later on
    if ($values['load_respondjs'] == 0) {
      // respondjs is OFF, we need to load the IE stylesheet with the standard layout, width, panels 
      // layouts and tack the lt ie7 panels layout overrides on the end
      $lt_ie9_css = $standard_layout_comment . implode("\n", $layout) . $width . "\n" . $panels_layouts . "\n" . $ie_panels_layouts;
    }
    if ($values['load_respondjs'] == 1) {
      // respondjs is ON, load all the panels layouts into one variable - this will go into 
      // themeName.responsive.layout.css file
      $panels_layouts = $panels_layouts . "\n" . $ie_panels_layouts;
      // Load a comment in the lt ie9 css file to tell the themer this file is not being used, we 
      // don't need it since respondjs is loading
      $lt_ie9_css = '/* respond.js is loading so we dont load this file. IE6, 7 and 8 will rely on respond.js to work its magic */';
    }

    // We need to check for the default layout again
    if ($is_default_layout == 'standard-layout') {
      // reset the IE styles variable to a comment, we don't need it if the standard layout is default
      $lt_ie9_css = '/* The standard layout is the default layout, IE styles are not required because you are doing desktop first design */';
      // is the default layout, $css gets a comment that this is the default, and we load the layout 
      // $styles into $default_layout
      $css = '/* The standard layout is the default layout - this is a desktop first design */';
      $default_layout = $standard_layout_comment . $styles . "\n" . $ie_panels_layouts;
    }
    else {
      // is not the default, load the $css var with the layout $styles and tack the lt ie7 panels layout 
      // overrides on the end (but only if respond.js is loading)
      if ($values['load_respondjs'] == 1) {
        $not_default_styles = $styles . "\n" . $ie_panels_layouts . "\n";
      }
      else {
        $not_default_styles = $styles . "\n";
      }
      $css = $comment . '@media ' . $media_query . ' {' . $not_default_styles . '}';
    }

    // Finally we really need to know if responsive capability is on or off...
    if ($values['disable_responsive_styles'] == 1) {
      $lt_ie9_css = '/* Responsive capabilites are disabled, we no longer need to load an IE specific layout */';
      $default_layout = $standard_layout_comment . $styles . "\n" . $ie_panels_layouts;
    }

     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.desktop.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    // add $css to the layouts array
    $layouts[] = $css;
  }

  // Now we need to generate and save three CSS files:
  // 1. themeName.default.layout.css
  // 2. themeName.responsive.layout.css
  // 3. themeName.lt-ie9.layout.css
  //
  // adaptivetheme_preprocess_html() will take care of loading the
  // right ones when they are needed.

  // Set up the stream wrapper and create the directory
  $path  = "public://at_css";
  file_prepare_directory($path, FILE_CREATE_DIRECTORY);

  // Set up variables for each layout, we already have the $default_layout but its not sanitized
  // $default_layout = $default_layout;

  // Next the responsive layout
  $responsive_layout_data = implode("\n", $layouts);
  $responsive_layout = $responsive_layout_data;
  // Nuke $responsive_layout if we really don't need it
  if ($values['disable_responsive_styles'] == 1) {
    $responsive_layout = '/* Responsive capabilites are disabled, only the default layout is loading, we dont need this so its not loaded */';
  }

  // Finally for lt ie9
  $lt_ie9_layout = $lt_ie9_css;
  
  // Not finished yet though, we need to aggregate the responsive stylesheets
  $responsive_css = implode("\n", $responsive_styles);

  // Build a keyed array: file names as key, layout data as value
  $files = array(
    "$theme_name.default.layout" => $default_layout,
    "$theme_name.responsive.layout" => $responsive_layout,
    "$theme_name.lt-ie9.layout" => $lt_ie9_layout,
    "$theme_name.responsive.styles" => $responsive_css,
  );

  // Loop over the array and save each file, and we're done... and none too soon...
  foreach ($files as $key => $value) {
    $filepath = "$path/$key.css";
    file_save_data($value, $filepath, FILE_EXISTS_REPLACE);
  }
}

// Build the core layouts, this is called once per orientation, except for smartphone portrait which is hard coded
function at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit) {

  // Set variables for language direction
  $left = 'left';
  $right = 'right';

  // build the $styles array
  $styles = array();
  if ($method == 'three-col-grail') {
    $sidebar_second = $sidebar_second . $sidebar_unit;
    $sidebar_first  = $sidebar_first . $sidebar_unit;
    $push_right = $sidebar_second;
    $push_left  = $sidebar_first;
    $pull_right = $sidebar_second;
    $styles[] = '
#content-column,.content-column,div.sidebar {float:left;clear:none}
.two-sidebars .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':' . $push_right . '}
.sidebar-first .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}
.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . ';margin-' . $left . ':0}
.region-sidebar-first {width:' . $sidebar_first . ';margin-' . $left . ':-100%}
.region-sidebar-second {width:' . $sidebar_second . ';margin-' . $left . ':-' . $pull_right . '}';
  }
  if ($method == 'three-col-right') {
    $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
    $push_right     = $sidebar_second . $sidebar_unit;
    $push_left      = $sidebar_first . $sidebar_unit;
    $left_margin    = $sidebar_second + $sidebar_first . $sidebar_unit;
    $right_margin   = $sidebar_second . $sidebar_unit;
    $styles[] = '
#content-column,.content-column,div.sidebar {float:left;clear:none}
.two-sidebars .content-inner {margin-' . $right . ':' . $content_margin . ';margin-' . $left . ':0}
.sidebar-first .content-inner {margin-' . $right . ':' . $push_left . ';margin-' . $left . ':0}
.sidebar-second .content-inner {margin-' . $right . ':' . $push_right . ';margin-' . $left . ':0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $left_margin . '}
.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';margin-' . $left . ':-' . $right_margin . '}
.sidebar-first .region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $sidebar_first . $sidebar_unit . '}';
  }
  if ($method == 'three-col-left') {
    $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
    $left_margin    = $sidebar_first . $sidebar_unit;
    $right_margin   = $sidebar_second . $sidebar_unit;
    $push_right     = $sidebar_first . $sidebar_unit;
    $styles[] = '
#content-column,.content-column,div.sidebar {float:left;clear:none}
.two-sidebars .content-inner {margin-' . $left . ': ' . $content_margin . ';margin-' . $right . ':0}
.sidebar-first .content-inner {margin-' . $left . ': ' . $left_margin . ';margin-' . $right . ':0}
.sidebar-second .content-inner {margin-' . $left . ': ' . $right_margin . ';margin-' . $right . ':0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-100%}
.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';margin-' . $left . ':-100%}
.two-sidebars .region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';position:relative;' . $left . ':' . $push_right . '}';
  }
  if ($method == 'two-col-stack') {
    $push_right = $sidebar_first . $sidebar_unit;
    $styles[] = '
#content-column,.content-column {float:left;clear:none}
.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0;margin-' . $right . ':' . $push_right . '}
.sidebar-second .content-inner {margin-right:0;margin-left:0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $push_right . ';float:left;clear:none}
.region-sidebar-second {width:100%;margin-left:0;margin-right:0;margin-top:20px;clear:both;overflow:hidden}
.region-sidebar-second .block {float:left;clear:none}';
  }
  if ($method == 'one-col-stack') {
    $styles[] = '
.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left:0;margin-right:0}
.region-sidebar-first,.region-sidebar-second,.region-sidebar-first .block,.region-sidebar-second .block {width:100%}
.region-sidebar-second {width:100%}
.content-inner,.region-sidebar-first,.region-sidebar-second {float:none}
.region-sidebar-first,.region-sidebar-second {clear:both}';
  }
  if ($method == 'one-col-vert') {
    $one_sidebar = $sidebar_first + $sidebar_second;
    $styles[] = '
.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left:0;margin-right:0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . '}
.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . '}
.one-sidebar .sidebar {width:' . $one_sidebar . $sidebar_unit . '}
.region-sidebar-first,.region-sidebar-second {overflow:hidden;margin-top:20px;float:left;clear:none}
.region-sidebar-first.block,.region-sidebar-second .block {width:100%}';
  }
  return $styles;
}