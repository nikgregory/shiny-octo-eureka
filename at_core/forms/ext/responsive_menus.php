<?php

use Drupal\Component\Utility\String;

$responsive_menu_breakpoint_group = theme_get_setting('settings.responsive_menu_breakpoint_group', $theme);
$responsive_menu_breakpoints = $breakpoints[$responsive_menu_breakpoint_group];

// build options
foreach ($responsive_menu_breakpoints as $rmb_key => $rmb_value) {
  $rmb_group_options[$rmb_value->getMediaQuery()] = $rmb_value->getLabel() . ': ' . $rmb_value->getMediaQuery();
}

// Menu blocks
if (!empty($theme_blocks)) {
  foreach ($theme_blocks as $block_key => $block_values) {
    $block_plugin = $block_values->get('plugin');
    $block_settings = $block_values->get('settings');
    if (in_array('system_menu_block', explode(':', $block_plugin))) {
      $menu_blocks[$block_values->id] = $block_settings['label'];
    }
    else {
      $menu_blocks['bummer'] = '-- no menu blocks available --';
    }
  }
}
else {
  $menu_blocks['bummer'] = '-- no menu blocks available --';
}


// menu style options
$responsive_menu_options = array(
  'none' => t('-- none --'),
  'horizontal' => t('Horizontal'),
  'vertical'   => t('Vertical'),
  'dropmenu'   => t('Drop menu'),
  'slidedown'  => t('Slide down'),
  'flipslide'  => t('Flip slide'),
  'offcanvas'  => t('Off canvas'),
  'overlay'    => t('Overlay'),
  'tiles'      => t('Tiles'),
);

$tiles_count = array(
  'two' => 2,
  'three' => 3,
  'four' => 4,
);

// Development settings
$form['responsive_menus'] = array(
  '#type' => 'details',
  '#title' => t('Responisve Menus'),
  '#group' => 'extension_settings',
  '#description' => t('<h3>Responsive Menus</h3><p>First select the block menu you want to apply resonsive style to. Next select the Breakpoint group, normally this will be the same as your layout Breakpoint group, however you can use any group. If you change the group you must save the settings for this to take effect. Finally configure the default and responsive styles, and set a breakpoint for the responsive style.</p>'),
);


// Block
$form['responsive_menus']['block'] = array(
  '#type' => 'fieldset',
  '#attributes' => array('class' => array('clearfix')),
);

$form['responsive_menus']['block']['settings_responsive_menu_block'] = array(
  '#type' => 'select',
  '#title' => t('Block Menu'),
  '#options' => $menu_blocks,
  '#default_value' => theme_get_setting('settings.responsive_menu_block', $theme),
);

// Default
$form['responsive_menus']['default'] = array(
  '#type' => 'fieldset',
  '#title' => t('Default menu settings'),
  '#attributes' => array('class' => array('clearfix')),
  '#states' => array(
    'enabled' => array('select[name="settings_responsive_menu_breakpoint_group"]' => array('value' => $responsive_menu_breakpoint_group)),
  ),
);

$form['responsive_menus']['default']['styles'] = array(
  '#type' => 'fieldset',
  '#attributes' => array('class' => array('responsive-menu-styles')),
);

$form['responsive_menus']['default']['styles']['settings_responsive_menu_default_style'] = array(
  '#type' => 'select',
  '#title' => t('Default style'),
  '#options' => $responsive_menu_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_default_style', $theme),
);

// Responsive
$form['responsive_menus']['responsive'] = array(
  '#type' => 'fieldset',
  '#title' => t('Responisve menu settings'),
  '#attributes' => array('class' => array('clearfix')),
  '#states' => array(
    'enabled' => array('select[name="settings_responsive_menu_breakpoint_group"]' => array('value' => $responsive_menu_breakpoint_group)),
  ),
);

$form['responsive_menus']['responsive']['styles'] = array(
  '#type' => 'fieldset',
  '#attributes' => array('class' => array('responsive-menu-styles')),
);

$form['responsive_menus']['responsive']['styles']['settings_responsive_menu_responsive_style'] = array(
  '#type' => 'select',
  '#title' => t('Responsive style'),
  '#options' => $responsive_menu_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_responsive_style', $theme),
);


