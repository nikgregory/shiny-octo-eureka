<?php
  /**
   * TODO: review documentation and provide links to help pages.
   */

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
      'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
    ),
  );
  $form['at-layout']['global-settings']['mode']['production_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Production Mode'),
    '#description' => t('Enabling Production mode reduces HTTP requests by aggregating the responsive stylesheets into one CSS file and uses embedded media queries. Enable this after you have finished writing your CSS styles. If you later change any styles in the responsive stylesheets you will need re-save the theme settings to re-write the aggregated CSS file.'),
    '#default_value' => theme_get_setting('production_mode'),
  );

  // Set default layout
  $form['at-layout']['global-settings']['default-layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mobile first or Mobile last'),
    '#description' => t('<h3>Mobile first or Desktop first</h3>'),
    '#states' => array(
      'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
    ),
  );
  $form['at-layout']['global-settings']['default-layout']['global_default_layout'] = array(
    '#type' => 'radios',
    '#description' => t('Adaptivetheme supports both mobile first and desktop first design approaches.'),
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
      'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
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
      'invisible' => array('input[name="production_mode"]' => array('checked' => TRUE)),
      'disabled' => array('input[name="enable_cascading_media_queries"]' => array('checked' => FALSE)),
    ),
  );

  // Polyfills
  $form['at-layout']['global-settings']['polyfills'] = array(
    '#type' => 'fieldset',
    '#title' => t('Polyfills'),
    '#description' => t('<h3>Polyfills</h3>'),
  );
  // HTML5 shiv
  $form['at-layout']['global-settings']['polyfills']['load_html5js'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable HTML5 support in IE8 and below'),
    '#description' => t('By checking this setting the site will load the <a href="!link" target="_blank">html5shiv</a>.', array('!link' => '//github.com/aFarkas/html5shiv')),
    '#default_value' => theme_get_setting('load_html5js'),
  );
  // Rounding error fix (CSS)
  $form['at-layout']['global-settings']['polyfills']['load_ltie8css'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable CSS rounding error fixes for IE7 and below'),
    '#description' => t('This will load a stylesheet that fixes percentage rounding errors in IE6/7.'),
    '#default_value' => theme_get_setting('load_ltie8css'),
  );
  // Respond.js
  $form['at-layout']['global-settings']['polyfills']['load_respondjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable media queries in IE6-8'),
    '#description' => t('By checking this setting IE6, 7 and 8 will rely on <a href="!link" target="_blank">respond.js</a> to set the layout.', array('!link' => '//github.com/scottjehl/Respond', '!link2' => '//github.com/scottjehl/Respond/issues')),
    '#default_value' => theme_get_setting('load_respondjs'),
    '#states' => array(
      'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
    ),
  );
  // Boxsizing.htc
  $form['at-layout']['global-settings']['polyfills']['load_boxsizinghtc'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Box Sizing HTC fix for IE6-7'),
    '#description' => t('<a href="!link" target="_blank">Box Sizing HTC</a> enables CSS3 <code>box-sizing</code> for IE6-IE8.', array('!link' => '//github.com/Schepp/box-sizing-polyfill')),
    '#default_value' => theme_get_setting('load_boxsizinghtc'),
  );
  // PIE
  $form['at-layout']['global-settings']['polyfills']['pie']['load_pie'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable PIE for IE'),
    '#description' => t('<a href="!link" target="_blank">PIE</a> enabes CSS3 backwards compatibility for IE. Turn PIE off if you are not using it, its 40kb.', array('!link' => 'http://css3pie.com')),
    '#default_value' => theme_get_setting('load_pie'),
  );
  $form['at-layout']['global-settings']['polyfills']['pie']['pie_crust'] = array(
    '#type' => 'fieldset',
    '#title' => t('Additional options for PIE'),
    '#states' => array(
      'visible' => array('input[name="load_pie"]' => array('checked' => TRUE)),
    ),
  );
  $form['at-layout']['global-settings']['polyfills']['pie']['pie_crust']['which_pie'] = array(
    '#type' => 'select',
    '#title' => t('Select which version of PIE to load'),
    '#default_value' => theme_get_setting('which_pie'),
    '#options' => array(
      'PIE.htc' => 'PIE.htc',
      'PIE.php' => 'PIE.php',
      'PIE.js' => 'PIE.js',
    ),
  );
  $form['at-layout']['global-settings']['polyfills']['pie']['pie_crust']['disble_pie_for_ie9'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable PIE for IE9'),
    '#description' => t('IE9 supports some CSS3, optionally disable PIE support in IE9.'),
    '#default_value' => theme_get_setting('disble_pie_for_ie9'),
  );
  // selectivizr is a JavaScript utility that emulates CSS3 pseudo-classes and attribute selectors in Internet Explorer 6-8
  $form['at-layout']['global-settings']['polyfills']['load_selectivizr'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Selectivizr for IE6-8'),
    '#description' => t('<a href="!link" target="_blank">Selectivizr</a> is a JavaScript utility that emulates CSS3 pseudo-classes and attribute selectors in Internet Explorer 6-8.', array('!link' => 'http://selectivizr.com')),
    '#default_value' => theme_get_setting('load_selectivizr'),
  );
  // Scalefix
  $form['at-layout']['global-settings']['polyfills']['load_scalefixjs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Scalefix for iOS'),
    '#description' => t('Fixes the iOS Orientationchange zoom bug.'),
    '#default_value' => theme_get_setting('load_scalefixjs'),
    '#states' => array(
      'invisible' => array('input[name="disable_responsive_styles"]' => array('checked' => TRUE)),
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

  // Enable extensions (really we just unhide the extensions)
  $form['at-layout']['global-settings']['extensions'] = array(
    '#type' => 'fieldset',
    '#title' => t('Enable Extensions'),
    '#description' => t('<h3>Enable Extensions</h3>'),
  );
  $form['at-layout']['global-settings']['extensions']['enable_extensions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable extensions'),
    '#description' => t('Extensions include many extras for configuring and styling your site and theme such as fonts, title styles, image alignment and captions, metatags &amp; touch icons for mobile and much more. Extensions can be enabled or disabled individually, so you only load what you need.'),
    '#default_value' => theme_get_setting('enable_extensions'),
  );
