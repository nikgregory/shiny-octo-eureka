<?php
  // Responsive Layouts and Styles

  $layout_variables = page_layout(); // Get the page layout config array
  $layouts = array(); // $layouts will hold all the page level layouts

  $is_default_layout = $values['global_default_layout']; // Initialize the $is_default_layout variable, we use this to test against
  $default_layout = 'standard-layout';  // Need this to support 7.x-2.x subthemes which are all desktop first

  $responsive_panels = responsive_panels(); // Get the panels config array
  $float = ".at-panel .region {display:inline;float:left}"; // Some layouts generate their Panels CSS, we need to add a float delcaration for them

  $responsive_styles = array(); // Holds all the styles from the responsive stylesheets, wrapped in media queries

  // Get the Panels subtypes settings
  foreach ($responsive_panels as $type => $subtypes) {
    foreach ($subtypes as $subtype => $setting) {
      $panel_subtypes[] = $setting['setting'];

      if (array_key_exists('styles', $setting)) {
        $panel_styles[] = $setting['styles'];
      }
    }
  }

  // If the Cascading media queries CSS file is enabled, load these styles first
  if ($values['enable_cascading_media_queries'] === 1) {
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.cascade.css', FALSE);
    $responsive_styles[] = $responsive_css . "\n";
  }

  // Smartphone Portrait
  if ($values['smartphone_portrait_media_query']) {

    $device = 'smartphone_portrait';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" . '_' . "$value"])) {
        $smartphone_portrait_layout_data[$value] = $values["$device" . '_' . "$value"];
      }
    }
    $layout = at_build_page_layout($smartphone_portrait_layout_data['layout'], $smartphone_portrait_layout_data['sidebar_first'], $smartphone_portrait_layout_data['sidebar_second'], $smartphone_portrait_layout_data['sidebar_unit']);

    $method = $smartphone_portrait_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $smartphone_portrait_layout_data['page_width'] . $smartphone_portrait_layout_data['page_unit'] . '}';
    $media_query = $smartphone_portrait_layout_data['media_query'];

    // If mobile first then smartphone portrait needs to do nothing, else its
    // problematic to set the layout later due to the cascade and inheritance
    if ($is_default_layout === 'smartphone-portrait') {
      $default_layout = '/* Smartphone portrait is default, mobile first dude - you rock the hardest! */';
      $css = "/* Smartphone portrait is default, mobile first dude - you rock the hardest! */";
    }
    else {
      // Smartphone portrait panels are all 100% width and stacked, we need to overpower the default layouts when in desktop first mode
      $smartphone_portrait_panels_layout = '.at-panel .region,.at-panel .panel-row,.at-panel .panel-row .region,.at-panel .inset-wrapper{float:none;display:block;width:100%}';
      $styles = $width . "\n" . $layout . "\n" . $smartphone_portrait_panels_layout;
      $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    }

    // Get and wrap the responsive CSS styles in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Smartphone Landscape
  if ($values['smartphone_landscape_layout']) {

    $device = 'smartphone_landscape';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" . '_' . "$value"])) {
        $smartphone_landscape_layout_data[$value] = $values["$device" . '_' . "$value"];
      }
    }
    $layout = at_build_page_layout($smartphone_landscape_layout_data['layout'], $smartphone_landscape_layout_data['sidebar_first'], $smartphone_landscape_layout_data['sidebar_second'], $smartphone_landscape_layout_data['sidebar_unit']);

    $method = $smartphone_landscape_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $smartphone_landscape_layout_data['page_width'] . $smartphone_landscape_layout_data['page_unit'] . '}';
    $media_query = $smartphone_landscape_layout_data['media_query'];

    // Get the panel layout settings
    foreach ($panel_subtypes as $subtype) {
      $smartphone_landscape_panel_settings[] = $device . '_' . $subtype;
    }
    foreach ($smartphone_landscape_panel_settings as $setting) {
      $option = $values[$setting];
      $smartphone_landscape_panels_data[] = $option;
    }
    // Generate the panels layouts
    $smartphone_landscape_panels_css = build_panels_layout_css($panel_styles, $smartphone_landscape_panels_data);

    $panels_layouts = $float . $smartphone_landscape_panels_css;
    $styles = $width . "\n" . $layout . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

    // Get and wrap the responsive CSS styles in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Tablet Portrait
  if ($values['tablet_portrait_layout']) {

    $device = 'tablet_portrait';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" . '_' . "$value"])) {
        $tablet_portrait_layout_data[$value] = $values["$device" . '_' . "$value"];
      }
    }
    $layout = at_build_page_layout($tablet_portrait_layout_data['layout'], $tablet_portrait_layout_data['sidebar_first'], $tablet_portrait_layout_data['sidebar_second'], $tablet_portrait_layout_data['sidebar_unit']);

    $method = $tablet_portrait_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $tablet_portrait_layout_data['page_width'] . $tablet_portrait_layout_data['page_unit'] . '}';
    $media_query = $tablet_portrait_layout_data['media_query'];

    // Get the panel layout settings and CSS for these layouts
    foreach ($panel_subtypes as $subtype) {
      $tablet_portrait_panel_settings[] = $device . '_' . $subtype;
    }
    foreach ($tablet_portrait_panel_settings as $setting) {
      $option = $values[$setting];
      $tablet_portrait_panels_data[] = $option;
    }
    // Generate the panels layouts
    $tablet_portrait_panels_css = build_panels_layout_css($panel_styles, $tablet_portrait_panels_data);

    $panels_layouts = $float . $tablet_portrait_panels_css;
    $styles = $width . "\n" . $layout . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

     // Get and wrap the responsive CSS styles in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Tablet Landscape
  if ($values['tablet_landscape_layout']) {

    $device = 'tablet_landscape';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" . '_' . "$value"])) {
        $tablet_landscape_layout_data[$value] = $values["$device" . '_' . "$value"];
      }
    }
    $layout = at_build_page_layout($tablet_landscape_layout_data['layout'], $tablet_landscape_layout_data['sidebar_first'], $tablet_landscape_layout_data['sidebar_second'], $tablet_landscape_layout_data['sidebar_unit']);

    $method = $tablet_landscape_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $tablet_landscape_layout_data['page_width'] . $tablet_landscape_layout_data['page_unit'] . '}';
    $media_query = $tablet_landscape_layout_data['media_query'];

    // Get the panel layout settings
    foreach ($panel_subtypes as $subtype) {
      $tablet_landscape_panel_settings[] = $device . '_' . $subtype;
    }
    foreach ($tablet_landscape_panel_settings as $setting) {
      $option = $values[$setting];
      $tablet_landscape_panels_data[] = $option;
    }
    // Generate the panels layouts
    $tablet_landscape_panels_css = build_panels_layout_css($panel_styles, $tablet_landscape_panels_data);

    $panels_layouts = $float . $tablet_landscape_panels_css;
    $styles = $width . "\n" . $layout . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

     // Get and wrap the responsive CSS styles in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Standard Layout (bigscreen)
  if ($values['bigscreen_layout']) {

    $device = 'bigscreen';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" . '_' . "$value"])) {
        $bigscreen_layout_data[$value] = $values["$device" . '_' . "$value"];
      }
    }
    $layout = at_build_page_layout($bigscreen_layout_data['layout'], $bigscreen_layout_data['sidebar_first'], $bigscreen_layout_data['sidebar_second'], $bigscreen_layout_data['sidebar_unit']);

    $method = $bigscreen_layout_data['layout'];
    $comment = "/* $device $method */";
    $width = "\n" . '.container {width:' . $bigscreen_layout_data['page_width'] . $bigscreen_layout_data['page_unit'] . '}';
    $media_query = $bigscreen_layout_data['media_query'];

    // Standard layout can have a max-width
    if ($values['bigscreen_set_max_width'] === 1 && $bigscreen_layout_data['page_unit'] === '%') {
      if (!empty($values['bigscreen_max_width'])) {
        $width = "\n" . '.container {width:' . $bigscreen_layout_data['page_width'] . $bigscreen_layout_data['page_unit'] . ';max-width:' . $values['bigscreen_max_width'] . $values['bigscreen_max_width_unit'] . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $bigscreen_layout_data['page_width'] . $bigscreen_layout_data['page_unit'] . ';max-width:' . $values['bigscreen_max_width'] . $values['bigscreen_max_width_unit'] . '}';
      }
    }

 
    // Load the default panels layouts
    $panels_layouts = drupal_load_stylesheet($path_to_panels_css . 'default.css', FALSE);

    // Get the lte ie7 panels layouts
    $ie_panels_layouts = drupal_load_stylesheet($path_to_panels_css . 'ie_defaults.css', FALSE);

    // Build a $styles variable with the layout, width and default panels layouts
    $styles = $width . "\n" . $layout . "\n" . $panels_layouts;

 
    // Is respond.js disabled or enabled?
    
    // respondjs is OFF
    if ($values['load_respondjs'] === 0) {
     
      if ($values['load_ltie8css'] === 0) {
        // Dont add in the lt ie 8 guff, we no wanty
        $lt_ie9_css = $comment . $width . "\n" . $layout . "\n" . $panels_layouts;
        $lt_ie8_css = '/* Support for IE6/7 rounding error fixes is OFF */';
      }
      else {
        // load the IE stylesheet with the standard layout, width, panels layouts and the lte ie7 panels layouts
        $lt_ie9_css = $comment . $width . "\n" . $layout . "\n" . $panels_layouts . "\n" . $ie_panels_layouts;
        $lt_ie8_css = '/* The IE6/7 panels layouts are loading in the lt IE9 CSS, to save an HTTP request hit on your server */';
      }
 
    }
    
    // respondjs is ON
    if ($values['load_respondjs'] === 1) {
      
      // Load a comment in the lt ie9 css file to tell the themer this file is not being used, we don't need it since respondjs is loading
      $lt_ie9_css = '/* respond.js is loading so we dont load this file. IE6, 7 and 8 will rely on respond.js to work its magic */';
      
      // Load the IE6/7 panels overrides in a conditional stylesheet just for them
      $lt_ie8_css = $ie_panels_layouts;
    
    }


    // Check the this is the default layout
    if ($is_default_layout === 'standard-layout') {
    
      // IS the default layout === DESKTOP FIRST
      // reset the IE styles variable to a comment, we don't need it if the standard layout is default
      $lt_ie9_css = '/* The standard layout is the default layout, IE styles are not required because you are doing desktop first design */';

      // Load the IE6/7 panels overrides in a conditional stylesheet just for them
      $lt_ie8_css = $ie_panels_layouts;
      
      // $css gets a comment that this is the default, and we load the layout
      // $styles into $default_layout
      $css = '/* The standard layout is the default layout - this is a desktop first design */';

      // Our default layout ready for priting into the file
      $default_layout = $comment . $styles . "\n";
    }
    
    // IS NOT the default layout === MOIBLE FIRST
    else {
      $mobile_first_styles = $styles . "\n";
      $css = $comment . "\n" . '@media ' . $media_query . ' {' . $mobile_first_styles . '}';
    }

    // Is responsive capability on or off?
    if ($values['disable_responsive_styles'] === 1) {
    
      // Its off, seriously, why are you using this theme?
      $lt_ie9_css = '/* Responsive capabilites are disabled, we no longer need to load an IE specific layout */';
      
      // Load the IE6/7 panels overrides in a conditional stylesheet just for them
      $lt_ie8_css = $ie_panels_layouts;
      
      $default_layout = $comment . $styles;
      
    }

     // Get and wrap the responsive CSS styles in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.desktop.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    // add $css to the layouts array
    $layouts[] = $css;
  }

  // Generate the layout and responsive styles stylesheets
  // Here we generate five files:
  // 1. $theme_name.default.layout
  // 2. $theme_name.responsive.layout
  // 3. $theme_name.lt-ie9.layout
  // 4. $theme_name.lt-ie8.layout
  // 5. $theme_name.responsive.styles
  // adaptivetheme_preprocess_html() will take care of loading the right ones when they are needed.

  // Set up variables for each layout, we already have the $default_layout

  // Next the responsive layout
  $responsive_layout_data = implode("\n", $layouts);
  $responsive_layout = $responsive_layout_data;

  // Nuke $responsive_layout if we really don't need it
  if ($values['disable_responsive_styles'] == 1) {
    $responsive_layout = '/* Responsive capabilites are disabled, only the default layout is loading, we dont need this so its not loaded */';
  }

  // Finally for IE
  $lt_ie9_layout = $lt_ie9_css;
  $lt_ie8_layout = $lt_ie8_css;

  // Not finished yet though, we need to aggregate the design stylesheets
  $responsive_css = implode("\n", $responsive_styles);

  // Build a keyed array: file names as key, layout data as value
  $files = array(
    "$theme_name.default.layout" => $default_layout,
    "$theme_name.responsive.layout" => $responsive_layout,
    "$theme_name.lt-ie9.layout" => $lt_ie9_layout,
    "$theme_name.lt-ie8.layout" => $lt_ie8_layout,
    "$theme_name.responsive.styles" => $responsive_css,
  );

  // Loop over the array and save each file, and we're done!
  foreach ($files as $key => $value) {
    $sanitized_values = filter_xss_admin($value); // Can contain user entered data, run final sanity checks
    $filepath = "$path/$key.css";
    file_save_data($sanitized_values, $filepath, FILE_EXISTS_REPLACE);
  }