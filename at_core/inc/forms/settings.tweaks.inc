<?php
  // TODO: We need to check if this is an old theme, and not show stuff and throw a warning

  // Enable Extensions
  $form['at']['site-tweaks'] = array(
    '#type' => 'fieldset',
    '#title' => t('Enable Extensions'),
    '#description' => t('<h3>Enable Additional Features</h3><p>New tabs will appear on the left after you have enabled your required features.'),
    '#weight' => 1000,
  );
  $form['at']['site-tweaks']['enable-stuff'] = array(
    '#type' => 'fieldset',
    '#title' => t('Enable Extensions'),
  );

  // Fonts
  $form['at']['site-tweaks']['enable-stuff']['enable_font_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fonts'),
    '#default_value' => theme_get_setting('enable_font_settings'),
    '#description' => t('Apply fonts to site elements (page, titles, headings). Supports websafe, custom and Google fonts, and <a href="!link" target="_blank">@font-your-face</a> integration.', array('!link' => 'http://drupal.org/project/fontyourface')),
  );

  // Title styles
  $form['at']['site-tweaks']['enable-stuff']['enable_heading_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Title styles'),
    '#default_value' => theme_get_setting('enable_heading_settings'),
    '#description' => t('Fine grain control over case, weight, alignment and CSS3 text shadows for page title, node, comment and block titles.'),
  );

  // Image alignment and captions
  $form['at']['site-tweaks']['enable-stuff']['enable_image_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Image alignment and captions'),
    '#default_value' => theme_get_setting('enable_image_settings'),
    '#description' => t('Set default image alignment and enable image captions.'),
  );

  // Breadcrumb display and seperator
  $form['at']['site-tweaks']['enable-stuff']['enable_breadcrumb_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Breadcrumb display and seperator'),
    '#default_value' => theme_get_setting('enable_breadcrumb_settings'),
    '#description' => t('Setting to customize the breadcrumb.'),
  );

  // Search result settings
  $form['at']['site-tweaks']['enable-stuff']['enable_search_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Search result content'),
    '#default_value' => theme_get_setting('enable_search_settings'),
    '#description' => t('Enables the ability to modify the content of your search results.'),
  );

  // Login block options
  $form['at']['site-tweaks']['enable-stuff']['enable_loginblock_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Login block options'),
    '#default_value' => theme_get_setting('enable_loginblock_settings'),
    '#description' => t('Stylize the login block - includes a setting for a horizonal form and other tweaks.'),
  );

  // Metatags for mobile
  $form['at']['site-tweaks']['enable-stuff']['enable_mobile_metatags'] = array(
    '#type' => 'checkbox',
    '#title' => t('Metatags for mobile'),
    '#description' => t('Enable and configure metatags for mobile devices.'),
    '#default_value' => theme_get_setting('enable_mobile_metatags'),
  );

  // Apple touch icons
  $form['at']['site-tweaks']['enable-stuff']['enable_apple_touch_icons'] = array(
    '#type' => 'checkbox',
    '#title' => t('Apple touch icons'),
    '#description' => t('Check this setting if you want to use touch icons for iOS, Android and Nokia devices.'),
    '#default_value' => theme_get_setting('enable_apple_touch_icons'),
  );

  // Markup overrides
  $form['at']['site-tweaks']['enable-stuff']['enable_markup_overides'] = array(
    '#type' => 'checkbox',
    '#title' => t('Markup overrides'),
    '#description' => t('Many additional options for adding, removing, hiding and changing site elements such as using 100% width header/section/footer wrappers, removing the RSS icon, hiding comment titles and more.'),
    '#default_value' => theme_get_setting('enable_markup_overides'),
  );