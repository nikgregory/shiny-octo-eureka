<?php

use Drupal\Component\Utility\Xss;

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

// TODO - do we need sanity checks anywhere here on the output?
$form['help']['menu'] = array(
  '#type' => 'container',
  '#markup' => Xss::filterAdmin($help_menu),
);
$form['help']['subtheme_types'] = array(
  '#type' => 'container',
  '#markup' => Xss::filterAdmin($subtheme_types),
);
$form['help']['updating_skins'] = array(
  '#type' => 'container',
  '#markup' => Xss::filterAdmin($updating_skins),
);
