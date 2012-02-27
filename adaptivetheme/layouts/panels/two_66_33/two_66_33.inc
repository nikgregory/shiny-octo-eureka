<?php
function adaptivetheme_two_66_33_panels_layouts() {
  $items['two_66_33'] = array(
    'title' => t('Two column 66/33 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon' => 'two_66_33.png',
    'theme' => 'two_66_33',
    'admin css' => 'two_66_33.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'two_66_33_top'    => t('Top (conditional)'),
      'two_66_33_first'  => t('Left'),
      'two_66_33_second' => t('Right'),
      'two_66_33_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
