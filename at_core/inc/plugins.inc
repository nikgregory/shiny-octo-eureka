<?php
// AT's liteweight plugin sub-system
global $theme_key, $theme_name;
$theme_name = $theme_key;
$path_to_at_core = drupal_get_path('theme', 'adaptivetheme');


/**
 * Find and return all plugins
 *
 * This will search all base themes and the active theme for "plugins" in their info files, and return all plugins
 * directories. Info file entries must follow the Panels plugin structure i.e.
 * "plugins[plugin_type][type] = path/to/plugins". For example for AT page layouts we use:
 * "plugins[page_layout][layout] = layouts/core". Parts of this function are shamelessly ripped from Drupal core's
 * _drupal_theme_initialize() and CTools. AT cannot rely on CTools because themes cannot *require* dependancies.
 *
 * @param $theme_name, usually the active theme, required.
 * @return array
 */
function at_get_plugins($theme_name) {

  $plugins = drupal_static(__FUNCTION__, array());
  if (empty($plugins)) {
    if ($plugins_cache = cache_get('at_get_plugins_plugins')) {
      $plugins = $plugins_cache->data;
    }
    else {
      $lt = list_themes();

      // First check for base themes and get info
      $base_theme = array();
      $ancestor = $theme_name;
      while ($ancestor && isset($lt[$ancestor]->base_theme)) {
        $ancestor = $lt[$ancestor]->base_theme;
        $base_theme[] = $lt[$ancestor];
      }
      foreach ($base_theme as $base) {
        $themes_names[$base->name] = $base->name;
        $themes_info[$base->name] = $base->info;
      }

      // Now the active theme
      $themes_names[$theme_name] = $theme_name;
      $themes_info[$theme_name] = $lt[$theme_name]->info;

      // Look for and get all the plugins
      if (!empty($themes_info)) {
        foreach ($themes_info as $this_theme => $info) {
          if (array_key_exists('plugins', $info)) {
            foreach ($info['plugins'] as $plugin_type => $types) {
              $plugins_list[$this_theme][$plugin_type] = $types;
            }
          }
        }
        array_unique($plugins_list);
        $plugins = $plugins_list;
      }
    }
    cache_set('at_get_plugins_plugins', $plugins, 'cache');
  }

  return $plugins;
}

//
// at_get_plugins_paths()
//
function at_get_plugins_paths($theme_name) {
  $provider_paths = array();
  $plugins_list = at_get_plugins($theme_name);

  foreach ($plugins_list as $plugin_provider => $provider) {
    foreach ($provider as $plugin_type => $types) {
      foreach ($types as $type => $path) {
        $provider_path = drupal_get_path('theme', $plugin_provider) . '/' . $path;
        $provider_paths[$plugin_provider][$plugin_type][$type] = $provider_path;
      }
    }
  }

  return $provider_paths;
}

//
// at_get_plugins_files()
//
function at_get_plugins_files($theme_name) {
  $plugins_files = array();
  $plugins_list = at_get_plugins($theme_name);

  $extension = 'inc';
  foreach ($plugins_list as $plugin_provider => $provider) {
    foreach ($provider as $plugin_type => $types) {
      foreach ($types as $type => $path) {
        $provider_path = drupal_get_path('theme', $plugin_provider) . '/' . $path;
        $plugins_files[$plugin_provider][$plugin_type][$type] = file_scan_directory($provider_path, '/\.' . $extension . '$/', array('key' => 'name'));
      }
    }
  }

  return $plugins_files;
}

/**
 * Extract plugin data structures
 *
 * In essence what this does is return the data strutures (arrays) for all plugins of a particular type. If you just
 * want everything then call at_get_plugins(), this is one step further along for free. Again, parts of this are ripped
 * from CTools. As of now only two types are used by AT - "panels" and "page_layout", however its possible more could be
 * used, such as "polyfill" and "font_family". This is not entirely generic enough to handle anything, but can if you
 * stick within the conventions provided, aka "panels", "page_layout" and "everything else".
 *
 * @param $theme_name, usually the active theme, required.
 * @param $plugin_type, the plugin type you need to return, usually one of "panels" or "page_layout", required.
 * @return array
 */
function at_load_plugins($theme_name, $plugin_type) {

  $plugin_data_structures = array();
  $plugins_list = at_get_plugins_files($theme_name);

  foreach ($plugins_list as $plugin_provider => $plugin_types) {
    $plugin_providers[] = $plugin_provider;
    foreach ($plugin_types as $type => $plugins) {
      if ($type === $plugin_type) {
        foreach ($plugins as $ptypes => $plugin) {
          $plugins_array[$plugin_provider][$type] = $plugin;
        }
      }
    }
  }
  $plugin_files = array();
  foreach ($plugins_array as $provider => $types) {
    foreach ($types as $key => $value) {
      $plugin_files = array_merge_recursive($plugin_files, $value);
    }
  }

  foreach ($plugin_files as $file_data) {

    include_once(DRUPAL_ROOT . '/' . $file_data->uri);

    // panels
    if ($plugin_type === 'panels') {
      foreach ($plugin_providers as $provider) {
        $identifiers[$provider] = $provider . '_' . $file_data->name . '_panels_layouts';
      }
      foreach ($identifiers as $identifier) {
        $panels_function = $identifier;
        if (function_exists($panels_function)) {
          $plugin_data_structures[] = $panels_function();
        }
      }
    }
    // page_layout
    if ($plugin_type === 'page_layout') {
      $identifier = $file_data->name;
      $page_layout_function = $identifier;
      if (function_exists($page_layout_function)) {
        $plugin_data_structures[] = $page_layout_function();
      }
    }

    // everything else
    else if ($plugin_type !== 'panels' && $plugin_type !== 'page_layout') {
      $identifier = $file_data->name;
      $function = $identifier;
      if (function_exists($function)) {
        $plugin_data_structures[] = $function();
      }
      else {
        drupal_set_message(t('You defined an existing plugin type but no functions exists that match. If you are using Panels then you must use the "function method" in your plugins, see AT Cores panel layout plugins for examples.'), 'error');
      }
    }
  }

  if (empty($plugin_data_structures)) {
    return;
  }

  return $plugin_data_structures;
}

