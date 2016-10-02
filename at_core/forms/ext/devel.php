<?php

/**
 * Generate form elements for the Devel settings.
 */

use Drupal\Component\Utility\Html;

// Development settings
$form['devel'] = [
  '#type' => 'details',
  '#title' => t('Developer tools'),
  '#group' => 'extension_settings',
  '#description' => t('Tools to aid theme development.'),
];

// Show page suggestions.
$form['devel']['settings_show_page_suggestions'] = [
  '#type' => 'checkbox',
  '#title' => t('Show Page Suggestions'),
  '#description' => t('Show all template suggestions for the current page. Appears in the messages area.'),
  '#default_value' => theme_get_setting('settings.show_page_suggestions', $theme),
];

// Window size
$form['devel']['settings_show_window_size'] = [
  '#type' => 'checkbox',
  '#title' => t('Show Window Size'),
  '#description' => t('Shows the window width (in pixels and ems) in the bottom right corner of the screen. Works for any device or browser that supports JavaScript.'),
  '#default_value' => theme_get_setting('settings.show_window_size', $theme),
];

// Enable LiveReload
$form['devel']['live_reload'] = [
  '#type' => 'fieldset',
  '#title' => t('Live Reload'),
];

$form['devel']['live_reload']['settings_enable_live_reload'] = [
  '#type' => 'checkbox',
  '#title' => t('LiveReload'),
  '#description' => t('Add the LiveReload script the page (this will trigger your LiveReload browser extension to connect).'),
  '#default_value' => theme_get_setting('settings.enable_live_reload', $theme),
];

$form['devel']['live_reload']['settings_live_reload_port'] = [
  '#type' => 'textfield',
  '#size' => 5,
  '#maxlength' => 5,
  '#title' => t('LiveReload Port'),
  '#description' => t('Change the port number, this should match the port in your watch tasks in Gruntfile.js. By default this is set to 1337'),
  '#default_value' => Html::escape(theme_get_setting('settings.live_reload_port', $theme)),
  '#states' => [
    'visible' => ['input[name="settings_enable_live_reload"]' => ['checked' => TRUE]],
  ],
];

// Show grid
//$form['devel']['settings_show_grid'] = array(
//  '#type' => 'checkbox',
//  '#title' => t('Show the grid'),
//  '#default_value' => theme_get_setting('settings.show_grid', $theme),
//  '#description' => t('Show the grid. Your theme must include a CSS file named "show-grid.css" in the /css folder which should load the grid CSS or image file. All UIKit based themes have this file and will show a toggle icon in the bottom left corner of the screen - hover the icon to show the grid as an overlay. The CSS grid overlay is an approximation. Browsers have extra trouble with sub-pixel rounding on background images. <b>This is meant for rough debugging, not for pixel-perfect measurements</b>.'),
//);

// Debug layout
//$form['devel']['settings_devel_layout'] = array(
//  '#type' => 'checkbox',
//  '#title' => t('Debug Layout <small>(hides all content, colorizes all rows and regions)</small>'),
//  '#default_value' => theme_get_setting('settings.devel_layout', $theme),
//  '#description' => t('An aggressive option that removes all content, hides the toolbar, and colorizes page rows and regions. Works with LiveReload.'),
//);

// Colorize regions.
//$form['devel']['settings_devel_color_regions'] = array(
//  '#type' => 'checkbox',
//  '#title' => t('Colorize regions'),
//  '#default_value' => theme_get_setting('settings.devel_color_regions', $theme),
//  '#description' => t('Add background color to regions. Also adds a margin-bottom for visual separation.'),
//  '#states' => array(
//    //'disabled' => array('input[name="settings_devel_layout"]' => array('checked' => TRUE)),
//  ),
//);

// Neutralize Toolbar.
$form['devel']['settings_nuke_toolbar'] = [
  '#type' => 'checkbox',
  '#title' => t('Neutralize Toolbar'),
  '#default_value' => theme_get_setting('settings.nuke_toolbar', $theme),
  '#description' => t('Completely removes the toolbar in the front end by hiding it with CSS and overriding all it\'s CSS rules.'),
];
