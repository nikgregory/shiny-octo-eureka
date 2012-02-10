<?php
// Validate some stuff
function at_theme_settings_validate($form, &$form_state) {

  $values = $form_state['values'];

  // Validate max_width values seperatly, they need a condition so they are actually visible on the page
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

// Custom submit function to generate and save the layout CSS embedded in media queries
function at_theme_settings_submit($form, &$form_state) {

  $values = $form_state['values'];

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
    $width          = "\n" . '.container {width: 100%;}';

    $panels         = at_layout_panels($device_group);
    $panels_layout  = implode("\n", $panels);

    $styles = implode("\n", $layout) . $width . $panels_layout;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    $layouts[] = check_plain($css);
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
    $width          = "\n" . '.container {width: ' . $page_width . $page_unit . ';}';

    if ($values['smartphone_landscape_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['smartphone_landscape_max_width'];
      $max_width_unit = $values['smartphone_landscape_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $max_width . $max_width_unit . ';}';
      }
      else {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $page_width . $page_unit . ';}';
      }
    }

    $panels         = at_layout_panels($device_group);
    $panels_layout  = implode("\n", $panels);

    $styles = implode("\n", $layout) . $width . $panels_layout;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    $layouts[] = check_plain($css);
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
    $width          = "\n" . '.container {width: ' . $page_width . $page_unit . ';}';

    if ($values['tablet_portrait_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['tablet_portrait_max_width'];
      $max_width_unit = $values['tablet_portrait_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $max_width . $max_width_unit . ';}';
      }
      else {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $page_width . $page_unit . ';}';
      }
    }

    $panels         = at_layout_panels($device_group);
    $panels_layout  = implode("\n", $panels);

    $styles = implode("\n", $layout) . $width . $panels_layout;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    $layouts[] = check_plain($css);
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
    $width          = "\n" . '.container {width: ' . $page_width . $page_unit . ';}';

    if ($values['tablet_landscape_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['tablet_landscape_max_width'];
      $max_width_unit = $values['tablet_landscape_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $max_width . $max_width_unit . ';}';
      }
      else {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $page_width . $page_unit . ';}';
      }
    }

    $panels         = at_layout_panels($device_group);
    $panels_layout  = implode("\n", $panels);

    $styles = implode("\n", $layout) . $width . $panels_layout;
    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    $layouts[] = check_plain($css);
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
    $width          = "\n" . '.container {width: '. $page_width . $page_unit . ';}';

    if ($values['bigscreen_set_max_width'] == 1 && $page_unit == '%') {
      $max_width = $values['bigscreen_max_width'];
      $max_width_unit = $values['bigscreen_max_width_unit'];
      if (!empty($max_width)) {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $max_width . $max_width_unit . ';}';
      }
      else {
        $width = "\n" . '.container {width: ' . $page_width . $page_unit . '; max-width: ' . $page_width . $page_unit . ';}';
      }
    }

    $panels         = at_layout_panels($device_group);
    $panels_layout  = implode("\n", $panels);

    $styles = implode("\n", $layout) . $width . $panels_layout;

    $css = $comment . '@media ' . $media_query . ' {' . $styles . "\n" . '}';
    $layouts[] = check_plain($css);
  }
  $layout_data = implode("\n", $layouts);

  $theme = $form_state['build_info']['args'][0];
  $file  = $theme . '.responsive.layout.css';
  $path  = "public://at_css";
  $data  = $layout_data;

  file_prepare_directory($path, FILE_CREATE_DIRECTORY);

  $filepath = $path . '/' . $file;
  file_save_data($data, $filepath, FILE_EXISTS_REPLACE);
  //drupal_chmod($file);

  // set variables so we can retrive them later to load the css file
  variable_set($theme . '_mediaqueries_path', $path);
  variable_set($theme . '_mediaqueries_css', $file);
}

