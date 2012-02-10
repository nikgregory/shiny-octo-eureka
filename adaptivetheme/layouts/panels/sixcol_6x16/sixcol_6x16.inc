<?php
function adaptivetheme_sixcol_6x16_panels_layouts() {
  $items['sixcol_6x16'] = array(
    'title'    => t('Six column 6x16'),
    'category' => t('AT Responsive Panels - 4,5,6 column'),
    'icon'     => 'sixcol_6x16.png',
    'theme'    => 'sixcol_6x16',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'six_first'  => t('Column 1'),
      'six_second' => t('Column 2'),
      'six_third'  => t('Column 3'),
      'six_fourth' => t('Column 4'),
      'six_fifth'  => t('Column 5'),
      'six_sixth'  => t('Column 6'),
    ),
  );
  return $items;
}
