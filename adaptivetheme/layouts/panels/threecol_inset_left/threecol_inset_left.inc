<?php
function adaptivetheme_threecol_inset_left_panels_layouts() {
  $items['threecol_inset_left'] = array(
    'title' => t('Three colume inset left (conditional stack)'),
    'category' => t('AT Responsive Panels - Inset'),
    'icon' => 'threecol_inset_left.png',
    'theme' => 'threecol_inset_left',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'threecol_inset_left_sidebar' => t('Sidebar'),
      'threecol_inset_left_top'     => t('Top (conditional)'),
      'threecol_inset_left_middle'  => t('Middle'),
      'threecol_inset_left_inset'   => t('Inset'),
      'threecol_inset_left_bottom'  => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
