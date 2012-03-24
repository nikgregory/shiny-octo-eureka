<?php
// Build the core layouts, this is called once per orientation/break-point
function at_build_page_layout($method, $sidebar_first, $sidebar_second, $sidebar_unit) {

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
  if ($method === 'two-sidebars-right') {
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
  if ($method === 'two-sidebars-left') {
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
  if ($method === 'two-sidebars-right-stack') {
    $push_right = $sidebar_first . $sidebar_unit;
    $styles[] = '
#content-column,.content-column {float:left;clear:none}
.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0;margin-' . $right . ':' . $push_right . '}
.sidebar-second .content-inner {margin-right:0;margin-left:0}
.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $push_right . ';float:left;clear:none}
.region-sidebar-second {width:100%;margin-left:0;margin-right:0;margin-top:20px;clear:both;overflow:hidden}
.region-sidebar-second .block {float:left;clear:none}';
  }
  if ($method === 'two-sidebars-left-stack') {
    $sidebar_first  = $sidebar_first . $sidebar_unit;
    $push_left  = $sidebar_first;
    $styles[] = '
#content-column,.content-column,.region-sidebar-first {float:left;clear:none}
.two-sidebars .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}
.sidebar-first .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}
.sidebar-second .content-inner {margin-' . $right . ':0;margin-' . $left . ':0}
.region-sidebar-first {width:' . $sidebar_first . ';margin-' . $left . ':-100%}
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

// Build font family CSS
function at_build_font_families($element, $selector, $font_values) {
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