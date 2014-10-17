<?php

use Drupal\Component\Utility\String;

// Development settings
$form['responsive_menus'] = array(
  '#type' => 'details',
  '#title' => t('Menus'),
  '#group' => 'at_settings',
  '#description' => t('<h3>Responsive Menus</h3><p>Set the method for your responsive navbar menu. To use these styles place a menu block in the navbar region, and configure as per the instructions found in the help section.</p>'),
);

$responsive_menu_options = array(
  'slidedown' => t('Slidedown'),
  'offcanvas' => t('Off canvas'),
  'tile' => t('Tiles'),
);

$form['responsive_menus']['settings_responsive_menu_style'] = array(
  '#type' => 'radios',
  '#title' => t('Menu styles'),
  '#options' => $responsive_menu_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_style', $theme),
);

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
