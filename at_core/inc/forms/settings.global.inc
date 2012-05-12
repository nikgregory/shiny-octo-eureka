<?php

/**
 * @file
 * Generate form elments for the Global settings.
 *
 * TODO: review documentation and provide links to help pages.
 */

// Global Settings
$form['at-settings']['global-settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('CSS'),
  '#description' => t('<h3>CSS Global Settings</h3><p>These settings modify the way CSS is loaded and what is loaded. These are primarly set during development - do not change these unless you are actively developing or modifying your theme. If you need to globally unset core CSS or other CSS files use the <em>Unset CSS Files</em> extension.</p>'),
);

// Mode
$form['at-settings']['global-settings']['mode'] = array(
  '#type' => 'fieldset',
  '#title' => t('Production Mode'),
  '#description' => t('<h3>Production Mode</h3>'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);
$form['at-settings']['global-settings']['mode']['production_mode'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable Production Mode'),
  '#description' => t('Enabling Production mode reduces HTTP requests by aggregating the responsive stylesheets into one CSS file and uses embedded media queries. Enable this after you have finished writing your CSS styles. If you later change any styles in the responsive stylesheets you will need re-save the theme settings to re-write the aggregated CSS file.'),
  '#default_value' => at_get_setting('production_mode'),
);

// Set default layout
$form['at-settings']['global-settings']['default-layout'] = array(
  '#type' => 'fieldset',
  '#title' => t('Mobile first or Mobile last'),
  '#description' => t('<h3>Mobile first or Desktop first</h3>'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);
$form['at-settings']['global-settings']['default-layout']['global_default_layout'] = array(
  '#type' => 'radios',
  '#description' => t('Adaptivetheme supports both mobile first and desktop first design approaches. This setting fundementally alters what CSS is loaded and how it is loaded. At the most basic level if you select Mobile first the Smartphone portrait layout will load as the default, if you select Desktop first the Standard layout will load as the default. Many other changes take place regarding loading of CSS for Internet Explorer, Responsive Gpanels and Panels as well. Please review the documentation. Do not change this if you are not sure what this does.'),
  '#default_value' => at_get_setting('global_default_layout'),
  '#options' => array(
    'smartphone-portrait'  => t('Mobile first'),
    'standard-layout'      => t('Desktop first'),
  ),
);

// Cascading media queries
$form['at-settings']['global-settings']['cascading-mediaqueries'] = array(
  '#type' => 'fieldset',
  '#title' => t('Cascading Media Queries'),
  '#description' => t('<h3>Cascading Media Queries</h3>'),
  '#states' => array(
    'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
  ),
);
$form['at-settings']['global-settings']['cascading-mediaqueries']['enable_cascading_media_queries'] = array(
  '#type' => 'checkbox',
  '#title'  => t('Enable the responsive.cascade.css file'),
  '#description' => t('Use <a href="http://zomigi.com/blog/essential-considerations-for-crafting-quality-media-queries/#mq-overlap-stack" target="_blank">overlapped media queries</a>. These are are in a seperate file: <code>themeName/css/responsive.cascade.css</code>, you must set the media queries in this file manually!'),
  '#default_value' => at_get_setting('enable_cascading_media_queries'),

);
$form['at-settings']['global-settings']['cascading-mediaqueries']['cascade_media_query'] = array(
  '#type' => 'textfield',
  '#description' => t('Enter the smallest min-width in your <code>responsive.cascade.css</code> file, this is used when loading the file in Development mode. Once you set Production mode (see above) this file is aggregated along with the responsive layout stylesheets.'),
  '#default_value' => check_plain(at_get_setting('cascade_media_query')),
  '#field_prefix' => '@media',
  '#states' => array(
    'invisible' => array('input[name="production_mode"]' => array('checked' => TRUE)),
    'disabled' => array('input[name="enable_cascading_media_queries"]' => array('checked' => FALSE)),
  ),
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
