<?php // $Id$
// adaptivethemes.com

/**
 * @file theme-settings.php
 */

/**
* Implementation of themehook_settings() function.
*
* @param $saved_settings
*   An array of saved settings for this theme.
* @return
*   A form array.
*/
function phptemplate_settings($saved_settings) {

  // Only open one of the general or node setting fieldsets at a time
  $js = <<<SCRIPT
    $(document).ready(function(){
      $("fieldset.general_settings > legend > a").click(function(){
      	if(!$("fieldset.node_settings").hasClass("collapsed")) {
          Drupal.toggleFieldset($("fieldset.node_settings"));
      	}
      });
      $("fieldset.node_settings > legend > a").click(function(){
      	if (!$("fieldset.general_settings").hasClass("collapsed")) {
          Drupal.toggleFieldset($("fieldset.general_settings"));
      	}
      });
    });
SCRIPT;
  drupal_add_js($js, 'inline');

  // Get the node types
  $node_types = node_get_types('names');
 
  /**
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the template.php file.
   */
  $defaults = array(
    'user_notverified_display'              => 1,
    'breadcrumb_display'                    => 'yes',
    'breadcrumb_separator'                  => ' &#187; ',
    'breadcrumb_home'                       => 0,
    'breadcrumb_trailing'                   => 0,
    'breadcrumb_title'                      => 0,
    'search_snippet'                        => 1,
    'search_info_type'                      => 1,
    'search_info_user'                      => 1,
    'search_info_date'                      => 1,
    'search_info_comment'                   => 1,
    'search_info_upload'                    => 1,
    'mission_statement_pages'               => 'home',
    'split_node_form'                       => 0,
    'taxonomy_display_default'              => 'only',
    'taxonomy_format_default'               => 'vocab',
    'taxonomy_delimiter_default'            => ', ',
    'taxonomy_enable_content_type'          => 0,
    'submitted_by_author_default'           => 1,
    'submitted_by_date_default'             => 1,
    'submitted_by_enable_content_type'      => 0,
    'rebuild_registry'                      => 0,
    'load_firebug_lite'                     => 0,
    'admin_user_links'                      => 1,
    'block_edit_links'                      => 1,
    'at_admin_hide_help'                    => 0,
    'layout_method'                         => '0',
    'layout_width'                          => '960px',
    'layout_sidebar_first_width'            => '240',
    'layout_sidebar_last_width'             => '240',
    'layout_enable_settings'                => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_width'                   => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_sidebars'                => 'off', // set to 'on' to enable, 'off' to disable
    'layout_enable_method'                  => 'off', // set to 'on' to enable, 'off' to disable
    'equal_heights_sidebars'                => 0,
    'equal_heights_blocks'                  => 0,
    'horizontal_login_block'                => 0,
    'horizontal_login_block_overlabel'      => 0,
    'horizontal_login_block_enable'         => 'off', // set to 'on' to enable, 'off' to disable
    'color_schemes'                         => 'colors-default.css',
    'color_enable_schemes'                  => 'off',  // set to 'on' to enable, 'off' to disable
  );
  
  // Make the default content-type settings the same as the default theme settings,
  // so we can tell if content-type-specific settings have been altered.
  $defaults = array_merge($defaults, theme_get_settings());
  
  // Set the default values for content-type-specific settings
  foreach ($node_types as $type => $name) {
    $defaults["taxonomy_display_{$type}"]         = $defaults['taxonomy_display_default'];
    $defaults["taxonomy_format_{$type}"]          = $defaults['taxonomy_format_default'];
    $defaults["taxonomy_delimiter_{$type}"]       = $defaults['taxonomy_delimiter_default'];
    $defaults["submitted_by_author_{$type}"]      = $defaults['submitted_by_author_default'];
    $defaults["submitted_by_date_{$type}"]        = $defaults['submitted_by_date_default'];
  }
    
  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);

  // Create theme settings form widgets using Forms API

  // General Settings
  $form['general_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('General settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#attributes' => array('class' => 'general_settings'),
  );
  // Mission Statement
  $form['general_settings']['mission_statement'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mission statement'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['mission_statement']['mission_statement_pages'] = array(
    '#type'          => 'radios',
    '#title'         => t('Where should the mission statement be displayed?'),
    '#default_value' => $settings['mission_statement_pages'],
    '#options'       => array(
                          'home' => t('Display the mission statement only on the home page'),
                          'all' => t('Display the mission statement on all pages'),
                        ),
  );
  $form['general_settings']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumb'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['breadcrumb']['breadcrumb_display'] = array(
    '#type'          => 'select',
    '#title'         => t('Display breadcrumb'),
    '#default_value' => $settings['breadcrumb_display'],
    '#options'       => array(
                          'yes'   => t('Yes'),
                          'no'    => t('No'),
                          'admin' => t('Only in the admin section'),                
                        ),
  );
  $form['general_settings']['breadcrumb']['breadcrumb_separator'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Breadcrumb separator'),
    '#description'   => t('Text only. Dont forget to include spaces.'),
    '#default_value' => $settings['breadcrumb_separator'],
    '#size'          => 8,
    '#maxlength'     => 10,
    '#prefix'        => '<div id="div-breadcrumb-collapse">',
  );
  $form['general_settings']['breadcrumb']['breadcrumb_home'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Show the home page link in breadcrumbs'),
    '#default_value' => $settings['breadcrumb_home'],
  );
  $form['general_settings']['breadcrumb']['breadcrumb_trailing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => $settings['breadcrumb_trailing'],
    '#description'   => t('Useful when the breadcrumb is placed just before the title.'),
  );
  $form['general_settings']['breadcrumb']['breadcrumb_title'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => $settings['breadcrumb_title'],
    '#description'   => t('Useful when the breadcrumb is not placed just before the title.'),
    '#suffix'        => '</div>',
  );
  // Username
  $form['general_settings']['username'] = array(
    '#type' => 'fieldset',
    '#title' => t('Username'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['username']['user_notverified_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display "not verified" for unregistered user names'),
    '#default_value' => $settings['user_notverified_display'],
  );
  // Search Settings
  $form['general_settings']['search_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search results'),
    '#description' => t('What additional information should be displayed in your search results?'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['search_container']['search_results']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => $settings['search_snippet'],
  );
  $form['general_settings']['search_container']['search_results']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => $settings['search_info_type'],
  );
  $form['general_settings']['search_container']['search_results']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => $settings['search_info_user'],
  );
  $form['general_settings']['search_container']['search_results']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => $settings['search_info_date'],
  );
  $form['general_settings']['search_container']['search_results']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => $settings['search_info_comment'],
  );
  $form['general_settings']['search_container']['search_results']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => $settings['search_info_upload'],
  );
  // Node Settings
  $form['node_type_specific'] = array(
    '#type' => 'fieldset',
    '#title' => t('Node settings'),
    '#description' => t('Here you can make adjustments to which information is shown with your content, and how it is displayed.  You can modify these settings so they apply to all content types, or check the "Use content-type specific settings" box to customize them for each content type.  For example, you may want to show the date on Stories, but not Pages.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#attributes' => array('class' => 'node_settings'),
  );
  
  //'split_node_form
  $form['node_type_specific']['split_node_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Custom Node Form'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['node_type_specific']['split_node_container']['split_node_form'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the custom node form layout'),
  '#description' => t('This will place additional node Save, Preview and Delete links and the taxonomy term fieldsets in a new column on the node edit form.'),
    '#default_value' => $settings['split_node_form'],
  );

  // Author & Date Settings
  $form['node_type_specific']['submitted_by_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Author & date'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (module_exists('submitted_by') == FALSE) {
    // Default & content-type specific settings
    foreach ((array('default' => 'Default') + node_get_types('names')) as $type => $name) {
      $form['node_type_specific']['submitted_by_container']['submitted_by'][$type] = array(
        '#type' => 'fieldset',
        '#title' => t('!name', array('!name' => t($name))),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['node_type_specific']['submitted_by_container']['submitted_by'][$type]["submitted_by_author_{$type}"] = array(
        '#type'          => 'checkbox',
        '#title'         => t('Display author\'s username'),
        '#default_value' => $settings["submitted_by_author_{$type}"],
      );
      $form['node_type_specific']['submitted_by_container']['submitted_by'][$type]["submitted_by_date_{$type}"] = array(
        '#type'          => 'checkbox',
        '#title'         => t('Display date posted (you can customize this format on the Date and Time settings page.)'),
        '#default_value' => $settings["submitted_by_date_{$type}"],
      );
      // Options for default settings
      if ($type == 'default') {
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#title'] = t('Default');
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#collapsed'] = $settings['submitted_by_enable_content_type'] ? TRUE : FALSE;
        $form['node_type_specific']['submitted_by_container']['submitted_by']['submitted_by_enable_content_type'] = array(
          '#type'          => 'checkbox',
          '#title'         => t('Use custom settings for each content type instead of the default above.'),
          '#default_value' => $settings['submitted_by_enable_content_type'],
        );
      }
      // Collapse content-type specific settings if default settings are being used
      else if ($settings['submitted_by_enable_content_type'] == 0) {
        $form['submitted_by'][$type]['#collapsed'] = TRUE;
      }
    }
  }
  else {
    $form['node_type_specific']['submitted_by_container']['#description'] = t('NOTICE: You currently have the "Submitted by" module installed and enabled, so the author and date theme settings have been disabled to prevent conflicts.  If you later wish to re-enable the author and date theme settings, you must first disable the "Submitted by" module.');
    $form['node_type_specific']['submitted_by_container']['submitted_by'][$type]['#disabled'] = 'disabled';
  }
  // Taxonomy Settings
  if (module_exists('taxonomy')) {
    $form['node_type_specific']['display_taxonomy_container'] = array(
      '#type' => 'fieldset',
      '#title' => t('Taxonomy terms'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    // Default & content-type specific settings
    foreach ((array('default' => 'Default') + node_get_types('names')) as $type => $name) {
      // taxonomy display per node
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type] = array(
        '#type' => 'fieldset',
        '#title'       => t('!name', array('!name' => t($name))),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      // Display
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_display_{$type}"] = array(
        '#type'          => 'select',
        '#title'         => t('When should taxonomy terms be displayed?'),
        '#default_value' => $settings["taxonomy_display_{$type}"],
        '#options'       => array(
                              '' => '',
                              'never' => t('Never display taxonomy terms'),
                              'all' => t('Always display taxonomy terms'),
                              'only' => t('Only display taxonomy terms on full node pages'),
                            ),
      );
      // Formatting
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_format_{$type}"] = array(
        '#type'          => 'radios',
        '#title'         => t('Taxonomy display format'),
        '#default_value' => $settings["taxonomy_format_{$type}"],
        '#options'       => array(
                              'vocab' => t('Display each vocabulary on a new line'),
                              'list' => t('Display all taxonomy terms together in single list'),
                            ),
      );
      // Delimiter
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_delimiter_{$type}"] = array(
        '#type'          => 'textfield',
        '#title'         => t('Delimiter'),
        '#description'   => t('Modify the delimiter. The default is a comma followed by a space.'),
        '#default_value' => $settings['taxonomy_delimiter_default'],
        '#size'          => 8,
        '#maxlength'     => 10,
      );
      // Get taxonomy vocabularies by node type
      $vocabs = array();
      $vocabs_by_type = ($type == 'default') ? taxonomy_get_vocabularies() : taxonomy_get_vocabularies($type);
      foreach ($vocabs_by_type as $key => $value) {
        $vocabs[$value->vid] = $value->name;
      }
      // Display taxonomy checkboxes
      foreach ($vocabs as $key => $vocab_name) {
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_vocab_display_{$type}_{$key}"] = array(
          '#type'          => 'checkbox',
          '#title'         => t('Display vocabulary: '. $vocab_name),
          '#default_value' => $settings["taxonomy_vocab_display_{$type}_{$key}"], 
        );
      }
      // Options for default settings
      if ($type == 'default') {
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['default']['#title'] = t('Default');
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['default']['#collapsed'] = $settings['taxonomy_enable_content_type'] ? TRUE : FALSE;
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['taxonomy_enable_content_type'] = array(
          '#type'          => 'checkbox',
          '#title'         => t('Use custom settings for each content type instead of the default above'),
          '#default_value' => $settings['taxonomy_enable_content_type'],
        );
      }
      // Collapse content-type specific settings if default settings are being used
      else if ($settings['taxonomy_enable_content_type'] == 0) {
        $form['display_taxonomy'][$type]['#collapsed'] = TRUE;
      }
    }
  }
  // Development settings
  $form['themedev'] = array(
    '#type' => 'fieldset',
    '#title' => t('Theme development settings'),
    '#collapsible' => TRUE,
    '#collapsed' => $settings['rebuild_registry'] ? FALSE : TRUE,
  );
 $form['themedev']['rebuild_registry'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild theme registry on every page.'),
    '#default_value' => $settings['rebuild_registry'],
    '#description' => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>. WARNING! This is a performance penalty and must be turned off on production websites.', array('!link' => 'http://drupal.org/node/173880#theme-registry')),
  );
 $form['themedev']['load_firebug_lite'] = array(
    '#type' => 'checkbox',
    '#title' => t('Load Firebug lite script for debugging in IE, Opera and Webkit.'),
    '#default_value' => $settings['load_firebug_lite'],
    '#description' => t('WARNING! To use Firebug lite you must first download and install the script to the /js/core/ directory in your theme. <a href="!link">Download Firebug lite</a>.', array('!link' => 'http://getfirebug.com/lite.html')),
  );
  // Admin settings
  $form['admin_settings']['administration'] = array(
    '#type' => 'fieldset',
    '#title' => t('Admin settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['admin_settings']['administration']['admin_user_links'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Show the built in Admin user menu.'),
    '#default_value' => $settings['admin_user_links'],
    '#description' => t('This will show or hide useful links in the header depending on what permissions the users role has been assigned.'),  
  );
  $form['admin_settings']['administration']['block_edit_links'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Show block editing and configuration links.'),
    '#default_value' => $settings['block_edit_links'],
    '#description' => t('When hovering or over a block or viewing blocks in the blocks list page, privileged users will see block editing and configuration links.'),  
  );
  $form['admin_settings']['administration']['at_admin_hide_help'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Hide help messages.'),
    '#default_value' => $settings['at_admin_hide_help'],
    '#description' => t('When this setting is checked all help messages will be hidden.'),  
  );
  // Layout settings  
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if ($settings['layout_enable_settings'] == 'on') {
    $image_path = path_to_theme() .'/css/core/core-images';
    $form['layout']['page_layout'] = array(
      '#type' => 'fieldset',
      '#title' => t('Page Layout'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description'   => t('Use these settings to customize the layout of your site. NOTE: If you have the built-in Admin theme enabled these settings will not affect the Admin section; they only apply to the "front end" theme. If no overrides are set the default layout will apply.'),
    );
    if ($settings['layout_enable_width'] == 'on') {
      $form['layout']['page_layout']['layout_width_help'] = array(
        '#prefix'        => '<div class="layout-help">',
        '#suffix'        => '</div>',
        '#value'   => t('<dl><dt>Page width</dt><dd>Set the overall width of the the page. Each width increment is 60px or 1 grid column.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_width'] = array(
        '#type'          => 'select',
        '#prefix'        => '<div class="page-width">',
        '#suffix'        => '</div>',
        '#default_value' => $settings['layout_width'],
        '#options'       => array(
          '720px'   => t('720px'),
          '780px'   => t('780px'),
          '840px'   => t('840px'),
          '900px'   => t('900px'),
          '960px'   => t('960px'),
          '1020px'   => t('1020px'),
          '1080px'   => t('1080px'),
          '1140px'   => t('1140px'),
          '1200px'   => t('1200px'),
          '1260px'   => t('1260px'),
        ),
        '#attributes' => array('class' => 'field-layout-width'),
      );
    } // endif width
    if ($settings['layout_enable_sidebars'] == 'on') {
      $form['layout']['page_layout']['layout_sidebar_help'] = array(
        '#prefix'        => '<div class="layout-help">',
        '#suffix'        => '</div>',
        '#value'   => t('<dl><dt>Sidebar widths</dt><dd>Set the width of each sidebar. Increments are in 60px or 1 grid column. The content columm will stretch to fill the rest of the page width.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_sidebar_first_width'] = array(
        '#type'          => 'select',
        '#title'         => t('Sidebar first'),
        '#prefix'       => '<div class="sidebar-width"><div class="sidebar-width-left">',
        '#suffix'       => '</div>',
        '#default_value' => $settings['layout_sidebar_first_width'],
        '#options'       => array(
          '60'    => t('60px'),
          '120'   => t('120px'),
          '180'   => t('180px'),
          '240'   => t('240px'),
          '300'   => t('300px'),
          '360'   => t('360px'),
          '420'   => t('420px'),
          '480'   => t('480px'),
          '540'   => t('540px'),
          '600'   => t('600px'),
          '660'   => t('660px'),
          '720'   => t('720px'),
          '780'   => t('780px'),
          '840'   => t('840px'),
          '900'   => t('900px'),
          '960'   => t('960px'),
        ),
        '#attributes' => array('class' => 'sidebar-width-select'),
      );
      $form['layout']['page_layout']['layout_sidebar_last_width'] = array(
        '#type'          => 'select',
        '#title'         => t('Sidebar last'),
        '#prefix'       => '<div class="sidebar-width-right">',
        '#suffix'       => '</div></div>',
        '#default_value' => $settings['layout_sidebar_last_width'],
        '#options'       => array(
          '60'    => t('60px'),
          '120'   => t('120px'),
          '180'   => t('180px'),
          '240'   => t('240px'),
          '300'   => t('300px'),
          '360'   => t('360px'),
          '420'   => t('420px'),
          '480'   => t('480px'),
          '540'   => t('540px'),
          '600'   => t('600px'),
          '660'   => t('660px'),
          '720'   => t('720px'),
          '780'   => t('780px'),
          '840'   => t('840px'),
          '900'   => t('900px'),
          '960'   => t('960px'),
        ),
        '#attributes' => array('class' => 'sidebar-width-select'),
      );
    } //endif layout sidebars
    if ($settings['layout_enable_method'] == 'on') {
      $form['layout']['page_layout']['layout_method_help'] = array(
        '#prefix'        => '<div class="layout-help">',
        '#suffix'        => '</div>',
        '#value'   => t('<dl><dt>Sidebar layout</dt><dd>Set the default sidebar configuration. You can choose a standard three column layout or place both sidebars to the right or left of the main content column.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_method'] = array(
        '#type' => 'radios',
        '#prefix'       => '<div class="layout-method">',
        '#suffix'       => '</div>',
        '#default_value' => $settings['layout_method'],      
        '#options' => array(
          '0' => t('<strong>Layout #1</strong>') . theme("image", $image_path ."/layout-default.png") . t('<span class="layout-type">Standard three column layout—left, content, right.</span>'),
          '1' => t('<strong>Layout #2</strong>') . theme("image", $image_path ."/layout-sidebars-right.png") . t('<span class="layout-type">Two columns on the right—content, left, right.</span>'),
          '2' => t('<strong>Layout #3</strong>') . theme("image", $image_path ."/layout-sidebars-left.png") . t('<span class="layout-type">Two columns on the left—left, right, content.</span>'),
        ),
       '#attributes' => array('class' => 'layouts'), 
      );
      $form['layout']['page_layout']['layout_enable_settings'] = array(
        '#type'    => 'hidden',
        '#value'   => $settings['layout_enable_settings'],
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
  $form['layout']['equal_heights']['equal_heights_sidebars'] = array(
    '#type' => 'checkbox',
    '#title' => t('Equal Height Sidebars'),
    '#default_value' => $settings['equal_heights_sidebars'],
    '#description'   => t('This setting will make the sidebars and the main content column equal to the hight of the tallest column.'),
  );
  $form['layout']['equal_heights']['equal_heights_blocks'] = array(
    '#type' => 'checkbox',
    '#title' => t('Equal Height Blocks'),
    '#default_value' => $settings['equal_heights_blocks'],
    '#description'   => t('This setting will make all blocks in regions equal to the height of the tallest block. <b>This will not affect blocks in sidebars and does not work for blocks in Gpanels or Panels.</b>'),
  );
  if ($settings['horizontal_login_block_enable'] == 'on') {
    $form['layout']['login_block'] = array(
      '#type' => 'fieldset',
      '#title' => t('Login Block'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['layout']['login_block']['horizontal_login_block'] = array(
      '#type' => 'checkbox',
      '#title' => t('Horizontal Login Block'),
      '#default_value' => $settings['horizontal_login_block'],
      '#description'   => t('Checking this setting will enable a horizontal style login block (all elements on one line).'),
    );
    $form['layout']['login_block']['horizontal_login_block_overlabel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Overlabel JavaScript'),
      '#default_value' => $settings['horizontal_login_block_overlabel'],
      '#description'   => t('Checking this setting will place the "User name:*" and "Password:*" labels inside the user name and password text fields.'),
    );
  } // endif horizontal block settings
  // Color schemes
  if ($settings['color_enable_schemes'] == 'on') {
    $form['color'] = array(
      '#type' => 'fieldset',
      '#title' => t('Color settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description'   => t('Use these settings to customize the colors of your site. If no stylesheet is selected the default colors will apply.'),
    );
    $form['color']['color_schemes'] = array(
      '#type' => 'select',
      '#title' => t('Color Schemes'),
      '#default_value' => $settings['color_schemes'],
      '#options' => array(
        'colors-default.css' => t('Default color scheme'),
        //'colors-example.css' => t('Example color scheme'), // add aditional stylesheets here, they must be in css/theme and match name perfectly!
      ),
    );
    $form['color']['color_enable_schemes'] = array(
      '#type'    => 'hidden',
      '#value'   => $settings['color_enable_schemes'],
    ); 
  } // endif color schemes
  return $form;
}