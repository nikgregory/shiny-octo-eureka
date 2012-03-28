<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */

// Get our config arrys
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/config.inc');

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function adaptivetheme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  $path_to_at_core = drupal_get_path('theme', 'adaptivetheme');

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  if (isset($form_id)) {
    return;
  }

  // Get the active theme name, we might need it at some stage
  $theme_name = $form_state['build_info']['args'][0];

  // Get the admin theme so we can set a class for styling, Seven does mental things with forms in vertical tabs...
  $admin_theme = variable_get('admin_theme') ? variable_get('admin_theme') : $theme_name;

  // LAYOUT SETTINGS
  // Build a custom header for the layout settings form
  $logo = file_create_url(drupal_get_path('theme', 'adaptivetheme') . '/logo.png');
  $layout_header  = '<div class="at-settings-form layout-settings-form admin-theme-'. drupal_html_class($admin_theme) .'"><div class="layout-header theme-settings-header clearfix">';
  $layout_header .= '<h1>' . t('Layout') . '</h1>';
  $layout_header .= '<a href="http://adaptivethemes.com" title="Adaptivethemes.com - Rocking the hardest since 2006" target="_blank"><img class="at-logo" src="' . $logo . '" /></a>';
  $layout_header .= '</div>';

  $form['at-layout'] = array(
    '#type' => 'vertical_tabs',
    '#description' => t('Layout'),
    '#prefix' => $layout_header,
    '#suffix' => '</div>',
    '#weight' => -10,
    '#attached' => array(
      'css' => array(drupal_get_path('theme', 'adaptivetheme') . '/css/at.settings.form.css'),
    ),
  );
  // Include layout forms, global settings and debug
  include_once($path_to_at_core . '/inc/forms/settings.pagelayout.inc');
  include_once($path_to_at_core . '/inc/forms/settings.responsivepanels.inc');
  include_once($path_to_at_core . '/inc/forms/settings.global.inc');
  include_once($path_to_at_core . '/inc/forms/settings.debug.inc');

  // EXTENSIONS
  if(theme_get_setting('enable_extensions') === 1) {

    // Build a custom header for the Extensions settings form
    $styles_header  = '<div class="at-settings-form style-settings-form admin-theme-'. $admin_theme .'"><div class="styles-header theme-settings-header clearfix">';
    $styles_header .= '<h1>' . t('Extensions') . '</h1>';
    $styles_header .= '</div>';

    $form['at'] = array(
      '#type' => 'vertical_tabs',
      '#weight' => -9,
      '#prefix' => $styles_header,
      '#suffix' => '</div>',
      '#states' => array(
        'visible' => array(':input[name="enable_extensions"]' => array('checked' => TRUE)),
      ),
    );

    // Font lists - we need these for both font and heading settings
    if(theme_get_setting('enable_font_settings') === 1 || theme_get_setting('enable_heading_settings') === 1) {
      include_once($path_to_at_core . '/inc/font.lists.inc');
    }
    // Heading styles
    if(theme_get_setting('enable_heading_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.headings.inc');
    }
    // Fonts
    if(theme_get_setting('enable_font_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.fonts.inc');
    }
    // Heading styles
    if(theme_get_setting('enable_heading_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.headings.inc');
    }
    // Breadcrumbs
    if (theme_get_setting('enable_breadcrumb_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.breadcrumbs.inc');
    }
    // Images
    if(theme_get_setting('enable_image_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.images.inc');
    }
    // Search Settings
    if (theme_get_setting('enable_search_settings') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.search.inc');
    }
    // Horizonatal login block
    if (theme_get_setting('horizontal_login_block_enable') === 'on') {
      if (theme_get_setting('enable_loginblock_settings') === 1) {
        include_once($path_to_at_core . '/inc/forms/settings.loginblock.inc');
      }
    }
    // modify output
    if (theme_get_setting('enable_markup_overides') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.modifyoutput.inc');
    }
    // Metatags
    if (theme_get_setting('enable_mobile_metatags') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.metatags.inc');
    }
    // Touch icons
    if (theme_get_setting('enable_apple_touch_icons') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.touchicons.inc');
    }
    // Custom CSS
    if (theme_get_setting('enable_custom_css') === 1) {
      include_once($path_to_at_core . '/inc/forms/settings.customcss.inc');
    }

    // Always include tweaks (extension settings)
    include_once($path_to_at_core . '/inc/forms/settings.tweaks.inc');
  }

  // Include a hidden form field with the current release information
  $form['at-release'] = array(
    '#type' => 'hidden',
    '#default_value' => '7.x-3.x',
  );

  // Collapse annoying forms
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
  $form['#validate'][] = 'at_core_settings_validate';
  $form['#submit'][] = 'at_core_settings_submit';
}
// Include custom form validation and submit functions
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/forms/at_core.validate.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/forms/at_core.submit.inc');