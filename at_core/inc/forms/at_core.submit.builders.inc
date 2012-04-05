<?php
global $theme_key, $theme_name;
$theme_name = $theme_key;
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');

include_once($path_to_at_core . '/inc/plugins.inc');

// Page Layout Builder
// As calls come in from the submit function they are dispatched to the right builder
function at_build_page_layout($method, $sidebar_first, $sidebar_second, $sidebar_unit) {
  global $theme_name;
  $output = '';
  
  // We need to invoke at_load_plugins() to get all the function for the methods
  at_load_plugins($theme_name, $plugin_type = 'page_layout');
  
  $builder_functions = responsive_page_layouts_data_structure();

  foreach ($builder_functions as $function_prefix => $redundant_values) {
    if ($method === $function_prefix) {
      $function = $function_prefix . '_layout';
      $output = $function($sidebar_first, $sidebar_second, $sidebar_unit);  
    }
  }

  return $output;
}

// Panels CSS Builder, programatically builds and optimizes CSS
function at_build_panels_layout_css($panel_styles, $device_panels_data) {
  $output = '';

  foreach ($panel_styles as $panel_subtypes_data) {
    foreach($panel_subtypes_data as $panel_subtype => $panel_subtype_styles_data) {
      if (in_array($panel_subtype, $device_panels_data)) {
        foreach ($panel_subtype_styles_data as $data_type_array) {
          foreach ($data_type_array as $declaration_type => $declaration_block) {
             foreach ($declaration_block as $selector => $declaration) {
              $selectors[$declaration_type][] = $selector;
              $declarations[$declaration_type] = array($declaration_type => $declaration);
            }
          }
        }  
      }
    }
  }
  foreach ($declarations as $type => $groups) {
    foreach ($groups as $group) {
      $these_groups[$group] = $selectors[$type];
    }
  }
  foreach ($these_groups as $css_styles => $selector_array) {
    $merged_css = $css_styles;
    $these_selectors_styles[] = implode(',',$selector_array) . ' {' . $merged_css . '}';
  }

  $output = implode('', $these_selectors_styles);
  return $output;
}

// Build font family CSS
function at_build_font_families($element, $selector, $font_values) {
  $output = '';
  
  // Format values as valid CSS
  $font_styles = array();
  if (!empty($font_values)) {
    if ($font_values['font_size'] === '<none>') {$font_values['font_size'] = '';}
    $font_styles[] = $font_values['font_style']  ? 'font-style:'  . $font_values['font_style'] . ';' : '';
    $font_styles[] = $font_values['font_weight'] ? 'font-weight:' . $font_values['font_weight'] . ';' : '';
    $font_styles[] = $font_values['font_size']   ? 'font-size:'   . $font_values['font_size'] . ';' : '';
    $font_styles[] = $font_values['font_family'] ? 'font-family:' . $font_values['font_family'] : '';
  }
  $font_styles = implode('', $font_styles);

  $css = array();
  switch ($element) {
    case $element:
      $css[] = $selector . '{' . $font_styles . '}';
    break;
  }
  
  $output = implode("\n", $css);
  return $output;
}