/**
 * Calls the plugin system and retrives all the Page layouts
 *
 * This is used in two places, in the page layouts form to retrieve the layout options for each device group and when
 * building the layout CSS in at_core.submit.builders.inc
 *
 * @return array
 */
function responsive_page_layouts_data_structure() {
  global $theme_name;

  $page_data_structure = drupal_static(__FUNCTION__, array());
  if (empty($page_data_structure)) {
    if ($cache = cache_get('at_page_layouts_data_structure')) {
      $page_data_structure = $cache->data;
    }
    else {

      $data_structure = at_load_plugins($theme_name, $plugin_type = 'page_layout');

      foreach ($data_structure as $plugin => $datum) {
        foreach ($datum as $method => $layout) {
          $page_data_structure[$method] = $layout;
        }
      }
      cache_set('at_page_layouts_data_structure', $page_data_structure, 'cache');
    }
  }

  return $page_data_structure;
}

/**
 * This is used to build the forms for responsive panels in theme settings.
 * Calls the plugin system and retrives all the Panels module layouts.
 *
 * @return array
 */
function responsive_panels_data_structure() {
  global $theme_name;

  $panels_data_structure = drupal_static(__FUNCTION__, array());
  if (empty($panels_data_structure)) {
    if ($cache = cache_get('at_panels_data_structure')) {
      $panels_data_structure = $cache->data;
    }
    else {

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
      cache_set('at_panels_data_structure', $panels_data_structure, 'cache');
    }
  }

  return $panels_data_structure;
}

//
// responsive_panels_plugin_names()
//
function responsive_panels_plugin_names() {

  $plugin_names = drupal_static(__FUNCTION__, array());
  if (empty($plugin_names)) {
    $data_structure = responsive_panels_data_structure();
    foreach ($data_structure as $key => $value) {
      foreach ($value as $type => $sub_types) {
        foreach ($sub_types as $k => $v) {
          $plugin_names[] = $k;
        }
      }
    }
  }

  return $plugin_names;
}

//
// responsive_page_layouts_device_group_options()
//
function responsive_page_layouts_device_group_options($theme_name) {

  $device_group_options = drupal_static(__FUNCTION__, array());
  if (empty($device_group_options)) {
    // $options for each device group come from the "page_layout" plugins
    $layout_methods = responsive_page_layouts_data_structure();

    foreach ($layout_methods as $method => $values) {
      foreach ($values as $key => $value) {
        if ($key == 'device_groups') {
          $method_values[$method] = $value;
        }
      }
    }
    foreach ($method_values as $this_method => $these_values) {
      foreach ($these_values as $k => $dv) {
        $device_group_options[$dv][] = $this_method;
      }
    }
  }

  return $device_group_options;
}

/**
 * Return all the admin css files for a plugin type, this is used primarly to theme the icons for each layout method.
 * The icons are in sprites.
 *
 * @param $theme_name
 * @param $plugin_type
 * @return array
 */
function responsive_plugins_admin_css($theme_name, $plugin_type) {
  $admin_css_files = array();
  $plugin_paths = at_get_plugins_paths($theme_name);

  foreach ($plugin_paths as $providers => $provided_types) {
    foreach ($provided_types as $provider => $type_path) {
      if ($provider === $plugin_type) {
        $admin_css_paths[$providers] = $type_path;
      }
    }
  }

  if ($plugin_type === 'panels') {
    $rpl = responsive_panels_data_structure();
    foreach ($rpl as $group => $layouts) {
      foreach ($layouts as $layout) {
        foreach ($layout as $layout_key => $value) {
          if (isset($value['admin css'])) {
            $css_files[$layout_key] = $value['admin css'];
          }
        }
      }
    }
  }

  if ($plugin_type === 'page_layout') {
    $rpl = responsive_page_layouts_data_structure();
    foreach ($rpl as $layout_method => $value) {
      if (isset($value['admin css'])) {
        $css_files[$layout_method] = $value['admin css'];
      }
    }
  }

  // else {
    // $function = $plugin_type . '_data_structure';
    // if (function_exists($function)) {
      // $rpl = $function();
    // }
  // }

  foreach ($admin_css_paths as $admin_css => $paths) {
    foreach ($paths as $path) {
      foreach ($css_files as $method_key => $file_name) {
        if($method_key !== 'one') {
          $admin_css_files[] = $path . '/' . $method_key . '/' . $file_name;
        }
      }
    }
  }

  if (!isset($rpl)) {
    return; // fail quietly
  }

  return $admin_css_files;
}

/**
 * Base config for page layout builder
 */
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

// this stuff working?
// $thesepaths = at_get_plugins_paths();
// dsm($thesepaths);
// global $theme_name;
// $pl = at_load_plugins($theme_name, $plugin_type = 'page_layout');
// dsm($pl);
