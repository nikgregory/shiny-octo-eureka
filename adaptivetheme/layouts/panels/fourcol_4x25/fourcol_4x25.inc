<?php
function adaptivetheme_fourcol_4x25_panels_layouts() {
  $items['fourcol_4x25'] = array(
    'title'    => t('Four column 4x25'),
    'category' => t('AT Responsive Panels - 4,5,6 column'),
    'icon'     => 'fourcol_4x25.png',
    'theme'    => 'fourcol_4x25',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'four_first'  => t('Column 1'),
      'four_second' => t('Column 2'),
      'four_third'  => t('Column 3'),
      'four_fourth' => t('Column 4'),
    ),
  );
  return $items;
}
