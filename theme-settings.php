<?php // $Id$
// adaptivethemes.com

/**
 * @file theme-settings.php
 */

/**
* Implementation of THEMEHOOK_settings() function.
*
* @param $saved_settings
*   array An array of saved settings for this theme.
* @return
*   array A form array.
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
    'breadcrumb'                            => 'yes',
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
    'front_page_title_display'              => 'title_slogan',
    'page_title_display_custom'             => '',
    'other_page_title_display'              => 'ptitle_slogan',
    'other_page_title_display_custom'       => '',
    'configurable_separator'                => ' | ',
    'meta_keywords'                         => '',
    'meta_description'                      => '',
    'taxonomy_display_default'              => 'only',
    'taxonomy_format_default'               => 'vocab',
    'taxonomy_enable_content_type'          => 0,
    'submitted_by_author_default'           => 1,
    'submitted_by_date_default'             => 1,
    'submitted_by_enable_content_type'      => 0,
    'readmore_default'                      => t('Read more'),
    'readmore_title_default'                => t('Read the rest of this posting.'),
    'readmore_prefix_default'               => '',
    'readmore_suffix_default'               => '',
    'readmore_enable_content_type'          => 0,
    'comment_singular_default'              => t('1 comment'),
    'comment_plural_default'                => t('@count comments'),
    'comment_title_default'                 => t('Jump to the first comment of this posting.'),
    'comment_prefix_default'                => '',
    'comment_suffix_default'                => '',
    'comment_new_singular_default'          => t('1 new comment'),
    'comment_new_plural_default'            => t('@count new comments'),
    'comment_new_title_default'             => t('Jump to the first new comment of this posting.'),
    'comment_new_prefix_default'            => '',
    'comment_new_suffix_default'            => '',
    'comment_add_default'                   => t('Add new comment'),
    'comment_add_title_default'             => t('Add a new comment to this page.'),
    'comment_add_prefix_default'            => '',
    'comment_add_suffix_default'            => '',
    'comment_node_default'                  => t('Add new comment'),
    'comment_node_title_default'            => t('Share your thoughts and opinions related to this posting.'),
    'comment_node_prefix_default'           => '',
    'comment_node_suffix_default'           => '',
    'comment_enable_content_type'           => 0,
    'rebuild_registry'                      => 0,
    'load_firebug_lite'                     => 0,
    'at_admin_theme'                        => 1,
    'at_admin_theme_node'                   => 1,
    'at_admin_theme_logo'                   => 0,
    'block_edit_links'                      => 1,
    'at_admin_hide_help'                    => 0,
    'layout_method'                         => '0',
    'layout_width'                          => '960px',
    'layout_sidebar_first_width'            => '240',
    'layout_sidebar_last_width'             => '240',
    'layout_enable_settings'                => 'off', // set to 'on' to enable, 'off' to disable
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
    $defaults["submitted_by_author_{$type}"]      = $defaults['submitted_by_author_default'];
    $defaults["submitted_by_date_{$type}"]        = $defaults['submitted_by_date_default'];
    $defaults["readmore_{$type}"]                 = $defaults['readmore_default'];
    $defaults["readmore_title_{$type}"]           = $defaults['readmore_title_default'];
    $defaults["readmore_prefix_{$type}"]          = $defaults['readmore_prefix_default'];
    $defaults["readmore_suffix_{$type}"]          = $defaults['readmore_suffix_default'];
    $defaults["comment_singular_{$type}"]         = $defaults['comment_singular_default'];
    $defaults["comment_plural_{$type}"]           = $defaults['comment_plural_default'];
    $defaults["comment_title_{$type}"]            = $defaults['comment_title_default'];
    $defaults["comment_prefix_{$type}"]           = $defaults['comment_prefix_default'];
    $defaults["comment_suffix_{$type}"]           = $defaults['comment_suffix_default'];
    $defaults["comment_new_singular_{$type}"]     = $defaults['comment_new_singular_default'];
    $defaults["comment_new_plural_{$type}"]       = $defaults['comment_new_plural_default'];
    $defaults["comment_new_title_{$type}"]        = $defaults['comment_new_title_default'];
    $defaults["comment_new_prefix_{$type}"]       = $defaults['comment_new_prefix_default'];
    $defaults["comment_new_suffix_{$type}"]       = $defaults['comment_new_suffix_default'];
    $defaults["comment_add_{$type}"]              = $defaults['comment_add_default'];
    $defaults["comment_add_title_{$type}"]        = $defaults['comment_add_title_default'];
    $defaults["comment_add_prefix_{$type}"]       = $defaults['comment_add_prefix_default'];
    $defaults["comment_add_suffix_{$type}"]       = $defaults['comment_add_suffix_default'];
    $defaults["comment_node_{$type}"]             = $defaults['comment_node_default'];
    $defaults["comment_node_title_{$type}"]       = $defaults['comment_node_title_default'];
    $defaults["comment_node_prefix_{$type}"]      = $defaults['comment_node_prefix_default'];
    $defaults["comment_node_suffix_{$type}"]      = $defaults['comment_node_suffix_default'];
  }
    
  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);
  
  // If content type-specifc settings are not enabled, reset the values
  if ($settings['readmore_enable_content_type'] == 0) {
    foreach ($node_types as $type => $name) {
      $settings["readmore_{$type}"]                    = $settings['readmore_default'];
      $settings["readmore_title_{$type}"]              = $settings['readmore_title_default'];
      $settings["readmore_prefix_{$type}"]             = $settings['readmore_prefix_default'];
      $settings["readmore_suffix_{$type}"]             = $settings['readmore_suffix_default'];
    }
  }
  if ($settings['comment_enable_content_type'] == 0) {
    foreach ($node_types as $type => $name) {
      $defaults["comment_singular_{$type}"]         = $defaults['comment_singular_default'];
      $defaults["comment_plural_{$type}"]           = $defaults['comment_plural_default'];
      $defaults["comment_title_{$type}"]            = $defaults['comment_title_default'];
      $defaults["comment_prefix_{$type}"]           = $defaults['comment_prefix_default'];
      $defaults["comment_suffix_{$type}"]           = $defaults['comment_suffix_default'];
      $defaults["comment_new_singular_{$type}"]     = $defaults['comment_new_singular_default'];
      $defaults["comment_new_plural_{$type}"]       = $defaults['comment_new_plural_default'];
      $defaults["comment_new_title_{$type}"]        = $defaults['comment_new_title_default'];
      $defaults["comment_new_prefix_{$type}"]       = $defaults['comment_new_prefix_default'];
      $defaults["comment_new_suffix_{$type}"]       = $defaults['comment_new_suffix_default'];
      $defaults["comment_add_{$type}"]              = $defaults['comment_add_default'];
      $defaults["comment_add_title_{$type}"]        = $defaults['comment_add_title_default'];
      $defaults["comment_add_prefix_{$type}"]       = $defaults['comment_add_prefix_default'];
      $defaults["comment_add_suffix_{$type}"]       = $defaults['comment_add_suffix_default'];
      $defaults["comment_node_{$type}"]             = $defaults['comment_node_default'];
      $defaults["comment_node_title_{$type}"]       = $defaults['comment_node_title_default'];
      $defaults["comment_node_prefix_{$type}"]      = $defaults['comment_node_prefix_default'];
      $defaults["comment_node_suffix_{$type}"]      = $defaults['comment_node_suffix_default'];
    }
  }

  // Create theme settings form widgets using Forms API

  // TNT Fieldset
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
    '#title'         => t('Where should your mission statement be displayed?'),
    '#default_value' => $settings['mission_statement_pages'],
    '#options'       => array(
                          'home' => t('Display mission statement only on front page'),
                          'all' => t('Display mission statement on all pages'),
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
    '#default_value' => $settings['breadcrumb'],
    '#options'       => array(
                          'yes'   => t('Yes'),
                          'no'    => t('No'),
                          'admin' => t('Only in admin section'),                
                        ),
  );
  $form['general_settings']['breadcrumb']['breadcrumb_separator'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Breadcrumb separator'),
    '#description'   => t('Text only. Dont forget to include spaces.'),
    '#default_value' => $settings['breadcrumb_separator'],
    '#size'          => 5,
    '#maxlength'     => 10,
    '#prefix'        => '<div id="div-breadcrumb-collapse">', // jquery hook to show/hide optional widgets
  );
  $form['general_settings']['breadcrumb']['breadcrumb_home'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Show home page link in breadcrumb'),
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
    '#suffix'        => '</div>', // #div-breadcrumb
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
    '#title' => t('Display "not verified" for unregistered usernames'),
    '#default_value' => $settings['user_notverified_display'],
  );
  
  // Search Settings
  $form['general_settings']['search_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search results'),
    '#description' => t('What additional information should be displayed on your search results page?'),
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
    '#description' => t('Here you can make adjustments to which information is shown with your content, and how it is displayed.  You can modify these settings so they apply to all content types, or check the "Use content-type specific settings" box to customize them for each content type.  For example, you may want to show the date on stories, but not pages.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#attributes' => array('class' => 'node_settings'),
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
        '#title'         => t('Display date posted (you can customize this format on your Date and Time settings page)'),
        '#default_value' => $settings["submitted_by_date_{$type}"],
      );
      // Options for default settings
      if ($type == 'default') {
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#title'] = t('Default');
        $form['node_type_specific']['submitted_by_container']['submitted_by']['default']['#collapsed'] = $settings['submitted_by_enable_content_type'] ? TRUE : FALSE;
        $form['node_type_specific']['submitted_by_container']['submitted_by']['submitted_by_enable_content_type'] = array(
          '#type'          => 'checkbox',
          '#title'         => t('Use custom settings for each content type instead of the default above'),
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
      // display
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
      // format
      $form['node_type_specific']['display_taxonomy_container']['display_taxonomy'][$type]["taxonomy_format_{$type}"] = array(
        '#type'          => 'radios',
        '#title'         => t('Taxonomy display format'),
        '#default_value' => $settings["taxonomy_format_{$type}"],
        '#options'       => array(
                              'vocab' => t('Display each vocabulary on a new line'),
                              'list' => t('Display all taxonomy terms together in single list'),
                            ),
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
  // Read More & Comment Link Settings
  $form['node_type_specific']['link_settings'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('Links'),
    '#description' => t('Customize the text of node links'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
   );
  // Read more link settings
  $form['node_type_specific']['link_settings']['readmore'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('“Read more”'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
   );
  // Default & content-type specific settings
  foreach ((array('default' => 'Default') + node_get_types('names')) as $type => $name) {
    // Read more
    $form['node_type_specific']['link_settings']['readmore'][$type] = array(
      '#type'        => 'fieldset',
      '#title'       => t('!name', array('!name' => t($name))),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['readmore'][$type]["readmore_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text'),
      '#default_value' => $settings["readmore_{$type}"],
      '#description'   => t('HTML is allowed.'),
    );
    $form['node_type_specific']['link_settings']['readmore'][$type]["readmore_title_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Title text (tool tip)'),
      '#default_value' => $settings["readmore_title_{$type}"],
      '#description'   => t('Displayed when hovering over link. Plain text only.'),
    );
    $form['node_type_specific']['link_settings']['readmore'][$type]["readmore_prefix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Prefix'),
      '#default_value' => $settings["readmore_prefix_{$type}"],
      '#description'   => t('Text or HTML placed before the link.'),
    );
    $form['node_type_specific']['link_settings']['readmore'][$type]["readmore_suffix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Suffix'),
      '#default_value' => $settings["readmore_suffix_{$type}"],
      '#description'   => t('Text or HTML placed after the link.'),
    );
    // Options for default settings
    if ($type == 'default') {
      $form['node_type_specific']['link_settings']['readmore']['default']['#title'] = t('Default');
      $form['node_type_specific']['link_settings']['readmore']['default']['#collapsed'] = $settings['readmore_enable_content_type'] ? TRUE : FALSE;
      $form['node_type_specific']['link_settings']['readmore']['readmore_enable_content_type'] = array(
        '#type'          => 'checkbox',
        '#title'         => t('Use custom settings for each content type instead of the default above'),
        '#default_value' => $settings['readmore_enable_content_type'],
      );
    }
    // Collapse content-type specific settings if default settings are being used
    else if ($settings['readmore_enable_content_type'] == 0) {
      $form['readmore'][$type]['#collapsed'] = TRUE;
    }
  }
  // Comments link settings
  $form['node_type_specific']['link_settings']['comment'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('“Comment”'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
  );
  // Default & content-type specific settings
  foreach ((array('default' => 'Default') + node_get_types('names')) as $type => $name) {
    $form['node_type_specific']['link_settings']['comment'][$type] = array(
      '#type'        => 'fieldset',
      '#title'       => t('!name', array('!name' => t($name))),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    // Full nodes
    $form['node_type_specific']['link_settings']['comment'][$type]['node'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('For full content'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('“Add new comment” link'),
      '#description' => t('The link when the full content is being displayed.'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add']["comment_node_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text'),
      '#default_value' => $settings["comment_node_{$type}"],
      '#description'   => t('HTML is allowed.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add']["comment_node_title_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Title text (tool tip)'),
      '#default_value' => $settings["comment_node_title_{$type}"],
      '#description'   => t('Displayed when hovering over link. Plain text only.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add']['extra'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('Advanced'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add']['extra']["comment_node_prefix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Prefix'),
      '#default_value' => $settings["comment_node_prefix_{$type}"],
      '#description'   => t('Text or HTML placed before the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['node']['add']['extra']["comment_node_suffix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Suffix'),
      '#default_value' => $settings["comment_node_suffix_{$type}"],
      '#description'   => t('Text or HTML placed after the link.'),
    );
    // Teasers
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('For teasers'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('“Add new comment” link'),
      '#description' => t('The link when there are no comments.'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add']["comment_add_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text'),
      '#default_value' => $settings["comment_add_{$type}"],
      '#description'   => t('HTML is allowed.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add']["comment_add_title_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Title text (tool tip)'),
      '#default_value' => $settings["comment_add_title_{$type}"],
      '#description'   => t('Displayed when hovering over link. Plain text only.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add']['extra'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('Advanced'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add']['extra']["comment_add_prefix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Prefix'),
      '#default_value' => $settings["comment_add_prefix_{$type}"],
      '#description'   => t('Text or HTML placed before the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['add']['extra']["comment_add_suffix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Suffix'),
      '#default_value' => $settings["comment_add_suffix_{$type}"],
      '#description'   => t('Text or HTML placed after the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('“Comments” link'),
      '#description' => t('The link when there are one or more comments.'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']["comment_singular_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text when there is 1 comment'),
      '#default_value' => $settings["comment_singular_{$type}"],
      '#description'   => t('HTML is allowed.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']["comment_plural_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text when there are multiple comments'),
      '#default_value' => $settings["comment_plural_{$type}"],
      '#description'   => t('HTML is allowed. @count will be replaced with the number of comments.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']["comment_title_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Title text (tool tip)'),
      '#default_value' => $settings["comment_title_{$type}"],
      '#description'   => t('Displayed when hovering over link. Plain text only.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']['extra'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('Advanced'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']['extra']["comment_prefix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Prefix'),
      '#default_value' => $settings["comment_prefix_{$type}"],
      '#description'   => t('Text or HTML placed before the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['standard']['extra']["comment_suffix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Suffix'),
      '#default_value' => $settings["comment_suffix_{$type}"],
      '#description'   => t('Text or HTML placed after the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('“New comments” link'),
      '#description' => t('The link when there are one or more new comments.'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']["comment_new_singular_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text when there is 1 new comment'),
      '#default_value' => $settings["comment_new_singular_{$type}"],
      '#description'   => t('HTML is allowed.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']["comment_new_plural_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Link text when there are multiple new comments'),
      '#default_value' => $settings["comment_new_plural_{$type}"],
      '#description'   => t('HTML is allowed. @count will be replaced with the number of comments.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']["comment_new_title_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Title text (tool tip)'),
      '#default_value' => $settings["comment_new_title_{$type}"],
      '#description'   => t('Displayed when hovering over link. Plain text only.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']['extra'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('Advanced'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']['extra']["comment_new_prefix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Prefix'),
      '#default_value' => $settings["comment_new_prefix_{$type}"],
      '#description'   => t('Text or HTML placed before the link.'),
    );
    $form['node_type_specific']['link_settings']['comment'][$type]['teaser']['new']['extra']["comment_new_suffix_{$type}"] = array(
      '#type'          => 'textfield',
      '#title'         => t('Suffix'),
      '#default_value' => $settings["comment_new_suffix_{$type}"],
      '#description'   => t('Text or HTML placed after the link.'),
    );
    // Options for default settings
    if ($type == 'default') {
      $form['node_type_specific']['link_settings']['comment']['default']['#title'] = t('Default');
      $form['node_type_specific']['link_settings']['comment']['default']['#collapsed'] = $settings['comment_enable_content_type'] ? TRUE : FALSE;
      $form['node_type_specific']['link_settings']['comment']['comment_enable_content_type'] = array(
        '#type'          => 'checkbox',
        '#title'         => t('Use custom settings for each content type instead of the default above'),
        '#default_value' => $settings['comment_enable_content_type'],
      );
    }
    // Collapse content-type specific settings if default settings are being used
    else if ($settings['comment_enable_content_type'] == 0) {
      $form['comment'][$type]['#collapsed'] = TRUE;
    }
  }
  
  // SEO settings
  $form['seo'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search engine optimization (SEO) settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // Page titles

  $form['seo']['page_format_titles'] = array(
    '#type' => 'fieldset',
    '#title' => t('Page titles'),
    '#description'   => t('This is the title that displays in the title bar of your web browser. Your site title, slogan, and mission can all be set on your Site Information page'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (module_exists('page_title') == FALSE) {
    // front page title
    $form['seo']['page_format_titles']['front_page_format_titles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Front page title'),
      '#description'   => t('Your front page in particular should have important keywords for your site in the page title'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['seo']['page_format_titles']['front_page_format_titles']['front_page_title_display'] = array(
      '#type' => 'select',
      '#title' => t('Set text of front page title'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#default_value' => $settings['front_page_title_display'],
      '#options' => array(
                    'title_slogan' => t('Site title | Site slogan'),
                    'slogan_title' => t('Site slogan | Site title'),
                    'title_mission' => t('Site title | Site mission'),
                    'custom' => t('Custom (below)'),
                  ),
    );
    $form['seo']['page_format_titles']['front_page_format_titles']['page_title_display_custom'] = array(
      '#type' => 'textfield',
      '#title' => t('Custom'),
      '#size' => 60,
      '#default_value' => $settings['page_title_display_custom'],
      '#description'   => t('Enter a custom page title for your front page'),
    );
    // other pages title
    $form['seo']['page_format_titles']['other_page_format_titles'] = array(
      '#type' => 'fieldset',
      '#title' => t('Other page titles'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['seo']['page_format_titles']['other_page_format_titles']['other_page_title_display'] = array(
      '#type' => 'select',
      '#title' => t('Set text of other page titles'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#default_value' => $settings['other_page_title_display'],
      '#options' => array(
                    'ptitle_slogan' => t('Page title | Site slogan'),
                    'ptitle_stitle' => t('Page title | Site title'),
                    'ptitle_smission' => t('Page title | Site mission'),
                    'ptitle_custom' => t('Page title | Custom (below)'),
                    'custom' => t('Custom (below)'),
                  ),
    );
    $form['seo']['page_format_titles']['other_page_format_titles']['other_page_title_display_custom'] = array(
      '#type' => 'textfield',
      '#title' => t('Custom'),
      '#size' => 60,
      '#default_value' => $settings['other_page_title_display_custom'],
      '#description'   => t('Enter a custom page title for all other pages'),
    );
    // SEO configurable separator
    $form['seo']['page_format_titles']['configurable_separator'] = array(
      '#type' => 'textfield',
      '#title' => t('Title separator'),
      '#description' => t('Customize the separator character used in the page title'),
      '#size' => 60,
      '#default_value' => $settings['configurable_separator'],
    );
  } 
  else {
    $form['seo']['page_format_titles']['#description'] = t('NOTICE: You currently have the "Page Title" module installed and enabled, so the page title theme settings have been disabled to prevent conflicts.  If you later wish to re-enable the page title theme settings, you must first disable the "Page Title" module.');
    $form['seo']['page_format_titles']['front_page_format_titles']['front_page_title_display']['#disabled'] = 'disabled';
    $form['seo']['page_format_titles']['other_page_format_titles']['other_page_title_display']['#disabled'] = 'disabled';
  }
  // Metadata
  $form['seo']['meta'] = array(
    '#type' => 'fieldset',
    '#title' => t('Meta tags'),
    '#description' => t('Meta tags aren\'t used much by search engines anymore, but the meta description is important -- this is what will be shown as the description of your link in search engine results.  NOTE: For more advanced meta tag functionality, check out the Meta Tags (aka. Node Words) module.  These theme settings do not work in conjunction with this module and will not appear if you have it enabled.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (module_exists('nodewords') == FALSE) {
    $form['seo']['meta']['meta_keywords'] = array(
      '#type' => 'textfield',
      '#title' => t('Meta keywords'),
      '#description' => t('Enter a comma-separated list of keywords'),
      '#size' => 60,
      '#default_value' => $settings['meta_keywords'],
    );
    $form['seo']['meta']['meta_description'] = array(
      '#type' => 'textarea',
      '#title' => t('Meta description'),
      '#cols' => 60,
      '#rows' => 6,
      '#default_value' => $settings['meta_description'],
    );
  } 
  else {
    $form['seo']['meta']['#description'] = t('NOTICE: You currently have the "Nodewords" (Meta tags) module installed and enabled, so the meta tag theme settings have been disabled to prevent conflicts.  If you later wish to re-enable the meta tag theme settings, you must first disable the "Nodewords" module.');
    $form['seo']['meta']['meta_keywords']['#disabled'] = 'disabled';
    $form['seo']['meta']['meta_description']['#disabled'] = 'disabled';
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
  // Administrationt settings
  $form['admin_settings']['administration'] = array(
    '#type' => 'fieldset',
    '#title' => t('Admin settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['admin_settings']['administration']['at_admin_theme'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Use the Administration theme for admin pages.'),
    '#default_value' => $settings['at_admin_theme'],
    '#description' => t('The site will use the built in Administration theme for admin pages.'),  
  );
  $form['admin_settings']['administration']['at_admin_theme_node'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Use the Administration theme for node add/edit/delete pages.'),
    '#default_value' => $settings['at_admin_theme_node'],
    '#description' => t('The site will use the built in Administration theme for for node add, edit and delete pages.'),  
  );
  $form['admin_settings']['administration']['at_admin_theme_logo'] = array(
    '#type'  => 'checkbox',
    '#title' => t('Show site logo on admin pages.'),
    '#default_value' => $settings['at_admin_theme_logo'],
    '#description' => t('When checked the site logo will show on admin pages.'),  
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
  if ($settings['layout_enable_settings'] == 'on') {
    $image_path = path_to_theme() .'/css/core/core-images';
    $form['layout'] = array(
      '#type' => 'fieldset',
      '#title' => t('Layout settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description'   => t('Use these settings to customize the layout of your site. NOTE: If you have the built-in Admin theme enabled these settings will not affect the Admin section; they only apply to the "front end" theme. If no overrides are set the default layout will apply.'),
    );
    $form['layout']['layout_width_help'] = array(
      '#prefix'        => '<div class="layout-help">',
      '#suffix'        => '</div>',
      '#value'   => t('<dl><dt>Page width</dt><dd>Set the overall width of the the page. Each width increment is 60px or 1 grid column.</dd></dl>'),
    );
    $form['layout']['layout_width'] = array(
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
    $form['layout']['layout_sidebar_help'] = array(
      '#prefix'        => '<div class="layout-help">',
      '#suffix'        => '</div>',
      '#value'   => t('<dl><dt>Sidebar widths</dt><dd>Set the width of each sidebar. Increments are in 60px or 1 grid column. The content columm will stretch to fill the rest of the page width.</dd></dl>'),
    );
    $form['layout']['layout_sidebar_first_width'] = array(
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
    $form['layout']['layout_sidebar_last_width'] = array(
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
    $form['layout']['layout_method_help'] = array(
      '#prefix'        => '<div class="layout-help">',
      '#suffix'        => '</div>',
      '#value'   => t('<dl><dt>Sidebar layout</dt><dd>Set the default sidebar configuration. You can choose a standard three column layout or place both sidebars to the right or left of the main content column.</dd></dl>'),
    );
    $form['layout']['layout_method'] = array(
      '#type' => 'radios',
      '#prefix'       => '<div class="layout-method">',
      '#suffix'       => '</div>',
      '#default_value' => $settings['layout_method'],      
      '#options' => array(
        '0' => t('<strong>Layout #1</strong>') . theme("image",$image_path."/layout-default.png")        . t('<span class="layout-type">Standard three column layout—left, content, right.</span>'),
        '1' => t('<strong>Layout #2</strong>') . theme("image",$image_path."/layout-sidebars-right.png") . t('<span class="layout-type">Two columns on the right—content, left, right.</span>'),
        '2' => t('<strong>Layout #3</strong>') . theme("image",$image_path."/layout-sidebars-left.png")  . t('<span class="layout-type">Two columns on the left—left, right, content.</span>'),
      ),
     '#attributes' => array('class' => 'layouts'), 
    );
    $form['layout']['layout_enable_settings'] = array(
      '#type'    => 'hidden',
      '#value'   => $settings['layout_enable_settings'],
    );   
  } //endif layout settings
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
  } //endif color schemes
  return $form;
}
