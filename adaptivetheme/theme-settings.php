<?php // $Id$
// adaptivethemes.com

/**
 * @file theme-settings.php
 */

// Initialize theme settings.
include_once(drupal_get_path('theme', 'adaptivetheme') .'/inc/template.theme-settings.inc');

/**
* Implementation of themehook_settings() function.
* The original concept of these advanced theme settings
* comes from the Acquia Marina theme, although they have been
* massivly changed and added to. Kudos to TNT for the inspiration.
*
* @param $saved_settings
*   An array of saved settings for this theme.
* @return
*   A form array.
*/
function adaptivetheme_settings($saved_settings, $subtheme_defaults = array()) {

  global $theme_info;

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

  // Get the default values from the .info file.
  $defaults = adaptivetheme_theme_get_default_settings('adaptivetheme');

  // Allow a subtheme to override the default values.
  $defaults = array_merge($defaults, $subtheme_defaults);

  // Set the default values for content-type-specific settings
  foreach ($node_types as $type => $name) {
    $defaults["taxonomy_display_{$type}"] = $defaults['taxonomy_display_default'];
    $defaults["taxonomy_format_{$type}"]  = $defaults['taxonomy_format_default'];
    $defaults["taxonomy_delimiter_{$type}"] = $defaults['taxonomy_delimiter_default'];
    $defaults["submitted_by_author_{$type}"] = $defaults['submitted_by_author_default'];
    $defaults["submitted_by_date_{$type}"] = $defaults['submitted_by_date_default'];
    $defaults["display_links_{$type}"] = $defaults['display_links_default'];
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
    '#default_value' => $settings['skip_navigation_display'],
    '#options' => array(
      'show' => t('Show skip navigation'),
      'focus' => t('Show skip navigation when in focus, otherwise is hidden'),
      'hide' => t('Hide skip navigation'),
    ),
  );
  // Mission Statement
  $form['general_settings']['mission_statement'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mission statement'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['mission_statement']['mission_statement_pages'] = array(
    '#type' => 'radios',
    '#title' => t('Where should the mission statement be displayed'),
    '#default_value' => $settings['mission_statement_pages'],
    '#options' => array(
      'home' => t('Display the mission statement only on the home page'),
      'all' => t('Display the mission statement on all pages'),
    ),
  );
  // Breadcrumbs
  $form['general_settings']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumb'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['breadcrumb']['breadcrumb_display'] = array(
    '#type' => 'select',
    '#title' => t('Display breadcrumb'),
    '#default_value' => $settings['breadcrumb_display'],
    '#options' => array(
      'yes' => t('Yes'),
      'no' => t('No'),
      'admin' => t('Only in the admin section'),
    ),
  );
  $form['general_settings']['breadcrumb']['breadcrumb_separator'] = array(
    '#type'  => 'textfield',
    '#title' => t('Breadcrumb separator'),
    '#description' => t('Text only. Dont forget to include spaces.'),
    '#default_value' => $settings['breadcrumb_separator'],
    '#size' => 8,
    '#maxlength' => 10,
    '#prefix' => '<div id="div-breadcrumb-collapse">',
  );
  $form['general_settings']['breadcrumb']['breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the home page link in breadcrumbs'),
    '#default_value' => $settings['breadcrumb_home'],
  );
  $form['general_settings']['breadcrumb']['breadcrumb_trailing'] = array(
    '#type' => 'checkbox',
    '#title' => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => $settings['breadcrumb_trailing'],
    '#description' => t('Useful when the breadcrumb is placed just before the title.'),
  );
  $form['general_settings']['breadcrumb']['breadcrumb_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => $settings['breadcrumb_title'],
    '#description' => t('Useful when the breadcrumb is not placed just before the title.'),
    '#suffix' => '</div>',
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
  $form['general_settings']['search'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Search forms
  $form['general_settings']['search']['search_form'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search forms'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['search']['search_form']['display_search_form_label'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display the search form label <em>"Search this site"</em>'),
    '#default_value' => $settings['display_search_form_label'],
  );
  // Search results
  $form['general_settings']['search']['search_results'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search results'),
    '#description' => t('What additional information should be displayed in your search results?'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['general_settings']['search']['search_results']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => $settings['search_snippet'],
  );
  $form['general_settings']['search']['search_results']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => $settings['search_info_type'],
  );
  $form['general_settings']['search']['search_results']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => $settings['search_info_user'],
  );
  $form['general_settings']['search']['search_results']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => $settings['search_info_date'],
  );
  $form['general_settings']['search']['search_results']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => $settings['search_info_comment'],
  );
  $form['general_settings']['search']['search_results']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => $settings['search_info_upload'],
  );
  //search_info_separator
  $form['general_settings']['search']['search_results']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Search info separator'),
    '#description' => t('Modify the separator. The default is a hypen with a space before and after.'),
    '#default_value' => $settings['search_info_separator'],
    '#size' => 8,
    '#maxlength' => 10,
  );
  // Node Settings
  $form['node_type_specific'] = array(
    '#type' => 'fieldset',
    '#title' => t('Content type settings'),
    '#description' => t('Here you can make adjustments to which information is shown with your content, and how it is displayed.  You can modify these settings so they apply to all content types, or check the <em>"Use content-type specific settings"</em> checkbox to customize them for each content type.  For example, you may want to show the date on Stories, but not Pages.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#attributes' => array('class' => 'node_settings'),
  );
  // Author & Date Settings
  $form['node_type_specific']['submitted_by_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Author &amp; Date'),
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
        '#type' => 'checkbox',
        '#title' => t('Display author\'s username'),
        '#default_value' => $settings["submitted_by_author_{$type}"],
      );
      $form['node_type_specific']['submitted_by_container']['submitted_by'][$type]["submitted_by_date_{$type}"] = array(
        '#type' => 'checkbox',
        '#title' => t('Display date posted (you can customize this format on the Date and Time settings page.)'),
        '#default_value' => $settings["submitted_by_date_{$type}"],
      );
      // Options for default settings
      if ($type == 'default') {
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#title'] = t('Default');
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#collapsed'] = $settings['submitted_by_enable_content_type'] ? TRUE : FALSE;
        $form['node_type_specific']['submitted_by_container']['submitted_by']['submitted_by_enable_content_type'] = array(
          '#type' => 'checkbox',
          '#title' => t('Use content-type specific settings.'),
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
  // Taxonomy term display
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
        '#title' => t('!name', array('!name' => t($name))),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      // Display
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_display_{$type}"] = array(
        '#type' => 'select',
        '#title' => t('When should taxonomy terms be displayed?'),
        '#default_value' => $settings["taxonomy_display_{$type}"],
        '#options' => array(
          'never' => t('Never display taxonomy terms'),
          'all' => t('Always display taxonomy terms'),
          'only' => t('Only display taxonomy terms on full node pages'),
        ),
      );
      // Formatting
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_format_{$type}"] = array(
        '#type' => 'radios',
        '#title' => t('Taxonomy display format'),
        '#default_value' => $settings["taxonomy_format_{$type}"],
        '#options' => array(
          'vocab' => t('Display each vocabulary on a new line'),
          'list' => t('Display all taxonomy terms together in single list'),
        ),
      );
      // Delimiter
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_delimiter_{$type}"] = array(
        '#type' => 'textfield',
        '#title' => t('Delimiter'),
        '#description' => t('Modify the delimiter. The default is a comma followed by a space.'),
        '#default_value' => $settings['taxonomy_delimiter_default'],
        '#size' => 8,
        '#maxlength' => 10,
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
          '#type' => 'checkbox',
          '#title' => t('Display vocabulary: '. $vocab_name),
          '#default_value' => $settings["taxonomy_vocab_display_{$type}_{$key}"],
        );
      }
      // Options for default settings
      if ($type == 'default') {
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['default']['#title'] = t('Default');
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['default']['#collapsed'] = $settings['taxonomy_enable_content_type'] ? TRUE : FALSE;
        $form['node_type_specific']['display_taxonomy_container']['display_taxonomy']['taxonomy_enable_content_type'] = array(
          '#type' => 'checkbox',
          '#title' => t('Use content-type specific settings.'),
          '#default_value' => $settings['taxonomy_enable_content_type'],
        );
      }
      // Collapse content-type specific settings if default settings are being used
      else if ($settings['taxonomy_enable_content_type'] == 0) {
        $form['display_taxonomy'][$type]['#collapsed'] = TRUE;
      }
    }
  }
  // Links display
  $form['node_type_specific']['links_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Links'),
    '#description' => t('Links are the "links" displayed at the bottom of articles.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Default & content-type specific settings
  foreach ((array('default' => 'Default') + node_get_types('names')) as $type => $name) {
    $form['node_type_specific']['links_container']['links'][$type] = array(
      '#type' => 'fieldset',
      '#title' => t('!name', array('!name' => t($name))),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['node_type_specific']['links_container']['links'][$type]["display_links_{$type}"] = array(
      '#type' => 'select',
      '#title' => t('Display Links'),
      '#default_value' => $settings['display_links_default'],
      '#options' => array(
        'never' => t('Never display links'),
        'all' => t('Always display links'),
        'only' => t('Only display links on full nodes'),
      ),
    );
    // Options for default settings
    if ($type == 'default') {
      $form['node_type_specific']['links_container']['links']['default']['#title'] = t('Default');
      $form['node_type_specific']['links_container']['links']['default']['#collapsed'] = $settings['display_links_enable_content_type'] ? TRUE : FALSE;
      $form['node_type_specific']['links_container']['links']['display_links_enable_content_type'] = array(
        '#type' => 'checkbox',
        '#title' => t('Use content-type specific settings.'),
        '#default_value' => $settings['display_links_enable_content_type'],
      );
    }
    // Collapse content-type specific settings if default settings are being used
    else if ($settings['display_links_enable_content_type'] == 0) {
      $form['links'][$type]['#collapsed'] = TRUE;
    }
  }
  // Layout settings
  $form['layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if ($settings['layout_enable_settings'] == 'on') {
    $image_path = drupal_get_path('theme', 'adaptivetheme') .'/css/core-images';
    $form['layout']['page_layout'] = array(
      '#type' => 'fieldset',
      '#title' => t('Page Layout'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => t('Use these settings to customize the layout of your theme.'),
    );
    if ($settings['layout_enable_width'] == 'on') {
      $form['layout']['page_layout']['layout_width_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Page width</dt><dd>Set the overall width of the the page. Each width increment is 60px or 1 grid column.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_width'] = array(
        '#type' => 'select',
        '#prefix' => '<div class="page-width">',
        '#suffix' => '</div>',
        '#default_value' => $settings['layout_width'],
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
        ),
        '#attributes' => array('class' => 'field-layout-width'),
      );
    } // endif width
    if ($settings['layout_enable_sidebars'] == 'on') {
      $form['layout']['page_layout']['layout_sidebar_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Sidebar widths</dt><dd>Set the width of each sidebar. Increments are in 60px or 1 grid column. The content columm will stretch to fill the rest of the page width.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_sidebar_first_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar first'),
        '#prefix' => '<div class="sidebar-width"><div class="sidebar-width-left">',
        '#suffix' => '</div>',
        '#default_value' => $settings['layout_sidebar_first_width'],
        '#options' => array(
          '60' => t('60px'),
          '120' => t('120px'),
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
        '#attributes' => array('class' => 'sidebar-width-select'),
      );
      $form['layout']['page_layout']['layout_sidebar_last_width'] = array(
        '#type' => 'select',
        '#title' => t('Sidebar last'),
        '#prefix' => '<div class="sidebar-width-right">',
        '#suffix' => '</div></div>',
        '#default_value' => $settings['layout_sidebar_last_width'],
        '#options' => array(
          '60' => t('60px'),
          '120' => t('120px'),
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
        '#attributes' => array('class' => 'sidebar-width-select'),
      );
    } //endif layout sidebars
    if ($settings['layout_enable_method'] == 'on') {
      $form['layout']['page_layout']['layout_method_help'] = array(
        '#prefix' => '<div class="layout-help">',
        '#suffix' => '</div>',
        '#value' => t('<dl><dt>Sidebar layout</dt><dd>Set the default sidebar configuration. You can choose a standard three column layout or place both sidebars to the right or left of the main content column.</dd></dl>'),
      );
      $form['layout']['page_layout']['layout_method'] = array(
        '#type' => 'radios',
        '#prefix' => '<div class="layout-method">',
        '#suffix' => '</div>',
        '#default_value' => $settings['layout_method'],
        '#options' => array(
          '0' => t('<strong>Layout #1</strong>') . theme('image', $image_path .'/layout-default.png') . t('<span class="layout-type">Standard three column layout—left, content, right.</span>'),
          '1' => t('<strong>Layout #2</strong>') . theme('image', $image_path .'/layout-sidebars-right.png') . t('<span class="layout-type">Two columns on the right—content, left, right.</span>'),
          '2' => t('<strong>Layout #3</strong>') . theme('image', $image_path .'/layout-sidebars-left.png') . t('<span class="layout-type">Two columns on the left—left, right, content.</span>'),
        ),
       '#attributes' => array('class' => 'layouts'),
      );
      $form['layout']['page_layout']['layout_enable_settings'] = array(
        '#type' => 'hidden',
        '#value' => $settings['layout_enable_settings'],
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
    '#default_value' => $settings['equal_heights_sidebars'],
    '#description'   => t('This setting will make the sidebars and the main content column equal to the height of the tallest column.'),
  );
  // Equal height blocks per region
  $equalized_blocks = array(
    'leaderboard' => t('Leaderboard region'),
    'header' => t('Header region'),
    'secondary-content' => t('Secondary Content region'),
    'content-top' => t('Content Top region'),
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
      '#description' => t('<p>Equal height blocks only makes sense for blocks aligned horizontally so is not applicable by default to sidebars. The equal height settings work well in conjuntion with the Skinr block layout classes that come with Adaptivetheme. Equal heights is applied with jQuery and applies to the block-inner DIV.</p><p>Select which regions to want to have equal height blocks.</p>'),
    );
    foreach ($equalized_blocks as $name => $title) {
      $form['layout']['equal_heights']['equal_heights_blocks']['equalize_'. $name] = array(
        '#type' => 'checkbox', 
        '#title' => $title, 
        '#default_value' => $settings['equalize_'. $name]);
    }
  // Horizonatal login block
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
      '#description' => t('Checking this setting will enable a horizontal style login block (all elements on one line). Note that if you are using OpenID this does not work well and you will need a more sophistocated approach than can be provided here.'),
    );
    $form['layout']['login_block']['horizontal_login_block_overlabel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Overlabel JavaScript'),
      '#default_value' => $settings['horizontal_login_block_overlabel'],
      '#description' => t('Checking this setting will place the "User name:*" and "Password:*" labels inside the user name and password text fields.'),
    );
  } // endif horizontal block settings
  //split_node_form
  $form['layout']['split_node_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Content Forms'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['split_node_container']['split_node_form'] = array(
    '#type' => 'checkbox',
    '#title' => t('Alternate content form layout'),
    '#description' => t('This setting does three things. 1) Splits content forms into two columns, 2) Adds Save, Preview and Delete buttons to the top of the form (in the new column) and 3) Moves the Taxonomy Term fieldset into the new column as well.'),
    '#default_value' => $settings['split_node_form'],
  );
  // Admin settings
  $form['admin_settings']['administration'] = array(
    '#type' => 'fieldset',
    '#title' => t('Admin settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Show user menu
  $form['admin_settings']['administration']['at_user_menu'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the built in User Menu.'),
    '#default_value' => $settings['at_user_menu'],
    '#description' => t('This will show or hide useful links in the header. NOTE that if the <a href="!link">Admin Menu</a> module is installed most links will not show up because they are included in the Admin Menu.', array('!link' => 'http://drupal.org/project/admin_menu')),
  );
  // Show block edit links
  $form['admin_settings']['administration']['block_edit_links'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show block editing and configuration links.'),
    '#default_value' => $settings['block_edit_links'],
    '#description' => t('When hovering over a block or viewing blocks in the blocks list page privileged users will see block editing and configuration links.'),
  );
  // Hide help messages
  $form['admin_settings']['administration']['at_admin_hide_help'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide help messages.'),
    '#default_value' => $settings['at_admin_hide_help'],
    '#description' => t('When this setting is checked all help messages will be hidden.'),
  );
  // Development settings
  $form['themedev']['dev'] = array(
    '#type' => 'fieldset',
    '#title' => t('Theme development settings'),
  '#description' => t('WARNING: These settings are for the theme developer! Changing these settings may break your site. Make sure you really know what you are doing before changing these.'),
    '#collapsible' => TRUE,
    '#collapsed' => $settings['rebuild_registry'] ? FALSE : TRUE,
  );
  // Rebuild registry
  $form['themedev']['dev']['rebuild_registry'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rebuild theme registry on every page.'),
    '#default_value' => $settings['rebuild_registry'],
    '#description' => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>. WARNING! This is a performance penalty and must be turned off on production websites.', array('!link' => 'http://drupal.org/node/173880#theme-registry')),
  );
  // Show $theme_info
  if (module_exists('devel')) {
    $form['themedev']['dev']['show_theme_info'] = array(
       '#type' => 'checkbox',
       '#title' => t('Show theme info.'),
       '#default_value' => $settings['show_theme_info'],
       '#description' => t('This will show the output of the global $theme_info variable using Krumo.'),
    );
  }
  else {
    $form['themedev']['dev']['show_theme_info'] = array(
       '#type' => 'checkbox',
       '#title' => t('Show theme info.'),
       '#default_value' => $settings['show_theme_info'],
       '#disabled' => 'disabled',
       '#description' => t('NOTICE: The setting requires the <a href="!link">Devel module</a> to be installed. This will show the output of the global $theme_info variable using Krumo.', array('!link' => 'http://drupal.org/project/devel')),
    );
  }
  // Firebug lite
  $form['themedev']['dev']['load_firebug_lite'] = array(
    '#type' => 'checkbox',
    '#title' => t('Load Firebug lite script for debugging in IE, Opera and Webkit.'),
    '#default_value' => $settings['load_firebug_lite'],
    '#description' => t('WARNING! To use Firebug lite you must first download and install the script to the /js/core/ directory in your theme. <a href="!link">Download Firebug lite</a>.', array('!link' => 'http://getfirebug.com/lite.html')),
  );
  // Add or remove extra classes
  $form['themedev']['dev']['classses'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add or Remove CSS Classes'),
    '#description' => t('<p>This is a fast and easy way to add or remove CSS classes during theme development, so you only print what you require. Once you have decided which classes you need you can set new defaults in your subthemes .info file - this is useful if your theme needs to be portable, such as a commercial theme or when moving from development server to the live site.</p><p>Note that whenever you change the defaults in the .info file you need to click <em>"Reset to defaults"</em> to save them to the variables table and have them applied.</p>'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  // Body classes
  $form['themedev']['dev']['classses']['body_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Page Classes'),
    '#description' => t('Page classes are added to the BODY element and apply to the whole page.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_section'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print section classes.'),
    '#default_value' => $settings['cleanup_classes_section'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .front and .not-front classes.'),
    '#default_value' => $settings['cleanup_classes_front'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_user_status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .logged-in and .not-logged-in classes.'),
    '#default_value' => $settings['cleanup_classes_user_status'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_arg_one'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .page-[arg(1)] classes.'),
    '#default_value' => $settings['cleanup_classes_arg_one'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_normal_path'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .page-[$normal_path] classes.'),
    '#default_value' => $settings['cleanup_classes_normal_path'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_node_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-type-[type] classes.'),
    '#default_value' => $settings['cleanup_classes_node_type'],
  );
  $form['themedev']['dev']['classses']['body_classes']['cleanup_classes_add_edit_delete'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print classes for node add, edit and delete pages (.article-[arg]).'),
    '#default_value' => $settings['cleanup_classes_add_edit_delete'],
  );
  // Node classes
  $form['themedev']['dev']['classses']['article_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Article Classes'),
    '#description' => t('Article classes apply to nodes. They print in the main wrapper DIV for all articles (nodes) in node.tpl.php.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_id'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique ID for each article e.g. #article-1.'),
    '#default_value' => $settings['cleanup_article_id'],
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_classes_sticky'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-sticky class for articles set to sticky.'),
    '#default_value' => $settings['cleanup_article_classes_sticky'],
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_classes_promote'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-promoted class for articles promoted to front.'),
    '#default_value' => $settings['cleanup_article_classes_promote'],
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_classes_teaser'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-teaser class for teaser view (such as front page and taxonomy term lists of articles).'),
    '#default_value' => $settings['cleanup_article_classes_teaser'],
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_classes_preview'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .article-preview class for article previews.'),
    '#default_value' => $settings['cleanup_article_classes_preview'],
  );
  $form['themedev']['dev']['classses']['article_classes']['cleanup_article_classes_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .[content-type]-article classes.'),
    '#default_value' => $settings['cleanup_article_classes_type'],
  );
  // Comment classes
  $form['themedev']['dev']['classses']['comment_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comment Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comments'),
    '#description' => t('Comment classes apply to all comments. They print in comment.tpl.php on the wrapper DIV for each comment.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments']['cleanup_comment_anonymous'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-anonymous for anonymous comments.'),
    '#default_value' => $settings['cleanup_comment_anonymous'],
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments']['cleanup_comment_article_author'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-article-author for author comments.'),
    '#default_value' => $settings['cleanup_comment_article_author'],
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments']['cleanup_comment_by_viewer'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-by-viewer for viewer comments.'),
    '#default_value' => $settings['cleanup_comment_by_viewer'],
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments']['cleanup_comment_new'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .comment-new for new comments.'),
    '#default_value' => $settings['cleanup_comment_new'],
  );
  $form['themedev']['dev']['classses']['comment_classes']['comments']['cleanup_comment_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for comments.'),
    '#default_value' => $settings['cleanup_comment_zebra'],
  );
  $form['themedev']['dev']['classses']['comment_classes']['comment-wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Comment Wrapper'),
   '#description' => t('This class prints in comment-wrapper.tpl.php. The DIV wrapper encloses both the comments and the comment form (if on the same page).'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['comment_classes']['comment-wrapper']['cleanup_comment_wrapper_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a content type class on the comments wrapper i.e. .[content-type]-comments.'),
    '#default_value' => $settings['cleanup_comment_wrapper_type'],
  );
  // Block classes
  $form['themedev']['dev']['classses']['block_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Block Classes'),
    '#description' => t('Comment classes apply to blocks. They print in the main wrapper DIV for all blocks in block.tpl.php.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['block_classes']['cleanup_block_block_module_delta'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a unique ID for each block (#block-module-delta).'),
    '#default_value' => $settings['cleanup_block_block_module_delta'],
  );
  $form['themedev']['dev']['classses']['block_classes']['cleanup_block_classes_module'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print a .block-[module] class.'),
    '#default_value' => $settings['cleanup_block_classes_module'],
  );
  $form['themedev']['dev']['classses']['block_classes']['cleanup_block_classes_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for blocks.'),
    '#default_value' => $settings['cleanup_block_classes_zebra'],
  );
  $form['themedev']['dev']['classses']['block_classes']['cleanup_block_classes_region'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .block-[region] classes.'),
    '#default_value' => $settings['cleanup_block_classes_region'],
  );
  $form['themedev']['dev']['classses']['block_classes']['cleanup_block_classes_count'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .block-[count] classes.'),
    '#default_value' => $settings['cleanup_block_classes_count'],
  );
  // Menu classes
  $form['themedev']['dev']['classses']['menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Menu &amp; Primary and Secondary Links Classes'),
    '#description' => t('Standard menus get their classes via the <code>theme_menu_tree</code> function override while the Primary and Secondary links use the <code>theme_links</code> function override (both are found in template.theme-overrides.inc). Note that the standard menu class options will not appear and will not be applied if the <a href="!link">DHTML Menu</a> module is installed.', array('!link' => 'http://drupal.org/project/dhtml_menu')),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (!function_exists('dhtml_menu_init')) {
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Menu Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    );
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes']['cleanup_menu_menu_class'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the ul.menu class.'),
      '#default_value' => $settings['cleanup_menu_menu_class'],
    );
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes']['cleanup_menu_leaf_class'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the .leaf class on menu list items.'),
      '#default_value' => $settings['cleanup_menu_leaf_class'],
    );
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes']['cleanup_menu_first_last_classes'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the .first and .last classes on menu list items.'),
      '#default_value' => $settings['cleanup_menu_first_last_classes'],
    );
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes']['cleanup_menu_active_classes'] = array(
      '#type' => 'checkbox',
      '#title' => t('Print the .active classes on menu list items (active classes always print on the anchor).'),
      '#default_value' => $settings['cleanup_menu_active_classes'],
    );
  }
  else {
    $form['themedev']['dev']['classses']['menu_classes']['#description'] = t('NOTICE: You currently have the DHTML Menu module installed. The custom menu class options have been disabled because this module will not work correctly with them enabled - you can still set classes for the Primary and Secondary links (below).');
    $form['themedev']['dev']['classses']['menu_classes']['menu_menu_classes']['#disabled'] = 'disabled';
  }
  $form['themedev']['dev']['classses']['menu_classes']['menu_links_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Primary and Secondary Links Classes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    );
  $form['themedev']['dev']['classses']['menu_classes']['menu_links_classes']['cleanup_links_type_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print the type class on Primary and Secondary links.'),
    '#default_value' => $settings['cleanup_links_type_class'],
  );
  $form['themedev']['dev']['classses']['menu_classes']['menu_links_classes']['cleanup_links_active_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print the active classes on Primary and Secondary links.'),
    '#default_value' => $settings['cleanup_links_active_classes'],
  );
  // Item list classes
  $form['themedev']['dev']['classses']['itemlist_classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Item list Classes'),
    '#description' => t('Item list classes are applied using the <code>theme_item_list</code> function override in template.theme-overrides.inc'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['themedev']['dev']['classses']['itemlist_classes']['cleanup_item_list_zebra'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .odd and .even classes for list items.'),
    '#default_value' => $settings['cleanup_item_list_zebra'],
  );
  $form['themedev']['dev']['classses']['itemlist_classes']['cleanup_item_list_first_last'] = array(
    '#type' => 'checkbox',
    '#title' => t('Print .first and .last classes for the first and last items in the list.'),
    '#default_value' => $settings['cleanup_item_list_first_last'],
  );
  return $form;
}