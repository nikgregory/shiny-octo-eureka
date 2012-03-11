<?php
function adaptivetheme_two_50_panels_layouts() {
  $items['two_50'] = array(
    'title'    => t('Two column 50/50 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon'     => 'two_50.png',
    'theme'    => 'two_50',
    'admin css' => 'two_50.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'two_50_top'    => t('Top (conditional)'),
      'two_50_first'  => t('Left'),
      'two_50_second' => t('Right'),
      'two_50_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}