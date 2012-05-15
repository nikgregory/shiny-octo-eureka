<?php

/**
 * @file
 * Generate form elments for the Global settings.
 *
 * TODO: review documentation and provide links to help pages.
 */

// Set variable for mode, we want to show a message based on this
$mode = variable_get('preprocess_css', '') == 1 ? TRUE : FALSE;
$performance_settings_path = base_path() . 'admin/config/development/performance';

// Global Settings
$form['at-settings']['global-settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('CSS'),
  '#description' => t('<h3>CSS Global Settings</h3><p>These settings are primarly set during development - do not change these unless you are actively developing or modifying your theme. If you need to globally unset core CSS or other CSS files use the <em>Unset CSS Files</em> extension.</p>'),
);

// Set message depending on the mode
if ($mode == TRUE) {
  $mode_message = t('<p><a href="!settings_path" target="_blank">CSS aggregation</a> is ON. Changes to your themes responsive CSS files will not show until you <strong>re-save the theme settings</strong> (click "Save configuration" at the bottom of this form).</p><div class="description"><p>This is required to re-write the production version of the responsive styles and is a performance optimization in Adaptivetheme, which saves your site at least 4 HTTP requests.</p></div>', array('!settings_path' => $performance_settings_path));
}
elseif ($mode == FALSE) {
  $mode_message = t('<p><a href="!settings_path" target="_blank">CSS aggregation</a> is OFF. Changes to your themes responsive CSS files will show automatically.</p><p><div class="description"><p>Note: when CSS aggregation is ON you need to re-save the theme settings if you make any changes to your themes responsive stylesheets. This is required to re-write the production version of the responsive styles and is a performance optimization in Adaptivetheme, which saves your site at least 4 HTTP requests.</p></div>', array('!settings_path' => $performance_settings_path));
}

// Mode
$form['at-settings']['global-settings']['mode'] = array(
  '#type' => 'fieldset',
  '#title' => t('CSS Mode'),
  '#description' => t('<h3>Mode</h3>') . $mode_message,
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);

// Custom media queries
$form['at-settings']['global-settings']['custom-mediaqueries'] = array(
  '#type' => 'fieldset',
  '#title' => t('Custom Media Queries'),
  '#description' => t('<h3>Custom Media Queries</h3>'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);
$form['at-settings']['global-settings']['custom-mediaqueries']['enable_custom_media_queries'] = array(
  '#type' => 'checkbox',
  '#title'  => t('Enable the responsive.custom.css file'),
  '#description' => t('Use this file if you need to use custom media queries, such as <a href="!zomigi" target="_blank">overlapped media queries</a>. This file is located here: <code>themeName/css/responsive.custom.css</code>. Adaptivetheme will load this file automagically when this setting enabled.', array('!zomigi' => 'http://zomigi.com/blog/essential-considerations-for-crafting-quality-media-queries/#mq-overlap-stack')),
  '#default_value' => at_get_setting('enable_cascading_media_queries'),
);

// Set default layout
$form['at-settings']['global-settings']['default-layout'] = array(
  '#type' => 'fieldset',
  '#title' => t('Mobile first or Mobile last'),
  '#description' => t('<h3>Mobile first or Desktop first</h3>'),
  '#states' => array(
    'invisible' => array(
      array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
      array('input[name="global_default_layout_disable"]' => array('value' => 1)),
    ),
  ),
);
$form['at-settings']['global-settings']['default-layout']['global_default_layout'] = array(
  '#type' => 'radios',
  '#description' => t('<p>Adaptivetheme supports both mobile first and desktop first design approaches. Please review the <a href="!docs_link" target="_blank">online documentation</a>.</p><p>This setting fundementally alters what CSS is loaded and how it is loaded. At the most basic level if you select Mobile first the Smartphone portrait layout will load as the default, if you select Desktop first the Standard layout will load as the default. Many other changes take place regarding loading of CSS for Internet Explorer, Responsive Gpanels and Panels as well.</p> ', array('!docs_link' => 'http://adaptivethemes.com/documentation/mobile-first-design-with-adaptivetheme')),
  '#default_value' => at_get_setting('global_default_layout'),
  '#options' => array(
    'smartphone-portrait' => t('Mobile first'),
    'standard-layout' => t('Desktop first'),
  ),
);
// Hidden setting allows us to hide the method setting from the info file
$form['at-settings']['global-settings']['default-layout']['global_default_layout_disable'] = array(
  '#type' => 'hidden',
  '#value' => at_get_setting('global_default_layout_disable'),
);

// Disable responsive layout
$form['at-settings']['global-settings']['disable-rs'] = array(
  '#type' => 'fieldset',
  '#title' => t('Disable Responsive CSS'),
  '#description' => t('<h3>Disable Responsive CSS</h3>'),
);
$form['at-settings']['global-settings']['disable-rs']['disable_responsive_styles'] = array(
  '#type' => 'checkbox',
  '#title' => t('Disable responsive layout and styles'),
  '#description' => t('By checking this setting the site will use only the Standard layout and the global styles. You can turn this back on at any time.'),
  '#default_value' => at_get_setting('disable_responsive_styles'),
);