// Build the core layouts
function at_layout_core($method, $sidebar_first, $sidebar_second, $sidebar_unit) {

  // Set variables for language direction
  $left = 'left';
  $right = 'right';

  // build the sytle arrays
  $styles = array();
  if ($method == 'three-col-grail') {
    $sidebar_second = $sidebar_second . $sidebar_unit;
    $sidebar_first  = $sidebar_first . $sidebar_unit;
    $push_right = $sidebar_second;
    $push_left  = $sidebar_first;
    $pull_right = $sidebar_second;
    $styles[] = '
.two-sidebars .content-inner {margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': ' . $push_right . ';}
.sidebar-first .content-inner {margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': 0;}
.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0;}
.region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -100%;}
.region-sidebar-second {width: ' . $sidebar_second . '; margin-' . $left . ': -' . $pull_right . '; clear: none;}';
  }
  if ($method == 'three-col-right') {
    $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
    $push_right     = $sidebar_second . $sidebar_unit;
    $push_left      = $sidebar_first . $sidebar_unit;
    $left_margin    = $sidebar_second + $sidebar_first . $sidebar_unit;
    $right_margin   = $sidebar_second . $sidebar_unit;
    $styles[] = '
.two-sidebars .content-inner {margin-' . $right . ': ' . $content_margin . '; margin-' . $left . ': 0;}
.sidebar-first .content-inner {margin-' . $right . ': ' . $push_left . '; margin-' . $left . ': 0;}
.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0;}
.region-sidebar-first {width: ' . $sidebar_first . $sidebar_unit . '; margin-' . $left . ': -' . $left_margin . ';}
.region-sidebar-second {width: ' . $sidebar_second . $sidebar_unit . '; margin-' . $left . ': -' . $right_margin . '; clear: none;}
.sidebar-first .region-sidebar-first {width: ' . $sidebar_first . $sidebar_unit . '; margin-' . $left . ': -' . $sidebar_first . $sidebar_unit . ';}';
  }
  if ($method == 'three-col-left') {
    $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
    $left_margin    = $sidebar_first . $sidebar_unit;
    $right_margin   = $sidebar_second . $sidebar_unit;
    $push_right     = $sidebar_first . $sidebar_unit;
    $styles[] = '
.two-sidebars .content-inner {margin-' . $left . ': ' . $content_margin . '; margin-' . $right . ': 0;}
.sidebar-first .content-inner {margin-' . $left . ': ' . $left_margin . '; margin-' . $right . ': 0;}
.sidebar-second .content-inner {margin-' . $left . ': ' . $right_margin . '; margin-' . $right . ': 0;}
.region-sidebar-first {width: ' . $sidebar_first . $sidebar_unit . '; margin-' . $left . ': -100%;}
.region-sidebar-second {width: ' . $sidebar_second . $sidebar_unit . '; margin-' . $left . ': -100%; clear: none;}
.two-sidebars .region-sidebar-second {width: ' . $sidebar_second . $sidebar_unit . '; position: relative; ' . $left . ': ' . $push_right . ' ;}';
  }
  if ($method == 'two-col-stack') {
    $push_right = $sidebar_first . $sidebar_unit;
    $styles[] = '
.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0; margin-' . $right . ': ' . $push_right . ';}
.sidebar-second .content-inner {margin-right: 0; margin-left: 0;}
.region-sidebar-first {width: ' . $sidebar_first . $sidebar_unit . '; margin-' . $left . ': -' . $push_right . ';}
.region-sidebar-second {width: 100%; margin-left: 0; margin-right: 0; margin-top: 20px; clear: both; overflow: hidden;}
.region-sidebar-second .block {float: left; clear: none;}';
  }
  if ($method == 'one-col-stack') {
    $styles[] = '
.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left: 0; margin-right: 0;}
.region-sidebar-first, .region-sidebar-second, .region-sidebar-first .block, .region-sidebar-second .block {width: 100%;}
.region-sidebar-second {width: 100%;}
.content-inner,.region-sidebar-first,.region-sidebar-second {float: none;}
.region-sidebar-first, .region-sidebar-second {clear: both;}';
  }
  if ($method == 'one-col-vert') {
    $one_sidebar = $sidebar_first + $sidebar_second;
    $styles[] = '
.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left: 0; margin-right: 0;}
.region-sidebar-first {width: ' . $sidebar_first . $sidebar_unit . ';}
.region-sidebar-second {width: ' . $sidebar_second . $sidebar_unit . ';}
.one-sidebar .sidebar {width: ' . $one_sidebar . $sidebar_unit . ';}
.region-sidebar-first, .region-sidebar-second {overflow: hidden; margin-top: 20px;}
.region-sidebar-first .block, .region-sidebar-second .block {width: 100%;}';
  }
  return $styles;
}

