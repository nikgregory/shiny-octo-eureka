<?php
// Note: this is the only layout for smartphone portrait, and for mobile first.
// All columns, regions, sidebars etc 100% width, stacked.
function one_col_stack() {
  $page_layout['one_col_stack'] = array(
    'title'     => t('One Column Stacked'),
    'category'  => t('AT Core layout'),
    'method'    => 'one_col_stack',
    'type'      => 'page_layout',
    'device_groups' => array(
      'tablet_portrait',
      'smartphone_landscape',
     ),
  );
  return $page_layout;
}

function one_col_stack_layout($sidebar_first, $sidebar_second, $sidebar_unit) {
  $output = '';
  $styles = array();

  $styles[] = '.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left:0;margin-right:0}';
  $styles[] = '.region-sidebar-first,.region-sidebar-second,.region-sidebar-first .block,.region-sidebar-second .block {width:100%}';
  $styles[] = '.region-sidebar-second {width:100%}';
  $styles[] = '.content-inner,.region-sidebar-first,.region-sidebar-second {float:none}';
  $styles[] = '.region-sidebar-first,.region-sidebar-second {clear:both}';

  $output = implode('', $styles);
  return $output;
}