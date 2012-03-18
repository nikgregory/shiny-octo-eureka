<?php 
  // Global Settings
  $form['at-layout']['global-settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Settings'),
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
  
  // Polyfills
  $form['at-layout']['global-settings']['polyfills'] = array(
    '#type' => 'fieldset',
    '#title' => t('Polyfills'),
    '#description' => t('<h3>Polyfills</h3>'),
  );
  $form['at-layout']['global-settings']['polyfills']['load_html5js'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable HTML5 support in IE8 and below'),
    '#description' => t('By checking this setting the site will load the <a href="!link3" target="_blank">html5shiv</a>.', array('!link3' => '//github.com/aFarkas/html5shiv')),
    '#default_value' => theme_get_setting('load_html5js'),
  );
  $form['at-layout']['global-settings']['polyfills']['load_respondjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable media queries in IE8 and below'),
    '#description' => t('By checking this setting IE6, 7 and 8 will rely on <a href="!link" target="_blank">respond.js</a> to set the layout.', array('!link' => '//github.com/scottjehl/Respond', '!link2' => '//github.com/scottjehl/Respond/issues')),
    '#default_value' => theme_get_setting('load_respondjs'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['global-settings']['polyfills']['load_boxsizinghtc'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Box Sizing HTC fix for IE'),
    '#description' => t('A polyfill for <code>box-sizing: border-box</code> for IE6 and IE7. Read the usage docs: TODO insert link to docs.', array('!link3' => '//github.com/aFarkas/html5shiv')),
    '#default_value' => theme_get_setting('load_boxsizinghtc'),
  );
  $form['at-layout']['global-settings']['polyfills']['load_scalefixjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Scalefix for iOS'),
    '#description' => t('This prevents iOS from overscaling the page on orientation change while preserving accessibility (scaling capability).', array('!link3' => '//github.com/aFarkas/html5shiv')),
    '#default_value' => theme_get_setting('load_scalefixjs'),
    '#states' => array(
      'invisible' => array(
        'input[name="disable_responsive_styles"]' => array('checked' => TRUE),
      ),
    ),
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