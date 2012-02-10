<?php
function adaptivetheme_onecol_panels_layouts() {
  $items['onecol'] = array(
    'title'    => t('One column'),
    'category' => t('AT Responsive Panels - 1 column'),
    'icon'     => 'onecol.png',
    'theme'    => 'onecol',
    'theme arguments' => array('id', 'content'),
    'regions' => array(
      'onecol_main'  => t('Column 1'),
    ),
  );
  return $items;
}
