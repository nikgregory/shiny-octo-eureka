<?
  
  
  // Base font - global font family unless overridden by a more specific selector
  $form['at']['font']['base-font'] = array (
    '#type' => 'fieldset',
    '#title' => t('Default font'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form['at']['font']['base-font']['base_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('base_font_type'),
  );
  $form['at']['font']['base-font']['base_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('base_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'bf')),
    '#states' => array (
      'visible' => array (
        'select[name="base_font_type"]' => array (
          'value' => ''
        )
      )
    )
  );
  $form['at']['font']['base-font']['base_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('base_font_gwf'),
    '#options' => font_list('gwf', 'bf'),
    '#states' => array (
      'visible' => array (
        'select[name="base_font_type"]' => array (
          'value' => 'gwf'
        )
      )
    )
  );
  $form['at']['font']['base-font']['base_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('base_font_cfs') ? theme_get_setting('base_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="base_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['base-font']['base_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('base_font_fyf'),
    '#options' => font_list('fyf', 'bf'),
    '#states' => array (
      'visible' => array (
        'select[name="base_font_type"]' => array (
          'value' => 'fyf'
        )
      )
    )
  );
  $form['at']['font']['base-font']['base_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => array(
      '<none>' => 'Not set',
      '62.5%' => '62.5% (10px)',
      '68.8%' => '68.8% (11px)',
      '75%'   => '75%   (12px)',
      '81.3%' => '81.3% (13px)',
      '87.5%' => '87.5% (14px)',
      '93.8%' => '93.8% (15px)',
      '100%'  => '100%  (16px)',
    ),
    '#description' => t('The default font size is set on the HTML element. All elements, margins, padding and widths using a relative unit such as em or % will scale relative to this value. Use "Not set" if you prefer to set this in your CSS. You can override this value in your responsive stylesheets also, to use a larger or smaller default size for different device types.'),
    '#default_value' => theme_get_setting('base_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="base_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  
  // Branding elements - site name and slogan
   $form['at']['font']['branding'] = array (
    '#type' => 'fieldset',
    '#title' => t('Branding'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ); 
  // Site name
  $form['at']['font']['branding']['site-name'] = array (
    '#type' => 'fieldset',
    '#title' => t('Site name'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form['at']['font']['branding']['site-name']['site_name_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('site_name_font_type')
  );
  $form['at']['font']['branding']['site-name']['site_name_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_name_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'snf')),
    '#states' => array (
      'visible' => array (
        'select[name="site_name_font_type"]' => array (
          'value' => ''
        )
      )
    )
  );
  $form['at']['font']['branding']['site-name']['site_name_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_name_font_gwf'),
    '#options' => font_list('gwf', 'snf'),
    '#states' => array (
      'visible' => array (
        'select[name="site_name_font_type"]' => array (
          'value' => 'gwf'
        )
      )
    )
  );
  $form['at']['font']['branding']['site-name']['site_name_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_name_font_cfs') ? theme_get_setting('site_name_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="site_name_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['branding']['site-name']['site_name_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_name_font_fyf'),
    '#options' => font_list('fyf', 'snf'),
    '#states' => array (
      'visible' => array (
        'select[name="site_name_font_type"]' => array (
          'value' => 'fyf'
        )
      )
    )
  );
  $form['at']['font']['branding']['site-name']['site_name_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('site_name_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="site_name_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  // Slogan
  $form['at']['font']['branding']['site-slogan'] = array (
    '#type' => 'fieldset',
    '#title' => t('Site slogan'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('site_slogan_font_type')
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_slogan_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'ssf')),
    '#states' => array (
      'visible' => array (
        'select[name="site_slogan_font_type"]' => array (
          'value' => ''
        )
      )
    )
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_slogan_font_gwf'),
    '#options' => font_list('gwf', 'ssf'),
    '#states' => array (
      'visible' => array (
        'select[name="site_slogan_font_type"]' => array (
          'value' => 'gwf'
        )
      )
    )
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_slogan_font_cfs') ? theme_get_setting('site_slogan_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="site_slogan_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('site_slogan_font_fyf'),
    '#options' => font_list('fyf', 'ssf'),
    '#states' => array (
      'visible' => array (
        'select[name="site_slogan_font_type"]' => array (
          'value' => 'fyf'
        )
      )
    )
  );
  $form['at']['font']['branding']['site-slogan']['site_slogan_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('site_slogan_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="site_slogan_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  // Slogan // Titles
   $form['at']['font']['titles'] = array (
    '#type' => 'fieldset',
    '#title' => t('Titles'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  ); 
  // Slogan // Titles // Page title
  $form['at']['font']['titles']['page-title'] = array (
    '#type' => 'fieldset',
    '#title' => t('Page titles'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form['at']['font']['titles']['page-title']['page_title_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('page_title_font_type')
  );
  $form['at']['font']['titles']['page-title']['page_title_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('page_title_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'ptf')),
    '#states' => array (
      'visible' => array (
        'select[name="page_title_font_type"]' => array (
          'value' => '',
        )
      )
    )
  );
  $form['at']['font']['titles']['page-title']['page_title_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('page_title_font_gwf'),
    '#options' => font_list('gwf', 'ptf'),
    '#states' => array (
      'visible' => array (
        'select[name="page_title_font_type"]' => array (
          'value' => 'gwf',
        )
      )
    )
  );
  $form['at']['font']['titles']['page-title']['page_title_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('page_title_font_cfs') ? theme_get_setting('page_title_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="page_title_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['titles']['page-title']['page_title_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('page_title_font_fyf'),
    '#options' => font_list('fyf', 'ptf'),
    '#states' => array (
      'visible' => array (
        'select[name="page_title_font_type"]' => array (
          'value' => 'fyf',
        )
      )
    )
  );
  $form['at']['font']['titles']['page-title']['page_title_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('page_title_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="page_title_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  // Slogan // Titles // Page title // Node title
  $form['at']['font']['titles']['node-title'] = array (
    '#type' => 'fieldset',
    '#title' => t('Node titles'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form ['at']['font']['titles']['node-title']['node_title_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('node_title_font_type')
  );
  $form['at']['font']['titles']['node-title']['node_title_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('node_title_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'ntf')),
    '#states' => array (
      'visible' => array (
        'select[name="node_title_font_type"]' => array (
          'value' => '',
        )
      )
    )
  );
  $form['at']['font']['titles']['node-title']['node_title_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('node_title_font_gwf'),
    '#options' => font_list('gwf', 'ntf'),
    '#states' => array (
      'visible' => array (
        'select[name="node_title_font_type"]' => array (
          'value' => 'gwf',
        )
      )
    )
  );
  $form['at']['font']['titles']['node-title']['node_title_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('node_title_font_cfs') ? theme_get_setting('node_title_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="node_title_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['titles']['node-title']['node_title_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('node_title_font_fyf'),
    '#options' => font_list('fyf', 'ntf'),
    '#states' => array (
      'visible' => array (
        'select[name="node_title_font_type"]' => array (
          'value' => 'fyf',
        )
      )
    )
  );
  $form['at']['font']['titles']['node-title']['node_title_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('node_title_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="node_title_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  // Slogan // Titles // Page title // Node title // Comment title
  $form['at']['font']['titles']['comment-title'] = array (
    '#type' => 'fieldset',
    '#title' => t('Comment titles'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('comment_title_font_type')
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('comment_title_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'ctf')),
    '#states' => array (
      'visible' => array (
        'select[name="comment_title_font_type"]' => array (
          'value' => '',
        )
      )
    )
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('comment_title_font_gwf'),
    '#options' => font_list('gwf', 'ctf'),
    '#states' => array (
      'visible' => array (
        'select[name="comment_title_font_type"]' => array (
          'value' => 'gwf',
        )
      )
    )
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('comment_title_font_cfs') ? theme_get_setting('comment_title_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="comment_title_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('comment_title_font_fyf'),
    '#options' => font_list('fyf', 'ctf'),
    '#states' => array (
      'visible' => array (
        'select[name="comment_title_font_type"]' => array (
          'value' => 'fyf',
        )
      )
    )
  );
  $form['at']['font']['titles']['comment-title']['comment_title_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('comment_title_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="comment_title_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );
  // Slogan // Titles // Page title // Node title // Comment title // Block title
  $form ['at']['font']['titles']['block-title'] = array (
    '#type' => 'fieldset',
    '#title' => t('Block titles'),
    '#attributes' => array('class' => array('font-element-wrapper')),
  );
  $form ['at']['font']['titles']['block-title']['block_title_font_type'] = array (
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => $font_type_options,
    '#default_value' => theme_get_setting('block_title_font_type')
  );
  $form ['at']['font']['titles']['block-title']['block_title_font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('block_title_font'),
    '#options' => str_replace("'", "", font_list('wsf', 'btf')),
    '#states' => array (
      'visible' => array (
        'select[name="block_title_font_type"]' => array (
          'value' => '',
        )
      )
    )
  );
  $form ['at']['font']['titles']['block-title']['block_title_font_gwf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('block_title_font_gwf'),
    '#options' => font_list('gwf', 'btf'),
    '#states' => array (
      'visible' => array (
        'select[name="block_title_font_type"]' => array (
          'value' => 'gwf',
        )
      )
    )
  );
  $form['at']['font']['titles']['block-title']['block_title_font_cfs'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('block_title_font_cfs') ? theme_get_setting('block_title_font_cfs') : '',
    '#description' => t("Enter a comma seperated list of fonts. Font names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array (
      'visible' => array (
        'select[name="block_title_font_type"]' => array (
          'value' => 'cfs',
        )
      )
    )
  );
  $form ['at']['font']['titles']['block-title']['block_title_font_fyf'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('block_title_font_fyf'),
    '#options' => font_list('fyf', 'btf'),
    '#states' => array (
      'visible' => array (
        'select[name="block_title_font_type"]' => array (
          'value' => 'fyf',
        )
      )
    )
  );
  $form['at']['font']['titles']['block-title']['block_title_font_size'] = array(
    '#type' => 'select',
    '#title' => t('Size'),
    '#options' => $font_sizes,
    '#default_value' => theme_get_setting('block_title_font_size'),
    '#attributes' => array('class' => array('font-size-wrapper')),
    '#states' => array (
      'invisible' => array (
        'select[name="block_title_font_type"]' => array (
          'value' => '<none>'
        )
      )
    )
  );