<?php
// Include custom form validation, submit function and layout builder functions
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/layout.inc');

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

  // Layout settings
  $form['at'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
    '#default_tab' => 'defaults',
    '#attached' => array(
      'css' => array(drupal_get_path('theme', 'adaptivetheme') . '/css/at.settings.form.css'),
    ),
  );
  // bigscreen
  $form['at']['bigscreen'] = array(
    '#type' => 'fieldset',
    '#title' => t('Standard Layout'),
    '#description' => t('<h3>Standard Layout</h3><p>The standard layout is for desktops, laptops and other large screen devices.'),
    '#attributes' => array(
      'class' => array(
        'at-layout-form',
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['bigscreen']['bigscreen-layout-wrapper']['bigscreen_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('bigscreen_layout'),
    '#options' => array(
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
    )
  );
  $form['at']['bigscreen']['bigscreen-sidebar-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['bigscreen']['bigscreen-sidebar-wrapper']['bigscreen_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('bigscreen_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['bigscreen']['bigscreen-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at']['bigscreen']['bigscreen-width-wrapper']['bigscreen_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('bigscreen_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['bigscreen']['bigscreen-width-wrapper']['bigscreen_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('bigscreen_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['bigscreen']['bigscreen-maxwidth-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="bigscreen_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('bigscreen_set_max_width'),
  );
  $form['at']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_max_width_unit'] = array(
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
  $form['at']['bigscreen']['bigscreen-maxwidth-wrapper']['bigscreen_max_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Max width'),
    '#default_value' => theme_get_setting('bigscreen_max_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#states' => array(
      'visible' => array(
        'input[name="bigscreen_set_max_width"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['at']['bigscreen']['media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Standard Screen Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at']['bigscreen']['media-queries-wrapper']['bigscreen_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('bigscreen_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // tablet
  $form['at']['tablet'] = array(
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
  $form['at']['tablet']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape tablet</h4>'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-layout-wrapper']['tablet_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('tablet_landscape_layout'),
    '#options' => array(
      'three-col-grail' => t('Standard three column'),
      'three-col-right' => t('Three column, both sidebars on the right'),
      'three-col-left'  => t('Three column, both sidebars on the left'),
      'two-col-stack'   => t('Two colums, sidebar second stacked below the main column (the second sidebar is full width)'),
    )
  );
  $form['at']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_landscape_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_landscape_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['tablet']['landscape']['tablet-landscape-sidebar-width-wrapper']['tablet_landscape_sidebar_second'] = array(
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
  $form['at']['tablet']['landscape']['tablet-landscape-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-page-width-wrapper']['tablet_landscape_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_landscape_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-page-width-wrapper']['tablet_landscape_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_landscape_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="tablet_landscape_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('tablet_landscape_set_max_width'),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_max_width_unit'] = array(
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
  $form['at']['tablet']['landscape']['tablet-landscape-page-max-width-wrapper']['tablet_landscape_max_width'] = array(
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
  $form['at']['tablet']['landscape']['tablet-landscape-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Landscape Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at']['tablet']['landscape']['tablet-landscape-media-queries-wrapper']['tablet_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('tablet_landscape_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // tablet portrait
  $form['at']['tablet']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait'),
    '#description' => t('<h4>Portrait tablet</h4>'),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-layout-wrapper']['tablet_portrait_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('tablet_portrait_layout'),
    '#options' => array(
      'one-col-stack' => t('One column'),
      'one-col-vert'  => t('Sidebars in two vertical columns below the main column'),
      'two-col-stack' => t('Two colums, sidebar second stacked below the main column (the second sidebar is full width)'),
    )
  );
  $form['at']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#states' => array(
      '!visible' => array(
        'input[name="tablet_portrait_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_portrait_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('tablet_portrait_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['tablet']['portrait']['tablet-portrait-sidebar-width-wrapper']['tablet_portrait_sidebar_second'] = array(
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
  $form['at']['tablet']['portrait']['tablet-portrait-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-page-width-wrapper']['tablet_portrait_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('tablet_portrait_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-page-width-wrapper']['tablet_portrait_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('tablet_portrait_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="tablet_portrait_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('tablet_portrait_set_max_width'),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_max_width_unit'] = array(
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
  $form['at']['tablet']['portrait']['tablet-portrait-page-max-width-wrapper']['tablet_portrait_max_width'] = array(
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
  $form['at']['tablet']['portrait']['tablet-portrait-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet Portrait Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at']['tablet']['portrait']['tablet-portrait-media-queries-wrapper']['tablet_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('tablet_portrait_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // smartphone
  $form['at']['smartphone'] = array(
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
  $form['at']['smartphone']['landscape'] = array(
    '#type' => 'fieldset',
    '#title' => t('Landscape'),
    '#description' => t('<h4>Landscape smartphone</h4>'),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-layout-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Choose sidebar layout'),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-layout-wrapper']['smartphone_landscape_layout'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Choose sidebar positions</strong>'),
    '#default_value' => theme_get_setting('smartphone_landscape_layout'),
    '#options' => array(
      'one-col-stack' => t('One column'),
      'one-col-vert'  => t('Sidebars in two vertical columns below the main column'),
    )
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set sidebar widths'),
    '#description' => t('<strong>Set the width of each sidebar</strong>'),
    '#states' => array(
      '!visible' => array(
        'input[name="smartphone_landscape_layout"]' => array('value' => 'one-col-stack'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_first'] = array(
    '#type' => 'textfield',
    '#title' => t('First sidebar'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_first'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-sidebar-width-wrapper']['smartphone_landscape_sidebar_second'] = array(
    '#type' => 'textfield',
    '#title' => t('Second sidebar'),
    '#default_value' => theme_get_setting('smartphone_landscape_sidebar_second'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set the page width'),
    '#description' => t('<strong>Set the page width</strong>'),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper']['smartphone_landscape_page_unit'] = array(
    '#type' => 'select',
    '#title' => t('Unit'),
    '#default_value' => theme_get_setting('smartphone_landscape_page_unit'),
    '#options' => array(
      'px' => 'px',
      '%' => '%',
      'em' => 'em',
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-width-wrapper']['smartphone_landscape_page_width'] = array(
    '#type'  => 'textfield',
    '#title' => t('Page width'),
    '#default_value' => theme_get_setting('smartphone_landscape_page_width'),
    '#size' => 4,
    '#maxlenght' => 4,
    '#required' => TRUE,
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Set a max width'),
    '#states' => array(
      'visible' => array(
        'select[name="smartphone_landscape_page_unit"]' => array('selected' => 'selected', 'value' => '%'),
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_set_max_width'] = array(
    '#type' => 'checkbox',
    '#title' => t('Set a max width'),
    '#default_value' => theme_get_setting('smartphone_landscape_set_max_width'),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_max_width_unit'] = array(
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
  $form['at']['smartphone']['landscape']['smartphone-landscape-page-max-width-wrapper']['smartphone_landscape_max_width'] = array(
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
  $form['at']['smartphone']['landscape']['smartphone-landscape-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Landscape Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at']['smartphone']['landscape']['smartphone-landscape-media-queries-wrapper']['smartphone_landscape_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('smartphone_landscape_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
    '#required' => TRUE,
  );
  // smartphone portrait
  $form['at']['smartphone']['portrait'] = array(
    '#type' => 'fieldset',
    '#title' => t('Portrait'),
    '#description' => t('<h4>Portrait smartphone</h4><div class="smartphone-portrait-layout">One column</div><p>The smartphone portrait layout always displays in one column with sidebars stacked horizontally below the main content. All widths are always 100%.</p>'),
  );
  $form['at']['smartphone']['portrait']['smartphone-portrait-media-queries-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone Portrait Media Queries'),
    '#weight' => 1,
    '#attributes' => array(
      'class' => array(
        'at-media-queries',
      ),
    ),
  );
  $form['at']['smartphone']['portrait']['smartphone-portrait-media-queries-wrapper']['smartphone_portrait_media_query'] = array(
    '#type' => 'textfield',
    '#title' => t('Media query for this layout'),
    '#default_value' => theme_get_setting('smartphone_portrait_media_query'),
    '#description' => t('Do not include @media, its included automatically.'),
    '#field_prefix' => '@media',
    '#size' => 100,
  );
  // All media queries for copy/pastings if you need them
  // build array for media query display
  $mq = array();
  $mq[] =  '/* Smartphone portrait */' . "\n" . '@media ' . theme_get_setting('smartphone_portrait_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Smartphone landscape */' . "\n" . '@media ' . theme_get_setting('smartphone_landscape_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Tablet portrait */' . "\n" . '@media ' . theme_get_setting('tablet_portrait_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Tablet landscape */' . "\n" . '@media ' . theme_get_setting('tablet_landscape_media_query') . ' {' . "\n" . '}';
  $mq[] =  '/* Standard layout */' . "\n" . '@media ' . theme_get_setting('bigscreen_media_query') . ' {' . "\n" . '}';
  $queries = implode("\n\n", $mq);
  $form['at']['mediaqueries'] = array(
    '#type' => 'fieldset',
    '#title' => t('All Media Queries'),
  );
  $form['at']['mediaqueries']['mq'] = array(
    '#type' => 'fieldset',
    '#title' => t('<h3>All Media Queries - Copy Only!</h3>'),
    '#description' => t('<h3>All Media Queries - Copy Only</h3><p>Copy and paste these to <strong>!themename.responsive.style.css</strong> in the CSS folder in the <em>!themename</em> subtheme. You only need to do this if you have modifed the default media query in one or more of the Standard, Tablet or Smartphone layouts.</p><p>Do not enter anything here - this is display only!</p>', array('!themename' => $GLOBALS['theme_key'])),
  );
  $form['at']['mediaqueries']['mq']['check'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable the text field so I can copy this now.'),
  );
  $form['at']['mediaqueries']['mq']['output'] = array(
    '#type' => 'textarea',
    '#rows' => 18,
    '#default_value' => $queries,
    '#states' => array(
      'disabled' => array(
          'input[name="check"]' => array('checked' => FALSE),
      ),
    ),
  );
  // Breadcrumbs
  $form['at']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#weight' => '96',
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
    '#weight' => '97',
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
      '#weight' => '99',
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
  $form['at']['comments'] = array(
    '#type' => 'fieldset',
    '#weight' => '100',
    '#title' => t('Comments'),
  );
  $form['at']['comments']['comment-title'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comment Options'),
    '#description' => t('<h3>Comment Options</h3>'),
  );
  $form['at']['comments']['comment-title']['comments_hide_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide the comment title'),
    '#default_value' => theme_get_setting('comments_hide_title'),
    '#description' => t('Checking this setting will hide comment titles using element-invisible. Hiding rather than removing titles maintains accessibility and semantic structure while not showing titles to sighted users.'),
  );
  // Development settings
  $form['at']['classes'] = array(
    '#type' => 'fieldset',
    '#weight' => '101',
    '#title' => t('CSS Classes'),
  );
  $form['at']['classes']['extra-classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Extra Classes'),
    '#description' => t('<h3>Add or Remove CSS Classes</h3><p>Many classes are removed by default (unchecked checkbox), to add classes check the checkbox.</p>'),
  );
  $form['at']['classes']['extra-classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pages: ') . '<span class="description">' . t('add page-path, add/edit/delete (for workflow states), and a language class (i18n).') . '</span>',
    '#default_value' => theme_get_setting('extra_page_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Articles: ') . '<span class="description">' . t('add promoted, sticky, preview, language, odd/even classes, user picture handling, and build mode classes such as .article-teaser and .article-full.') . '</span>',
    '#default_value' => theme_get_setting('extra_article_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Comments: ') . '<span class="description">' . t('add anonymous, author, viewer, new, odd/even classes and classes for title, user picture and signature handling.') . '</span>',
    '#default_value' => theme_get_setting('extra_comment_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Blocks: ') . '<span class="description">' . t('add odd/even, block region and block count classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_block_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Menus: ') . '<span class="description">' . t('add an extra class based on the menu link ID (mlid).') . '</span>',
    '#default_value' => theme_get_setting('extra_menu_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Item-lists: ') . '<span class="description">' . t('add first, last and odd/even classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_item_list_classes'),
  );
  // Menu Links Settings
  $form['at']['markup'] = array(
    '#type' => 'fieldset',
    '#title' => t('Markup'),
    '#weight' => 101,
  );
  $form['at']['markup']['menu-links'] = array(
    '#type' => 'fieldset',
    '#title' => t('Menu items markup'),
    '#description' => t('<h3>Extra Markup Options</h3>'),
  );
  // Add spans to theme_links
  $form['at']['markup']['menu-links']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu item text in SPAN tags - useful for certain theme or design related techniques'),
    '#description' => t('Note: this does not work for Superfish menus, which includes its own feature for doing this.'),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );

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