// Build the panels layouts - these cover both AT Panels and Panels module layouts.
// This doesnt care about what you want in terms of responsive layouts for each
// Panel layout in each device group aka its hard coded. You could override this
// in a responsive.styles.css or hack this file, or help me build a better way
// of handling this...
function at_layout_panels($device_group) {
  $styles = array();

  // Smartphone device group
  if ($device_group == 'smartphone') {
    $styles[] = '
.at-panel .region {display: block; float: none; width: 100% !important;}';
  }

  // Tablet device group
  if ($device_group == 'tablet_portrait') {
    $styles[] = '

.two-33-66 .region-two-33-66-second,
.two-66-33 .region-two-66-33-first {width: 66.666%;}

.three-3x33 .region,
.two-33-66 .region-two-33-66-first,
.two-66-33 .region-two-66-33-second,
.six-6x16 .region,.five-5x20 .region-five-first,
.five-5x20 .region-five-second,
.five-5x20 .region-five-third{width: 33.333%;}

.six-6x16 .region-six-third,
.five-5x20 .region-five-fourth{clear: left;}

.two-50 .region,
.three-50-25-25 .region-three-50-25-25-first,
.three-25-50-25 .region-three-25-50-25-second,
.three-25-25-50 .region-three-25-25-50-third,
.brick-wrapper .region {width: 50%;}

.four-4x25 .region,
.three-25-25-50 .region-three-25-25-50-first,
.three-25-25-50 .region-three-25-25-50-second,
.three-25-50-25 .region-three-25-50-25-first,
.three-25-50-25 .region-three-25-50-25-third,
.three-50-25-25 .region-three-50-25-25-second,
.three-50-25-25 .region-three-50-25-25-third,
.region-threecol-inset-right-sidebar,
.region-threecol-inset-left-sidebar {width: 25%;}

.inset-wrapper {float: left; width: 75%;}
.inset-wrapper .region {width: 100%;}
.inset-wrapper .region-threecol-inset-left-middle,
.inset-wrapper .region-threecol-inset-right-middle {width: 70%;}

.inset-wrapper .region-threecol-inset-right-inset,
.inset-wrapper .region-threecol-inset-left-inset {width: 30%;}

.region-threecol-inset-left-bottom,
.region-threecol-inset-right-bottom {clear: both;}

.threecol-inset-left .region-threecol-inset-left-right,
.threecol-inset-left .region-threecol-inset-left-middle,
.threecol-inset-left .inset-wrapper {float: right;}

.onecol .region,
.twocol-brick .region-twocol-brick-top,
.twocol-brick .region-twocol-brick-middle,
.twocol-brick .region-twocol-brick-bottom {float: none; display: block;}

.two-50 .region,
.three-50-25-25 .region-three-50-25-25-first,
.three-25-50-25 .region-three-25-50-25-second,
.three-25-25-50 .region-three-25-25-50-third,
.brick-wrapper .region,
.five-5x20 .region-five-fourth,
.five-5x20 .region-five-fifth{width: 50%;}';
  }

  // Tablet device group
  if ($device_group == 'tablet_landscape') {
    $styles[] = '

.two-33-66 .region-two-33-66-second,
.two-66-33 .region-two-66-33-first {width: 66.666%;}

.three-3x33 .region,
.two-33-66 .region-two-33-66-first,
.two-66-33 .region-two-66-33-second,
.six-6x16 .region,.five-5x20 .region-five-first,
.five-5x20 .region-five-second,
.five-5x20 .region-five-third{width: 33.333%;}

.six-6x16 .region-six-third,
.five-5x20 .region-five-fourth{clear: left;}

.two-50 .region,
.three-50-25-25 .region-three-50-25-25-first,
.three-25-50-25 .region-three-25-50-25-second,
.three-25-25-50 .region-three-25-25-50-third,
.brick-wrapper .region {width: 50%;}

.four-4x25 .region,
.three-25-25-50 .region-three-25-25-50-first,
.three-25-25-50 .region-three-25-25-50-second,
.three-25-50-25 .region-three-25-50-25-first,
.three-25-50-25 .region-three-25-50-25-third,
.three-50-25-25 .region-three-50-25-25-second,
.three-50-25-25 .region-three-50-25-25-third,
.region-threecol-inset-right-sidebar,
.region-threecol-inset-left-sidebar {width: 25%;}

.inset-wrapper {float: left; width: 75%;}
.inset-wrapper .region {width: 100%;}

.inset-wrapper .region-threecol-inset-left-middle,
.inset-wrapper .region-threecol-inset-right-middle {width: 70%;}

.inset-wrapper .region-threecol-inset-right-inset,
.inset-wrapper .region-threecol-inset-left-inset {width: 30%;}

.region-threecol-inset-left-bottom,
.region-threecol-inset-right-bottom {clear: both;}

.threecol-inset-left .region-threecol-inset-left-right,
.threecol-inset-left .region-threecol-inset-left-middle,
.threecol-inset-left .inset-wrapper {float: right;}

.onecol .region,
.twocol-brick .region-twocol-brick-top,
.twocol-brick .region-twocol-brick-middle,
.twocol-brick .region-twocol-brick-bottom {float: none; display: block;}

.two-50 .region,
.three-50-25-25 .region-three-50-25-25-first,
.three-25-50-25 .region-three-25-50-25-second,
.three-25-25-50 .region-three-25-25-50-third,
.brick-wrapper .region,
.five-5x20 .region-five-fourth,
.five-5x20 .region-five-fifth{width: 50%;}';
  }

  // Bigscreen device group
  if ($device_group == 'bigscreen') {
    $styles[] = '

.two-33-66 .region-two-33-66-second,
.two-66-33 .region-two-66-33-first {width: 66.666%;}

.three-3x33 .region,
.two-33-66 .region-two-33-66-first,
.two-66-33 .region-two-66-33-second {width: 33.333%;}

.five-5x20 .region {width: 20%;}
.six-6x16 .region {width: 16.666%;}

.two-50 .region,
.three-50-25-25 .region-three-50-25-25-first,
.three-25-50-25 .region-three-25-50-25-second,
.three-25-25-50 .region-three-25-25-50-third,
.brick-wrapper .region {width: 50%;}

.four-4x25 .region,
.three-25-25-50 .region-three-25-25-50-first,
.three-25-25-50 .region-three-25-25-50-second,
.three-25-50-25 .region-three-25-50-25-first,
.three-25-50-25 .region-three-25-50-25-third,
.three-50-25-25 .region-three-50-25-25-second,
.three-50-25-25 .region-three-50-25-25-third,
.region-threecol-inset-right-sidebar,
.region-threecol-inset-left-sidebar {width: 25%;}

.inset-wrapper {float: left; width: 75%;}
.inset-wrapper .region {width: 100%;}

.inset-wrapper .region-threecol-inset-left-middle,
.inset-wrapper .region-threecol-inset-right-middle {width: 70%;}

.inset-wrapper .region-threecol-inset-right-inset,
.inset-wrapper .region-threecol-inset-left-inset {width: 30%;}

.region-threecol-inset-left-bottom,
.region-threecol-inset-right-bottom {clear: both; display: block;}

.threecol-inset-left .region-threecol-inset-left-right,
.threecol-inset-left .region-threecol-inset-left-middle,
.threecol-inset-left .inset-wrapper {float: right;}

.onecol .region,
.twocol-brick .region-twocol-brick-top,
.twocol-brick .region-twocol-brick-middle,
.twocol-brick .region-twocol-brick-bottom {float: none; display: block;}';
  }

  // resturn the sytles
  return $styles;
}
