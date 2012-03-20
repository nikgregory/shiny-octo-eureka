<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */

// Validate some stuff
function at_theme_settings_validate($form, &$form_state) {

  $values = $form_state['values'];
  $theme_name = $form_state['build_info']['args'][0];

  // Validate our form #state required fields, #states are UI only.
  if ($values['bigscreen_set_max_width'] === 1) {
    if (empty($values['bigscreen_max_width'])) {
      form_set_error('bigscreen_max_width', t('Standard layout max-width is empty - enter a value or deselect "Set a max width".'));
    }
  }
  if ($values['smartphone_landscape_layout'] === 'one-col-vert') {
    if (empty($values['smartphone_landscape_sidebar_first'])) {
      form_set_error('smartphone_landscape_sidebar_first', t('Smartphone First Sidebar width is empty - enter a value or choose another layout.'));
    }
    if (empty($values['smartphone_landscape_sidebar_second'])) {
      form_set_error('smartphone_landscape_sidebar_second', t('Smartphone Second Sidebar width is empty - enter a value or choose another layout.'));
    }
  }

  // Check if the apple touch icon path is correct
  if ($values['enable_apple_touch_icons'] === 1) {
    // low
    // if (empty($values['apple_touch_icon_path_l'])) {
      // form_set_error('apple_touch_icon_path_l', t('Ops! The Apple touch 57x57 icon path is missing, you must enter a path or disable touch icons.'));
    // }
    if (!empty($values['apple_touch_icon_path_l'])) {
      $l = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_l'];
      if (!file_exists($l)) {
        form_set_error('apple_touch_icon_path_l', t('Ops! The Apple touch 57x57 icon path is not right, check the file exists or the path to the file is correct.'));
      }
    }
    // medium
    if (!empty($values['apple_touch_icon_path_m'])) {
      $m = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_m'];
      if (!file_exists($m)) {
        form_set_error('apple_touch_icon_path_m', t('Ops! The Apple touch 72x72 icon path is not right, check the file exists or the path to the file is correct.'));
      }
    }
    // high
    if (!empty($values['apple_touch_icon_path_h'])) {
      $h = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_h'];
      if (!file_exists($h)) {
        form_set_error('apple_touch_icon_path_h', t('Ops! The Apple touch 114x114 icon path is not right, check the file exists or the path to the file is correct.'));
      }
    }
  }
}

