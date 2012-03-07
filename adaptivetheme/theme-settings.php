<?php
/**
 * Implements hook_form_system_theme_settings_alter().
 */
function adaptivetheme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  if (isset($form_id)) {
    return;
  }
  
  // Get the admin theme so we can set an attribute class based on it
  $admin_theme = variable_get('admin_theme');

  // Build a custom header for the layout settings form
  $layout_header  = '<div class="at-settings-form layout-settings-form admin-theme-'. $admin_theme .'"><div class="layout-header theme-settings-header clearfix">';
  $layout_header .= '<h1>' . t('Layout Settings') . '</h1>';
  $layout_header .= '<a href="http://adaptivethemes.com" target="_blank"><img class="at-logo" src="' . drupal_get_path('theme', 'adaptivetheme') . '/logo.png" /></a>';
  $layout_header .= '</div>';
  // Layout settings
  $form['at-layout'] = array(
    '#type' => 'vertical_tabs',
    '#description' => t('Layout Settings'),
    '#prefix' => $layout_header,
    '#suffix' => '</div>',
    '#weight' => -10,
    '#default_tab' => 'defaults',
    '#attached' => array(
      'css' => array(drupal_get_path('theme', 'adaptivetheme') . '/css/at.settings.form.css'),
    ),
  );
  // bigscreen
  $form['at-layout']['bigscreen'] = array(
    '#type' => 'fieldset',
    '#title' => t('Standard Layout'),
    '#description' => t('<h3>Standard Layout</h3><p>The standard layout is for desktops, laptops and other large screen devices.'),
    '#attributes' => array(
      'class' => array(
        'at-layout-form',
      ),
    ),
  );
  $form['at-layout']['bigscreen']['bigscreen-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at-layout']['bigscreen']['bigscreen-layout-wrapper']['bigscreen_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('bigscreen_layout'),
    '#options' => array(
      'three-col-grail' => t('<span>Standard three column</span>'),
      'three-col-right' => t('<span>Two sidebars right</span>'),
      'three-col-left'  => t('<span>Two sidebars left</span>'),
    )
  );
  $form['at-layout']['bigscreen']['bigscreen-sidebar-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
  );
  $form['at-layout']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at-layout']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at-layout']['bigscreen']['bigscreen-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at-layout']['bigscreen']['bigscreen-width-wrapper']['bigscreen_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('bigscreen_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['bigscreen']['bigscreen-width-wrapper']['bigscreen_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('bigscreen_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at-layout']['bigscreen']['bigscreen-maxwidth-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="bigscreen_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at-layout']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('bigscreen_set_max_width'),
  );
  $form['at-layout']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_max_width_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('bigscreen_max_width_unit'),
    '#options' => array(
      'px' => 'px',
      'em' => 'em',
    ),
    '#states' => array(
      'visible' => array(
        'input[name="bigscreen_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_max_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('bigscreen_max_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'visible' => array(
        'input[name="bigscreen_set_max_width"]' => array('checked' => TRUE),
      ),
      'required' => array(
        'input[name="bigscreen_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['bigscreen']['media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Standard Screen Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at-layout']['bigscreen']['media-queries-wrapper']['bigscreen_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('bigscreen_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // tablet
  $form['at-layout']['tablet'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Layout'),
    '#description' => t('<h3>Tablet Layout</h3><p>Tablet devices such as iPad have two orientations - landscape and portrait. You can configure a different layout for each orientation.</p>'),
    '#attributes' => array(
      'class' => array(
        'at-layout-form',
      ),
    ),
  );
  // landscape
  $form['at-layout']['tablet']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape tablet</h4>'),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-layout-wrapper']['tablet_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('tablet_landscape_layout'),
    '#options' => array(
      'three-col-grail' => t('<span>Standard three column</span>'),
      'three-col-right' => t('<span>Two sidebar right</span>'),
      'three-col-left'  => t('<span>Two sidebar left</span>'),
      'two-col-stack'   => t('<span>Sidebar second stacked</span>'),
    )
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_landscape_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_landscape_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_landscape_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
    '#states' => array(
      'disabled' => array(
        'input[name="tablet_landscape_layout"]' => array('value' => 'two-col-stack'),
      ),
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-width-wrapper']['tablet_landscape_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_landscape_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-width-wrapper']['tablet_landscape_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_landscape_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  /*
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="tablet_landscape_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('tablet_landscape_set_max_width'),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_max_width_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_landscape_max_width_unit'),
    '#options' => array(
      'px' => 'px',
      'em' => 'em',
    ),
    '#states' => array(
      'visible' => array(
        'input[name="tablet_landscape_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_max_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('tablet_landscape_max_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'visible' => array(
        'input[name="tablet_landscape_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  */
  $form['at-layout']['tablet']['landscape']['tablet-landscape-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Landscape Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at-layout']['tablet']['landscape']['tablet-landscape-media-queries-wrapper']['tablet_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('tablet_landscape_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // tablet portrait
  $form['at-layout']['tablet']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait'),
    '#description' => t('<h4>Portrait tablet</h4>'),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-layout-wrapper']['tablet_portrait_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('tablet_portrait_layout'),
    '#options' => array(
      'one-col-stack' => t('<span>Sidebars stacked</span>'),
      'one-col-vert'  => t('<span>Sidebars wrapped</span>'),
      'two-col-stack' => t('<span>Second sidebar stacked</span>'),
    )
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#states' => array(
      '!visible' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_portrait_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_portrait_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('tablet_portrait_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
    '#states' => array(
      'disabled' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'two-col-stack'),
      ),
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-width-wrapper']['tablet_portrait_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_portrait_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-width-wrapper']['tablet_portrait_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_portrait_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  /*
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="tablet_portrait_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('tablet_portrait_set_max_width'),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_max_width_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_portrait_max_width_unit'),
    '#options' => array(
      'px' => 'px',
      'em' => 'em',
    ),
    '#states' => array(
      'visible' => array(
        'input[name="tablet_portrait_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_max_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('tablet_portrait_max_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'visible' => array(
        'input[name="tablet_portrait_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  */
  $form['at-layout']['tablet']['portrait']['tablet-portrait-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Portrait Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at-layout']['tablet']['portrait']['tablet-portrait-media-queries-wrapper']['tablet_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('tablet_portrait_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // smartphone
  $form['at-layout']['smartphone'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Layout'),
    '#description' => t('<h3>Smartphone Layout</h3><p>Smartphone devices such as iPhone, Android and Windows phones have two orientations - landscape and portrait. You can configure a layout for landscape orientation only - portrait orientation always displays in one column with sidebars stacked below the main content.</p>'),
    '#attributes' => array(
      'class' => array(
        'at-layout-form',
      ),
    ),
  );
  // landscape
  $form['at-layout']['smartphone']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape smartphone</h4>'),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-layout-wrapper']['smartphone_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('smartphone_landscape_layout'),
    '#options' => array(
      'one-col-stack' => t('<span>One column stacked</span>'),
      'one-col-vert'  => t('<span>Sidebars wrapped</span>'),
    )
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#states' => array(
      '!visible' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array('required' => array(
        'input[id="edit-smartphone-landscape-layout-one-col-vert"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'required' => array(
        'input[id="edit-smartphone-landscape-layout-one-col-vert"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper']['smartphone_landscape_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('smartphone_landscape_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper']['smartphone_landscape_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('smartphone_landscape_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  /*
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="smartphone_landscape_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('smartphone_landscape_set_max_width'),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_max_width_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('smartphone_landscape_max_width_unit'),
    '#options' => array(
      'px' => 'px',
      'em' => 'em',
    ),
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_landscape_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_max_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('smartphone_landscape_max_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'visible' => array(
        'input[name="smartphone_landscape_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  */
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Landscape Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at-layout']['smartphone']['landscape']['smartphone-landscape-media-queries-wrapper']['smartphone_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('smartphone_landscape_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // smartphone portrait
  $form['at-layout']['smartphone']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait'),
    '#description' => t('<h4>Portrait smartphone</h4><div class="smartphone-portrait-layout">One column</div><p>The smartphone portrait layout always displays in one column with sidebars stacked horizontally below the main content. All widths are always 100%.</p>'),
  );
  $form['at-layout']['smartphone']['portrait']['smartphone-portrait-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Portrait Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at-layout']['smartphone']['portrait']['smartphone-portrait-media-queries-wrapper']['smartphone_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('smartphone_portrait_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
  );
  // AT PANELS LAYOUTS
    $form['at-layout']['rp'] = array(
    '#type' => 'fieldset',
    '#title' => t('Responsive Panels'),
  );
  $form['at-layout']['rp']['rpw'] = array(
    '#type' => 'fieldset',
    '#attributes' => array('class' => array('panel-option-lists')),
    '#description' => t('<h3>Responsive Panels</h3><p>These settings apply to both <a href="!gpanels_link" target="_blank">Gpanels</a> and <a href="!panels_link" target="_blank">Panels module</a> layouts.</p><p><strong>Usage:</strong> select layout options for each mobile device orientation.</p>', array('!panels_link' => 'http://drupal.org/project/panels', '!gpanels_link' => 'http://adaptivethemes.com/documentation/using-gpanels')),
  );
  // TABLET landscape
  $form['at-layout']['rp']['rpw']['tl'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet landscape'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['tl']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('tablet_landscape_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('tablet_landscape_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('tablet_landscape_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('tablet_landscape_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL
  $form['at-layout']['rp']['rpw']['tl']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('tablet_landscape_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('tablet_landscape_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('tablet_landscape_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('tablet_landscape_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['tl']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['fourcol']['tablet_landscape_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('tablet_landscape_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['tl']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['fivecol']['tablet_landscape_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('tablet_landscape_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['tl']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['sixcol']['tablet_landscape_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('tablet_landscape_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['tl']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['tl']['inset']['tablet_landscape_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('tablet_landscape_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['tl']['inset']['tablet_landscape_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('tablet_landscape_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );
  // TABLET portrait
  $form['at-layout']['rp']['rpw']['tp'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet portrait'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['tp']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('tablet_portrait_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('tablet_portrait_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('tablet_portrait_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('tablet_portrait_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL TABLET PORTRAIT
  $form['at-layout']['rp']['rpw']['tp']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('tablet_portrait_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('tablet_portrait_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('tablet_portrait_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('tablet_portrait_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['tp']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['fourcol']['tablet_portrait_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('tablet_portrait_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['tp']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['fivecol']['tablet_portrait_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('tablet_portrait_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['tp']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['sixcol']['tablet_portrait_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('tablet_portrait_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['tp']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['tp']['inset']['tablet_portrait_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('tablet_portrait_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['tp']['inset']['tablet_portrait_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('tablet_portrait_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );
  // SMARTPHONE landscape
  $form['at-layout']['rp']['rpw']['sl'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone landscape'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['sl']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL
  $form['at-layout']['rp']['rpw']['sl']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['sl']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['fourcol']['smartphone_landscape_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('smartphone_landscape_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['sl']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['fivecol']['smartphone_landscape_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('smartphone_landscape_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['sl']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['sixcol']['smartphone_landscape_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('smartphone_landscape_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['sl']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['sl']['inset']['smartphone_landscape_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('smartphone_landscape_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['sl']['inset']['smartphone_landscape_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('smartphone_landscape_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );
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
  /*
  $form['at-layout']['global-settings']['mode']['disable_mode_warning'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable the annoying warning message about development mode'),
    '#default_value' => theme_get_setting('disable_mode_warning'),
  );
  */
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
  // DEBUG
  $form['at-layout']['debug'] = array(
    '#type' => 'fieldset',
    '#title' => t('Debug'),
  );
  $form['at-layout']['debug']['debug-layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Debug Layout'),
    '#description' => t('<h3>Debug Layout</h3>'),
  );
  $form['at-layout']['debug']['debug-layout']['expose_regions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Highlight regions'),
    '#default_value' => theme_get_setting('expose_regions'),
  );
  $form['at-layout']['debug']['debug-layout']['load_all_panels'] = array(
    '#type' => 'checkbox',
    '#title' => t('Replace the front page with panels_test.html - useful for studying and debugging Responsive Panels.'),
    '#default_value' => theme_get_setting('load_all_panels'),
  );
  $form['at-layout']['debug']['debug-layout']['show_window_size'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show window size - appears in the bottom left corner.'),
    '#default_value' => theme_get_setting('show_window_size'),
  );
  // All media queries for copy/pastings if you need them
  // build array for media query display
  $queries = '';
  $mq = array();
  $mq[] =  '/* Smartphone portrait */' . "\n" . '@media ' . theme_get_setting('smartphone_portrait_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Smartphone landscape */' . "\n" . '@media ' . theme_get_setting('smartphone_landscape_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Tablet portrait */' . "\n" . '@media ' . theme_get_setting('tablet_portrait_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Tablet landscape */' . "\n" . '@media ' . theme_get_setting('tablet_landscape_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Standard layout */' . "\n" . '@media ' . theme_get_setting('bigscreen_media_query') . ' {' . "\n" . '}';
  $queries = implode("\n\n", $mq);
  $form['at-layout']['debug']['mediaqueries'] = array(
    '#type' => 'fieldset',
    '#title' => t('<h3>All Media Queries - Copy Only!</h3>'),
    '#description' => t('<h3>All Media Queries - Copy Only</h3><p>These are the media queries currently being used by your theme. This is provided so you may save a backup copy for reference. Do not enter anything here - this is display only!</p>'),
  );
  $form['at-layout']['debug']['mediaqueries']['check'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable the text field so I can copy this now.'),
  );
  $form['at-layout']['debug']['mediaqueries']['output'] = array(
    '#type' => 'textarea',
    '#rows' => 18,
    '#default_value' => $queries ? $queries : '',
    '#states' => array(
      'disabled' => array(
          'input[name="check"]' => array('checked' => FALSE),
      ),
    ),
  );
  // STYLE SETTINGS
  // Build a custom header for the style settings form
  $styles_header  = '<div class="at-settings-form style-settings-form admin-theme-'. $admin_theme .'"><div class="styles-header theme-settings-header clearfix">';
  $styles_header .= '<h1>' . t('Style Settings') . '</h1>';
  $styles_header .= '</div>';
  $form['at'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -9,
    '#prefix' => $styles_header,
    '#suffix' => '</div>',
    '#default_tab' => 'defaults',
  );
  // Breadcrumbs
  $form['at']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#weight' => 96,
    '#title' => t('Breadcrumbs'),
  );
  $form['at']['breadcrumb']['bd'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumbs'),
    '#description' => t('<h3>Breadcrumb Settings</h3>'),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_display'] = array(
    '#type' => 'select',
    '#title' => t('Show breadcrumbs'),
    '#default_value' => theme_get_setting('breadcrumb_display'),
    '#options' => array(
      'yes' => t('Yes'),
      'no' => t('No'),
    ),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_separator'] = array(
    '#type'  => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Text only. Dont forget to include spaces.'),
    '#default_value' => theme_get_setting('breadcrumb_separator'),
    '#size' => 8,
    '#maxlength' => 10,
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array('value' => 'yes'),
      ),
    ),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_home'] = array(
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
  $form['at']['search-results'] = array(
    '#type' => 'fieldset',
    '#weight' => 97,
    '#title' => t('Search Results'),
  );
  $form['at']['search-results']['srs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results Display'),
    '#description' => t('<h3>Search Result Display</h3><p>Modify what is displayed below each search result</p>'),
  );
  $form['at']['search-results']['srs']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );
  $form['at']['search-results']['srs']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );
  $form['at']['search-results']['srs']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );
  $form['at']['search-results']['srs']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );
  $form['at']['search-results']['srs']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );
  $form['at']['search-results']['srs']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );
  // Search_info_separator
  $form['at']['search-results']['srs']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Modify the separator between each of the above items. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );
  // Horizonatal login block
  if (theme_get_setting('horizontal_login_block_enable') == 'on') {
    $form['at']['login-block'] = array(
      '#type' => 'fieldset',
      '#weight' => 99,
      '#title' => t('Login Block'),
    );
    $form['at']['login-block']['hlb'] = array(
      '#type' => 'fieldset',
      '#title' => t('Login Block'),
      '#description' => t('<h3>Login Block Options</h3>'),
    );

    $form['at']['login-block']['hlb']['horizontal_login_block'] = array(
      '#type' => 'checkbox',
      '#title' => t('Horizontal Login Block'),
      '#default_value' => theme_get_setting('horizontal_login_block'),
      '#description' => t('Checking this setting will enable a horizontal style login block (all elements on one line). Note that if you are using OpenID this does not work well and you will need a more sophistocated approach than can be provided here.'),
    );
  } // endif horizontal block settings
    // Comments
  $form['at']['site-tweaks'] = array(
    '#type' => 'fieldset',
    '#weight' => 100,
    '#title' => t('Site Tweaks'),
  );
  // Comments
  $form['at']['site-tweaks']['comments'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comments'),
    '#description' => t('<h3>Hide Comment Title</h3>'),
  );
  $form['at']['site-tweaks']['comments']['comments_hide_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide the comment title'),
    '#default_value' => theme_get_setting('comments_hide_title'),
    '#description' => t('Checking this setting will hide comment titles using element-invisible. Hiding rather than removing titles maintains accessibility and semantic structure while not showing titles to sighted users.'),
  );
  // Feed icons
  $form['at']['site-tweaks']['feed-icons'] = array(
    '#type' => 'fieldset',
    '#title' => t('Feed Icons'),
    '#description' => t('<h3>Remove Feed Icons</h3>'),
  );
  $form['at']['site-tweaks']['feed-icons']['feed_icons_hide'] = array(
    '#type' => 'checkbox',
    '#title' => t('Remove RSS feed icons'),
    '#default_value' => theme_get_setting('feed_icons_hide'),
    '#description' => t('Checking this setting will remove RSS feed icons. This will not affect the Syndicate block icon.'),
  );
  // Welcome message
  $form['at']['site-tweaks']['block-system-main'] = array(
    '#type' => 'fieldset',
    '#title' => t('Main content block settings'),
    '#description' => t('<h3>Remove Main Content Block</h3>'),
  );
  $form['at']['site-tweaks']['block-system-main']['unset_block_system_main_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Do not display the Main content block on the front page'),
    '#default_value' => theme_get_setting('unset_block_system_main_front'),
    '#description' => t('Checking this setting will remove the Main content block from the front page only - useful for removing the welcome message and allowing use of another block.'),
  );
  // Menu Links Settings
  $form['at']['site-tweaks']['menu-links'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add Span tags menu items'),
    '#description' => t('<h3>Menu Item Span Tags</h3>'),
  );
  // Add spans to theme_links
  $form['at']['site-tweaks']['menu-links']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu item text in SPAN tags - useful for certain theme or design related techniques'),
    '#description' => t('Note: this does not work for Superfish menus, which includes its own feature for doing this.'),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );
  // Designers settings
  $form['at']['classes'] = array(
    '#type' => 'fieldset',
    '#weight' => 102,
    '#title' => t('CSS Classes'),
  );
  $form['at']['classes']['extra-classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Extra Classes'),
    '#description' => t('<h3>Extra CSS Classes</h3>'),
  );
  $form['at']['classes']['extra-classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pages: ') . '<span class="description">' . t('add page-path, add/edit/delete (for workflow states), content type classes, section classes and a language class (i18n).') . '</span>',
    '#default_value' => theme_get_setting('extra_page_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Articles: ') . '<span class="description">' . t('add promoted, sticky, preview, language, odd/even classes, user picture handling, and build mode classes such as .article-teaser and .article-full.') . '</span>',
    '#default_value' => theme_get_setting('extra_article_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Comments: ') . '<span class="description">' . t('add anonymous, author, viewer, new, odd/even classes and classes for hidden titles, user picture and signature handling.') . '</span>',
    '#default_value' => theme_get_setting('extra_comment_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Blocks: ') . '<span class="description">' . t('add odd/even (zebra classes), block region and block count classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_block_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Menus: ') . '<span class="description">' . t('add extra classes based on the menu link ID (mlid) and menu level (depth).') . '</span>',
    '#default_value' => theme_get_setting('extra_menu_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Item-lists: ') . '<span class="description">' . t('add first, last and odd/even classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_item_list_classes'),
  );
  $form['theme_settings']['#collapsible'] = TRUE;
  $form['theme_settings']['#collapsed'] = TRUE;
  $form['logo']['#collapsible'] = TRUE;
  $form['logo']['#collapsed'] = TRUE;
  $form['favicon']['#collapsible'] = TRUE;
  $form['favicon']['#collapsed'] = TRUE;
  /**
   * The following will be processed even if the theme is inactive.
   * If you are on a theme specific settings page but it is not an active
   * theme (example.com/admin/apearance/settings/THEME_NAME), it will
   * still be processed.
   *
   * Build a list of themes related to the theme specific form. If the form
   * is specific to a sub-theme, all parent themes leading to it will have
   * hook_form_theme_settings invoked. For example, if a theme named
   * 'grandchild' has its settings form in focus, the following will be invoked.
   * - parent_form_theme_settings()
   * - child_form_theme_settings()
   * - grandchild_form_theme_settings()
   *
   * If 'child' was in focus it will invoke:
   * - parent_form_theme_settings()
   * - child_form_theme_settings()
   */
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
  // Custom validate and submit functions
  $form['#validate'][] = 'at_theme_settings_validate';
  $form['#submit'][] = 'at_theme_settings_submit';
}
// Include custom form validation, submit function and layout builder functions
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/layout.inc');