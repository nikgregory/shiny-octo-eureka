<?php
function adaptivetheme_twocol_66_33_panels_layouts() {
  $items['twocol_66_33'] = array(
    'title' => t('Two column 66/33 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon' => 'twocol_66_33.png',
    'theme' => 'twocol_66_33',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'twocol_366_33_top'    => t('Top (conditional)'),
      'twocol_366_33_first'  => t('Left'),
      'twocol_366_33_second' => t('Right'),
      'twocol_366_33_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
