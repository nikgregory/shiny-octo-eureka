<?php
  // Responsive Layouts and Styles
  $layout_variables = page_layout(); // Get the page layout config array
  $layouts = array(); // $layouts will hold all the page level layouts
  $is_default_layout = $values['global_default_layout']; // Initialize the $is_default_layout variable, we use this to test against
  $default_layout = 'standard-layout';  // Need this to support 7.x-2.x subthemes which are all desktop first

  // Get the panels config array
  $responsive_panels = responsive_panels();

  // Three of the five layouts write their Panels CSS dynamically, we need to add a float delcaration just for them.
  $float = ".at-panel .region {display:inline;float:left}";

  // $responsive_styles will hold all the styles from the responsive stylesheets, wrapped in media queries.
  $responsive_styles = array();

  // Get the panels subtypes settings, we will build our settings array from this
  foreach ($responsive_panels as $type => $subtypes) {
    //dsm($subtypes);
    foreach ($subtypes as $subtype => $setting) {
      $panel_subtypes[] = $setting['setting'];
    }
  }

  // If the cascade setting is true, load up these styles first
  if ($values['enable_cascading_media_queries'] === 1) {
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.cascade.css', FALSE);
    $responsive_styles[] = $responsive_css . "\n";
  }

  // Smartphone Portrait
  if ($values['smartphone_portrait_media_query']) {

    $device = 'smartphone_portrait';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" .'_'. "$value"])) {
        $smartphone_portrait_layout_data[$value] = $values["$device" .'_'. "$value"];
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
      $default_layout = '/* Smartphone portrait is default, you the man! */';
      $css = "/* Smartphone portrait is default, you the man! */";
    }
    else {
      // Smartphone portrait panels are all 100% width and stacked, we need to overpower the default layouts when in desktop first mode
      $panels_layout = '.at-panel .region,.at-panel .panel-row,.at-panel .panel-row .region,.at-panel .inset-wrapper{float:none;display:block;width:100%}';
      $styles = implode("\n", $layout) . $width . "\n" . $panels_layout;
      $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    }

    // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Smartphone Landscape
  if ($values['smartphone_landscape_layout']) {

    $device = 'smartphone_landscape';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" .'_'. "$value"])) {
        $smartphone_landscape_layout_data[$value] = $values["$device" .'_'. "$value"];
      }
    }
    $layout = at_build_page_layout($smartphone_landscape_layout_data['layout'], $smartphone_landscape_layout_data['sidebar_first'], $smartphone_landscape_layout_data['sidebar_second'], $smartphone_landscape_layout_data['sidebar_unit']);

    $method = $smartphone_landscape_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $smartphone_landscape_layout_data['page_width'] . $smartphone_landscape_layout_data['page_unit'] . '}';
    $media_query = $smartphone_landscape_layout_data['media_query'];

    // Get the panel layout settings and CSS for these layouts
    $device = 'smartphone_landscape';
    foreach ($panel_subtypes as $subtype) {
      $smartphone_landscape_panel_settings[] = $device . '_' . $subtype;
    }
    $css_files = array();
    foreach ($smartphone_landscape_panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }

    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] === 1) {
      foreach ($smartphone_landscape_panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] = drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }

    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

    // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.smartphone.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Tablet Portrait
  if ($values['tablet_portrait_layout']) {

    $device = 'tablet_portrait';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" .'_'. "$value"])) {
        $tablet_portrait_layout_data[$value] = $values["$device" .'_'. "$value"];
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
    $css_files = array();
    foreach ($tablet_portrait_panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }

    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] === 1) {
      foreach ($smartphone_landscape_panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] =  drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }

    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.portrait.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Tablet Landscape
  if ($values['tablet_landscape_layout']) {

    $device = 'tablet_landscape';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" .'_'. "$value"])) {
        $tablet_landscape_layout_data[$value] = $values["$device" .'_'. "$value"];
      }
    }
    $layout = at_build_page_layout($tablet_landscape_layout_data['layout'], $tablet_landscape_layout_data['sidebar_first'], $tablet_landscape_layout_data['sidebar_second'], $tablet_landscape_layout_data['sidebar_unit']);

    $method = $tablet_landscape_layout_data['layout'];
    $comment = "/* $device $method */\n";
    $width = "\n" . '.container {width:' . $tablet_landscape_layout_data['page_width'] . $tablet_landscape_layout_data['page_unit'] . '}';
    $media_query = $tablet_landscape_layout_data['media_query'];

    // Get the panel layout settings and CSS for these layouts
    foreach ($panel_subtypes as $subtype) {
      $tablet_landscape_panel_settings[] = $device . '_' . $subtype;
    }
    $css_files = array();
    foreach ($tablet_landscape_panel_settings as $setting) {
      $option = $values[$setting];
      $css_files[] = drupal_load_stylesheet($path_to_panels_css . $option . '.css', FALSE);
    }

    // respond.js is enabled, we need to push all the IE specific panels layout overrides into media queries
    if ($values['load_respondjs'] === 1) {
      foreach ($tablet_landscape_panel_settings as $setting) {
        $option = $values[$setting];
        $css_files[] =  drupal_load_stylesheet($path_to_panels_css . '/ie/' . $option . '.css', FALSE);
      }
    }

    $panels_layouts = $float . "\n" . implode("\n", $css_files);
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';

     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.tablet.landscape.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    $layouts[] = $css;
  }

  // Standard Layout (bigscreen)
  if ($values['bigscreen_layout']) {

    $device = 'bigscreen';

    // Build an array of page layout settings values
    foreach ($layout_variables as $key => $value) {
      if (isset($values["$device" .'_'. "$value"])) {
        $bigscreen_layout_data[$value] = $values["$device" .'_'. "$value"];
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
        $width = "\n" . '.container {width:' . $bigscreen_layout_data['page_width'] . $bigscreen_layout_data['page_unit'] .';max-width:' . $values['bigscreen_max_width'] . $values['bigscreen_max_width_unit'] . '}';
      }
      else {
        $width = "\n" . '.container {width:' . $bigscreen_layout_data['page_width'] . $bigscreen_layout_data['page_unit'] .';max-width:' . $values['bigscreen_max_width'] . $values['bigscreen_max_width_unit'] . '}';
      }
    }

    // Load $panels_layout, we always need to load this somewhere
    $panels_layouts = drupal_load_stylesheet($path_to_panels_css . 'default.css', FALSE);

    // Get the lte ie7 panels layouts
    $ie_panels_layouts = drupal_load_stylesheet($path_to_panels_css . '/ie/' . 'ie_defaults.css', FALSE);

    // Build a $styles variable with the layout, width and default panels layouts
    $styles = implode("\n", $layout) . $width . "\n" . $panels_layouts;

    // Is respond.js disabled or enabled?
    if ($values['load_respondjs'] === 0) {
      // respondjs is OFF, load the IE stylesheet with the standard layout, width, panels layouts and the lte ie7 panels layouts
      $lt_ie9_css = $comment . implode("\n", $layout) . $width . "\n" . $panels_layouts . "\n" . $ie_panels_layouts;
    }
    if ($values['load_respondjs'] === 1) {
      // respondjs is ON, load all the panels layouts into one variable - this will go into themeName.responsive.layout.css
      $panels_layouts = $panels_layouts . "\n" . $ie_panels_layouts;
      // Load a comment in the lt ie9 css file to tell the themer this file is not being used, we don't need it since respondjs is loading
      $lt_ie9_css = '/* respond.js is loading so we dont load this file. IE6, 7 and 8 will rely on respond.js to work its magic */';
    }

    // Check the this is the default layout
    if ($is_default_layout === 'standard-layout') {
      // IS the default layout... means this is desktop first design method
      // reset the IE styles variable to a comment, we don't need it if the standard layout is default
      $lt_ie9_css = '/* The standard layout is the default layout, IE styles are not required because you are doing desktop first design */';

      // $css gets a comment that this is the default, and we load the layout
      // $styles into $default_layout
      $css = '/* The standard layout is the default layout - this is a desktop first design */';

      // Our default layout ready for priting into the file
      $default_layout = $comment . $styles . "\n" . $ie_panels_layouts;
    }
    // IS NOT the default layout... means this is mobile first design method
    else {
      // load the $css variable with $styles and tack the lt ie7 panels layouts on the end
      if ($values['load_respondjs'] === 1) {
        $not_default_styles = $styles . "\n" . $ie_panels_layouts . "\n";
      }
      else {
        $not_default_styles = $styles . "\n";
      }
      $css = $comment . "\n" . '@media ' . $media_query . ' {' . $not_default_styles . '}';
    }

    // Is responsive capability on or off?
    if ($values['disable_responsive_styles'] === 1) {
      // Its off, what a looser...
      $lt_ie9_css = '/* Responsive capabilites are disabled, we no longer need to load an IE specific layout */';
      $default_layout = $comment . $styles . "\n" . $ie_panels_layouts;
    }

     // Get and wrap the responsive CSS in the relative media query
    $responsive_css = drupal_load_stylesheet($path_to_responsive_css . 'responsive.desktop.css', FALSE);
    $responsive_styles[] = '@media ' . $media_query . ' {' . "\n" . $responsive_css . "\n" . '}';

    // add $css to the layouts array
    $layouts[] = $css;
  }

  // Generate the layout and responsive styles stylesheets
  // Here we generate four files:
  // 1. $theme_name.default.layout
  // 2. $theme_name.responsive.layout
  // 3. $theme_name.lt-ie9.layout
  // 4. $theme_name.responsive.styles
  // adaptivetheme_preprocess_html() will take care of loading the right ones when they are needed.

  // Set up variables for each layout, we already have the $default_layout

  // Next the responsive layout
  $responsive_layout_data = implode("\n", $layouts);
  $responsive_layout = $responsive_layout_data;

  // Nuke $responsive_layout if we really don't need it
  if ($values['disable_responsive_styles'] == 1) {
    $responsive_layout = '/* Responsive capabilites are disabled, only the default layout is loading, we dont need this so its not loaded */';
  }

  // Finally for lt ie9
  $lt_ie9_layout = $lt_ie9_css;

  // Not finished yet though, we need to aggregate the design stylesheets
  $responsive_css = implode("\n", $responsive_styles);

  // Build a keyed array: file names as key, layout data as value
  $files = array(
    "$theme_name.default.layout" => $default_layout,
    "$theme_name.responsive.layout" => $responsive_layout,
    "$theme_name.lt-ie9.layout" => $lt_ie9_layout,
    "$theme_name.responsive.styles" => $responsive_css,
  );

  // Loop over the array and save each file, and we're done!
  foreach ($files as $key => $value) {
    $sanitized_values = filter_xss($value); // some data might be user entered, check_plain gave me encoding errors
    $filepath = "$path/$key.css";
    file_save_data($sanitized_values, $filepath, FILE_EXISTS_REPLACE);
  }