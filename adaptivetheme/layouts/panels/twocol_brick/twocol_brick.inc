<?php
function adaptivetheme_twocol_brick_panels_layouts() {
  $items['twocol_brick'] = array(
    'title'    => t('Two column brick'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon'     => 'twocol_brick.png',
    'theme'    => 'twocol_brick',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'twocol_brick_top'         => t('Top (conditional)'),
      'twocol_brick_left_above'  => t('Left above'),
      'twocol_brick_right_above' => t('Right above'),
      'twocol_brick_middle'      => t('Middle'),
      'twocol_brick_left_below'  => t('Left below'),
      'twocol_brick_right_below' => t('Right below'),
      'twocol_brick_bottom'      => t('Bottom'),
    ),
  );
  return $items;
}
