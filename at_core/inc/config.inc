<?php
global $path_to_at_core, $theme_name;
include_once($path_to_at_core . '/inc/plugins.inc');

// Originally this file contained very large multidimensional arrays that drove
// the various sub-systems, however since I made the layout system pluggable its
// a bit naked now, and lost its original purpose, it may be removed and these
// functions shifted into the plugins.inc file, especially if I make the fonts
// system and the Polyloader plugabble as well.

// Base config for page layout builder
function page_layout() {
  $variables_array = array(
    'layout',
    'media_query',
    'page_width',
    'page_unit',
    'sidebar_first',
    'sidebar_second',
    'sidebar_unit',
  );
  return $variables_array;
}

/**
 * Calls the plugin system and retrives all the Page layouts
 * Depending on how this function is called it will return different data.
 * For example you might just want the device groups:
 * $pagelayout = responsive_page_layouts_data_structure($variable = 'device_groups');
 *
 * This is used in two places, in the page layouts form to retrieve the layout options
 * for each device group and when building the layout CSS in at_core.submit.builders.inc
 */
function responsive_page_layouts_data_structure($variable = NULL) {
  global $theme_name;

  $data_structure = at_load_plugins($theme_name, $plugin_type = 'page_layout');

  foreach ($data_structure as $plugin => $datum) {

    foreach ($datum as $method => $layout) {

      if (isset($variable)) {
        foreach ($layout as $layout_item => $value) {
          if ($variable == $layout_item) {
            $page_data_structure[$method] = $value;
          }
        }
      }
      else {
        $page_data_structure[$method] = $layout;
      }
    }
  }

  return $page_data_structure;
}

/**
 * This is used to build the forms for responsive panels in theme settings.
 * Calls the plugin system and retrives all the Panels module layouts.
 */
function responsive_panels_data_structure() {
  global $theme_name;

  $data_structure = at_load_plugins($theme_name, $plugin_type = 'panels');

  foreach ($data_structure as $plugin => $datum) {
    foreach ($datum as $key => $value) {
      $type = $value['type'];
      $panels_data_structure[$type][] = $datum;
    }
  }
  if (empty($panels_data_structure)) {
    return; // fail silently
  }

  return $panels_data_structure;
}

/**
 * The fonts array is used by the fonts sub-system to generate forms,
 * build stylesheets and load font styles.
 */
function font_elements() {
  $elements_array = &drupal_static(__FUNCTION__, array());
  if (empty($elements_array)) {
    $elements_array = array(
      'base' => array(
        'type' => 'default',
        'subtype' => 'global',
        'element' => 'bf',
        'selector' => 'html',
        'setting' => 'base_font',
      ),
      'site_name' => array(
        'type' => 'title',
        'subtype' => 'branding',
        'element' => 'sn',
        'selector' => '#site-name',
        'setting' => 'site_name_font',
      ),
      'site_slogan' => array(
        'type' => 'title',
        'subtype' => 'branding',
        'element' => 'ss',
        'selector' => '#site-slogan',
        'setting' => 'site_slogan_font',
      ),
      'page_title' => array(
        'type' => 'title',
        'subtype' => 'global',
        'element' => 'pt',
        'selector' => '#page-title',
        'setting' => 'page_title_font',
      ),
      'node_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'nt',
        'selector' => '.node-title',
        'setting' => 'node_title_font',
      ),
      'comment_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'ct',
        'selector' => '.comment-title',
        'setting' => 'comment_title_font',
      ),
      'block_title' => array(
        'type' => 'title',
        'subtype' => 'entity_title',
        'element' => 'bt',
        'selector' => '.block-title',
        'setting' => 'block_title_font',
      ),
      'content_headings_h1h4' => array(
        'type' => 'heading',
        'subtype' => 'heading_group',
        'element' => 'h1h4',
        'selector' => 'h1,h2,h3,h4',
        'setting' => 'content_headings_h1h4_font',
      ),
      'content_headings_h5h6' => array(
        'type' => 'heading',
        'subtype' => 'heading_group',
        'element' => 'h5h6',
        'selector' => 'h5,h6',
        'setting' => 'content_headings_h5h6_font',
      ),
      'content_headings_h1' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h1',
        'selector' => 'h1',
        'setting' => 'h1_font',
      ),
      'content_headings_h2' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h2',
        'selector' => 'h2',
        'setting' => 'h2_font',
      ),
      'content_headings_h3' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h3',
        'selector' => 'h3',
        'setting' => 'h3_font',
      ),
      'content_headings_h4' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h4',
        'selector' => 'h4',
        'setting' => 'h4_font',
      ),
      'content_headings_h5' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h5',
        'selector' => 'h5',
        'setting' => 'h5_font',
      ),
      'content_headings_h6' => array(
        'type' => 'heading',
        'subtype' => 'heading_level',
        'element' => 'h6',
        'selector' => 'h6',
        'setting' => 'h6_font',
      ),
      'main_menu' => array(
        'type' => 'menu',
        'subtype' => 'menu_variable',
        'element' => 'mm',
        'selector' => '#menu-bar ul,#primary-menu ul',
        'setting' => 'main_menu_font',
      ),
      'secondary_menu' => array(
        'type' => 'menu',
        'subtype' => 'menu_variable',
        'element' => 'sm',
        'selector' => '#secondary-menu ul',
        'setting' => 'secondary_menu_font',
      ),
      'block_menu' => array(
        'type' => 'menu',
        'subtype' => 'menu_block',
        'element' => 'bm',
        'selector' => 'nav.block ul',
        'setting' => 'block_menu_font',
      ),
      'selectors' => array(
        'type' => 'custom',
        'subtype' => '',
        'element' => 'css',
        'selector' => 'custom_css',
        'setting' => 'selectors_font',
      ),
    );
  }

  return $elements_array;
}