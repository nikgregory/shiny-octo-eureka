<?php
// generate option values with unit
function at_option_values($min, $max, $increment, $postfix) {
  $array = array();
  for ($a = $min; $a <= $max; $a += $increment) {
    $array[$a . $postfix] = $a . $postfix;
  }
  return $array;
}

/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * Work around a core double invoke bug. This only needs to exist in the base
 * theme. Use hook_form_theme_settings() for the real work.
 *
 * @see http://drupal.org/node/943212
 * @see http://drupal.org/node/1135794
 */
function adaptivetheme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  
  // set the default layout type early
  $layout_type = 'adaptive';

  // set options for widths by type
  if (theme_get_setting('layout_type') == 'adaptive') {
    $smartphone_sidebar_options_landscape = at_option_values(60, 480, 60, 'px'); // 8 col grid / 60px
    $tablet_sidebar_options_portrait = at_option_values(60, 660, 60, 'px');
    $tablet_sidebar_options_landscape = at_option_values(120, 840, 60, 'px');
    $sidebar_options = at_option_values(60, 480, 60, 'px');
  }
  if (theme_get_setting('layout_type') == 'responsive') {
    $layout_type = 'responsive';
    $smartphone_sidebar_options_landscape = at_option_values(20, 80, 10, '%');
    $tablet_sidebar_options_portrait = at_option_values(20, 80, 5, '%');
    $tablet_sidebar_options_landscape = at_option_values(20, 80, 5, '%');
    $sidebar_options = at_option_values(10, 50, 5, '%');
  }
  // unconditional options, these are all width options for Adaptive type layouts
  $smartphone_width_options_landscape = at_option_values(300, 480, 60, 'px'); // 8 col grid / 60px
  $tablet_width_options_portrait = at_option_values(540, 720, 60, 'px'); // 16 col grid / 45px
  $tablet_width_options_landscape = at_option_values(600, 960, 60, 'px'); // 16 col grid / 60px
  $laptop_width_options = at_option_values(960, 1260, 60, 'px');
  $laptop_min_max_options = at_option_values(960, 1260, 60, 'px');
  $desktop_width_options = at_option_values(960, 1800, 60, 'px');
  $desktop_min_max_options = at_option_values(960, 1800, 60, 'px');

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  if (isset($form_id)) {
    return;
  }

  // Add some CSS so we can style our form in any theme, i.e. in Seven.
  drupal_add_css(drupal_get_path('theme', 'adaptivetheme') . '/css/theme-settings.css', array('group' => CSS_THEME));

  // Layout settings
  $form['at'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
    '#default_tab' => 'defaults',
  );
  // desktop
  $form['at']['desktop'] = array(
    '#type' => 'fieldset',
    '#title' => t('Large screen layout'),
    '#description' => t('<h3>Large Screen Layout</h3>'),
  );
  $form['at']['desktop']['desktop-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['desktop']['desktop-sidebar-layout-wrapper']['desktop_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('desktop_layout'),
    '#options' => array(
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
    )
  );
  $form['at']['desktop']['desktop-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#weight' => 1,
  );
  $form['at']['desktop']['desktop-sidebar-width-wrapper']['desktop_layout']["desktop_sidebar_first_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting("desktop_sidebar_first_$layout_type"),
    '#options' => $sidebar_options,
  );
  $form['at']['desktop']['desktop-sidebar-width-wrapper']['desktop_layout']["desktop_sidebar_second_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting("desktop_sidebar_second_$layout_type"),
    '#options' => $sidebar_options,
  );
  $form['at']['desktop']['desktop-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set page widths'),
    '#description' => t('<strong>Set the page width</strong>'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
    '#weight' => 2,
  );
  $form['at']['desktop']['desktop-page-width-wrapper']['desktop_layout']['desktop_layout_width'] = array(
    '#type'  => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('desktop_layout_width'),
    '#options' => $desktop_width_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['desktop']['desktop-page-minmax-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set min and max widths'),
    '#description' => t('<strong>Set min and max page widths for responsive layouts - pages will flex between these two widths</strong>'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
    '#weight' => 3,
  );
  $form['at']['desktop']['desktop-page-minmax-wrapper']['desktop_layout']['desktop_layout_min_width'] = array(
    '#type'  => 'select',
    '#title' => t('Min width'),
    '#default_value' => theme_get_setting('desktop_layout_min_width'),
    '#options' => $desktop_min_max_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['desktop']['desktop-page-minmax-wrapper']['desktop_layout']['desktop_layout_max_width'] = array(
    '#type'  => 'select',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('desktop_layout_max_width'),
    '#options' => $desktop_min_max_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  // laptop
  $form['at']['laptop'] = array(
    '#type' => 'fieldset',
    '#title' => t('Desktop &amp; Laptop layout'),
    '#description' => t('<h3>Mid-size Screen Layout</h3>'),
  );
  $form['at']['laptop']['laptop-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['laptop']['laptop-sidebar-layout-wrapper']['laptop_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('laptop_layout'),
    '#options' => array(
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
    )
  );
  $form['at']['laptop']['laptop-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#weight' => 1,
  );
  $form['at']['laptop']['laptop-sidebar-width-wrapper']['laptop_layout']["laptop_sidebar_first_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting("laptop_sidebar_first_$layout_type"),
    '#options' => $sidebar_options,
  );
  $form['at']['laptop']['laptop-sidebar-width-wrapper']['laptop_layout']["laptop_sidebar_second_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting("laptop_sidebar_second_$layout_type"),
    '#options' => $sidebar_options,
  );
  $form['at']['laptop']['laptop-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set page widths'),
    '#description' => t('<strong>Set the page width</strong>'),
    '#weight' => 2,
  );
  $form['at']['laptop']['laptop-page-width-wrapper']['laptop_layout']['laptop_layout_width'] = array(
    '#type'  => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('laptop_layout_width'),
    '#options' => $laptop_width_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['laptop']['laptop-page-width-wrapper']['laptop_layout']['laptop_layout_min_width'] = array(
    '#type'  => 'select',
    '#title' => t('Min width'),
    '#default_value' => theme_get_setting('laptop_layout_min_width'),
    '#options' => $laptop_min_max_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['laptop']['laptop-page-width-wrapper']['laptop_layout']['laptop_layout_max_width'] = array(
    '#type'  => 'select',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('laptop_layout_max_width'),
    '#options' => $laptop_min_max_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  // tablet
  $form['at']['tablet'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet layout'),
    '#description' => t('<h3>Tablet Layout</h3><p>Tablet devices such as iPad have two orientations - landscape and portrait. You can configure a different layout for each orientation.</p>'),
  );
  // tablet landscape
  $form['at']['tablet']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape Tablet</h4>'),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-layout-wrapper']['tablet_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('tablet_landscape_layout'),
    '#options' => array(
      'two-col-stack'   => t('Two colums, sidebar second stacked below the main column (the second sidebar is full width)'),
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
    )
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
    '#description' => t('<strong>Set sidebar widths</strong>'),
    '#weight' => 1,
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']["tablet_sidebar_first_landscape_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting("tablet_sidebar_first_landscape_$layout_type"),
    '#options' => $tablet_sidebar_options_landscape,
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']["tablet_sidebar_second_landscape_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting("tablet_sidebar_second_landscape_$layout_type"),
    '#options' => $tablet_sidebar_options_landscape,
    '#states' => array(
      'invisible' => array(
        'input[name="tablet_landscape_layout"]' => array('value' => 'two-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
    '#weight' => 2,
  );
  $form['at']['tablet']['landscape']['tablet-page-width-wrapper']['tablet_override_landscape_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override tablet landscape width'),
    '#default_value' => theme_get_setting('tablet_override_landscape_width'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-page-width-wrapper']['tablet_landscape_width_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Landscape width'),
    '#default_value' => theme_get_setting('tablet_landscape_width_adaptive'),
    '#options' => $tablet_width_options_landscape,
    '#states' => array(
      'visible' => array(
        'input[name="tablet_override_landscape_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  // tablet portrait
  $form['at']['tablet']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait'),
    '#description' => t('<h4>Portrait Tablet</h4>'),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-layout-wrapper']['tablet_portrait_layout'] = array(
    '#type' => 'radios',
    '#title' => t('Configure sidebar positions'),
    '#default_value' => theme_get_setting('tablet_portrait_layout'),
    '#options' => array(
      'two-col-stack' => t('Two colums, sidebar second stacked below the main column (the second sidebar is full width)'),
      'one-col-vert'  => t('Sidebars in two vertical columns below the main column'),
      'one-col-stack' => t('One column'),
    )
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
    '#description' => t('<strong>Set sidebar widths</strong>'),
    '#weight' => 1,
    '#states' => array(
      'invisible' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']["tablet_sidebar_first_portrait_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting("tablet_sidebar_first_portrait_$layout_type"),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'invisible' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']["tablet_sidebar_second_portrait_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting("tablet_sidebar_second_portrait_$layout_type"),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'visible' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-vert'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
    '#weight' => 2,
  );
  $form['at']['tablet']['portrait']['tablet-page-width-wrapper']['tablet_override_portrait_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override tablet portrait width'),
    '#default_value' => theme_get_setting('tablet_override_landscape_width'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-page-width-wrapper']["tablet_portrait_width_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Portrait width'),
    '#default_value' => theme_get_setting("tablet_portrait_width_$layout_type"),
    '#options' => $tablet_width_options_portrait,
    '#states' => array(
      'visible' => array(
        'input[name="tablet_override_portrait_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  // smartphone
  $form['at']['smartphone'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone layout'),
    '#description' => t('<h3>Smartphone Layout</h3><p>Smartphone devices such as iPhone, Android and Windows phones have two orientations - landscape and portrait. You can configure a layout for landscape orientation only - portrait orientation always displays in one column with sidebars stacked below the main content.</p>'),
  );
  // smartphone landscape
  $form['at']['smartphone']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape Smartphone</h4>'),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-layout-wrapper']['smartphone_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar layout</strong>'),
    '#default_value' => theme_get_setting('smartphone_landscape_layout'),
    '#options' => array(
      'one-col-stack' => t('One column'),
      'one-col-vert' => t('Sidebars in two vertical columns below the main column'),
    )
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar width'),
    '#description' => t('<strong>Set sidebar width</strong>'),
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-vert'),
      ),
    ),
    '#weight' => 1,
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']["smartphone_sidebar_first_landscape_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting("smartphone_sidebar_first_landscape_$layout_type"),
    '#options' => $smartphone_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-vert'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']["smartphone_sidebar_second_landscape_$layout_type"] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting("smartphone_sidebar_second_landscape_$layout_type"),
    '#options' => $smartphone_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-vert'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set page width'),
    '#description' => t('<strong>Set page width</strong>'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
    '#weight' => 2,
  );
  $form['at']['smartphone']['landscape']['smartphone-page-width-wrapper']['smartphone_override_landscape_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override smartphone landscape width'),
    '#default_value' => theme_get_setting('smartphone_override_landscape_width'),
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-page-width-wrapper']['smartphone_landscape_width_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('smartphone_landscape_width_adaptive'),
    '#options' => $smartphone_width_options_landscape,
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_override_landscape_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  // smartphone portrait
  $form['at']['smartphone']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Portrait Layout'),
    '#description' => t('<h3>Portrait Smartphone</h3><p>In portrait content always displays in one column - sidebars automatically stack below the main content column. This is the same as the "One column" setting for landscape orientation.</p>'),
  );
  // layout type - adaptive or responsive
  $form['at']['type'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout type'),
    '#description' => t('<h3>Select Adaptive or Resonsive Layout</h3><p>Adaptive and responsive layouts allow your theme to change dynamically depending on the size of the users device, such as smartphones, tablets, laptops and desktop computers. Adaptive layouts use fixed width layouts while responsive layouts use fluid widths.</p><p><strong>If you change this setting you must save the theme settings before altering any other settings.</strong></p>'),
  );
  $form['at']['type']['layout_type'] = array(
    '#type' => 'select',
    '#title' => t('Layout type'),
    '#default_value' => theme_get_setting('layout_type'),
    '#options' => array(
      'adaptive' => t('Adaptive'),
      'responsive' => t('Responsive'),
    )
  );
  // Breadcrumbs
  $form['at']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#weight' => '96',
    '#title' => t('Breadcrumb'),
    '#description' => t('<h3>Breadcrumb Settings</h3>'),
  );
  $form['at']['breadcrumb']['breadcrumb_display'] = array(
    '#type' => 'select',
    '#title' => t('Show breadcrumbs'),
    '#default_value' => theme_get_setting('breadcrumb_display'),
    '#options' => array(
      'yes' => t('Yes'),
      'no' => t('No'),
    ),
  );
  $form['at']['breadcrumb']['breadcrumb_separator'] = array(
    '#type'  => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Text only. Dont forget to include spaces.'),
    '#default_value' => theme_get_setting('breadcrumb_separator'),
    '#size' => 8,
    '#maxlength' => 10,
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array('value' => 'yes',),
      ),
    ),
  );
  $form['at']['breadcrumb']['breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the homepage link'),
    '#default_value' => theme_get_setting('breadcrumb_home'),
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array(
            'value' => 'yes',
          ),
      ),
    ),
  );
  // Search Settings
  $form['at']['search_results'] = array(
    '#type' => 'fieldset',
    '#weight' => '97',
    '#title' => t('Search Results'),
    '#description' => t('<h3>Search Result Display</h3><p>Modify what is displayed below each search result</p>'),
  );
  $form['at']['search_results']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );
  $form['at']['search_results']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );
  $form['at']['search_results']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );
  $form['at']['search_results']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );
  $form['at']['search_results']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );
  $form['at']['search_results']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );
  // Search_info_separator
  $form['at']['search_results']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Modify the separator between each of the above items. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );
  // Horizonatal login block
  if (theme_get_setting('horizontal_login_block_enable') == 'on') {
    $form['at']['login_block'] = array(
      '#type' => 'fieldset',
      '#weight' => '99',
      '#title' => t('Login Block'),
      '#description' => t('<h3>Login Block Options</h3>'),
    );
    $form['at']['login_block']['horizontal_login_block'] = array(
      '#type' => 'checkbox',
      '#title' => t('Horizontal Login Block'),
      '#default_value' => theme_get_setting('horizontal_login_block'),
      '#description' => t('Checking this setting will enable a horizontal style login block (all elements on one line). Note that if you are using OpenID this does not work well and you will need a more sophistocated approach than can be provided here.'),
    );
  } // endif horizontal block settings
  // Development settings
  $form['at']['classes'] = array(
    '#type' => 'fieldset',
    '#weight' => '100',
    '#title' => t('CSS Classes'),
    '#description' => t('<h3>Add or Remove CSS Classes</h3><p>Many classes are removed by default (unchecked checkbox), to add classes check the checkbox.</p>'),
  );
  $form['at']['classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pages: ') . '<span class="description">' . t('add page-path, add/edit/delete (for workflow states), and a language class (i18n).') . '</span>',
    '#default_value' => theme_get_setting('extra_page_classes'),
  );
  $form['at']['classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Articles: ') . '<span class="description">' . t('add promoted, sticky, preview, language, odd/even classes and build mode classes such as .article-teaser and .article-full.') . '</span>',
    '#default_value' => theme_get_setting('extra_article_classes'),
  );
  $form['at']['classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Comments: ') . '<span class="description">' . t('add anonymous, author, viewer, new, and odd/even classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_comment_classes'),
  );
  $form['at']['classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Blocks: ') . '<span class="description">' . t('add odd/even, block region and block count classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_block_classes'),
  );
  $form['at']['classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Menus: ') . '<span class="description">' . t('add an extra class based on the menu link ID (mlid).') . '</span>',
    '#default_value' => theme_get_setting('extra_menu_classes'),
  );
  $form['at']['classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Item-lists: ') . '<span class="description">' . t('add first, last and odd/even classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_item_list_classes'),
  );
  // Menu Links Settings
  $form['at']['markup'] = array(
    '#type' => 'fieldset',
    '#title' => t('Markup'),
    '#description' => t('<h3>Extra Markup Options</h3>'),
    '#weight' => 101,
  );
  // Add spans to theme_links
  $form['at']['markup']['menu_links']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu item text in SPAN tags - useful for certain theme or design related techniques'),
    '#description' => t('Note: this does not work for Superfish menus, which includes its own feature for doing this.'),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );
  // Media queries
  $form['at']['media_queries'] = array(
    '#type' => 'fieldset',
    '#title' => t('Media queries'),
    '#description' => t('<h3>Media Queries</h3><p>Modify the media query breakpoints for each class of device/screen size. Because both smartphones and tablets can rotate the screen orientation both are supported. This is a basic implimentation of media queries and you may need to change them depending on your requirements.</p>'),
    '#weight' => 102,
  );
  // smartphone breakpoints
  $form['at']['media_queries']['smartphone_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override media queries for smartphones'),
    '#default_value' => theme_get_setting('smartphone_override_media_query'),
  );
  $form['at']['media_queries']['smartphone_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Landscape media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('smartphone_landscape_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at']['media_queries']['smartphone_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Portrait media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('smartphone_portrait_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  // tablet breakpoints
  $form['at']['media_queries']['tablet_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override media queries for tablets'),
    '#default_value' => theme_get_setting('tablet_override_media_query'),
  );
  $form['at']['media_queries']['tablet_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Landscape media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('tablet_landscape_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="tablet_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at']['media_queries']['tablet_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Portrait media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('tablet_portrait_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="tablet_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  // laptop breakpoints
  $form['at']['media_queries']['laptop_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override media queries for laptops and desktops'),
    '#default_value' => theme_get_setting('laptop_override_media_query'),
  );
  $form['at']['media_queries']['laptop_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('laptop_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="laptop_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  // desktop breakpoints
  $form['at']['media_queries']['desktop_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override media queries for large desktops'),
    '#default_value' => theme_get_setting('desktop_override_media_query'),
  );
  $form['at']['media_queries']['desktop_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('desktop_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="desktop_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );

  // The following will be processed even if the theme is inactive.
  // If you are on a theme specific settings page but it is not an active
  // theme (example.com/admin/apearance/settings/THEME_NAME), it will
  // still be processed.

  // Build a list of themes related to the theme specific form. If the form
  // is specific to a sub-theme, all parent themes leading to it will have
  // hook_form_theme_settings invoked. For example, if a theme named
  // 'grandchild' has its settings form in focus, the following will be invoked.
  //
  // - parent_form_theme_settings()
  // - child_form_theme_settings()
  // - grandchild_form_theme_settings()
  //
  // If 'child' was in focus it will invoke:
  //
  // - parent_form_theme_settings()
  // - child_form_theme_settings()

  $form_themes = array();
  $themes = list_themes();
  $_theme = $GLOBALS['theme_key'];
  while (isset($_theme)) {
    $form_themes[$_theme] = $_theme;
    $_theme = isset($themes[$_theme]->base_theme) ? $themes[$_theme]->base_theme : NULL;
  }
  $form_themes = array_reverse($form_themes);

  foreach ($form_themes as $theme_key) {
    if (function_exists($form_settings = "{$theme_key}_form_theme_settings")) {
      $form_settings($form, $form_state);
    }
  }
}
