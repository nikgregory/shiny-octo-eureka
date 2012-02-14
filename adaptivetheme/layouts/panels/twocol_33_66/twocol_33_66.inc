<?php
function adaptivetheme_twocol_33_66_panels_layouts() {
  $items['twocol_33_66'] = array(
    'title' => t('Two column 33/66 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon' => 'twocol_33_66.png',
    'theme' => 'twocol_33_66',
    'admin css' => 'twocol_33_66.admin.css',
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
