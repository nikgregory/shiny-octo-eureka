<?php

/**
 * @file
 * Generate settings for the Menu Toggle form.
 */
function at_core_menu_toggle_form(&$form) {
  $form['at']['menu-toggle'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mobile Menu Toggle'),
    '#weight' => 30,
    '#description' => t('<h2>Menu Toggle for Mobile</h2><p>The Menu Toggle will hide the main menu (either a block in the Menu bar region, or the Main menu) and a single clickable link will appear. Clicking this link toggles the menu open and closed - suitable for mobile devices.</p><p>Note: if you start using the Superfish module you should re-save the theme settings so this will work correctly.</p>'),
  );
  // Toggle on existing breakpoints
  $form['at']['menu-toggle']['breakpoints'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breakpoints'),
    '#description' => t('<strong>Select which devices/breakpoints you want to use the toggle menu for.</strong>'),
    //'#states' => array(
    //  'disabled' => array('input[name="menu_toggle_custom_media_query_toggle"]' => array('checked' => TRUE)),
    //),
  );
  $form['at']['menu-toggle']['breakpoints']['menu_toggle_smalltouch_portrait'] = array(
    '#type' => 'checkbox',
    '#title' => t('Apply to Smalltouch Portrait'),
    '#default_value' => at_get_setting('menu_toggle_smalltouch_portrait') ? at_get_setting('menu_toggle_smalltouch_portrait') : 1,
  );
  $form['at']['menu-toggle']['breakpoints']['menu_toggle_smalltouch_landscape'] = array(
    '#type' => 'checkbox',
    '#title' => t('Apply to Smalltouch Landscape'),
    '#default_value' => at_get_setting('menu_toggle_smalltouch_landscape') ? at_get_setting('menu_toggle_smalltouch_landscape') : 1,
  );
  $form['at']['menu-toggle']['breakpoints']['menu_toggle_tablet_portrait'] = array(
    '#type' => 'checkbox',
    '#title' => t('Apply to Tablet Portrait'),
    '#default_value' => at_get_setting('menu_toggle_tablet_portrait') ? at_get_setting('menu_toggle_tablet_portrait') : 0,
  );
  $form['at']['menu-toggle']['breakpoints']['menu_toggle_tablet_landscape'] = array(
    '#type' => 'checkbox',
    '#title' => t('Apply to Tablet Landscape'),
    '#default_value' => at_get_setting('menu_toggle_tablet_landscape') ? at_get_setting('menu_toggle_tablet_landscape') : 0,
  );

  // Custom media query - currently disabled, in the future we might be able to make this work better...
  //$form['at']['menu-toggle']['custom_media_query'] = array(
  //  '#type' => 'fieldset',
  //  '#title' => t('Custom Media Query'),
  //  '#description' => t('<strong>Use Custom Media Queries</strong>'),
  //);
  //$form['at']['menu-toggle']['custom_media_query']['menu_toggle_custom_media_query_toggle'] = array(
  //  '#type' => 'checkbox',
  //  '#title' => t('Enter a custom media query <span class="description">(this will override all other settings)</span>'),
  //  '#default_value' => at_get_setting('menu_toggle_custom_media_query_toggle') ? at_get_setting('menu_toggle_custom_media_query_toggle') : 0,
  //);
  //$form['at']['menu-toggle']['custom_media_query']['menu_toggle_custom_media_query'] = array(
  //  '#type' => 'textfield',
  //  '#title' => t('Custom media query for the Menu Toggle'),
  //  '#default_value' => check_plain(at_get_setting('menu_toggle_custom_media_query')),
  //  '#description' => t('Do not include @media, it\'s included automatically. For example you might use <code>only screen and (max-width:768px)</code>.'),
  //  '#size' => 100,
  //  '#states' => array(
  //    'visible' => array('input[name="menu_toggle_custom_media_query_toggle"]' => array('checked' => TRUE)),
  //    'required' => array('input[name="menu_toggle_custom_media_query_toggle"]' => array('checked' => TRUE)),
  //  ),
  //);

  // Menu style - probaly deprecated entirely, this is better done in subtheme responsive CSS directly
  //$form['at']['menu-toggle']['menu_toggle_style'] = array(
  //  '#type' => 'fieldset',
  //  '#title' => t('Menu Style'),
  //  '#description' => t('<strong>Menu Style</strong>'),
  //);
  //$form['at']['menu-toggle']['menu_toggle_style']['menu_toggle_style_setting'] = array(
  //  '#type' => 'radios',
  //  '#title' => t('Slide down menu pushes the content down, the Overlay style displays the menu over the content.'),
  //  '#options' => array(
  //    'toggle_menu_slide' => t('Slide menu'),
  //    'toggle_menu_overlay' => t('Overlay menu'),
  //  ),
  //  '#default_value' => at_get_setting('menu_toggle_style_setting') ? at_get_setting('menu_toggle_style_setting') : 'toggle_menu_slide',
  //);
}
