<?php

/**
 * @file
 * Generate form elments for the Panel and Gpanel Reponsive Layout settings.
 *
 * This gets its data via responsive_panels_data_structure() in config.inc,
 * which pulls its data from each Panels layout plugin.
 */

// Supported devices
$devices = array(
  'tablet_landscape',
  'tablet_portrait',
  'smartphone_landscape',
);

// Get the configuration array for panel layouts
$rpl = responsive_panels_data_structure();

// Check if panels exists
$panels = module_exists('panels') ? TRUE : FALSE;

// Set variables here for active Gpanels, we need to test on this later
$active_gpanels = array();
$active_gpanels_arrays = array();

// Get the active themes info array
$info_array = at_get_info($theme_name);

// Build a keyed array of the regions, we need to reset the keys for array_intersect()
foreach ($info_array['regions'] as $key => $value) {
  $theme_regions[] = $key;
}

// Get the standard Gpanel regions list
$gpanel_regions = gpanel_regions();

// Figure out which gpanels are actually being used in the theme
$active_gpanels = array();
foreach ($gpanel_regions as $gpanel => $gpanel_array) {

    if (array_intersect($theme_regions, $gpanel_array['regions'])) {
      $active_gpanels[] = $gpanel;
      $active_gpanels_arrays[] = $gpanel_array;
  }
}
array_unique($active_gpanels);

// Get all standard Plugin layout names
foreach ($rpl as $rpl_key => $values) {
  foreach ($values as $value) {
    foreach ($value as $k => $v) {
      $active_rpl[] = $v['theme'];
      $active_types[] = $v['type'];
    }
  }
}

// List of inactive layouts
$inactive_gpanels = array_diff($active_rpl, $active_gpanels);

// Unset all the inactive layouts, but only if Panels module is disabled!
if ($panels == FALSE) {
  foreach ($inactive_gpanels as $inactive_gpanel) {
    foreach ($rpl as $rpl_key => $values) {
      foreach ($values as $key => $value) {
        unset($rpl[$rpl_key][$key][$inactive_gpanel]);
        if (empty($rpl[$rpl_key][$key])) {
          unset($rpl[$rpl_key][$key]);
        }
      }
    }
  }
}

// This is will not be sustaintable, what I need is a function that sorts the
// layouts properly, alphabetical is no good, it needs to be in a very precise
// order to make sense in the UI.
if (isset($rpl['one'])) {
  unset($rpl['one']);
}
if (isset($rpl['six'])) {
  $six = $rpl['six']; unset($rpl['six']); $rpl['six'] = $six;
}
if (isset($rpl['inset'])) {
  $inset = $rpl['inset']; unset($rpl['inset']); $rpl['inset'] = $inset;
}

// We need the admin_css files for each plugin
$panels_admin_css_files = responsive_plugins_admin_css($theme_name, $plugin_type = 'panels');
foreach ($panels_admin_css_files as $filepath) {
  if (file_exists($filepath)) {
    $panels_css_files['css'][] = array('type' => 'file', 'data' => $filepath, 'weight' => 100);
  }
}

// Set conditional labels, lists and messages...
// Is Panels module enabled or not?
// Are there any Gpanels active in this theme?

// Make a list of all active Gpanel titles, if any
if (!empty($active_gpanels)) {
  foreach ($active_gpanels_arrays as $active_gpanel) {
    $gpanels_titles[] = $active_gpanel['title'];
  }
  $used_gpanels = theme('item_list', array('items' => $gpanels_titles));
}

// Default title and message assumes nothing is active or enabled
$panels_title = t('Panels &amp; Gpanels');
$panels_message = t('Adaptivetheme can support responsive Panels and Gpanel layouts. To use this capability you can either use the <a href="!panels_link" target="_blank">Panels module</a> or Adaptivethemes native <a href="!gpanels_link" target="_blank">Gpanels</a> layouts.</p><p>New settings will appear if you install the Panels module or use Gpanels in your theme - if you don\'t want to use either, no problem, its all optional.</p>', array('!panels_link' => 'http://drupal.org/project/panels', '!gpanels_link' => 'http://adaptivethemes.com/documentation/using-gpanels'));

