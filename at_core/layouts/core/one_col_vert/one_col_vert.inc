<?php
// Displays sidebars vertically side by side beneath the main content column.
function one_col_vert() {
  $page_layout['one_col_vert'] = array(
    'title'     => t('One Column Vertical Sidebars'),
    'category'  => t('AT Core layout'),
    'method'    => 'one_col_vert',
    'type'      => 'page_layout',
    'device_groups' => array(
      'tablet_portrait',
      'smartphone_landscape',
     ),
  );
  return $page_layout;
}

function one_col_vert_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $one_sidebar = $sidebar_first + $sidebar_second;
 
  $styles[] = '.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left:0;margin-right:0}';
  $styles[] = '.region-sidebar-first {width:' . $sidebar_first . $sidebar_unit . '}';
  $styles[] = '.region-sidebar-second {width:' . $sidebar_second . $sidebar_unit . '}';
  $styles[] = '.one-sidebar .sidebar {width:' . $one_sidebar . $sidebar_unit . '}';
  $styles[] = '.region-sidebar-first,.region-sidebar-second {overflow:hidden;margin-top:20px;float:left;clear:none}';
  $styles[] = '.region-sidebar-first.block,.region-sidebar-second .block {width:100%}';

  $output = implode('', $styles);
  return $output;
}