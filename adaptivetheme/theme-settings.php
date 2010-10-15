<?php
// $Id$

function adaptivetheme_form_system_theme_settings_alter(&$form, $form_state) {
  // Layout settings
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (theme_get_setting('layout_enable_settings') == 'on') {
    $image_path = drupal_get_path('theme', 'adaptivetheme') . '/css/core-images';
    $form['layout']['page_layout'] = array(
      '#type' => 'fieldset',
      '#title' => t('Page Layout'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => t('Use these settings to customize the layout of your theme.'),
    );
    if (theme_get_setting('layout_enable_width') == 'on') {
      $form['layout']['page_layout']['layout_width_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Page width</dt><dd>Set the overall width of the the page.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_width'] = array(
        '#type' => 'select',
        '#title' => t('Page width'),
        '#prefix' => '<div class="page-width">',
        '#suffix' => '</div>',
        '#default_value' => theme_get_setting('layout_width'),
        '#options' => array(
          '600px' => t('600px'),
          '660px' => t('660px'),
          '720px' => t('720px'),
          '780px' => t('780px'),
          '840px' => t('840px'),
          '900px' => t('900px'),
          '960px' => t('960px'),
          '1020px' => t('1020px'),
          '1080px' => t('1080px'),
          '1140px' => t('1140px'),
          '1200px' => t('1200px'),
          '1260px' => t('1260px'),
          '1320px' => t('1320px'),
          '1380px' => t('1380px'),
          '1440px' => t('1440px'),
          '1500px' => t('1500px'),
          '80%'  => t('80%'),
          '85%'  => t('85%'),
          '90%'  => t('90%'),
          '95%'  => t('95%'),
          '100%' => t('100%'),
        ),
        //'#attributes' => array('class' => 'field-layout-width'),
      );
    } // endif width
    if (theme_get_setting('layout_enable_sidebars') == 'on') {
      $form['layout']['page_layout']['layout_sidebar_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Sidebar widths</dt><dd>Set the width of each sidebar. The content columm will stretch to fill the rest of the page width.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_sidebar_first_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar first width'),
        '#prefix' => '<div class="sidebar-width"><div class="sidebar-width-left">',
        '#suffix' => '</div>',
        '#default_value' => theme_get_setting('layout_sidebar_first_width'),
        '#options' => array(
          '60' => t('60px'),
          '120' => t('120px'),
          '160' => t('160px'),
          '180' => t('180px'),
          '240' => t('240px'),
          '300' => t('300px'),
          '320' => t('320px'),
          '360' => t('360px'),
          '420' => t('420px'),
          '480' => t('480px'),
          '540' => t('540px'),
          '600' => t('600px'),
          '660' => t('660px'),
          '720' => t('720px'),
          '780' => t('780px'),
          '840' => t('840px'),
          '900' => t('900px'),
          '960' => t('960px'),
        ),
        //'#attributes' => array('class' => 'sidebar-width-select'),
      );
      $form['layout']['page_layout']['layout_sidebar_last_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar last width'),
        '#prefix' => '<div class="sidebar-width-right">',
        '#suffix' => '</div></div>',
        '#default_value' => theme_get_setting('layout_sidebar_last_width'),
        '#options' => array(
          '60' => t('60px'),
          '120' => t('120px'),
          '160' => t('160px'),
          '180' => t('180px'),
          '240' => t('240px'),
          '300' => t('300px'),
          '320' => t('320px'),
          '360' => t('360px'),
          '420' => t('420px'),
          '480' => t('480px'),
          '540' => t('540px'),
          '600' => t('600px'),
          '660' => t('660px'),
          '720' => t('720px'),
          '780' => t('780px'),
          '840' => t('840px'),
          '900' => t('900px'),
          '960' => t('960px'),
        ),
        //'#attributes' => array('class' => 'sidebar-width-select'),
      );
    } //endif layout sidebars
    if (theme_get_setting('layout_enable_method') == 'on') {
      $form['layout']['page_layout']['layout_method_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Sidebar layout</dt><dd>Set the default sidebar configuration. You can choose a standard three column layout or place both sidebars to the right or left of the main content column.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_method'] = array(
        '#type' => 'radios',
        '#title' => t('Sidebar layout'),
        '#description' => t('The sidebar layout descriptions are for LTR (left to right languages), these will flip in RTL mode.'),
        '#prefix' => '<div class="layout-method">',
        '#suffix' => '</div>',
        '#default_value' => theme_get_setting('layout_method'),
        '#options' => array(
          0 => t('<strong>Layout #1</strong> <span class="layout-type-0">Standard three column layout — Sidebar first | Content | Sidebar last</span>'),
          1 => t('<strong>Layout #2</strong> <span class="layout-type-1">Two columns on the right — Content | Sidebar first | Sidebar last</span>'),
          2 => t('<strong>Layout #3</strong> <span class="layout-type-2">Two columns on the left — Sidebar first | Sidebar last | Content</span>'),
        ),
       //'#attributes' => array('class' => 'layouts'),
      );
      $form['layout']['page_layout']['layout_enable_settings'] = array(
        '#type' => 'hidden',
        '#value' => theme_get_setting('layout_enable_settings'),
      );
    } // endif layout method
  } // endif layout settings
  // Equal heights settings
  $form['layout']['equal_heights'] = array(
    '#type' => 'fieldset',
    '#title' => t('Equal Heights'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description'   => t('These settings allow you to set the sidebars and/or region blocks to be equal height.'),
  );
  // Equal height sidebars
  $form['layout']['equal_heights']['equal_heights_sidebars'] = array(
    '#type' => 'checkbox',
    '#title' => t('Equal Height Sidebars'),
    '#default_value' => theme_get_setting('equal_heights_sidebars'),
    '#description'   => t('This setting will make the sidebars and the main content column equal to the height of the tallest column.'),
  );
  // Equal height gpanels
  $form['layout']['equal_heights']['equal_heights_gpanels'] = array(
    '#type' => 'checkbox',
    '#title' => t('Equal Height Gpanels'),
    '#default_value' => theme_get_setting('equal_heights_gpanels'),
    '#description'   => t('This will make all Gpanel blocks equal to the height of the tallest block in any Gpanel, regardless of which Gpanel the blocks are in. Good for creating a grid type block layout, however it could be too generic if you have more than one Gpanel active in the page.'),
  );
  // Equal height blocks per region
  $equalized_blocks = array(
    'leaderboard' => t('Leaderboard region'),
    'header' => t('Header region'),
    'secondary-content' => t('Secondary Content region'),
    'highlight' => t('Highlight region'),
    'content-bottom' => t('Content Bottom region'),
    'tertiary-content' => t('Tertiary Content region'),
    'footer' => t('Footer region'),
  );
  $form['layout']['equal_heights']['equal_heights_blocks'] = array(
    '#type' => 'fieldset',
    '#title' => t('Equal Height Blocks'),
  );
  $form['layout']['equal_heights']['equal_heights_blocks'] += array(
    '#prefix' => '<div id="div-equalize-collapse">',
    '#suffix' => '</div>',
    '#description' => t('<p>Equal height blocks only makes sense for blocks aligned horizontally so do not apply to sidebars. The equal height settings work well in conjunction with the Skinr block layout classes.</p>'),
  );
  foreach ($equalized_blocks as $name => $title) {
    $form['layout']['equal_heights']['equal_heights_blocks']['equalize_'. $name] = array(
      '#type' => 'checkbox',
      '#title' => $title,
      '#default_value' => theme_get_setting('equalize_'. $name),
    );
  }
  // Horizonatal login block
  if (theme_get_setting('horizontal_login_block_enable') == 'on') {
    $form['layout']['login_block'] = array(
      '#type' => 'fieldset',
      '#title' => t('Login Block'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['layout']['login_block']['horizontal_login_block'] = array(
      '#type' => 'checkbox',
      '#title' => t('Horizontal Login Block'),
      '#default_value' => theme_get_setting('horizontal_login_block'),
      '#description' => t('Checking this setting will enable a horizontal style login block (all elements on one line). Note that if you are using OpenID this does not work well and you will need a more sophistocated approach than can be provided here.'),
    );
    $form['layout']['login_block']['horizontal_login_block_overlabel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Overlabel JavaScript'),
      '#default_value' => theme_get_setting('horizontal_login_block_overlabel'),
      '#description' => t('Checking this setting will place the "User name:*" and "Password:*" labels inside the user name and password text fields.'),
      '#states' => array(
        'visible' => array(
          'input[name*=horizontal_login_block]' => array(
            'value' => '1',
          ),
        ),
      ),
    );
  } // endif horizontal block settings
  // Skip Navigation
  $form['skip_navigation'] = array(
    '#type' => 'fieldset',
    '#title' => t('Skip Navigation'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['skip_navigation']['skip_navigation_display'] = array(
    '#type' => 'radios',
    '#title'  => t('Modify the display of the skip navigation'),
    '#default_value' => theme_get_setting('skip_navigation_display'),
    '#options' => array(
      'show' => t('Show skip navigation'),
      'focus' => t('Show skip navigation when in focus, otherwise is hidden'),
      'hide' => t('Hide skip navigation'),
    ),
  );
  // Search Settings
  $form['search_results'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results'),
    '#description' => t('What additional information should be displayed in your search results?'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['search_results']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );
  $form['search_results']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );
  $form['search_results']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );
  $form['search_results']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );
  $form['search_results']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );
  $form['search_results']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );
  // Search_info_separator
  $form['search_results']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Search info separator'),
    '#description' => t('Modify the separator. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );
  // Breadcrumbs
  $form['breadcrumb_settings']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumb'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['breadcrumb_settings']['breadcrumb']['breadcrumb_display'] = array(
    '#type' => 'select',
    '#title' => t('Display breadcrumb'),
    '#default_value' => theme_get_setting('breadcrumb_display'),
    '#options' => array(
      'yes' => t('Yes'),
      'no' => t('No'),
    ),
  );
  $form['breadcrumb_settings']['breadcrumb']['breadcrumb_separator'] = array(
    '#type'  => 'textfield',
    '#title' => t('Breadcrumb separator'),
    '#description' => t('Text only. Dont forget to include spaces.'),
    '#default_value' => theme_get_setting('breadcrumb_separator'),
    '#size' => 8,
    '#maxlength' => 10,
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array(
            'value' => 'yes',
          ),
      ),
    ),
  );
  $form['breadcrumb_settings']['breadcrumb']['breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the homepage link in breadcrumbs'),
    '#default_value' => theme_get_setting('breadcrumb_home'),
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array(
            'value' => 'yes',
          ),
      ),
    ),
  );
  // Development settings
  $form['themedev']['dev'] = array(
    '#type' => 'fieldset',
    '#title' => t('Development'),
    '#description' => t('WARNING: These settings are for the theme developer! Changing these settings may break your site. Make sure you really know what you are doing before changing these.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Add or remove extra classes
  $form['themedev']['dev']['classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('CSS Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra page classes',
    '#default_value' => theme_get_setting('extra_page_classes'),
    '#description' => t('Adds a page-[path-to-page] class, add, edit and delete classes for articles, and a lang-[language] class.'),
  );
  $form['themedev']['dev']['classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra article classes',
    '#default_value' => theme_get_setting('extra_article_classes'),
    '#description' => t('Adds promoted, sticky, preview, language and odd/even classes to articles.'),
  );
  $form['themedev']['dev']['classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra comment classes',
    '#default_value' => theme_get_setting('extra_comment_classes'),
    '#description' => t('Adds anonymous, author, viewer, new, and odd/even classes to comments.'),
  );
  $form['themedev']['dev']['classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra block classes',
    '#default_value' => theme_get_setting('extra_block_classes'),
    '#description' => t('Adds a bunch of new classes to blocks.'),
  );
  $form['themedev']['dev']['classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra menu classes',
    '#default_value' => theme_get_setting('extra_menu_classes'),
    '#description' => t('Adds a bunch of new classes to all menus (main, secondary, normal menus etc).'),
  );
  $form['themedev']['dev']['classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => 'Print extra item-list classes',
    '#default_value' => theme_get_setting('extra_item_list_classes'),
    '#description' => t('Adds first, last and odd/even classes to item lists.'),
  );
  // Menu Links Settings
  $form['themedev']['dev']['menu_links'] = array(
    '#type' => 'fieldset',
    '#title' => t('Modify Links'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Add spans to theme_links
  $form['themedev']['dev']['menu_links']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => check_plain(t('Add <span></span> tags to menu items')),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );
  // Theme Settings Export
  /*
  $form['themedev']['dev']['export'] = array(
    '#type' => 'fieldset',
    '#title' => t('Export'),
    '#description' => t('<p>Copy and paste these settings to a plain text file for backup or paste to your themes .info file.</p><p>WARNING! If you are using a WYSIWYG editor it must be disabled for this text area, otherwise all special characters are likely to be converted to HTML entities. If your editor has a \'view source\' feature try that first.</p>'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['export']['exported_settings'] = array(
    '#type' => 'textarea',
    '#default_value' => $exports,
    '#resizable' => FALSE,
    '#cols' => 60,
    '#rows' => 25,
  );
  */
}