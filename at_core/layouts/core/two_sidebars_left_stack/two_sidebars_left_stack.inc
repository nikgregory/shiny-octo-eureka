<?php
// Both sidebars are pushed to the left, however the second sidebar wraps below
// the main content column and is 100% width. Sometimes known as a "column drop" layout,
// and conceptually similar to the two_sidebars_right_stack.
function two_sidebars_left_stack() {
  $page_layout['two_sidebars_left_stack'] = array(
    'title'     => t('Two Sidebars Left Stacked'),
    'category'  => t('AT Core layout'),
    'method'    => 'two_sidebars_left_stack',
    'type'      => 'page_layout',
    'icon'      => 'two_sidebars_left_stack.png',
    'admin_css' => 'two_sidebars_left_stack.css',
    'device_groups' => array(
      //'bigscreen',
      'tablet_landscape',
     ),
  );
  return $page_layout;
}

function two_sidebars_left_stack_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $left = 'left';
  $right = 'right';

  $sidebar_first  = $sidebar_first . $sidebar_unit;
  $push_left  = $sidebar_first;

  $styles[] = '#content-column,.content-column,.region-sidebar-first {float:left;clear:none}';
  $styles[] = '.two-sidebars .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}';
  $styles[] = '.sidebar-first .content-inner {margin-' . $left . ':' . $push_left . ';margin-' . $right . ':0}';
  $styles[] = '.sidebar-second .content-inner {margin-' . $right . ':0;margin-' . $left . ':0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . ';margin-' . $left . ':-100%}';
  $styles[] = '.region-sidebar-second {width:100%;margin-left:0;margin-right:0;margin-top:20px;clear:both;overflow:hidden}';
  $styles[] = '.region-sidebar-second .block {float:left;clear:none}';
  
  $output = implode('', $styles);
  return $output;
}