<?php
// $Id$

/**
 * @file theme-settings.php
 */
function adaptivetheme_form_system_theme_settings_alter(&$form, $form_state) {
  // Create the form using Forms API: http://api.drupal.org/api/7
  // Layout settings
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  if (theme_get_setting('layout_enable_settings') == 'on') {
    $image_path = drupal_get_path('theme', 'adaptivetheme') .'/css/core-images';
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
        '#prefix' => '<div class="page-width">',
        '#suffix' => '</div>',
        '#default_value' => theme_get_setting('layout_width'),
        '#options' => array(
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
          '85%'  => t('85% Fluid'),
          '100%' => t('100% Fluid'),
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
        '#title' => t('Sidebar first'),
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
        '#title' => t('Sidebar last'),
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
        '#prefix' => '<div class="layout-method">',
        '#suffix' => '</div>',
        '#default_value' => theme_get_setting('layout_method'),
        '#options' => array(
          0 => t('<strong>Layout #1</strong> <span class="layout-type-0">Standard three column layout—left, content, right.</span>'),
          1 => t('<strong>Layout #2</strong> <span class="layout-type-1">Two columns on the right—content, left, right.</span>'),
          2 => t('<strong>Layout #3</strong> <span class="layout-type-2">Two columns on the left—left, right, content.</span>'),
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
  // General Settings
  $form['general_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Skip navigation &amp; Search result settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  // Skip Navigation
  $form['general_settings']['skip_navigation'] = array(
    '#type' => 'fieldset',
    '#title' => t('Skip Navigation'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['skip_navigation']['skip_navigation_display'] = array(
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
  $form['general_settings']['search_results'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results'),
    '#description' => t('What additional information should be displayed in your search results?'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['search_results']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );
  $form['general_settings']['search_results']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );
  $form['general_settings']['search_results']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );
  $form['general_settings']['search_results']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );
  $form['general_settings']['search_results']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );
  $form['general_settings']['search_results']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );
  // Search_info_separator
  $form['general_settings']['search_results']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Search info separator'),
    '#description' => t('Modify the separator. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );
  // Development settings
  $form['theme']['development'] = array(
    '#type' => 'fieldset',
    '#title' => t('Theme development settings'),
    '#description' => t('These settings are for the theme developer. Changing these settings may break your theme.'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  // Global settings
  $form['theme']['development']['global'] = array(
    '#type' => 'fieldset',
    '#title' => t('Global Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => theme_get_setting('rebuild_registry') ? FALSE : TRUE,
  );
  // Rebuild registry
  $form['theme']['development']['global']['rebuild_registry'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild the theme registry on every page load.'),
    '#default_value' => theme_get_setting('rebuild_registry'),
    '#description' => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>. WARNING! This is a performance penalty and must be turned off on production websites.', array('!link' => 'http://drupal.org/node/173880#theme-registry')),
  );
  // Show $theme_info
  $form['theme']['development']['global']['show_theme_info'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show theme info.'),
    '#default_value' => theme_get_setting('show_theme_info'),
    '#description' => t('This will show the output of the global $theme_info variable using Krumo.'),
  );
  if (!module_exists('devel')) {
    $form['theme']['development']['global']['show_theme_info']['#description'] = t('NOTICE: The setting requires the <a href="!link">Devel module</a> to be installed. This will show the output of the global $theme_info variable using Krumo.', array('!link' => 'http://drupal.org/project/devel'));
    $form['theme']['development']['global']['show_theme_info']['#disabled'] = 'disabled';
  }
  // Add or remove markup
  $form['theme']['development']['markup'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add SPAN tags to Menu Anchors'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Add spans to main menu anchor text
  $form['theme']['development']['markup']['theme_main_menu_spans'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap Main menu anchor text in SPAN tags.'),
    '#default_value' => theme_get_setting('theme_main_menu_spans'),
  );
  // Add spans to secondary menu anchor text
  $form['theme']['development']['markup']['theme_sec_menu_spans'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap Secondary menu anchor text in SPAN tags.'),
    '#default_value' => theme_get_setting('theme_sec_menu_spans'),
  );
  // Add spans to theme_menu_link
  $form['theme']['development']['markup']['theme_menu_link_spans'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu link anchor text in SPAN tags.'),
    '#default_value' => theme_get_setting('theme_menu_link_spans'),
  );
  // Add or remove extra classes
  $form['theme']['development']['classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add or Remove CSS Classes'),
    '#description' => t('<p>This is a fast and easy way to add or remove CSS classes during theme development,
     so you only print what you require. Once you have decided which classes you need you can set new defaults in your subthemes .info file -
     this is useful if your theme needs to be portable, such as a commercial theme or when moving from development server to the live site.
     </p><p>Note that whenever you change the defaults in the .info file you need to clear the cache on the <a href="!link">Performance Settings</a> page.</p>', array('!link' => base_path() . 'admin/config/development/performance')),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Body classes
  $form['theme']['development']['classes']['body_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Page Classes'),
    '#description' => t('Page classes are added to the BODY element and apply to the whole page.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['body_classes']['cleanup_classes_section'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print section classes (.section-$section, uses the path-alias)'),
    '#default_value' => theme_get_setting('cleanup_classes_section'),
  );
  $form['theme']['development']['classes']['body_classes']['cleanup_classes_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .front and .not-front classes.'),
    '#default_value' => theme_get_setting('cleanup_classes_front'),
  );
  $form['theme']['development']['classes']['body_classes']['cleanup_classes_user_status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .logged-in and .not-logged-in classes.'),
    '#default_value' => theme_get_setting('cleanup_classes_user_status'),
  );
  $form['theme']['development']['classes']['body_classes']['cleanup_classes_node_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-type-[type] classes.'),
    '#default_value' => theme_get_setting('cleanup_classes_node_type'),
  );
  if (function_exists('locale')) {
   $form['theme']['development']['classes']['body_classes']['cleanup_classes_language'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print classes for Locale page language such as .lang-en, .lang-sv'),
      '#default_value' => theme_get_setting('cleanup_classes_language'),
    );
  }
  // Node classes
  $form['theme']['development']['classes']['article_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Article Classes'),
    '#description' => t('Article classes apply to nodes. They print in the main wrapper DIV for all articles (nodes) in node.tpl.php.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_id'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique ID for each article e.g. #article-1.'),
    '#default_value' => theme_get_setting('cleanup_article_id'),
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_sticky'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-sticky class for articles set to sticky.'),
    '#default_value' => theme_get_setting('cleanup_article_classes_sticky'),
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_promote'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-promoted class for articles promoted to front.'),
    '#default_value' => theme_get_setting('cleanup_article_classes_promote'),
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_teaser'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-teaser class on article teasers.'),
    '#default_value' => theme_get_setting('cleanup_article_classes_teaser'),
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_preview'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-preview class for article previews.'),
    '#default_value' => theme_get_setting('cleanup_article_classes_preview'),
  );
  $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .[content-type]-article classes.'),
    '#default_value' => theme_get_setting('cleanup_article_classes_type'),
  );
  if (function_exists('i18n_init')) {
    $form['theme']['development']['classes']['article_classes']['cleanup_article_classes_language'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print .article-lang-[language] classes (requires i18n module)'),
      '#default_value' => theme_get_setting('cleanup_article_classes_language'),
    );
  }
  // Comment classes
  $form['theme']['development']['classes']['comment_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comment Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['comment_classes']['comments'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comments'),
    '#description' => t('Comment classes apply to all comments. They print in comment.tpl.php on the wrapper DIV for each comment.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['comment_classes']['comments']['cleanup_comment_anonymous'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-anonymous for anonymous comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_anonymous'),
  );
  $form['theme']['development']['classes']['comment_classes']['comments']['cleanup_comment_article_author'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-article-author for author comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_article_author'),
  );
  $form['theme']['development']['classes']['comment_classes']['comments']['cleanup_comment_by_viewer'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-viewer for viewer comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_by_viewer'),
  );
  $form['theme']['development']['classes']['comment_classes']['comments']['cleanup_comment_new'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-new for new comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_new'),
  );
  $form['theme']['development']['classes']['comment_classes']['comments']['cleanup_comment_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_zebra'),
  );
  $form['theme']['development']['classes']['comment_classes']['comment-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comment Wrapper'),
   '#description' => t('This class prints in comment-wrapper.tpl.php. The DIV wrapper encloses both the comments and the comment form (if on the same page).'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['comment_classes']['comment-wrapper']['cleanup_comment_wrapper_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a content type class on the comments wrapper i.e. .[content-type]-comments.'),
    '#default_value' => theme_get_setting('cleanup_comment_wrapper_type'),
  );
  // Block classes
  $form['theme']['development']['classes']['block_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Block Classes'),
    '#description' => t('Comment classes apply to blocks. They print in the main wrapper DIV for all blocks in block.tpl.php.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['block_classes']['cleanup_block_block_module_delta'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique ID for each block (#block-module-delta).'),
    '#default_value' => theme_get_setting('cleanup_block_block_module_delta'),
  );
  $form['theme']['development']['classes']['block_classes']['cleanup_block_classes_module'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a .block-[module] class.'),
    '#default_value' => theme_get_setting('cleanup_block_classes_module'),
  );
  $form['theme']['development']['classes']['block_classes']['cleanup_block_classes_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for blocks.'),
    '#default_value' => theme_get_setting('cleanup_block_classes_zebra'),
  );
  $form['theme']['development']['classes']['block_classes']['cleanup_block_classes_region'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .block-[region] classes.'),
    '#default_value' => theme_get_setting('cleanup_block_classes_region'),
  );
  $form['theme']['development']['classes']['block_classes']['cleanup_block_classes_count'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .block-[count] classes.'),
    '#default_value' => theme_get_setting('cleanup_block_classes_count'),
  );
  // Menu classes
  $form['theme']['development']['classes']['menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Menu Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (!function_exists('dhtml_menu_init')) {
    $form['theme']['development']['classes']['menu_classes']['menu_menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Block Menu Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    );
    $form['theme']['development']['classes']['menu_classes']['menu_menu_classes']['cleanup_menu_menu_class'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the ul.menu class.'),
      '#default_value' => theme_get_setting('cleanup_menu_menu_class'),
    );
    $form['theme']['development']['classes']['menu_classes']['menu_menu_classes']['cleanup_menu_link_classes'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print extra clases on menu link items, such as leaf, collasped, first and last.'),
      '#default_value' => theme_get_setting('cleanup_menu_link_classes'),
    );
    $form['theme']['development']['classes']['menu_classes']['menu_menu_classes']['cleanup_menu_title_class'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print classes based on the menu title, i.e. .menu-[title].'),
      '#default_value' => theme_get_setting('cleanup_menu_title_class'),
    );
  }
  else {
    $form['theme']['development']['classes']['menu_classes']['#description'] = t('NOTICE: You currently have the DHTML Menu module installed. The custom menu class options have been disabled because this module will not work correctly with them enabled - you can still set classes for the Primary and Secondary links (below).');
    $form['theme']['development']['classes']['menu_classes']['menu_menu_classes']['#disabled'] = 'disabled';
  }
  // Main menu classes
  $form['theme']['development']['classes']['menu_classes']['main_menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Main Menu Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['menu_classes']['main_menu_classes']['cleanup_main_active_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print the active classes.'),
    '#default_value' => theme_get_setting('cleanup_main_active_classes'),
  );
  $form['theme']['development']['classes']['menu_classes']['main_menu_classes']['cleanup_main_firstlast_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .first and .last classes.'),
     '#default_value' => theme_get_setting('cleanup_main_firstlast_classes'),
  );
  $form['theme']['development']['classes']['menu_classes']['main_menu_classes']['cleanup_main_mlid_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique identifier class, e.g. .menu-345'),
     '#default_value' => theme_get_setting('cleanup_main_mlid_class'),
  );
  // Secondary menu classes
  $form['theme']['development']['classes']['menu_classes']['secondary_menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Secondary Menu Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['menu_classes']['secondary_menu_classes']['cleanup_sec_active_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print the active classes.'),
    '#default_value' => theme_get_setting('cleanup_sec_active_classes'),
  );
  $form['theme']['development']['classes']['menu_classes']['secondary_menu_classes']['cleanup_sec_firstlast_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .first and .last classes.'),
     '#default_value' => theme_get_setting('cleanup_sec_firstlast_classes'),
  );
  $form['theme']['development']['classes']['menu_classes']['secondary_menu_classes']['cleanup_sec_mlid_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique identifier class, e.g. .menu-345'),
     '#default_value' => theme_get_setting('cleanup_sec_mlid_class'),
  );
  // Item list classes
  $form['theme']['development']['classes']['itemlist_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Item list Classes'),
    '#description' => t('Item list classes are applied using the <code>theme_item_list</code> function override in template.theme-overrides.inc'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['itemlist_classes']['cleanup_item_list_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for list items.'),
    '#default_value' => theme_get_setting('cleanup_item_list_zebra'),
  );
  $form['theme']['development']['classes']['itemlist_classes']['cleanup_item_list_first_last'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .first and .last classes for the first and last items in the list.'),
    '#default_value' => theme_get_setting('cleanup_item_list_first_last'),
  );
  // Views classes
  if (module_exists('views')) {
    $form['theme']['development']['classes']['views_classes'] = array(
      '#type' => 'fieldset',
      '#title' => t('Views Classes'),
      '#description' => t('NOTE: If you are using custom Views templates you must use the template overrides that come with Adaptivetheme to preserve these settings.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['theme']['development']['classes']['views_classes']['display'] = array(
      '#type' => 'fieldset',
      '#title' => t('Display Classes'),
      '#description' => t('Control the classes for Views displays (views-view.tpl.php).'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['theme']['development']['classes']['views_classes']['display']['cleanup_views_css_name'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the CSS Name class.'),
      '#default_value' => theme_get_setting('cleanup_views_css_name'),
    );
    $form['theme']['development']['classes']['views_classes']['display']['cleanup_views_view_name'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the View Name class.'),
      '#default_value' => theme_get_setting('cleanup_views_view_name'),
    );
    $form['theme']['development']['classes']['views_classes']['display']['cleanup_views_display_id'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the Display ID class.'),
      '#default_value' => theme_get_setting('cleanup_views_display_id'),
    );
    $form['theme']['development']['classes']['views_classes']['display']['cleanup_views_dom_id'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the DOM ID class.'),
      '#default_value' => theme_get_setting('cleanup_views_dom_id'),
    );
    $form['theme']['development']['classes']['views_classes']['style'] = array(
      '#type' => 'fieldset',
      '#title' => t('Views Style Classes'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['theme']['development']['classes']['views_classes']['style']['cleanup_views_unformatted'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print extra classes for unformatted views (views-view-unformatted.tpl.php).'),
      '#default_value' => theme_get_setting('cleanup_views_unformatted'),
    );
    $form['theme']['development']['classes']['views_classes']['style']['cleanup_views_item_list'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print extra classes for item list views (views-view-list.tpl.php).'),
      '#default_value' => theme_get_setting('cleanup_views_item_list'),
    );
  }
  // Field classes (CCK).
  if (module_exists('content')) {
    $form['theme']['development']['classes']['field_classes'] = array(
      '#type' => 'fieldset',
      '#title' => t('Field Classes'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
   $form['theme']['development']['classes']['field_classes']['cleanup_fields_type'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print field type classes.'),
      '#default_value' => theme_get_setting('cleanup_fields_type'),
    );
   $form['theme']['development']['classes']['field_classes']['cleanup_fields_name'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print field name classes.'),
      '#default_value' => theme_get_setting('cleanup_fields_name'),
    );
    $form['theme']['development']['classes']['field_classes']['cleanup_fields_zebra'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print odd/even zebra classes on CCK fields.'),
      '#default_value' => theme_get_setting('cleanup_fields_zebra'),
    );
  }
  // Title classes for headings
  $form['theme']['development']['classes']['heading_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Heading Classes'),
    '#description' => t('Heading classes apply to article, block and comment titles (h2, h3 etc).'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['theme']['development']['classes']['heading_classes']['cleanup_headings_title_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add the .title class to all headings.'),
    '#default_value' => theme_get_setting('cleanup_headings_title_class'),
  );
  $form['theme']['development']['classes']['heading_classes']['cleanup_headings_namespaced_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add a pseudo name spaced title class to headings, i.e. .article-title, .block-title, .comment-title.'),
    '#default_value' => theme_get_setting('cleanup_headings_namespaced_class'),
  );
}