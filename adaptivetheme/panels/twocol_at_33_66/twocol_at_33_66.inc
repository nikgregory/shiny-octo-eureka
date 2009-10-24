<?php
// adpativethemes.com

/**
 * Implementation of hook_panels_layouts()
 */
function adaptivetheme_twocol_at_33_66_panels_layouts() {
  $items['twocol_at_33_66'] = array(
    'title' => t('Two column AT 33/66'),
    'icon' => 'twocol_at_33_66.png',
    'theme' => 'twocol_at_33_66',
    'theme arguments' => array('id', 'content'),
    'css' => 'twocol_at_33_66.css',
    'panels' => array(
      'left' => t('Left side'),
      'right' => t('Right side'),
    ),
  );

  return $items;
}