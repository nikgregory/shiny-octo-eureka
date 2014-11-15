<?php

use Drupal\Component\Utility\String;

use Drupal\at_core\Library\SystemLibraries;
use Drupal\at_core\Library\ThemeLibraries;

// Development settings
$form['devel'] = array(
  '#type' => 'details',
  '#title' => t('Developer tools'),
  '#group' => 'extension_settings',
  '#description' => t('See the Help tab section "Developer Tools".'),
);

// Show page suggestions.
$form['devel']['settings_show_page_suggestions'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show Page Suggestions'),
  '#description' => t('Show all template suggestions for the current page. Appears in the messages area.'),
  '#default_value' => theme_get_setting('settings.show_page_suggestions', $theme),
);

// Window size
$form['devel']['settings_show_window_size'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show Window Size'),
  '#description' => t('Shows the window width (in pixels and ems) in the bottom right corner of the screen. Works for any device or browser that supports JavaScript.'),
  '#default_value' => theme_get_setting('settings.show_window_size', $theme),
);

// Show grid
$form['devel']['settings_show_grid'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show the grid'),
  '#default_value' => theme_get_setting('settings.show_grid', $theme),
  '#description' => t('Show the grid. Your theme must include a CSS file named "show-grid.css" in the /css folder which should load the grid CSS or image file. All UIKit based themes have this file and will show a toggle icon in the bottom left corner of the screen - hover to icon to show the grid as an overlay.'),
);

// Debug layout
$form['devel']['settings_devel_layout'] = array(
  '#type' => 'checkbox',
  '#title' => t('Debug Layout <small>(hides all content, colorizes all rows and regions)</small>'),
  '#default_value' => theme_get_setting('settings.devel_layout', $theme),
  '#description' => t('An agressive option that removes all content, hides the toolbar, and colorizes page rows and regions. Works with LiveReload.'),
);

// Colorize regions.
$form['devel']['settings_devel_color_regions'] = array(
  '#type' => 'checkbox',
  '#title' => t('Colorize regions'),
  '#default_value' => theme_get_setting('settings.devel_color_regions', $theme),
  '#description' => t('Add background color to regions. Also adds a margin-bottom for visual seperation.'),
  '#states' => array(
    'disabled' => array('input[name="settings_devel_layout"]' => array('checked' => TRUE)),
  ),
);

// Neutralize Toolbar.
$form['devel']['settings_nuke_toolbar'] = array(
  '#type' => 'checkbox',
  '#title' => t('Neutralize Toolbar'),
  '#default_value' => theme_get_setting('settings.nuke_toolbar', $theme),
  '#description' => t('Completely removes the toolbar in the front end by hiding it with CSS and overriding all it\'s CSS rules.'),
);

// LiveReload
$form['devel']['settings_livereload'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable LiveReload'),
  '#description' => t('See <a href="!lv" target="_blank">Livereload.com</a> for more information on setting up and using LiveReload. Also see the Help tab for more details.'),
  '#default_value' => theme_get_setting('settings.livereload', $theme),
);
$livereload_snippet = String::checkPlain("document.write('<script src=\"http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1\"></' + 'script>'))");
$livereload_tags = String::checkPlain('<script></script>');
$form['devel']['settings_livereload_snippet'] = array(
  '#type' => 'textarea',
  '#rows' => 2,
  '#title' => t('LiveReload Snippet'),
  '#description' => t('Paste in the snippet from the LiveReload app. Remove the outer <code>!tags</code> tags, so it\'s something like this:<br /><code>!snippet</code>', array('!snippet' => $livereload_snippet, '!tags' => $livereload_tags)),
  '#default_value' => theme_get_setting('settings.livereload_snippet', $theme),
  '#states' => array(
    'visible' => array('input[name="settings_livereload"]' => array('checked' => TRUE)),
  ),
);


// Libraries Form
//----------------------------------------------------------------------
$drupal_libs = new SystemLibraries();
$core_libraries = $drupal_libs->getDrupalCoreSystemLibraries();
$theme_libs = new ThemeLibraries($theme);
$theme_libraries = $theme_libs->getThemeTrailLibraries();

$form['devel']['libraries'] = array(
  '#type' => 'details',
  '#title' => t('Libraries'),
  //'#group' => 'at_settings',
);

// Theme Trail Libraries
$form['devel']['libraries']['theme_libraries'] = array(
  '#type' => 'details',
  '#title' => t('Theme Libraries'),
  '#description' => t('Dependancies are loaded automatically whether checked or not.'),
  '#collapsed' => FALSE,
  '#collapsible' => FALSE,
);