// Breakpoints
$form['responsive_menus']['breakpoints'] = array(
  '#type' => 'fieldset',
  '#attributes' => array('class' => array('clearfix')),
  '#default_value' => theme_get_setting('settings.responsive_menu_breakpoint', $theme),
  '#states' => array(
    'invisible' => array(
      ':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'none'),
    ),
  ),
);

// Breakpoint group
$form['responsive_menus']['breakpoints']['settings_responsive_menu_breakpoint_group'] = array(
  '#type' => 'select',
  '#title' => t('Breakpoints group'),
  '#options' => $breakpoint_options,
  '#default_value' => $responsive_menu_breakpoint_group,
);

// Breakpoint
$form['responsive_menus']['breakpoints']['settings_responsive_menu_breakpoint'] = array(
  '#type' => 'select',
  '#title' => t('Breakpoint'),
  '#options' => $rmb_group_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_breakpoint', $theme),
);














//
// Default Options
//
/*
$form['responsive_menus']['default']['options'] = array(
  '#type' => 'fieldset',
  '#title' => t('Default style options'),
  '#attributes' => array('class' => array('responsive-menu-options')),
  '#states' => array(
    'invisible' => array(
      ':input[name="settings_responsive_menu_default_style"]' => array('value' => 'none'),
    ),
  ),
);

// Horizontal
$form['responsive_menus']['default']['options']['responsive_menu_horizontal_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Horizontal'),
  '#states' => array(
    'visible' => array(
      ':input[name="settings_responsive_menu_default_style"]' => array('value' => 'horizontal'),
    ),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_horizontal_wrapper']['responsive_menu_horizontal'] = array(
  '#markup' => t('Horizontal menu style shows only the top level items, all sub-menus are hidden.'),
);

// vertical
$form['responsive_menus']['default']['options']['responsive_menu_vertical_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Vertical'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'vertical')),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_vertical_wrapper']['responsive_menu_vertical'] = array(
  '#markup' => t('Vertical menus will show sub-menus when expanded.'),
);

// Drop
$form['responsive_menus']['default']['options']['responsive_menu_dropmenu_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Drop menu'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'dropmenu')),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_dropmenu_wrapper']['responsive_menu_dropmenu'] = array(
  '#markup' => t('Drop menus will show sub-menus (on hover or touch) when expanded. Set items to expanded in the menu settings and the max levels to display in the block configuration.'),
);

// slidedown
$form['responsive_menus']['default']['options']['responsive_menu_slidedown_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Slide down'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'slidedown')),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_slidedown_wrapper']['responsive_menu_slidedown'] = array(
  '#markup' => t('Standard mobile/hamburger slide down menu.'),
);

// flipslide
$form['responsive_menus']['default']['options']['responsive_menu_flipslide_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Flip slide'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'flipslide')),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_flipslide_wrapper']['responsive_menu_flipslide'] = array(
  '#markup' => t('Flip the top level horizontal menu items to vertical and show sub-menus horizontally.'),
);

// Offcanvas options
$form['responsive_menus']['default']['options']['responsive_menu_offcanvas_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Offcanvas'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'offcanvas')),
  ),
);
$form['responsive_menus']['default']['options']['responsive_menu_offcanvas_wrapper']['responsive_menu_offcanvas'] = array(
  '#markup' => t('Off canvas menu position.'),
);
$form['responsive_menus']['default']['options']['responsive_menu_offcanvas_wrapper']['settings_responsive_menu_default_offcanvas_position'] = array(
  '#type' => 'radios',
  '#title' => t('Off Canvas position'),
  '#options' => array(
    'offcanvas-left' => t('Left'),
    'offcanvas-right' => t('Right'),
  ),
  '#default_value' => theme_get_setting('settings.responsive_menu_default_offcanvas_position', $theme),
  '#description' => t('Choose which side of the page the menu will slide in from.'),
);

// Tiles options
$form['responsive_menus']['default']['options']['responsive_menu_tiles_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Tiles'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'tiles')),
  ),
);
// Tiles count
$form['responsive_menus']['default']['options']['responsive_menu_tiles_wrapper']['settings_responsive_menu_default_tiles_count'] = array(
  '#type' => 'select',
  '#title' => t('Tile count'),
  '#options' => $tiles_count,
  '#default_value' => String::checkPlain(theme_get_setting('settings.responsive_menu_default_tiles_count', $theme)),
  '#description' => t('Set the number of tiles to display horizontally.'),
);
// Show tile sub-menus
$form['responsive_menus']['default']['options']['responsive_menu_tiles_wrapper']['settings_responsive_menu_default_tiles_submenus'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show tile sub-menus'),
  '#default_value' => theme_get_setting('settings.responsive_menu_default_tiles_submenus', $theme),
  '#description' => t('This setting will show the sub-menus for each top level item within the tile. Tiles will be equal-height to the height of the tallest tile.'),
);
*/









