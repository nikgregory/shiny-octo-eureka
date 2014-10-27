<?php

use Drupal\Component\Utility\String;

// menu style options
$responsive_menu_options = array(
  'none' => t('-- none --'),
  'horizontal' => t('Horizontal'),
  'vertical'   => t('Vertical'),
  'dropmenu'   => t('Drop menu'),
  'slidedown'  => t('Slide down'),
  'flipslide'  => t('Flip slide'),
  'offcanvas'  => t('Off canvas'),
  'tiles'      => t('Tiles'),
);

// breakpoint options
foreach ($theme_breakpoints as $bpid => $bpvalue) {
  $breakpoint_options[$bpvalue->getMediaQuery()] = $bpvalue->getLabel() . ' - ' . $bpvalue->getMediaQuery();
}

$tiles_count = array(
  'two' => 2,
  'three' => 3,
  'four' => 4,
);

// Development settings
$form['responsive_menus'] = array(
  '#type' => 'details',
  '#title' => t('Menus'),
  '#group' => 'at_settings',
  '#description' => t('<h3>Responsive Menu Blocks</h3><p>Select options for each style and the region you want to use for responsive menu blocks.</p>'),
);

// Regions
$form['responsive_menus']['settings_responsive_menu_region'] = array(
  '#type' => 'select',
  '#title' => t('Responsive menu region'),
  '#options' => $theme_regions,
  '#default_value' => theme_get_setting('settings.responsive_menu_region', $theme),
  '#description' => t('Menu blocks placed in this region will inherit the styles as configured below.'),
);


//
// DEFAULT
//
$form['responsive_menus']['default'] = array(
  '#type' => 'fieldset',
  '#title' => t('Default Style'),
  '#attributes' => array('class' => array('clearfix')),
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
  '#description' => t('Set the default style for your navbar menu.'),
);

$form['responsive_menus']['default']['styles']['settings_responsive_menu_default_breakpoint'] = array(
  '#type' => 'select',
  '#title' => t('Default Style Breakpoint'),
  '#options' => $breakpoint_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_default_breakpoint', $theme),
  '#description' => t('Set the breakpoint the default style will show. Do not allow this to bleed or cascade into the Responsive breakpoint.'),
  '#states' => array(
    'invisible' => array(
      ':input[name="settings_responsive_menu_default_style"]' => array('value' => 'none'),
    ),
  ),
);

//
// Default Options
//
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
  '#markup' => t('Drop menus will show sub-menus (on hover or touch) when expanded. Set items to expanded in the menu settings.'),
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
  '#markup' => t('Slide down menus show expanded sub-menus.'),
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
  '#markup' => t('Flip slide shows top level items when collapsed. When expanded the top level items flip to one side (vertical) and the sub-menus appear horizontally.'),
);



// Offcanvas options
$form['responsive_menus']['default']['options']['responsive_menu_offcanvas_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Offcanvas'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_default_style"]' => array('value' => 'offcanvas')),
  ),
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

//
// RESPONISVE
//
$form['responsive_menus']['responsive'] = array(
  '#type' => 'fieldset',
  '#title' => t('Responisve Style'),
  '#attributes' => array('class' => array('clearfix')),
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
  '#description' => t('Set the responsive style, this is how the menu will look and behave in the selected breakpoint.'),
);

$form['responsive_menus']['responsive']['styles']['settings_responsive_menu_responsive_breakpoint'] = array(
  '#type' => 'select',
  '#title' => t('Responsive Style Breakpoint'),
  '#options' => $breakpoint_options,
  '#default_value' => theme_get_setting('settings.responsive_menu_responsive_breakpoint', $theme),
  '#description' => t('Set the breakpoint the responsive style will show. Do not allow this to bleed or cascade into the Default breakpoint.'),
  '#states' => array(
    'invisible' => array(
      ':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'none'),
    ),
  ),
);

//
// Responsive Options
//
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
  '#markup' => t('Drop menus will show sub-menus (on hover or touch) when expanded. Set items to expanded in the menu settings.'),
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
  '#markup' => t('Slide down menus show expanded sub-menus.'),
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
  '#markup' => t('Flip slide shows top level items when collapsed. When expanded the top level items flip to one side (vertical) and the sub-menus appear horizontally.'),
);

// Offcanvas options
$form['responsive_menus']['responsive']['options']['responsive_menu_offcanvas_wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Offcanvas'),
  '#states' => array(
    'visible' => array(':input[name="settings_responsive_menu_responsive_style"]' => array('value' => 'offcanvas')),
  ),
);
$form['responsive_menus']['responsive']['options']['responsive_menu_offcanvas_wrapper']['settings_responsive_menu_responsive_offcanvas_position'] = array(
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
















