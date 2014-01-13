<?php

// Help
$form['subtheme_help'] = array(
  '#type' => 'details',
  '#title' => t('Help'),
  '#group' => 'settings',
  //'#description' => t('Help docs to get you on the right track.'),
);

// Layouts
$form['subtheme_help']['using_layouts'] = array(
  '#type' => 'details',
  '#title' => t('Using Layouts'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-using.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Building Layouts
$form['subtheme_help']['building_layouts'] = array(
  '#type' => 'details',
  '#title' => t('Building Layouts'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['defining_layouts'] = array(
  '#type' => 'details',
  '#title' => t('Step by Step Guide'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-build-first.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['css_files'] = array(
  '#type' => 'details',
  '#title' => t('CSS Files'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-css-files.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['css_selectors'] = array(
  '#type' => 'details',
  '#title' => t('CSS Selectors'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-css-selectors.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['css_selector_cheatsheet'] = array(
  '#type' => 'details',
  '#title' => t('CSS Selector Cheatsheet'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-dynamic-class-combinations.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['media_quries'] = array(
  '#type' => 'details',
  '#title' => t('Media Queries'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-mediaqueries.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['elf'] = array(
  '#type' => 'details',
  '#title' => t('Easy Layout Framework'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-elf.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);
$form['subtheme_help']['building_layouts']['sass'] = array(
  '#type' => 'details',
  '#title' => t('Getting Started with SASS'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/layout-sass.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Libraries
$form['subtheme_help']['libraries'] = array(
  '#type' => 'details',
  '#title' => t('Libraries'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/libraries.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Development Settings
$form['subtheme_help']['devel_settings'] = array(
  '#type' => 'details',
  '#title' => t('Devel Settings'),
  '#markup' => file_get_contents(filter_xss_admin($at_core_path . '/docs/help_subtheme/devel-settings.html')),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);










