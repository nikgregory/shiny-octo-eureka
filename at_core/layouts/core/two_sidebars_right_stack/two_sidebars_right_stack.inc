<?php
// Both sidebars are pushed to the right, however the second sidebar wraps below
// the main content column and is 100% width. Sometimes known as a "column drop" layout,
// and conceptually similar to the two_sidebars_left_stack.
function two_sidebars_right_stack() {
  $page_layout['two_sidebars_right_stack'] = array(
    'title'     => t('Two Sidebars Right'),
    'category'  => t('AT Core layout'),
    'method'    => 'two_sidebars_right_stack',
    'type'      => 'page_layout',
    'device_groups' => array(
      //'bigscreen',
      'tablet_landscape',
     ),
  );
  return $page_layout;
}

function two_sidebars_right_stack_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $left = 'left';
  $right = 'right';

  $push_right = $sidebar_first . $sidebar_unit;

  $styles[] = '#content-column,.content-column {float:left;clear:none}';
  $styles[] = '.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0;margin-' . $right . ':' . $push_right . '}';
  $styles[] = '.sidebar-second .content-inner {margin-right:0;margin-left:0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $push_right . ';float:left;clear:none}';
  $styles[] = '.region-sidebar-second {width:100%;margin-left:0;margin-right:0;margin-top:20px;clear:both;overflow:hidden}';
  $styles[] = '.region-sidebar-second .block {float:left;clear:none}';

  $output = implode('', $styles);
  return $output;
}