<?php
function adaptivetheme_three_inset_right_panels_layouts() {
  $items['three_inset_right'] = array(
    'title'    => t('Three column inset right (conditional stack)'),
    'category' => t('AT Responsive Panels - Inset'),
    'icon'     => 'three_inset_right.png',
    'theme'    => 'three_inset_right',
    'admin css' => 'three_inset_right.admin.css',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'three_inset_right_sidebar' => t('Sidebar'),
      'three_inset_right_top'     => t('Top (conditional)'),
      'three_inset_right_middle'  => t('Middle'),
      'three_inset_right_inset'   => t('Inset'),
      'three_inset_right_bottom'  => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
