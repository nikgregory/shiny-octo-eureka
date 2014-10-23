<?php

use Drupal\Component\Utility\String;

// Development settings
$form['responsive_menus'] = array(
  '#type' => 'details',
  '#title' => t('Menus'),
  '#group' => 'at_settings',
  '#description' => t('<h3>Responsive Menu Blocks</h3><p>Select options for each style and the region you want to use for responsive menu blocks.</p>'),
);

$responsive_menu_options = array(
  'slidedown' => t('Slidedown'),
  'offcanvas' => t('Off canvas'),
  'tiles' => t('Tiles'),
);

$form['responsive_menus']['settings_responsive_menu_style'] = array(
  '#type' => 'radios',
  '#title' => t('Menu styles'),
  '#options' => $responsive_menu_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_style', $theme),
);

// Offcanvas options
$form['responsive_menus']['settings_responsive_menu_offcanvas_position'] = array(
  '#type' => 'radios',
  '#title' => t('Off Canvas position'),
  '#options' => array(
    'offcanvas-left' => t('Left'),
    'offcanvas-right' => t('Right'),
  ),
  '#default_value' => theme_get_setting('settings.responsive_menu_offcanvas_position', $theme),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_style"]' => array('value' => 'offcanvas')),
  ),
  '#description' => t('Choose which side of the page the menu will slide in from.'),
);

// Tiles options
// Tile count
$form['responsive_menus']['settings_responsive_menu_tiles_count'] = array(
  '#type' => 'select',
  '#title' => t('Tile count'),
  '#options' => array(2,3,4),
  '#default_value' => String::checkPlain(theme_get_setting('settings.responsive_menu_tiles_count', $theme)),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_style"]' => array('value' => 'tiles')),
  ),
  '#description' => t('Set the number of tiles to display horizontally.'),
);

// Show tile sub-menus
$form['responsive_menus']['settings_responsive_menu_tiles_submenus'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show tile sub-menus'),
  '#default_value' => theme_get_setting('settings.responsive_menu_tiles_submenus', $theme),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_style"]' => array('value' => 'tiles')),
  ),
  '#description' => t('This setting will show the sub-menus for each top level item within the tile. Tiles will be equal-height to the height of the tallest tile.'),
);

// Regions
$form['responsive_menus']['settings_responsive_menu_region'] = array(
  '#type' => 'select',
  '#title' => t('Block region'),
  '#options' => $theme_regions,
  '#default_value' => theme_get_setting('settings.responsive_menu_region', $theme),
  '#description' => t('Menu blocks in this region will be styled as responsive menus.'),
);
















