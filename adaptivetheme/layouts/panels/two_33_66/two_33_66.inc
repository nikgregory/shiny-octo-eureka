<?php
function adaptivetheme_two_33_66_panels_layouts() {
  $items['two_33_66'] = array(
    'title' => t('Two column 33/66 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon' => 'two_33_66.png',
    'theme' => 'two_33_66',
    'admin css' => 'two_33_66.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'two_33_66_top'    => t('Top (conditional)'),
      'two_33_66_first'  => t('Left'),
      'two_33_66_second' => t('Right'),
      'two_33_66_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
