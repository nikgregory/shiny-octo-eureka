<?php
function adaptivetheme_two_brick_panels_layouts() {
  $items['two_brick'] = array(
    'title'    => t('Two column brick'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon'     => 'two_brick.png',
    'theme'    => 'two_brick',
    'admin css' => 'two_brick.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'two_brick_top'         => t('Top (conditional)'),
      'two_brick_left_above'  => t('Left above'),
      'two_brick_right_above' => t('Right above'),
      'two_brick_middle'      => t('Middle'),
      'two_brick_left_below'  => t('Left below'),
      'two_brick_right_below' => t('Right below'),
      'two_brick_bottom'      => t('Bottom'),
    ),
  );
  return $items;
}
