<?php

// Development settings
$form['devel'] = array(
  '#type' => 'details',
  '#title' => t('Devel'),
  '#group' => 'at_settings',
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
  '#description' => t('Shows the window width (in pixels) in the bottom right corner of the screen. Works for any device or browser that supports JavaScript.'),
  '#default_value' => theme_get_setting('settings.show_window_size', $theme),
);

// LiveReload
$form['devel']['settings_livereload'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable LiveReload'),
  '#description' => t('See <a href="!lv" target="_blank">Livereload.com</a> for more information on setting up and using LiveReload. Also see the Help tab for more details.'),
  '#default_value' => theme_get_setting('settings.livereload', $theme),
);
$livereload_snippet = check_plain("document.write('<script src=\"http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1\"></' + 'script>'))");
$livereload_tags = check_plain('<script></script>');
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

// Paint regions and rows.
$form['devel']['settings_devel_layout'] = array(
  '#type' => 'checkbox',
  '#title' => t('Debug Layout'),
  '#default_value' => theme_get_setting('settings.devel_layout', $theme),
  '#description' => t('Paint regions and rows with color, remove all content and hide the toolbar - purely for use when developing layouts, works very well with LiveReload.'),
);

// Neutralize Toolbar.
$form['devel']['settings_nuke_toolbar'] = array(
  '#type' => 'checkbox',
  '#title' => t('Neutralize Toolbar'),
  '#default_value' => theme_get_setting('settings.nuke_toolbar', $theme),
  '#description' => t('Completely removes the toolbar in the front end by hiding it with CSS and overriding all it\'s CSS rules.'),
);
