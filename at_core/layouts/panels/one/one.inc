<?php
function adaptivetheme_one_panels_layouts() {
  $items['one'] = array(
    'title'    => t('One column'),
    'category' => t('AT Responsive Panels - 1 column'),
    'icon'     => 'one.png',
    'theme'    => 'one',
    'admin css' => 'one.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'one_main'  => t('Main'),
    ),
  );
  return $items;
}
