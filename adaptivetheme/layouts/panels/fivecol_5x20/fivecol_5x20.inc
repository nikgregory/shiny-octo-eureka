<?php
function adaptivetheme_fivecol_5x20_panels_layouts() {
  $items['fivecol_5x20'] = array(
    'title'    => t('Five column 5x20'),
    'category' => t('AT Responsive Panels - 4,5,6 column'),
    'icon'     => 'fivecol_5x20.png',
    'theme'    => 'fivecol_5x20',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'five_first'  => t('Column 1'),
      'five_second' => t('Column 2'),
      'five_third'  => t('Column 3'),
      'five_fourth' => t('Column 4'),
      'five_fifth'  => t('Column 5'),
    ),
  );
  return $items;
}
