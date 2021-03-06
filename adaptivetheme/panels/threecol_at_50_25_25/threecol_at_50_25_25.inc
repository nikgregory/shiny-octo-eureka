<?php // $Id$
// adpativethemes.com

/**
 * @file twocol_at_50_25_25.inc
 * Implementation of hook_panels_layouts()
 */
function adaptivetheme_threecol_at_50_25_25_panels_layouts() {
  $items['threecol_at_50_25_25'] = array(
    'title' => t('Three column AT 50/25/25'),
    'icon' => 'threecol_at_50_25_25.png',
    'theme' => 'threecol_at_50_25_25',
    'theme arguments' => array('id', 'content'),
    'css' => 'threecol_at_50_25_25.css',
    'panels' => array(
      'left' => t('Left side'),
      'middle' => t('Middle column'),
      'right' => t('Right side'),
    ),
  );

  return $items;
}
