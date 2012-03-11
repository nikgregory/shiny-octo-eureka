<?php 
  // TODO: We need to check if this is an old theme, and not show stuff and throw a warning
  // SITE TWEAKS
  $form['at']['site-tweaks'] = array(
    '#type' => 'fieldset',
    '#weight' => 100,
    '#title' => t('Site Tweaks'),
  );
  // Enable extra style settings
  $form['at']['site-tweaks']['style-settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Styles'),
    '#description' => t('<h3>Enable Stuff</h3><p>After enabling stuff new theme setting will be available, first enable, then enjoy...'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_font_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fonts'),
    '#default_value' => theme_get_setting('enable_font_settings'),
    '#description' => t('Apply fonts to site elements - includes websafe font-stacks, Google fonts and @font-your-face integration.'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_heading_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Stylize headings (page title, node, comment and block titles)'),
    '#default_value' => theme_get_setting('enable_heading_settings'),
    '#description' => t('Fine grain control over case, weight, alignment and CSS3 text shadows.'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_image_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Image tweaks'),
    '#default_value' => theme_get_setting('enable_image_settings'),
    '#description' => t('Set default image alignment and enable image captions.'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_breadcrumb_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Breadcrumb tweaks'),
    '#default_value' => theme_get_setting('enable_breadcrumb_settings'),
    '#description' => t('Setting to customize the breadcrumb.'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_search_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Search results tweaks'),
    '#default_value' => theme_get_setting('enable_search_settings'),
    '#description' => t('Enables the ability to modify the content of your search results.'),
  );
  $form['at']['site-tweaks']['style-settings']['enable_loginblock_settings'] = array(
    '#type' => 'checkbox',
    '#title' => t('Login block tweaks'),
    '#default_value' => theme_get_setting('enable_loginblock_settings'),
    '#description' => t('Stylize the login block - includes a setting for a horizonal form and other tweaks.'),
  );
  // Hide or Remove Stuff
  $form['at']['site-tweaks']['hide-stuff'] = array(
    '#type' => 'fieldset',
    '#title' => t('Hide or Remove Stuff'),
    '#description' => t('<h3>Hide or Remove Stuff</h3>'),
  );
  $form['at']['site-tweaks']['hide-stuff']['comments_hide_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide the comment title'),
    '#default_value' => theme_get_setting('comments_hide_title'),
    '#description' => t('Checking this setting will hide comment titles using element-invisible. Hiding rather than removing titles maintains accessibility and semantic structure while not showing titles to sighted users.'),
  );
  $form['at']['site-tweaks']['hide-stuff']['feed_icons_hide'] = array(
    '#type' => 'checkbox',
    '#title' => t('Remove RSS feed icons'),
    '#default_value' => theme_get_setting('feed_icons_hide'),
    '#description' => t('Checking this setting will remove RSS feed icons. This will not affect the Syndicate block icon.'),
  );
  $form['at']['site-tweaks']['hide-stuff']['unset_block_system_main_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Do not display the Main content block on the front page'),
    '#default_value' => theme_get_setting('unset_block_system_main_front'),
    '#description' => t('Checking this setting will remove the Main content block from the front page only - useful for removing the welcome message and allowing use of another block.'),
  );
  // Add stuff
  $form['at']['site-tweaks']['add-stuff'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add Stuff'),
    '#description' => t('<h3>Add Stuff</h3>'),
  );
  // Use the page full width wrappers template
  $form['at']['site-tweaks']['add-stuff']['page_full_width_wrappers'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use page--full-width-wrappers.tpl.php by default'),
    '#description' => t('This setting will load a page template will full width DIV wrappers - perfect for designs with 100% width sections, header or footer. Note that if you choose to use other page template suggestions you should use copies of page--full-width-wrappers.tpl.php, you will find this in adaptivetheme/at-core/templates.'),
    '#default_value' => theme_get_setting('page_full_width_wrappers'),
  );
  // Add spans to theme_links
  $form['at']['site-tweaks']['add-stuff']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu item text in SPAN tags - useful for certain theme or design related techniques'),
    '#description' => t('Note: this does not work for Superfish menus, which includes its own feature for doing this.'),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );