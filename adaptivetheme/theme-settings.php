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

  $layout_type = '';
  // set options for widths by type
  if (theme_get_setting('layout_type') == 'adaptive') {
    $layout_type = 'adaptive';
    $smartphone_sidebar_options         = at_option_values(120, 480,  60, 'px');
    $smartphone_width_options_landscape = at_option_values(240, 480,  60, 'px');
    $tablet_sidebar_options_portrait    = at_option_values(120, 480,  60, 'px');
    $tablet_sidebar_options_landscape   = at_option_values(120, 600,  60, 'px');
    $tablet_width_options_portrait      = at_option_values(480, 780,  60, 'px');
    $tablet_width_options_landscape     = at_option_values(600, 1020, 60, 'px');
    $sidebar_options                    = at_option_values(120, 480,  60, 'px');
  }
  if (theme_get_setting('layout_type') == 'responsive') {
    $layout_type = 'responsive';
    $smartphone_sidebar_options       = at_option_values(10, 50, 5, '%');
    $tablet_sidebar_options_portrait  = at_option_values(10, 50, 5, '%');
    $tablet_sidebar_options_landscape = at_option_values(10, 50, 5, '%');
    $sidebar_options                  = at_option_values(10, 50, 5, '%');
  }
  // unconditional options, set width for adaptive, max-width for responsive
  $bigscreen_width_options        = at_option_values(960,  1320, 60, 'px');

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  if (isset($form_id)) {
    return;
  }

  // Add some CSS so we can style our form in any theme, i.e. in Seven.
  drupal_add_css(drupal_get_path('theme', 'adaptivetheme') . '/css/theme-settings-form-defaults.css', array('group' => CSS_THEME));

  // Layout settings
  $form['at'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
    '#default_tab' => 'defaults',
    '#tree' => FALSE,
  );
  // bigscreen
  $form['at']['bigscreen'] = array(
    '#type' => 'fieldset',
    '#title' => t('Standard layout'),
    '#description' => t('<h3>Standard Layout</h3><p>The standard layout is for desktops, laptops and other large screen devices.'),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-layout-wrapper']['bigscreen_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('bigscreen_layout'),
    '#options' => array(
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
    )
  );
  $form['at']['bigscreen']['bigscreen-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#weight' => 1,
  );
  $form['at']['bigscreen']['bigscreen-sidebar-width-wrapper']['bigscreen_sidebar_first_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_first_adaptive'),
    '#options' => $sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-width-wrapper']['bigscreen_sidebar_second_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_second_adaptive'),
    '#options' => $sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-width-wrapper']['bigscreen_sidebar_first_responsive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_first_responsive'),
    '#options' => $sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-width-wrapper']['bigscreen_sidebar_second_responsive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_second_responsive'),
    '#options' => $sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
    '#weight' => 2,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-page-width-wrapper']['bigscreen_layout_width'] = array(
    '#type'  => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('bigscreen_layout_width'),
    '#options' => $bigscreen_width_options,
  );
  $form['at']['bigscreen']['bigscreen-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#description' => t('<strong>Set a max width for responsive layouts</strong>'),
    '#weight' => 2,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-page-max-width-wrapper']['bigscreen_layout_max_width'] = array(
    '#type'  => 'select',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('bigscreen_layout_max_width'),
    '#options' => $bigscreen_width_options,
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
  $form['at']['tablet']['landscape']['tablet-landscape-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape width'),
    '#description' => t('<strong>Set the overall page width</strong>'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-width-wrapper']['tablet_landscape_width'] = array(
    '#type' => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_landscape_width'),
    '#options' => $tablet_width_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
    '#description' => t('<strong>Set sidebar widths</strong>'),
    '#weight' => 1,
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']['tablet_sidebar_first_landscape_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_first_landscape_adaptive'),
    '#options' => $tablet_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']['tablet_sidebar_second_landscape_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_second_landscape_adaptive'),
    '#options' => $tablet_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
      'disabled' => array(
        'input[name="tablet_landscape_layout"]' => array('value' => 'two-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']['tablet_sidebar_first_landscape_responsive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_first_landscape_responsive'),
    '#options' => $tablet_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-sidebar-width-wrapper']['tablet_landscape_layout']['tablet_sidebar_second_landscape_responsive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_second_landscape_responsive'),
    '#options' => $tablet_sidebar_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
      'disabled' => array(
        'input[name="tablet_landscape_layout"]' => array('value' => 'two-col-stack'),
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
      'one-col-stack' => t('One column'),
      'one-col-vert'  => t('Sidebars in two vertical columns below the main column'),
    )
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
    '#description' => t('<strong>Set sidebar widths</strong>'),
    '#weight' => 1,
    '#states' => array(
      'disabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait width'),
    '#description' => t('<strong>Set the overall page width</strong>'),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-width-wrapper']['tablet_portrait_width'] = array(
    '#type' => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_portrait_width'),
    '#options' => $tablet_width_options_portrait,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']['tablet_sidebar_first_portrait_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_first_portrait_adaptive'),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']['tablet_sidebar_second_portrait_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_second_portrait_adaptive'),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
      'disabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'two-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']['tablet_sidebar_first_portrait_responsive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_first_portrait_responsive'),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
      'disabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-sidebar-width-wrapper']['tablet_portrait_layout']['tablet_sidebar_second_portrait_responsive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_sidebar_second_portrait_responsive'),
    '#options' => $tablet_sidebar_options_portrait,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
      'disabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
      '!enabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'two-col-stack'),
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
  $form['at']['smartphone']['landscape']['smartphone-landscape-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape width'),
    '#description' => t('<strong>Set the overall page width</strong>'),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-width-wrapper']['smartphone_landscape_width'] = array(
    '#type' => 'select',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('smartphone_landscape_width'),
    '#options' => $smartphone_width_options_landscape,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar width'),
    '#description' => t('<strong>Set sidebar width</strong>'),
    '#states' => array(
      'disabled' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
    '#weight' => 1,
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']['smartphone_sidebar_first_landscape_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('smartphone_sidebar_first_landscape_adaptive'),
    '#options' => $smartphone_sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']['smartphone_sidebar_second_landscape_adaptive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('smartphone_sidebar_second_landscape_adaptive'),
    '#options' => $smartphone_sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'adaptive'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']['smartphone_sidebar_first_landscape_responsive'] = array(
    '#type' => 'select',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('smartphone_sidebar_first_landscape_responsive'),
    '#options' => $smartphone_sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-sidebar-width-wrapper']['smartphone_landscape_layout']['smartphone_sidebar_second_landscape_responsive'] = array(
    '#type' => 'select',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('smartphone_sidebar_second_landscape_responsive'),
    '#options' => $smartphone_sidebar_options,
    '#states' => array(
      'visible' => array(
        'select[name="layout_type"]' => array('selected' => 'selected', 'value' => 'responsive'),
      ),
    ),
  );
  // smartphone portrait
  $form['at']['smartphone']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Portrait Layout'),
    '#description' => t('<h3>Portrait Smartphone</h3><p>In portrait content always displays in one column - sidebars automatically stack below the main content column. This is the same as the "One column" setting for landscape orientation.</p>'),
  );
  $form['at']['smartphone']['portrait']['smartphone_portrait_layout'] = array(
    '#type' => 'hidden',
    '#value' => 'one-col-stack',
  );
  // layout type - adaptive or responsive
  $form['at']['layout_master'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout Defaults'),
  );
  $form['at']['layout_master']['type'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout Type'),
    '#description' => t('<h3>Select Adaptive or Resonsive Layout</h3><p>Adaptive and responsive layouts allow your theme to change dynamically depending on the size of the users device, such as smartphones, tablets, bigscreens and desktop computers. Adaptive layouts use fixed width layouts while responsive layouts use fluid widths.</p><p><strong>If you change this setting you must save the theme settings before altering any other settings.</strong></p>'),
  );
  $form['at']['layout_master']['type']['layout_type'] = array(
    '#type' => 'select',
    '#title' => t('Layout type'),
    '#default_value' => theme_get_setting('layout_type'),
    '#options' => array(
      'adaptive' => t('Adaptive'),
      'responsive' => t('Responsive'),
    )
  );
  // Media queries
  $form['at']['layout_master']['media_queries'] = array(
    '#type' => 'fieldset',
    '#title' => t('Media Queries'),
    '#description' => t('<h3>Media Queries</h3><p>You can override the default media queries for each class of device/screen size. Because both smartphones and tablets can rotate the screen orientation both landscape and portrait orientations are supported.<p></p>This is a basic implimentation of media queries and you may need to change them depending on your requirements.</p>'),
    '#weight' => 102,
  );
  // bigscreen breakpoints
  $form['at']['layout_master']['media_queries']['bigscreen'] = array(
    '#type' => 'fieldset',
    '#title' => t('Srandard Screen Media Queries'),
  );
  $form['at']['layout_master']['media_queries']['bigscreen']['bigscreen_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override Standard screen media queries'),
    '#default_value' => theme_get_setting('bigscreen_override_media_query'),
  );
  $form['at']['layout_master']['media_queries']['bigscreen']['bigscreen_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query'),
    '#size' => 100,
    '#default_value' => theme_get_setting('bigscreen_media_query'),
    '#states' => array(
      'visible' => array(
        'input[name="bigscreen_override_media_query"]' => array('checked' => TRUE),
      ),
    ),
  );
  // tablet breakpoints
  $form['at']['layout_master']['media_queries']['tablet'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Media Queries'),
  );
  $form['at']['layout_master']['media_queries']['tablet']['tablet_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override Tablet media queries'),
    '#default_value' => theme_get_setting('tablet_override_media_query'),
  );
  $form['at']['layout_master']['media_queries']['tablet']['tablet_landscape_media_query'] = array(
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
  $form['at']['layout_master']['media_queries']['tablet']['tablet_portrait_media_query'] = array(
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
  // smartphone breakpoints
  $form['at']['layout_master']['media_queries']['smartphone'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Media Queries'),
  );
  $form['at']['layout_master']['media_queries']['smartphone']['smartphone_override_media_query'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override Smartphone media queries'),
    '#default_value' => theme_get_setting('smartphone_override_media_query'),
  );
  $form['at']['layout_master']['media_queries']['smartphone']['smartphone_landscape_media_query'] = array(
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
  $form['at']['layout_master']['media_queries']['smartphone']['smartphone_portrait_media_query'] = array(
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
  // debug
  $message = '';
  if (variable_get('preprocess_css', '') == 1 && theme_get_setting('debug_media_queries') == 1) {
    $message = t('<p class="message warning">CSS aggregation is ON, for optimal performance uncheck this this setting.</p>');
  }
  if (variable_get('preprocess_css', '') == 1 && theme_get_setting('debug_media_queries') == 0) {
    $message = t('<p class="message warning">CSS aggregation is ON, leave this setting unchecked as its not required.</p>');
  }
  $form['at']['layout_master']['media_queries']['debug'] = array(
    '#type' => 'fieldset',
    '#title' => t('Debug Media Queries'),
    '#description' => t('<h3>Debug Media Queries</h3><p>When CSS aggregation is off Drupal adds all stylesheets using the @import method, this setting will load it using the link element without the @import method, however it will also be exempt from normal aggregation. This is needed so respond.js can parse the CSS file (to provide graceful degradation for media queries in < IE8) since respond.js cannot parse CSS files loaded using the @import method.</p>') . $message,
  );
  $form['at']['layout_master']['media_queries']['debug']['debug_media_queries'] = array(
    '#type' => 'checkbox',
    '#title' => 'Debug media queries in IE8 or lower',
    '#default_value' => theme_get_setting('debug_media_queries'),
  );
  // Breadcrumbs
  $form['at']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#weight' => '96',
    '#title' => t('Breadcrumbs'),
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
  // Custom submit function
  $form['#submit'][] = 'at_theme_settings_submit';

  //kpr($form);
}

function at_theme_settings_submit($form, &$form_state) {

  // Set variables for the language direction, if someone can figure out
  // how to get the global $language here I can support RTL in two seconds, otherwise....
  $lang = 'ltr';

  // just in case, for some weird ass reason $form_state values are empty, set a variable...
  $values = '';
  $values = $form_state['values'];

  // set variables for the layout and unit types
  $layout_type = '';
  if ($values['layout_type'] == 'adaptive') {
    $layout_type = 'adaptive';
    $unit = 'px';
  }
  if ($values['layout_type'] == 'responsive') {
    $layout_type = 'responsive';
    $unit = '%';
  }
  // when switching layout types its not set when the settings form first reloads...
  if (isset($layout_type)) {
    $layouts = array();
    if ($values['smartphone_portrait_layout']) {
      $method         = 'one-col-stack';
      $sidebar_first  = '100%';
      $sidebar_second = '100%';
      $media_query    = check_plain($values['smartphone_portrait_media_query']);
      $layout         = at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit);
      $width          = "\n" . '.container {width: 100%;}';
      $comment        = "/* Smartphone portrait - $method, $layout_type */\n";
      
      $styles = implode("\n", $layout) . $width;
      $css = $comment . '@media ' . $media_query . ' {' . "\n" . $styles . "\n" . '}';
      $layouts[] = $css;
    }
    if ($values['smartphone_landscape_layout']) {
      $method         = $values['smartphone_landscape_layout'];
      $sidebar_first  = $values["smartphone_sidebar_first_landscape_$layout_type"];
      $sidebar_second = $values["smartphone_sidebar_second_landscape_$layout_type"];
      $media_query    = check_plain($values['smartphone_landscape_media_query']);
      $layout         = at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit);
      $comment        = "/* Smartphone landscape - $method, $layout_type */\n";

      if ($layout_type == 'responsive') {
        $width = "\n" . '.container {width: 100%;}';
      }
      if ($layout_type == 'adaptive') {
        $width = "\n" . '.container {width: ' . $values['smartphone_landscape_width'] . ';}';
      }

      $styles = implode("\n", $layout) . $width;
      $css = $comment . '@media ' . $media_query . ' {' . "\n" . $styles . "\n" . '}';
      $layouts[] = $css;
    }
    if ($values['tablet_portrait_layout']) {
      $method         = $values['tablet_portrait_layout'];
      $sidebar_first  = $values["tablet_sidebar_first_portrait_$layout_type"];
      $sidebar_second = $values["tablet_sidebar_second_portrait_$layout_type"];
      $media_query    = check_plain($values['tablet_portrait_media_query']);
      $layout         = at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit);
      $comment        = "/* Tablet portrait - $method, $layout_type */\n";

      if ($layout_type == 'responsive') {
        $width = "\n" . '.container {width: 100%;}';
      }
      if ($layout_type == 'adaptive') {
        $width = "\n" . '.container {width: ' . $values['tablet_portrait_width'] . ';}';
      }

      $styles = implode("\n", $layout) . $width;
      $css = $comment . '@media ' . $media_query . ' {' . "\n" . $styles . "\n" . '}';
      $layouts[] = $css;
    }
    if ($values['tablet_landscape_layout']) {
      $method         = $values['tablet_landscape_layout'];
      $sidebar_first  = $values["tablet_sidebar_first_landscape_$layout_type"];
      $sidebar_second = $values["tablet_sidebar_second_landscape_$layout_type"];
      $media_query    = check_plain($values['tablet_landscape_media_query']);
      $layout         = at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit);
      $comment        = "/* Tablet landscape - $method, $layout_type */\n";

      if ($layout_type == 'responsive') {
        $width = "\n" . '.container {width: 100%;}';
      }
      if ($layout_type == 'adaptive') {
        $width = "\n" . '.container {width: ' . $values['tablet_landscape_width'] . ';}';
      }
      
      $styles = implode("\n", $layout) . $width;
      $css = $comment . '@media ' . $media_query . ' {' . "\n" . $styles . "\n" . '}';
      $layouts[] = $css;
    }
    if ($values['bigscreen_layout']) {
      $method         = $values['bigscreen_layout'];
      $sidebar_first  = $values["bigscreen_sidebar_first_$layout_type"];
      $sidebar_second = $values["bigscreen_sidebar_second_$layout_type"];
      $media_query    = check_plain($values['bigscreen_media_query']);
      $layout         = at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit);
      $comment        = "/* Standard layout - $method, $layout_type */\n";

      if ($layout_type == 'responsive') {
        $max_width = $values['bigscreen_layout_max_width'];
        $width = "\n" . '.container {width: 100%; max-width: ' . $max_width . ';}';
      }
      if ($layout_type == 'adaptive') {
        $width = "\n" . '.container {width: ' . $values['bigscreen_layout_width'] . ';}';
      }

      $styles = implode("\n", $layout) . $width;
      $css = $comment . '@media ' . $media_query . ' {' . "\n" . $styles . "\n" . '}';
      $layouts[] = $css;
    }
    $layout_data = implode("\n",$layouts);
  }

  $theme = $form_state['build_info']['args'][0];
  $file  = $theme . '_mediaqueries.css';
  $path  = "public://at_css";
  $data  = $layout_data;

  file_prepare_directory($path, FILE_CREATE_DIRECTORY);

  $filepath = $path . '/' . $file;
  file_save_data($data, $filepath, FILE_EXISTS_REPLACE);
  drupal_chmod($file);

  // set variables so we can retrive them later to load the css file
  variable_set($theme . '_mediaqueries_path', $path);
  variable_set($theme . '_mediaqueries_css', $file);
}