// No panels, but we have some Gpanels
if ($panels == FALSE && !empty($active_gpanels)) {
  $panels_title = t('Gpanels');
  $panels_message = t('<h3>Responsive Gpanels</h3><p>These settings apply to your themes <a href="!gpanels_link" target="_blank">Gpanel</a> layouts. If you add more Gpanels to your theme new settings will show up here. If you enable the <a href="!panels_link" target="_blank">Panels module</a> these settings will apply to Panels layouts as well.</p><p>Your theme currently uses the following Gpanel layouts - check your block settings to see where these are used on the page:</p>' . filter_xss_admin($used_gpanels) . '</p><strong>Usage:</strong> select layout options for each mobile device orientation.</p>', array('!gpanels_link' => 'http://adaptivethemes.com/documentation/using-gpanels', '!panels_link' => 'http://drupal.org/project/panels',));
}

// Panels, but no Gpanels
elseif ($panels == TRUE && empty($active_gpanels)) {
  $panels_title = t('Panels');
  $panels_message = t('<h3>Responsive Panels</h3><p>These settings apply to your sites <a href="!panels_link" target="_blank">Panels layouts</a> (your site has the Panels module enabled).</p><p><strong>Usage:</strong> select layout options for each mobile device orientation. Panels layouts can also be used with <a href="!ds_link" target="_blank">Display Suite</a> for content type displays (and more), check it out, it rocks!</p>', array('!panels_link' => 'http://drupal.org/project/panels', '!ds_link' => 'http://drupal.org/project/ds'));
}

// Both... this can happen, such as using Panels and/or Display Suite with Pixture Reloaded or similar
elseif ($panels == TRUE && !empty($active_gpanels)) {
  $panels_message = t('<h3>Responsive Panels and Gpanels</h3><p>These settings apply to your themes <a href="!panels_link" target="_blank">Panels</a> and <a href="!gpanels_link" target="_blank">Gpanel</a> layouts (you currently have the Panels module enabled and your theme uses Gpanels). All layout options are shown because Panels or <a href="!ds_link" target="_blank">Display Suite</a> can use any of these layouts at any time. Your theme is currently uses the following Gpanel layouts - check your block settings to see where these are used on the page:</p>' . filter_xss_admin($used_gpanels) . '<p><strong>Usage:</strong> select layout options for each mobile device orientation.</p>', array('!gpanels_link' => 'http://adaptivethemes.com/documentation/using-gpanels', '!panels_link' => 'http://drupal.org/project/panels', '!ds_link' => 'http://drupal.org/project/ds'));
}

// Gpanels and Panels top level wrapper
$form['at-settings']['panels'] = array(
  '#type' => 'fieldset',
  '#title' => $panels_title,
  '#description' => $panels_message,
  '#attached' => $panels_css_files,
);

// Disabled message
$panels_disable_message = t('<p>The responsive layout is disabled, only the default layout for each Gpanel and Panel layout will be used.</p><p>To re-enable uncheck the "Disable responsive layout and styles" checkbox under the "Settings" tab.</p>');
if(at_get_setting('disable_responsive_styles') === 1) {
  $form['at-settings']['panels']['disabled'] = array(
    '#markup' => '<div class="messages warning">' . $panels_disable_message . '</div>',
  );
}

$form['at-settings']['panels']['panels-wrapper'] = array(
  '#type' => 'fieldset',
  '#attributes' => array('class' => array('panel-option-lists')),
  '#states' => array(
    'disabled' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);

// Do not print any of the layout options if Panels is not enabled or there are
// no active Gpanels used in the theme
if ($panels == TRUE || !empty($active_gpanels)) {
  // Build the forms
  foreach ($devices as $device) {
    $title = str_replace('_', ' ', drupal_ucfirst($device));
    $form['at-settings']['panels']['panels-wrapper'][$device] = array(
      '#type' => 'fieldset',
      '#title' => t($title),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    if (isset($rpl)) {
      foreach ($rpl as $group => $layouts) {
        if (!empty($layouts)) {
          $form['at-settings']['panels']['panels-wrapper'][$device][$group] = array(
            '#type' => 'fieldset',
            '#title' => t($group),
            '#collapsible' => TRUE,
            '#collapsed' => TRUE,
          );
          foreach ($layouts as $layout) {
            foreach ($layout as $key => $value) {
              if (isset($value['theme'])) {
                $form['at-settings']['panels']['panels-wrapper'][$device][$group][$device . '_' . $value['theme']] = array(
                  '#type' => 'radios',
                  '#title' => t($value['title']),
                  '#default_value' => at_get_setting($device . '_' . $value['theme']),
                  '#options' => $value['options'],
                );
              }
            }
          }
        }
      }
    }
  }
}
