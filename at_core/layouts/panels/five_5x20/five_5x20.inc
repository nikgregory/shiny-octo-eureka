<?php
function adaptivetheme_five_5x20_panels_layouts() {
  $items['five_5x20'] = array(
    'title'    => t('Five column 5x20'),
    'category' => t('AT Responsive Panels - 4,5,6 column'),
    'icon'     => 'five_5x20.png',
    'theme'    => 'five_5x20',
    'admin css' => 'five_5x20.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'five_first'  => t('Column 1'),
      'five_second' => t('Column 2'),
      'five_third'  => t('Column 3'),
      'five_fourth' => t('Column 4'),
      'five_fifth'  => t('Column 5'),
    ),
    // AT
    'type' => 'five',
    'options' => array(
      'five-5x20' => 'default',
      'five-5x20-2x3-grid' => '2/3 split grid',
      'five-5x20-1x2x2-grid' => 'stack',
      'five-5x20-stack-2' => 'Stack 50/50 bottom',
      'five-5x20-stack' => 'stack',
    ),
    'styles' => array(
      'five-5x20' => array(
        'css' => array(
          '40' => array('.five-5x20 .row-1' => 'width:40%'),
          '60' => array('.five-5x20 .row-2' => 'width:60%'),
          'fl' => array('.five-5x20 .panel-row' => 'float:left'),
          '50' => array('.five-5x20 .row-1 .region' => 'width:50%'),
          '33' => array('.five-5x20 .row-2 .region' => 'width:33.333333%'),
        ),
      ),
      'five-5x20-2x3-grid' => array(
        'css' => array(
          '33' => array('.five-5x20 .region' => 'width:33.333333%'),
          '50' => array('.five-5x20 .row-1 .region-five-first,.five-5x20 .row-1 .region-five-second' => 'width:50%'),
          'fdw' => array('.five-5x20 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
        ),
      ),
      'five-5x20-1x2x2-grid' => array(
        'css' => array(
          '50' => array('.five-5x20 .panel-row .region' => 'width:50%'),
          'fdw' => array('.five-5x20 .panel-row,.five-5x20 .panel-row .region-five-fifth' => 'float:none;display:block;width:100%;clear:both'),
        ),
      ),
      'five-5x20-stack-2' => array(
        'css' => array(
          '50' => array('.five-5x20 .row-1 .region' => 'width:50%'),
          'fdw' => array('.five-5x20 .panel-row,.five-5x20 .row-2 .region' => 'float:none;display:block;width:100%;clear:both'),
        ),
      ),
      'five-5x20-stack' => array(
        'css' => array(
          'fdw' => array('.five-5x20 .panel-row .region,.five-5x20 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
        ),
      ),
    ),
  );

  return $items;
}
