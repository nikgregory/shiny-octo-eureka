<?php
function adaptivetheme_twocol_50_panels_layouts() {
  $items['twocol_50'] = array(
    'title'    => t('Two column 50/50 (conditional stack)'),
    'category' => t('AT Responsive Panels - 2 column'),
    'icon'     => 'twocol_50.png',
    'theme'    => 'twocol_50',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'twocol_50_top'    => t('Top (conditional)'),
      'twocol_50_first'  => t('Left'),
      'twocol_50_second' => t('Right'),
      'twocol_50_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}