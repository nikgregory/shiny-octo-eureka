<?php
// Positions both sidebars to the left of the main content column.
function two_sidebars_left() {
  $page_layout['two_sidebars_left'] = array(
    'title'     => t('Two Sidebars Left'),
    'category'  => t('AT Core layout'),
    'method'    => 'two_sidebars_left',
    'type'      => 'page_layout',
    'icon'      => 'two_sidebars_left.png',
    'admin_css' => 'two_sidebars_left.css',
    'device_groups' => array(
      'bigscreen',
      'tablet_landscape',
     ),
  );
  return $page_layout;
}

function two_sidebars_left_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $left = 'left';
  $right = 'right';

  $content_margin = $sidebar_second + $sidebar_first . $sidebar_unit;
  $left_margin    = $sidebar_first . $sidebar_unit;
  $right_margin   = $sidebar_second . $sidebar_unit;
  $push_right     = $sidebar_first . $sidebar_unit;

  $styles[] = '#content-column,.content-column,div.sidebar {float:left;clear:none}';
  $styles[] = '.two-sidebars .content-inner {margin-' . $left . ': ' . $content_margin . ';margin-' . $right . ':0}';
  $styles[] = '.sidebar-first .content-inner {margin-' . $left . ': ' . $left_margin . ';margin-' . $right . ':0}';
  $styles[] = '.sidebar-second .content-inner {margin-' . $left . ': ' . $right_margin . ';margin-' . $right . ':0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . ';margin-' . $left . ':-100%}';
  $styles[] = '.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';margin-' . $left . ':-100%}';
  $styles[] = '.two-sidebars .region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . ';position:relative;' . $left . ':' . $push_right . '}';

  $output = implode('', $styles);
  return $output;
}