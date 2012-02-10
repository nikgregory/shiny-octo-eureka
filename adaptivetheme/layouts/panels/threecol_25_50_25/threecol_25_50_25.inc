<?php
function adaptivetheme_threecol_25_50_25_panels_layouts() {
  $items['threecol_25_50_25'] = array(
    'title'    => t('Three column 25/50/25 (conditional stack)'),
    'category' => t('AT Responsive Panels - 3 column'),
    'icon'     => 'threecol_25_50_25.png',
    'theme'    => 'threecol_25_50_25',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'three_25_50_25_top'    => t('Top (conditional)'),
      'three_25_50_25_first'  => t('Left'),
      'three_25_50_25_second' => t('Center'),
      'three_25_50_25_third'  => t('Right'),
      'three_25_50_25_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
