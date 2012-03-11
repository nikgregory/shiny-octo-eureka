<?php
function adaptivetheme_three_inset_left_panels_layouts() {
  $items['three_inset_left'] = array(
    'title' => t('Three colume inset left (conditional stack)'),
    'category' => t('AT Responsive Panels - Inset'),
    'icon' => 'three_inset_left.png',
    'theme' => 'three_inset_left',
    'admin css' => 'three_inset_left.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'three_inset_left_sidebar' => t('Sidebar'),
      'three_inset_left_top'     => t('Top (conditional)'),
      'three_inset_left_middle'  => t('Middle'),
      'three_inset_left_inset'   => t('Inset'),
      'three_inset_left_bottom'  => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