// Process layout styles
function at_layout_styles($method, $sidebar_first, $sidebar_second, $lang, $unit) {

  // Set variables for language direction
  $left = 'left';
  $right = 'right';
  if ($lang == 'rtl') {
    $left = 'right';
    $right = 'left';
  }

  // build the sytle arrays, params are passed to the function from preprocess_html
  $styles = array();
  if ($method == 'three-col-grail') {
    $push_right = $sidebar_second;
    $push_left  = $sidebar_first;
    $pull_right = $sidebar_second;
    $styles[] = '.two-sidebars .content-inner {margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': ' . $push_right . ';}';
    $styles[] = '.sidebar-first .content-inner {margin-' . $left . ': ' . $push_left . '; margin-' . $right . ': 0;}';
    $styles[] = '.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0;}';
    $styles[] = '.region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -100%;}';
    $styles[] = '.region-sidebar-second {width: ' . $sidebar_second . '; margin-' . $left . ': -' . $pull_right . '; clear: none;}';
  }
  if ($method == 'three-col-right') {
    $content_margin = $sidebar_second + $sidebar_first . $unit;
    $push_right     = $sidebar_second;
    $push_left      = $sidebar_first;
    $left_margin    = $sidebar_second + $sidebar_first . $unit;
    $right_margin   = $sidebar_second;
    $styles[] = '.two-sidebars .content-inner {margin-' . $right . ': ' . $content_margin . '; margin-'. $left . ': 0;}';
    $styles[] = '.sidebar-first .content-inner {margin-' . $right . ': ' . $push_left . '; margin-' . $left . ': 0;}';
    $styles[] = '.sidebar-second .content-inner {margin-' . $right . ': ' . $push_right . '; margin-' . $left . ': 0;}';
    $styles[] = '.region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -' . $left_margin . ';}';
    $styles[] = '.region-sidebar-second {width: ' . $sidebar_second . '; margin-' . $left . ': -' . $right_margin . '; clear: none;}';
    $styles[] = '.sidebar-first .region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -' . $sidebar_first . ';}';
  }
  if ($method == 'three-col-left') {
    $content_margin = $sidebar_second + $sidebar_first . $unit;
    $left_margin    = $sidebar_first;
    $right_margin   = $sidebar_second;
    $push_right     = $sidebar_first;
    $styles[] = '.two-sidebars .content-inner {margin-' . $left . ': ' . $content_margin . '; margin-' . $right . ': 0;}';
    $styles[] = '.sidebar-first .content-inner {margin-' . $left . ': ' . $left_margin . '; margin-' . $right . ': 0;}';
    $styles[] = '.sidebar-second .content-inner {margin-' . $left . ': ' . $right_margin . '; margin-' . $right . ': 0;}';
    $styles[] = '.region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -100%;}';
    $styles[] = '.region-sidebar-second {width: ' . $sidebar_second . '; margin-' . $left . ': -100%; clear: none;}';
    $styles[] = '.two-sidebars .region-sidebar-second {width: ' . $sidebar_second . '; position: relative; ' . $left . ': ' . $push_right . ' ;}';
  }
  if ($method == 'two-col-stack') {
    $push_right = $sidebar_first;
    $styles[] = '.two-sidebars .content-inner,.sidebar-first .content-inner {margin-' . $left . ': 0; margin-' . $right . ': ' . $push_right . ';}';
    $styles[] = '.sidebar-second .content-inner {margin-right: 0; margin-left: 0;}';
    $styles[] = '.region-sidebar-first {width: ' . $sidebar_first . '; margin-' . $left . ': -' . $push_right . ';}';
    $styles[] = '.region-sidebar-second {width: 100%; margin-left: 0; margin-right: 0; margin-top: 20px; clear: both; overflow: hidden;}';
    $styles[] = '.region-sidebar-second .block {float: left; clear: none;}';
  }
  if ($method == 'one-col-stack') {
    $styles[] = '.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left: 0; margin-right: 0;}';
    $styles[] = '.region-sidebar-first, .region-sidebar-second, .region-sidebar-first .block, .region-sidebar-second .block {width: 100%;}';
    $styles[] = '.region-sidebar-second {width: 100%;}';
    $styles[] = '.content-inner,.region-sidebar-first,.region-sidebar-second {float: none;}';
    $styles[] = '.region-sidebar-first, .region-sidebar-second {clear: both;}';
  }
  if ($method == 'one-col-vert') {
    $styles[] = '.two-sidebars .content-inner,.one-sidebar .content-inner,.region-sidebar-first,.region-sidebar-second {margin-left: 0; margin-right: 0;}';
    $styles[] = '.region-sidebar-first {width: ' . $sidebar_first . ';}';
    $styles[] = '.region-sidebar-second {width: ' . $sidebar_second . ';}';
    $styles[] = '.region-sidebar-first, .region-sidebar-second {overflow: hidden; margin-top: 20px;}';
    $styles[] = '.region-sidebar-first .block, .region-sidebar-second .block {width: 100%;}';
  }
  return $styles;
}
