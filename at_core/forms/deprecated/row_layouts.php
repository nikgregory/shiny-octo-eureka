<?php

use Drupal\at_core\Layout\LayoutSettings;
use Drupal\at_core\Theme\ThemeSettingsInfo;



// set whether this is default page template or suggestion - the whole thing saves as either (not per row, the whole thing), so we just end up with this big array
// for this particular page suggestion

// glob css files from the supported layout

// get the supported layout from theme.info.yml, so only ONE layout can be valid at a time

// get the rows and breakpoints, rows from the layout plugin yml, breakpoints from the themes breakpoints yml

// build a container or fieldset for each row, with breakpoints

// foreach breakpoint allow to set a layout (a css file). initially use select lists, if the row as 4 regions then only show the four-region layout options etc
// use the not-set option for no layout etc, aka for mobile probably has no layout.

// array is going to look something like this:
/*
  layout:
    - plugin: site-builder
    - suggestions:
      - page
        - rows:
          - footer:
            - breakpoints:
              - mobile: no-layout
              - tablet: three-12-6-6
              - widescreen: three-3-6-3
      - page-front
        - rows:
          - footer:
            - breakpoints:
              - mobile: no-layout
              - tablet: three-12-6-6
              - widescreen: three-3-6-3
*/

// on settings save we always save a new page.html.twig file, so we are 100% certain of our rows and regions, always save a backup also
// strickly speaking suggestion template generation is optional, but we could do this without too much problems at all.
// we also should rewrite the info file regions array, just like we do now.

// On page load we load the configuation array (from config or theme settings) into preprocess and:

//  - add each breakpoint layout into drupalSettings on pageload, per suggestion, so if this is page-front, only page-front settings get added to drupalSettings
//  - load all CSS files unconditionally for all suggestions, no duplicates
//  - get drupalSettings in a script, use espire to apply the layout class to the row per breakpoint (match) and remove (no match) etc, bingo, we are home.

// the BIG unknown is how slow the JS is going to be, its quite a large array and espire is fast, but this is the make or break of the system - how fast the JS is.

// also there is not vialbe other way than using JavaScript to do this idea, every other way would require generating CSS files, maybe with accestic?


$form['row_layouts'] = array(
  '#type' => 'details',
  '#title' => t('Row Layouts'),
  '#open'=> TRUE,
  '#attributes' => array('class' => array('clearfix')),
  '#weight' => -200,
);

// Rows
$provider = theme_get_setting('settings.template_suggestion_provider_page', $theme);
$plugin = theme_get_setting('settings.template_suggestion_plugin_page', $theme);

$layout_config_classitis = new LayoutSettings($provider);
$settings_data_classitis = $layout_config_classitis->settingsPrepareData();

foreach ($settings_data_classitis as $settings_data_key =>  $settings_data_value ) {
  if ($settings_data_key == $plugin) {
    foreach ($settings_data_value['rows'] as $row_key => $row_value) {
      $row_data[$row_key] = $row_value;
    }
  }
}

$form['row_layouts']['rows'] = array(
  '#type' => 'fielset',
  '#title' => t('Rows'),
);
foreach ($row_data as $row_data_key => $row_data_value) {
  $form['row_layouts']['rows']['settings_page_row_' . $row_data_key] = array(
    '#type' => 'textfield',
    '#title' => t('page-row__' . $row_data_key),
    '#default_value' => theme_get_setting('settings.page_row_' . $row_data_key, $theme),
  );
}