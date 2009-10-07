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
    'admin_user_links'                      => 1,
    'block_edit_links'                      => 1,
    'at_admin_hide_help'                    => 0,
  );
  
  // Make the default content-type settings the same as the default theme settings,
  // so we can tell if content-type-specific settings have been altered.
  $defaults = array_merge($defaults, theme_get_settings());
    
  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);

  // Create theme settings form widgets using Forms API

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
  return $form;
}