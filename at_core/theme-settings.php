<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */

global $theme_key, $path_to_at_core;

// Get our config arrys
include_once($path_to_at_core . '/inc/config.inc');

// Pull in the font lists
if(theme_get_setting('enable_font_settings', $theme_key) === 1) {
  include_once($path_to_at_core . '/inc/font.lists.inc');
}

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function adaptivetheme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {

  global $path_to_at_core;

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  if (isset($form_id)) {
    return;
  }

  // Get the admin theme so we can set a class for styling, Seven does mental things with forms in vertical tabs...
  $admin_theme = variable_get('admin_theme');

  // LAYOUT SETTINGS
  // Build a custom header for the layout settings form
  $layout_header  = '<div class="at-settings-form layout-settings-form admin-theme-'. $admin_theme .'"><div class="layout-header theme-settings-header clearfix">';
  $layout_header .= '<h1>' . t('Layout Settings') . '</h1>';
  $layout_header .= '<a href="http://adaptivethemes.com" target="_blank"><img class="at-logo" src="' . $path_to_at_core . '/logo.png" /></a>';
  $layout_header .= '</div>';

  $form['at-layout'] = array(
    '#type' => 'vertical_tabs',
    '#description' => t('Layout Settings'),
    '#prefix' => $layout_header,
    '#suffix' => '</div>',
    '#weight' => -10,
    '#default_tab' => 'defaults',
    '#attached' => array(
      'css' => array($path_to_at_core . '/css/at.settings.form.css'),
    ),
  );
  include_once($path_to_at_core . '/inc/settings.pagelayout.inc');
  include_once($path_to_at_core . '/inc/settings.responsivepanels.inc');
  include_once($path_to_at_core . '/inc/settings.global.inc');
  include_once($path_to_at_core . '/inc/settings.debug.inc');

  // STYLE SETTINGS
  // Build a custom header for the style settings form
  $styles_header  = '<div class="at-settings-form style-settings-form admin-theme-'. $admin_theme .'"><div class="styles-header theme-settings-header clearfix">';
  $styles_header .= '<h1>' . t('Style Settings') . '</h1>';
  $styles_header .= '</div>';

  $form['at'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -9,
    '#prefix' => $styles_header,
    '#suffix' => '</div>',
    '#default_tab' => 'defaults',
  );
  // Fonts
  if(theme_get_setting('enable_font_settings') === 1) {
    include_once($path_to_at_core . '/inc/font.lists.inc');
    include_once($path_to_at_core . '/inc/settings.fonts.inc');
  }
  // Heading styles
  if(theme_get_setting('enable_heading_settings') === 1) {
    include_once($path_to_at_core . '/inc/settings.headings.inc');
  }
  // Breadcrumbs
  if (theme_get_setting('enable_breadcrumb_settings') === 1) {
    include_once($path_to_at_core . '/inc/settings.breadcrumbs.inc');
  }
  // Images
  if(theme_get_setting('enable_image_settings') === 1) {
    include_once($path_to_at_core . '/inc/settings.images.inc');
  }
  // Search Settings
  if (theme_get_setting('enable_search_settings') === 1) {
    include_once($path_to_at_core . '/inc/settings.search.inc');
  }
  // Horizonatal login block
  if (theme_get_setting('horizontal_login_block_enable') === 'on') {
    if (theme_get_setting('enable_loginblock_settings') === 1) {
      include_once($path_to_at_core . '/inc/settings.loginblock.inc');
    }
  }
  include_once($path_to_at_core . '/inc/settings.tweaks.inc');
  include_once($path_to_at_core . '/inc/settings.classes.inc');

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
  $form['#validate'][] = 'at_theme_settings_validate';
  $form['#submit'][] = 'at_theme_settings_submit';
}
// Include custom form validation and submit functions
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/theme.settings.submit.inc');