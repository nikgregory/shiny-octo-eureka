<?php
function adaptivetheme_threecol_50_25_25_panels_layouts() {
  $items['threecol_50_25_25'] = array(
    'title'    => t('Three column 50/25/25 (conditional stack)'),
    'category' => t('AT Responsive Panels - 3 column'),
    'icon'     => 'threecol_50_25_25.png',
    'theme'    => 'threecol_50_25_25',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'three_50_25_25_top'    => t('Top (conditional)'),
      'three_50_25_25_first'  => t('Left'),
      'three_50_25_25_second' => t('Center'),
      'three_50_25_25_third'  => t('Right'),
      'three_50_25_25_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