// Custom submit function - this mostly builds and saves stylesheets for
// various features such as the responsive layout and font styles.
function at_theme_settings_submit($form, &$form_state) {

  // Setup some variables we're going to use a lot
  $values = $form_state['values'];
  $theme_name = $form_state['build_info']['args'][0];
  $is_default_layout = $values['global_default_layout'];
  $default_layout = 'standard-layout'; // need this to support 7.x-2.x subthemes
  $path_to_panels_css = drupal_get_path('theme', 'adaptivetheme') . '/layouts/css/';
  $path_to_responsive_css = drupal_get_path('theme', $theme_name) . '/css/';

  // Set up our files directory we will save all generated files here. Files are never
  // overwritten, however the files are really small - total around 25kb depending, of
  // which around 8kb is never loaded - the Theme info config file, which is just a backup.
  $id = $theme_name . '_files_' . substr(hash('sha256', serialize($theme_name) . microtime()), 0, 8);
  $path  = 'public://adaptivetheme/' . $id;
  file_prepare_directory($path, FILE_CREATE_DIRECTORY);
  variable_set('theme_' . $theme_name . '_files_directory', $path);

  // Theme info config - this is the info file backed up to a file. Its perfectly
  // formatted and holds all current values - useful for migration or simply
  // capturing the current theme settings as defaults, which is why I built it.
  $theme_settings = $values;
  if ($theme_settings) {
    $dynamic_settings_info = array();
    $static_settings_info = array();
    $lt = list_themes();
    foreach ($lt as $key => $value) {
      if ($theme_name == $key) {
        $this_themes_info = $lt[$theme_name]->info;
        foreach ($this_themes_info as $k => $v) {
          if ($k == 'name') {
            $static_settings_info[] = 'name = ' . $v;
          }
          if ($k == 'description') {
            $v = $v ? $v : $theme_name. ' is rocking the hardest!';
            $static_settings_info[] = 'description = ' . $v;
          }
          if ($k == 'base theme') {
            $static_settings_info[] = 'base theme = ' . $v;
          }
          if ($k == 'release') {
            $static_settings_info[] = 'release = ' . $v;
          }
          if ($k == 'core') {
            $static_settings_info[] = 'core = 7.x';
          }
          if ($k == 'engine') {
            $static_settings_info[] = 'engine = phptemplate';
          }
          if ($k == 'screenshot') {
            $static_settings_info[] = 'screenshot = screenshot.png';
          }
          if ($k == 'stylesheets' && !empty($k)) {
            foreach ($v as $media_target => $stylesheet_file) {
              foreach ($stylesheet_file as $stylesheet_file_name => $stylesheet_file_path) {
                $static_settings_info[] = 'stylesheets[' . $media_target . '][] = ' . $stylesheet_file_name;
              }
            }
          }
          if ($k == 'scripts' && !empty($k)) {
             foreach ($v as $scripts => $file) {
               $static_settings_info[] = 'scripts[] = ' . $file;
             }
          }
          if ($k == 'regions') {
            foreach ($v as $name => $label) {
              $static_settings_info[] = 'regions[' . $name . '] = ' . $label;
            }
          }
          if ($k == 'features') {
            foreach ($v as $feature_key => $feature) {
              $static_settings_info[] = 'features[] = ' . $feature;
            }
          }
        }
      }
    }
    if (!empty($static_settings_info)) {
      $static_settings = implode("\n", $static_settings_info);
    }
    $exclusions = array(
      'var',
      'default_logo',
      'logo_path',
      'logo_upload',
      'default_favicon',
      'favicon_path',
      'favicon_upload',
      'at__active_tab',
      'at-layout__active_tab',
      'at-styles__active_tab',
      'submit',
      'form_build_id',
      'form_token',
      'form_id',
      'check',
      'output',
      'op',
    );
    foreach ($theme_settings as $setting_name => $setting_value) {
      if (!in_array($setting_name, $exclusions)) {
        $setting_value = $setting_value ? $setting_value : "''";
        $dynamic_settings_info[] = 'settings['. $setting_name .'] = ' . $setting_value;
      }
    }
    if (!empty($dynamic_settings_info)) {
      $dynamic_settings_info = $dynamic_settings_info ? $dynamic_settings_info : "''";
      $dynamic_settings = implode("\n", $dynamic_settings_info);
    }
    if (!empty($static_settings_info) && !empty($dynamic_settings_info)) {
      $sanitized_values = filter_xss($static_settings . "\n" . $dynamic_settings); /* could contain malicious data */
      $filepath = "$path/$theme_name" . '_settings.info';
      file_save_data($sanitized_values, $filepath, FILE_EXISTS_REPLACE);
    }
  }

  // Layout
  // Initialize the panels layout variable, this will by and large hold the panels layouts
  $panels_layout = '';
  // $layouts will hold all the page level layouts
  $layouts = array();
  // $responsive_styles will hold all the styles from the responsive stylesheets, wrapped in media queries.
  $responsive_styles = array();

  // If the cascade setting is true, load up these styles first
  if ($values['enable_cascading_media_queries'] === 1) {
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
    if ($is_default_layout === 'smartphone-portrait') {
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
    if ($values['load_respondjs'] === 1) {
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
    if ($values['load_respondjs'] === 1) {
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
    if ($values['load_respondjs'] === 1) {
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

    if ($values['bigscreen_set_max_width'] === 1 && $page_unit === '%') {
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
    if ($values['load_respondjs'] === 0) {
      // respondjs is OFF, we need to load the IE stylesheet with the standard layout, width, panels
      // layouts and tack the lt ie7 panels layout overrides on the end
      $lt_ie9_css = $standard_layout_comment . implode("\n", $layout) . $width . "\n" . $panels_layouts . "\n" . $ie_panels_layouts;
    }
    if ($values['load_respondjs'] === 1) {
      // respondjs is ON, load all the panels layouts into one variable - this will go into
      // themeName.responsive.layout.css file
      $panels_layouts = $panels_layouts . "\n" . $ie_panels_layouts;
      // Load a comment in the lt ie9 css file to tell the themer this file is not being used, we
      // don't need it since respondjs is loading
      $lt_ie9_css = '/* respond.js is loading so we dont load this file. IE6, 7 and 8 will rely on respond.js to work its magic */';
    }

    // We need to check for the default layout again
    if ($is_default_layout === 'standard-layout') {
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
      if ($values['load_respondjs'] === 1) {
        $not_default_styles = $styles . "\n" . $ie_panels_layouts . "\n";
      }
      else {
        $not_default_styles = $styles . "\n";
      }
      $css = $comment . '@media ' . $media_query . ' {' . $not_default_styles . '}';
    }

    // Finally we really need to know if responsive capability is on or off...
    if ($values['disable_responsive_styles'] === 1) {
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

  // Loop over the array and save each file, and we're done!
  foreach ($files as $key => $value) {
    $sanitized_values = filter_xss($value); // some data might be user entered, check_plain gave me encoding errors
    $filepath = "$path/$key.css";
    file_save_data($sanitized_values, $filepath, FILE_EXISTS_REPLACE);
  }

  // Fonts
  // Save the font settings in a CSS file

  // Only run this if fonts are actually enabled, otherwise we might get a
  // a nasty error...
  if ($values['enable_font_settings'] === 1) {

    // $font_styles_data holds all data for the stylesheet
    $font_styles_data = array();

    // Get the font elements array. Everything is built from the items in this
    // array, simply adding to it will generate new CSS, as long as the form
    // items exist to collect the values - ideally the form itself should be
    // generated from this array, although for now its hard coded.
    // Note to self: generate ALL forms from multidimensional arrays...
    $font_elements = font_elements();

    // Check if fontyourface is enabled, doing this in the loop will be expensive
    $fontyourface = FALSE;
    if (module_exists('fontyourface')) {
      static $fontyourface;
      $fontyourface = TRUE;
    }

    // How this works: first loop over the elements array, which is really a
    // list of html elements and selectors that we want to apply font styles
    // to. For each "element" it grabs the values from the form[values]
    // and sets these into variables. Initially it wants to know what
    // type we are looking at such as websafe, google, custom stack or
    // @font-your-face. For websafe, google and font-your-face it needs to
    // find a match - where the setting matches a font in one of those
    // arrays. Custom stack is easier, we just take the value we are given.
    // Once we know all the winners etc we format the values etc into CSS
    // using build_font_families_css(). In short this was not built for
    // performance and its pretty slow, but it does the job.
    foreach ($font_elements as $key => $value) {

      // Each item in $font_elements has 3 key value pairs
      $element  = $value['element']  ? $value['element'] : '';  // a key to use later
      $selector = $value['selector'] ? $value['selector'] : ''; // the selector to use when building the CSS
      $setting  = $value['setting']  ? $value['setting'] : '';  // the theme setting used to retrieve the font values

      // We need to retrive this, its in a theme setting not in the font elements array
      if ($selector == 'custom_css' && !empty($values['selectors_css'])) {
        $selector = filter_xss($values['selectors_css']); // user entered data
      }

      // Get the font type if isset, not all font settings have a type
      if (isset($values[$setting . '_type'])) {
        $font_type = $values[$setting . '_type'];
      }
      else {
        $font_type = '<none>'; // this is an individual "in-content" heading
      }

      // Get the font size if isset, not all font settings have size
      if (isset($values[$setting . '_size'])) {
        $font_size = $values[$setting . '_size'];
      }
      else {
        $font_size = '<none>'; // this is a grouped "in-content" heading or custom selectors
      }

      // Get the font value (the font name or family) for the right font type if isset,
      // not all font settings have a value
      if (isset($values[$setting . (!empty($font_type) ? '_' . $font_type : '')])) {
        $font_value = $values[$setting . (!empty($font_type) ? '_' . $font_type : '')];
      }

      // Some Content Headings have no type or family, we add these first,
      // these are the h1 to h6 settings that only have a size
      if ($font_type === '<none>') {
        $font_values['font_family'] = '';
        $font_values['font_size']   = $font_size;
        $font_values['font_style']  = '';
        $font_values['font_weight'] = '';

        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = build_font_families_css($element, $selector, $font_values);
      }

      // Websafe Fonts
      if ($font_type === '') {
        // Get a list of websafe fonts
        $websafe_fonts = websafe_fonts_list($element);
        // Loop over the websafe fonts list and get a match
        foreach ($websafe_fonts as $k => $v) {
          if ($k == $font_value) {
            $font_family = $v;
          }
        }
        $font_values['font_family'] = $font_family;
        $font_values['font_size']   = $font_size;
        $font_values['font_style']  = '';
        $font_values['font_weight'] = '';

        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = build_font_families_css($element, $selector, $font_values);
      }

      // Custom Font stacks (user entered data)
      if ($font_type === 'cfs') {
        $font_values['font_family'] = filter_xss($font_value); // check_plain can give weird encoding issues
        $font_values['font_size']   = $font_size;
        $font_values['font_style']  = '';
        $font_values['font_weight'] = '';
        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = build_font_families_css($element, $selector, $font_values);
      }

      // Google Fonts
      if ($font_type === 'gwf') {
        // Get the Google font list
        $google_fonts = google_fonts_list($element);
        // Loop over the Google font list and get a match, this is slow...
        foreach ($google_fonts as $k => $v) {
          if ($k == $font_value) {
            $font_family = $v;
          }
        }
        $font_values['font_family'] = $font_family;
        $font_values['font_size']   = $font_size;
        $font_values['font_style']  = '';
        $font_values['font_weight'] = '';
        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = build_font_families_css($element, $selector, $font_values);
      }

      // Font Your Face
      if ($fontyourface === TRUE) {
        if ($font_type === 'fyf') {
          // pull the font list, we need to iterate over it
          $fyf_fonts = font_your_face_fonts_list($element); // this is a keyed array
          // loop over fyf_fonts list and get a match and retrive the font name
          foreach ($fyf_fonts as $k => $v) {
            if ($k == $font_value) {
              $font_value = $v;
            }
          }
          // Get the font objects from font-your-face, we need additional data out of each object
          $enabled_fonts = fontyourface_get_fonts('enabled = 1');
          foreach ($enabled_fonts as $font) {
            // we need to know if the $font_value matches a $font->name
            if ($font_value == $font->name) {
              // Now we need a buch of variables to get the font family, font style and font weight
              $font_values['font_family'] = $font->css_family ? $font->css_family : 'Arial';
              $font_values['font_style']  = $font->css_style  ? $font->css_style  : 'normal';
              $font_values['font_weight'] = $font->css_weight ? $font->css_weight : '400';
            }
          }
          // Load the font size
          $font_values['font_size'] = $font_size;
          // Add styles to the array for printing into the stylsheet
          $font_styles_data[] = build_font_families_css($element, $selector, $font_values);
        }
      }
    }
    // finally implode the data into a variable, ready for printing to file
    $font_styles = implode("\n", $font_styles_data);

    $file_name = $theme_name . '_font_families.css';
    $filepath = "$path/$file_name";
    file_save_data($font_styles, $filepath, FILE_EXISTS_REPLACE);
  }
}

// Build the core layouts, this is called once per orientation, except for smartphone portrait which is hard coded
function at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit) {

  // Set variables for language direction
  static $left = 'left';
  static $right = 'right';

  // build the $styles array
  $styles = array();
  if ($method === 'three-col-grail') {
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
  if ($method === 'three-col-right') {
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
  if ($method === 'three-col-left') {
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
  if ($method === 'two-col-stack') {
    $push_right = $sidebar_first . $sidebar_unit;
    $styles[] = '
#content-column,.content-column {float:left;clear:none}
.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0;margin-' . $right . ':' . $push_right . '}
.sidebar-second .content-inner {margin-right:0;margin-left:0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $push_right . ';float:left;clear:none}
.region-sidebar-second {width:100%;margin-left:0;margin-right:0;margin-top:20px;clear:both;overflow:hidden}
.region-sidebar-second .block {float:left;clear:none}';
  }
  if ($method === 'one-col-stack') {
    $styles[] = '
.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left:0;margin-right:0}
.region-sidebar-first,.region-sidebar-second,.region-sidebar-first .block,.region-sidebar-second .block {width:100%}
.region-sidebar-second {width:100%}
.content-inner,.region-sidebar-first,.region-sidebar-second {float:none}
.region-sidebar-first,.region-sidebar-second {clear:both}';
  }
  if ($method === 'one-col-vert') {
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

// Build CSS and save it as a file, or not, I havent decided yet...
function build_font_families_css($element, $selector, $font_values) {
  // Format values as valid CSS
  $font_styles = array();
  if (!empty($font_values)) {
    if ($font_values['font_size'] === '<none>') {$font_values['font_size'] = '';}
    $font_styles[] = $font_values['font_style']  ? 'font-style:'  . $font_values['font_style'] . ';' : '';
    $font_styles[] = $font_values['font_weight'] ? 'font-weight:' . $font_values['font_weight'] . ';' : '';
    $font_styles[] = $font_values['font_size']   ? 'font-size:'   . $font_values['font_size'] . ';' : '';
    $font_styles[] = $font_values['font_family'] ? 'font-family:' . $font_values['font_family'] : '';
  }
  $font_styles = implode('', $font_styles);
  $css = array();
  switch ($element) {
    case $element:
      $css[] = $selector . '{' . $font_styles . '}';
    break;
  }
  $styles = implode("\n", $css);
  return $styles;
}
