<?php 
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