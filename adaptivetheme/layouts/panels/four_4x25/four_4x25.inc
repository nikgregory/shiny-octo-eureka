<?php
function adaptivetheme_four_4x25_panels_layouts() {
  $items['four_4x25'] = array(
    'title'    => t('Four column 4x25'),
    'category' => t('AT Responsive Panels - 4,5,6 column'),
    'icon'     => 'four_4x25.png',
    'theme'    => 'four_4x25',
    'admin css' => 'four_4x25.admin.css',
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
