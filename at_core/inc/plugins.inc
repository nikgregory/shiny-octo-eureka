<?php
// AT's liteweight plugin sub-system

/**
 * Find and return all plugins
 *
 * This will search all base themes and the active theme
 * for "plugins" in their info files, and return all associated
 * plugins. Info file entries must follow the Panels plugin structure
 * i.e. "plugins[plugin_type][type] = path/to/plugins", for example for
 * AT page layouts we use: "plugins[page_layout][layout] = layouts/core".
 * Parts of this function are shamelessly ripped from Drupal core's
 * _drupal_theme_initialize() and CTools. AT cannot rely on CTools
 * because themes cannot *require* dependancies.
 *
 * Parameters
 * - $theme_name, usually the active theme, required.
 * - $getpath, optional, if set to TRUE only the paths to
 *   the plugin directories are returned.
 */
function at_get_plugins($theme_name, $getpath = FALSE) {
  $plugins = array();
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
    $extension = 'inc';
    array_unique($plugins_list);
    foreach ($plugins_list as $plugin_provider => $provider) {
      foreach ($provider as $plugin_type => $types) {
        foreach ($types as $type => $path) {
          $provider_path = drupal_get_path('theme', $plugin_provider) . '/' . $path;
          // Provide a way to skip out early with just the paths
          if ($getpath == TRUE) {
            $plugins[$plugin_provider][$plugin_type][$type] = $plugin_provider;
          }
          // Get all the plugin data
          else {
            $plugins[$plugin_provider][$plugin_type][$type] = file_scan_directory($provider_path, '/\.' . $extension . '$/', array('key' => 'name'));
          }
        }
      }
    }
  }

  return $plugins;
}

/**
 * Extract plugin data structures
 *
 * In essence what this does is return the data strutures (arrays)
 * for all plugins of a particular type. If you just want everything
 * then call at_get_plugins(), this is one step further along for free.
 * Again, parts of this are ripped from CTools. As of now only two types
 * are used by AT - "panels" and "page_layout", however its possible
 * more could be used, such as "polyfill" and "font_family". This is not
 * entirely generic enough to handle anything, but can if you stick within
 * the conventions provided, aka "panels", "page_layout" and "everything
 * else".
 *
 * Parameters
 * - $theme_name, usually the active theme, required.
 * - $plugin_type, the plugin type you need to return,
 *   usually one of "panels" or "page_layout", required.
 */
function at_load_plugins($theme_name, $plugin_type) {

  $plugin_data_structures = array();
  $plugins_list = at_get_plugins($theme_name);

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

// Wrapper for at_get_plugins() that returns only the paths 
// of the active theme.
function at_get_plugins_paths() {
  global $theme_name;
  $paths = at_get_plugins($theme_name, $getpath = TRUE);
  return $paths;
}

// this stuff working?
// $thesepaths = at_get_plugins_paths();
// dsm($thesepaths);
// global $theme_name;
// $pl = at_load_plugins($theme_name, $plugin_type = 'page_layout');
// dsm($pl);
