<?php 
  // Global Settings
  $form['at-layout']['global-settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Global Settings'),
  );
  // Mode
  $form['at-layout']['global-settings']['mode'] = array(
    '#type' => 'fieldset',
    '#title' => t('Production Mode'),
    '#description' => t('<h3>Production Mode</h3><p>Enabling Production mode reduces HTTP requests by aggregating the responsive stylesheets and disables <code>system_rebuild_theme_data()</code> and <code>drupal_theme_rebuild()</code> being called on every page request. TODO - insert link to docs.</p>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['mode']['production_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Production Mode'),
    '#default_value' => theme_get_setting('production_mode'),
  );
  // set default layout
  $form['at-layout']['global-settings']['default-layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mobile first or Mobile last'),
    '#description' => t('<h3>Mobile first or Desktop first</h3>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['default-layout']['global_default_layout'] = array(
    '#type' => 'radios',
    '#description' => t('Adaptivetheme supports both mobile first and desktop first design approaches. Please review our documentation (TODO - insert link).'),
    '#default_value' => theme_get_setting('global_default_layout'),
    '#options' => array(
      'smartphone-portrait'  => t('Mobile first'),
      'standard-layout'      => t('Desktop first'),
    ),
  );
  // Cascading media queries
  $form['at-layout']['global-settings']['cascading-mediaqueries'] = array(
    '#type' => 'fieldset',
    '#title' => t('Cascading Media Queries'),
    '#description' => t('<h3>Cascading Media Queries</h3><p>Use <a href="http://zomigi.com/blog/essential-considerations-for-crafting-quality-media-queries/#mq-overlap-stack" target="_blank">overlapped media queries</a>. These are are in a seperate file: <code>themeName/css/responsive.cascade.css</code>, you must set the media queries in this file manually!'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['cascading-mediaqueries']['enable_cascading_media_queries'] = array(
    '#type' => 'checkbox',
    '#default_value' => theme_get_setting('enable_cascading_media_queries'),
    '#title'  => t('Enable the responsive.cascade.css file'),
  );
  $form['at-layout']['global-settings']['cascading-mediaqueries']['cascade_media_query'] = array(
    '#type' => 'textfield',
    '#description' => t('Enter the smallest min-width in your <code>responsive.cascade.css</code> file, this is used when loading the file in Development mode.'),
    '#default_value' => theme_get_setting('cascade_media_query'),
    '#field_prefix' => '@media',
    '#states' => array(
      'invisible' => array(
        'input[name="production_mode"]' => array('checked' => TRUE),
      ),
      'disabled' => array(
        'input[name="enable_cascading_media_queries"]' => array('checked' => FALSE),
      ),
    ),
  );
  // Enable respond.js
  $form['at-layout']['global-settings']['respondjs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Enable Media Query Support in Non-supporting Browsers'),
    '#description' => t('<h3>Enable Media Query Support in Non-supporting Browsers</h3>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['respondjs']['load_respondjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable media queries in IE8 and below'),
    '#description' => t('By checking this setting IE6, 7 and 8 will rely on <a href="!link" target="_blank">respond.js</a> to set the layout.', array('!link' => '//github.com/scottjehl/Respond', '!link2' => '//github.com/scottjehl/Respond/issues')),
    '#default_value' => theme_get_setting('load_respondjs'),
  );
  // Disable responsive layout
  $form['at-layout']['global-settings']['disable-rs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Disable Responsive Layout'),
    '#description' => t('<h3>Disable Responsive Layout</h3>'),
  );
  $form['at-layout']['global-settings']['disable-rs']['disable_responsive_styles'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable responsive layout and styles'),
    '#description' => t('By checking this setting the site will use only the Standard layout and the global styles. You can turn this back on at any time.'),
    '#default_value' => theme_get_setting('disable_responsive_styles'),
  );
  $form['at-layout']['global-settings']['dev'] = array(
    '#type' => 'fieldset',
    '#title' => t('Rebuild Theme Data and Registry'),
    '#description' => t('<h3>Rebuild Theme Data and Registry</h3><p>Enabling this setting will fire <code>system_rebuild_theme_data()</code> and <code>drupal_theme_rebuild()</code> on every page request. Disable this when your site is live - its a major performance overhead. TODO - insert link to docs.</p>'),
  );
  $form['at-layout']['global-settings']['dev']['rebuild_theme_data'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild theme data and the theme registry on every page request'),
    '#default_value' => theme_get_setting('rebuild_theme_data'),
  );