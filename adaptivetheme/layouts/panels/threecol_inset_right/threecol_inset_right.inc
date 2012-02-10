<?php
function adaptivetheme_threecol_inset_right_panels_layouts() {
  $items['threecol_inset_right'] = array(
    'title'    => t('Three column inset right (conditional stack)'),
    'category' => t('AT Responsive Panels - Inset'),
    'icon'     => 'threecol_inset_right.png',
    'theme'    => 'threecol_inset_right',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'threecol_inset_right_sidebar' => t('Sidebar'),
      'threecol_inset_right_top'     => t('Top (conditional)'),
      'threecol_inset_right_middle'  => t('Middle'),
      'threecol_inset_right_inset'   => t('Inset'),
      'threecol_inset_right_bottom'  => t('Bottom (conditional)'),
    ),
  );
  return $items;
}