//
// Responsive Options
//
/*
$form['responsive_menus']['responsive']['options'] = array(
  '#type' => 'fieldset',
  '#title' => t('Responsive style options'),
  '#attributes' => array('class' => array('responsive-menu-options')),
  '#states' => array(
    'invisible' => array(
      ':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'none'),
    ),
  ),
);

// Horizontal
$form['responsive_menus']['responsive']['options']['responsive_menu_horizontal_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Horizontal'),
  '#states' => array(
    'visible' => array(
      ':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'horizontal'),
    ),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_horizontal_wrapper']['responsive_menu_horizontal'] = array(
  '#markup' => t('Horizontal menu style shows only the top level items, all sub-menus are hidden.'),
);

// vertical
$form['responsive_menus']['responsive']['options']['responsive_menu_vertical_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Vertical'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'vertical')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_vertical_wrapper']['responsive_menu_vertical'] = array(
  '#markup' => t('Vertical menus will show sub-menus when expanded.'),
);

// Drop
$form['responsive_menus']['responsive']['options']['responsive_menu_dropmenu_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Drop menu'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'dropmenu')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_dropmenu_wrapper']['responsive_menu_dropmenu'] = array(
  '#markup' => t('Drop menus will show sub-menus (on hover or touch) when expanded. Set items to expanded in the menu settings and the max levels to display in the block configuration.'),
);

// slidedown
$form['responsive_menus']['responsive']['options']['responsive_menu_slidedown_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Slide down'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'slidedown')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_slidedown_wrapper']['responsive_menu_slidedown'] = array(
  '#markup' => t('Standard mobile/hamburger slide down menu.'),
);

// flipslide
$form['responsive_menus']['responsive']['options']['responsive_menu_flipslide_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Flip slide'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'flipslide')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_flipslide_wrapper']['responsive_menu_flipslide'] = array(
  '#markup' => t('Flip the top level horizontal menu items to vertical and show sub-menus horizontally.'),
);

// Offcanvas options
$form['responsive_menus']['responsive']['options']['responsive_menu_offcanvas_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Offcanvas'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'offcanvas')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_offcanvas_wrapper']['responsive_menu_offcanvas'] = array(
  '#markup' => t('Off canvas menu position.'),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_offcanvas_wrapper']['settings_responsive_menu_responsive_offcanvas_position'] = array(
  '#type' => 'radios',
  '#title' => t('Off Canvas position'),
  '#options' => array(
    'offcanvas-left' => t('Left'),
    'offcanvas-right' => t('Right'),
  ),
  '#default_value' => theme_get_setting('settings.responsive_menu_responsive_offcanvas_position', $theme),
  '#description' => t('Choose which side of the page the menu will slide in from.'),
);

// Tiles options
$form['responsive_menus']['responsive']['options']['responsive_menu_tiles_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Tiles'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'tiles')),
  ),
);
// Tile count
$form['responsive_menus']['responsive']['options']['responsive_menu_tiles_wrapper']['settings_responsive_menu_responsive_tiles_count'] = array(
  '#type' => 'select',
  '#title' => t('Tile count'),
  '#options' => $tiles_count,
  '#default_value' => String::checkPlain(theme_get_setting('settings.responsive_menu_responsive_tiles_count', $theme)),
  '#description' => t('Set the number of tiles to display horizontally.'),
);
// Show tile sub-menus
$form['responsive_menus']['responsive']['options']['responsive_menu_tiles_wrapper']['settings_responsive_menu_responsive_tiles_submenus'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show tile sub-menus'),
  '#default_value' => theme_get_setting('settings.responsive_menu_responsive_tiles_submenus', $theme),
  '#description' => t('This setting will show the sub-menus for each top level item within the tile. Tiles will be equal-height to the height of the tallest tile.'),
);
*/