foreach ($theme_libraries as $theme_key => $theme_library) {
  unset($theme_library_key);
  $form['devel']['libraries']['theme_libraries'][$theme_key] = array(
    '#type' => 'details',
    '#title' => t($theme_key),
    '#collapsed' => FALSE,
    '#collapsible' => FALSE,
  );
  foreach ($theme_library as $theme_library_key => $theme_library_value) {
    $unset_array = array('at.window_size', 'at.addListener', 'at.slimbox2');
    if (in_array($theme_library_key, $unset_array)) {
      unset($theme_library_key);
    }
    if (isset($theme_library_key)) {
      $theme_library_setting = str_replace('.', '_', strtolower($theme_library_key));
      $version = isset($theme_library_value['version']) ? $theme_library_value['version'] : '';
      $website = isset($theme_library_value['remote']) ? $theme_library_value['remote'] : '';

      $form['devel']['libraries']['theme_libraries'][$theme_key]["settings_theme_library_$theme_library_setting"] = array(
        '#type' => 'checkbox',
        '#title' => t('!title', array('!title' => $theme_library_key)),
        '#default_value' => theme_get_setting("settings.theme_library_$theme_library_setting", $theme),
      );
      $form['devel']['libraries']['theme_libraries'][$theme_key]["settings_theme_library_info_$theme_library_setting"] = array(
        '#type' => 'container',
        '#markup' => t('<small>Version: !version <a href="!website" target="_blank">!website</a></small>', array('!version' => $version, '!website' => $website)),
        '#attributes' => array(
          'classes' => array('core-library-info'),
        ),
        '#states' => array(
          'visible' => array(
             "input[name=\"settings_theme_library_$theme_library_setting\"]" => array('checked' => TRUE),
          ),
        ),
      );
    }
  }
}


// Drupal Core Libraries
$form['devel']['libraries']['drupal_core'] = array(
  '#type' => 'details',
  '#title' => t('Drupal Core Libraries'),
  '#description' => t('Dependancies are loaded automatically whether checked or not. Please see the Help section on Drupal core libraries for more advice on dependancies.'),
  '#collapsed' => FALSE,
  '#collapsible' => FALSE,
);

foreach ($core_libraries as $key => $value) {
  $core_library_setting = str_replace('.', '_', strtolower($key));
  $version = isset($value['version']) ? $value['version'] : '';
  $website = isset($value['remote']) ? $value['remote'] : '';

  $form['devel']['libraries']['drupal_core']["settings_drupal_core_$core_library_setting"] = array(
    '#type' => 'checkbox',
    '#title' => t('!title', array('!title' => $key)),
    '#default_value' => theme_get_setting("settings.drupal_core_$core_library_setting", $theme),
  );
  $form['devel']['libraries']['drupal_core']["settings_drupal_core_info_$core_library_setting"] = array(
    '#type' => 'container',
    '#markup' => t('<small>Version: !version <a href="!website" target="_blank">!website</a></small>', array('!version' => $version, '!website' => $website)),
    '#attributes' => array(
      'classes' => array('core-library-info'),
    ),
    '#states' => array(
      'visible' => array(
         "input[name=\"settings_drupal_core_$core_library_setting\"]" => array('checked' => TRUE),
      ),
    ),
  );
}


// Support legacy browsers
//----------------------------------------------------------------------
$form['devel']['legacy_browsers'] = array(
  '#type' => 'details',
  '#title' => t('Legacy Browsers'),
  //'#group' => 'at_settings',
  '#description' => t('Support IE8 (and maybe below - no guarantees given). Without this setting IE8 will display in one column and some styles may fail to be applied. By checking this setting two polyfills will be loaded for IE8 and below: <b>Respond.js</b> - mainly to support the Layout and Responsive Menu options, and <b>Selectivrz</b> - mainly for the Layout and UIKit styles which use many CSS3 selectors. Additionally the YUI3 library will load for IE8 only - this is for Selectivr because Drupal core uses jQuery 2, which does not support IE8. In all, this is a bit of a hack and good luck.'),
);

// Show page suggestions.
$form['devel']['legacy_browsers']['settings_legacy_browser_polyfills'] = array(
  '#type' => 'checkbox',
  '#title' => t('Loads polyfills to support IE8'),
  '#default_value' => theme_get_setting('settings.legacy_browser_polyfills', $theme),
);



