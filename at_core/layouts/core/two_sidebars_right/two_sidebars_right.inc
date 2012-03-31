<?php
// Positions both sidebars to the right of the main content column.
function two_sidebars_right() {
  $page_layout['two_sidebars_right'] = array(
    'title'     => t('Two Sidebars Right'),
    'category'  => t('AT Core layout'),
    'method'    => 'two_sidebars_right',
    'type'      => 'page_layout',
    'icon'      => 'two_sidebars_right.png',
    'admin_css' => 'two_sidebars_right.css',
    'device_groups' => array(
      'bigscreen',
      'tablet_landscape',
     ),
  );
  return $page_layout;
}

function two_sidebars_right_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $left = 'left';
  $right = 'right';

  $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
  $push_right     = $sidebar_second . $sidebar_unit;
  $push_left      = $sidebar_first . $sidebar_unit;
  $left_margin    = $sidebar_second + $sidebar_first . $sidebar_unit;
  $right_margin   = $sidebar_second . $sidebar_unit;

  $styles[] = '#content-column,.content-column,div.sidebar {float:left;clear:none}';
  $styles[] = '.two-sidebars .content-inner {margin-' . $right . ':' . $content_margin . ';margin-' . $left . ':0}';
  $styles[] = '.sidebar-first .content-inner {margin-' . $right . ':' . $push_left . ';margin-' . $left . ':0}';
  $styles[] = '.sidebar-second .content-inner {margin-' . $right . ':' . $push_right . ';margin-' . $left . ':0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $left_margin . '}';
  $styles[] = '.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';margin-' . $left . ':-' . $right_margin . '}';
  $styles[] = '.sidebar-first .region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-' . $sidebar_first . $sidebar_unit . '}';

  $output = implode('', $styles);
  return $output;
}