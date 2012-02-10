<?php
function adaptivetheme_threecol_3x33_panels_layouts() {
  $items['threecol_3x33'] = array(
    'title'    => t('Three column 33/34/33 (conditional stack)'),
    'category' => t('AT Responsive Panels - 3 column'),
    'icon'     => 'threecol_3x33.png',
    'theme'    => 'threecol_3x33',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'three_33_top'    => t('Top (conditional)'),
      'three_33_first'  => t('Left'),
      'three_33_second' => t('Center'),
      'three_33_third'  => t('Right'),
      'three_33_bottom' => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
