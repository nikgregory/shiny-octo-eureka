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
    '#description' => t('<h3>Production Mode</h3>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['mode']['production_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Production Mode'),
    '#description' => t('Enabling Production mode reduces HTTP requests by aggregating the responsive stylesheets into one CSS file and uses embedded media queries. TODO: add link to documentation.'),
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
    '#description' => t('<h3>Cascading Media Queries</h3>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['cascading-mediaqueries']['enable_cascading_media_queries'] = array(
    '#type' => 'checkbox',
    '#title'  => t('Enable the responsive.cascade.css file'),
    '#description' => t('Use <a href="http://zomigi.com/blog/essential-considerations-for-crafting-quality-media-queries/#mq-overlap-stack" target="_blank">overlapped media queries</a>. These are are in a seperate file: <code>themeName/css/responsive.cascade.css</code>, you must set the media queries in this file manually!'),
    '#default_value' => theme_get_setting('enable_cascading_media_queries'),
    
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
  // Meta information
  $form['at-layout']['global-settings']['meta'] = array(
    '#type' => 'fieldset',
    '#title' => t('Metatags'),
    '#description' => t('<h3>Metatags</h3>'),
  );
  $form['at-layout']['global-settings']['meta']['mobile_friendly_metatags'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable mobile friendly metatags'),
    '#description' => t('TODO: add link to mobile metatags documentation.'),
    '#default_value' => theme_get_setting('mobile_friendly_metatags'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  
  $form['at-layout']['global-settings']['meta']['adaptivetheme_meta_viewport'] = array(
    '#type' => 'textfield',
    '#title' => t('viewport'),
    '#default_value' => theme_get_setting('adaptivetheme_meta_viewport'),
    '#states' => array(
      'visible' => array(
        'input[name="mobile_friendly_metatags"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['meta']['adaptivetheme_meta_mobileoptimized'] = array(
    '#type' => 'textfield',
    '#title' => t('MobileOptimized'),
    '#default_value' => theme_get_setting('adaptivetheme_meta_mobileoptimized'),
    '#states' => array(
      'visible' => array(
        'input[name="mobile_friendly_metatags"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['meta']['adaptivetheme_meta_handheldfriendly'] = array(
    '#type' => 'textfield',
    '#title' => t('HandheldFriendly'),
    '#default_value' => theme_get_setting('adaptivetheme_meta_handheldfriendly'),
    '#states' => array(
      'visible' => array(
        'input[name="mobile_friendly_metatags"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['meta']['adaptivetheme_meta_apple_mobile_web_app_capable'] = array(
    '#type' => 'textfield',
    '#title' => t('apple-mobile-web-app-capable'),
    '#default_value' => theme_get_setting('adaptivetheme_meta_apple_mobile_web_app_capable'),
    '#states' => array(
      'visible' => array(
        'input[name="mobile_friendly_metatags"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['meta']['chrome_edge'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Chrome Edge support for IE'),
    '#description' => t('TODO: add link to Chrome Edge documentation.'),
    '#default_value' => theme_get_setting('chrome_edge'),
  );
  // Polyfills
  $form['at-layout']['global-settings']['polyfills'] = array(
    '#type' => 'fieldset',
    '#title' => t('Polyfills'),
    '#description' => t('<h3>Polyfills</h3>'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['polyfills']['load_respondjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable media queries in IE8 and below'),
    '#description' => t('By checking this setting IE6, 7 and 8 will rely on <a href="!link" target="_blank">respond.js</a> to set the layout.', array('!link' => '//github.com/scottjehl/Respond', '!link2' => '//github.com/scottjehl/Respond/issues')),
    '#default_value' => theme_get_setting('load_respondjs'),
  );
  $form['at-layout']['global-settings']['polyfills']['load_html5js'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable HTML5 support in IE8 and below'),
    '#description' => t('By checking this setting the site will load the <a href="!link3" target="_blank">html5shiv</a>.', array('!link3' => '//github.com/aFarkas/html5shiv')),
    '#default_value' => theme_get_setting('load_html5js'),
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