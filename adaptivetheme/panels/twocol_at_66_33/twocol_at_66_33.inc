<?php // $Id$
// adpativethemes.com

/**
 * @file twocol_at_66_33.inc
 * Implementation of hook_panels_layouts()
 */
function adaptivetheme_twocol_at_66_33_panels_layouts() {
  $items['twocol_at_66_33'] = array(
    'title' => t('Two column AT 66/33'),
    'icon' => 'twocol_at_66_33.png',
    'theme' => 'twocol_at_66_33',
    'theme arguments' => array('id', 'content'),
    'css' => 'twocol_at_66_33.css',
    'panels' => array(
      'left' => t('Left side'),
      'right' => t('Right side'),
    ),
  );

  return $items;
}
