<?php

// Help
//----------------------------------------------------------------------
$help_menu      = file_get_contents($at_core_path . '/docs/help_core/index.html');
$subtheme_types = file_get_contents($at_core_path . '/docs/help_core/subtheme-types.html');
$updating_skins = file_get_contents($at_core_path . '/docs/help_core/updating-skin-info-files.html');

$form['help'] = array(
  '#type' => 'details',
  '#title' => t('Help'),
  '#group' => 'atsettings',
  '#tree' => TRUE,
);
$form['help']['menu'] = array(
  '#type' => 'container',
  '#markup' => filter_xss_admin($help_menu),
);
$form['help']['subtheme_types'] = array(
  '#type' => 'container',
  '#markup' => filter_xss_admin($subtheme_types),
);
$form['help']['updating_skins'] = array(
  '#type' => 'container',
  '#markup' => filter_xss_admin($updating_skins),
);
