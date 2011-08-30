<?php
function at_layout_styles($method, $sidebar_first, $sidebar_second) {

  global $language;

  // set language direction
  if ($language->dir == 'ltr') {
    $left = 'left';
    $right = 'right';
  }
  if ($language->dir == 'rtl') {
    $left = 'right';
    $right = 'left';
  }

  // set a variable for the unit value, we need this because the unit gets stripped when we add values.
  $unit = 'px';
  if (theme_get_setting('layout_type') == 'responsive') {
    $unit = '%';
  }

  // build the sytle arrays, params are passed to the function from preprocess_html
  $styles = array();
  if ($method == 'three-col-grail') {
    $push_right = $sidebar_second;
    $push_left  = $sidebar_first;
    $pull_right = $sidebar_second;
    $styles[] = '.two-sidebars .content-inner { margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': ' . $push_right . '; }';
    $styles[] = '.sidebar-first .content-inner { margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': 0; }';
    $styles[] = '.sidebar-second .content-inner { margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0; }';
    $styles[] = '.region-sidebar-first { width:' . $sidebar_first . '; margin-' . $left . ': -100%; }';
    $styles[] = '.region-sidebar-second { width:' . $sidebar_second . '; margin-' . $left . ': -' . $pull_right . '; }';
  }
  if ($method == 'three-col-right') {
    $content_margin = $sidebar_second + $sidebar_first . $unit;
    $push_right     = $sidebar_second;
    $push_left      = $sidebar_first;
    $left_margin    = $sidebar_second + $sidebar_first . $unit;
    $right_margin   = $sidebar_second;
    $styles[] = '.two-sidebars .content-inner { margin-' . $right . ': ' . $content_margin . '; margin-'. $left . ': 0; }';
    $styles[] = '.sidebar-first .content-inner { margin-' . $right . ': ' . $push_left . '; margin-' . $left . ': 0; }';
    $styles[] = '.sidebar-second .content-inner { margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0; }';
    $styles[] = '.region-sidebar-first { width: ' . $sidebar_first . '; margin-' . $left . ': -' . $left_margin . '; }';
    $styles[] = '.region-sidebar-second { width:' . $sidebar_second . '; margin-' . $left . ': -' . $right_margin . '; }';
    $styles[] = '.sidebar-first .region-sidebar-first { width:' . $sidebar_first . '; margin-' . $left . ': -' . $sidebar_first . '; }';
  }
  if ($method == 'three-col-left') {
    $content_margin = $sidebar_second + $sidebar_first . $unit;
    $left_margin    = $sidebar_first;
    $right_margin   = $sidebar_second;
    $push_right     = $sidebar_first;
    $styles[] = '.two-sidebars .content-inner { margin-' . $left . ': ' . $content_margin . '; margin-' . $right . ': 0; }';
    $styles[] = '.sidebar-first .content-inner { margin-' . $left . ': ' . $left_margin . '; margin-' . $right . ': 0; }';
    $styles[] = '.sidebar-second .content-inner { margin-' . $left . ': ' . $right_margin . '; margin-' . $right . ': 0; }';
    $styles[] = '.region-sidebar-first { width:' . $sidebar_first . '; margin-' . $left . ': -100%; }';
    $styles[] = '.region-sidebar-second { width:' . $sidebar_second . '; margin-' . $left . ': -100%; }';
    $styles[] = '.two-sidebars .region-sidebar-second { width:' . $sidebar_second . '; position: relative; ' . $left . ': ' . $push_right . ' ;}';
  }
  if ($method == 'two-col-stack') {
    $push_right = $sidebar_first;
    $styles[] = '.two-sidebars .content-inner { margin-' . $left . ': 0; margin-' . $right . ':' . $push_right . '; }';
    $styles[] = '.sidebar-second .content-inner { margin-right: 0; margin-left: 0; }';
    $styles[] = '.region-sidebar-first { width:' . $sidebar_first . '; margin-' . $left . ':-' . $push_right . '; }';
    $styles[] = '.region-sidebar-second { width: 100%; margin-left: 0; margin-right: 0; clear: both; }';
  }
  if ($method == 'one-col-stack') {
    $styles[] = '.two-sidebars .content-inner { margin-left: 0; margin-right: 0; }';
    $styles[] = '.sidebar-second .content-inner { margin-right: 0; margin-left: 0; }';
    $styles[] = '.region-sidebar-first { width: 100%; margin-right: 0; margin-left: 0; clear: both; }';
    $styles[] = '.region-sidebar-second { width: 100%; margin-left: 0; margin-right: 0; clear: both; }';
  }
  // one col vert
  if ($method == 'one-col-vert') {
    $styles[] = '.two-sidebars .content-inner { margin-left: 0; margin-right: 0; }';
    $styles[] = '.sidebar-second .content-inner { margin-right: 0; margin-left: 0; }';
    $styles[] = '.region-sidebar-first { width: 100%; margin-right: 0; margin-left: 0; clear: both; }';
    $styles[] = '.region-sidebar-second { width: 100%; margin-left: 0; margin-right: 0; clear: both; }';
  }
  return $styles;
}
