<?php

/**
 * @file
 * Submit generator settings.
 * // TODO skins are disable until further testing and dev.
 */

use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;
use Drupal\Component\Uuid;
use Drupal\Component\Utility\Html;
use Drupal\Component\Serialization\Yaml;

/**
 * Form submit handler for the theme settings form.
 * @param $form
 * @param $form_state
 */
function at_generator_submit_generator(&$form, &$form_state) {
  $build_info = $form_state->getBuildInfo();
  $values = $form_state->getValues();
  $theme = $build_info['args'][0];

  // Generate a new theme.
  if (!empty($values['generate']['generate_machine_name']) && $theme == 'at_generator') {

    $fileOperations      = new FileOperations();
    $directoryOperations = new DirectoryOperations();

    // Prepare form values and set them into variables.
    $machine_name        = $values['generate']['generate_machine_name'];
    $friendly_name       = Html::escape($values['generate']['generate_friendly_name']);
    $subtheme_type       = $values['generate']['generate_type'];
    //$skin_base_theme     = $values['generate']['generate_skin_base'] ?: 0;
    $clone_source        = $values['generate']['generate_clone_source'] ?: '';
    $templates           = $values['generate']['options']['generate_templates'];
    $uikit               = $values['generate']['options']['generate_uikit'];
    $color               = $values['generate']['options']['generate_color'];
    $theme_settings_file = $values['generate']['options']['generate_themesettingsfile'];
    $description         = preg_replace('/[^A-Za-z0-9. ]/', '', Html::escape($values['generate']['options']['generate_description']));
    $version             = Html::escape($values['generate']['options']['generate_version']);

    // Initialize variables.
    $source        = '';
    $source_theme  = '';

    // Generated date time for descriptions.
    $datetime = \Drupal::service('date.formatter')->format(REQUEST_TIME, 'custom', 'D jS, M - G:i');

    // Generic description if the desc field is not set.
    $generic_description = 'Sub theme of AT Core';

    // Path to themes
    $path = drupal_get_path('theme', 'at_core');
    $at_generator_path = drupal_get_path('theme', 'at_generator');

    // Path to where we will save the cloned theme.
    // This could be configurable?
    $at_core_path_parts = explode("/", $path);
    if (in_array('contrib', $at_core_path_parts)) {
      $target_path = array('themes', 'custom');
    }
    else {
      $target_path = array('themes');
    }

    $target_dir = $directoryOperations->directoryPrepare($target_path);
    $target = "$target_dir/$machine_name";

    if (!is_writable('themes')) {
      drupal_set_message(t('The "themes" directory is not writable. To generate a new theme reset permissions for the "@themespath" directory so it is writable, e.g. chmod themes 0755.', array('@themespath' => base_path() .  'themes')), 'error');
      return;
    }

    // Array of UIKit tools.
    $uikit_tools = array(
      'package.json',
      '.csslintrc',
      'Gruntfile.js',
      'Gemfile',
      'Gemfile.lock',
    );

    // Standard and Minimal variables.
    if ($subtheme_type === 'standard') {
      $source_theme = 'THEMENAME';
      $source = $at_generator_path . '/components/starterkit';
    }

    // Clone variables.
    if ($subtheme_type === 'clone') {
      $source_theme = $clone_source;
      $source = drupal_get_path('theme', $source_theme);
    }

    // Skin variables.
//    if ($subtheme_type === 'skin') {
//      $source_theme = $skin_base_theme;
//      $source = drupal_get_path('theme', $source_theme);
//    }

    // All themes have configuration.
    $configuration_files = $directoryOperations->directoryScan("$source/config/install");

    // Files to strip replace strings
    $info_file = "$target/$machine_name.info.yml";
    $library_file = "$target/$machine_name.libraries.yml";
    $shortcodes_file = "$target/$machine_name.shortcodes.yml";

    // Begin generation.
    //------------------------------------------------------------------------------------------------

    // Recursively copy the source theme.
    if (is_dir($source)) {
      $directoryOperations->directoryRecursiveCopy($source, $target);
    }

    // Generated CSS files.
    $generated_css = $directoryOperations->directoryScan("$source/styles/css/generated");
    foreach ($generated_css as $old_css_file) {
      $new_css_file = str_replace($source_theme, $machine_name, $old_css_file);
      $fileOperations->fileRename("$target/styles/css/generated/$old_css_file", "$target/styles/css/generated/$new_css_file");
    }

    // UIKit and Color
    if ($subtheme_type === 'standard') {

      // UIKit
      if ($uikit === 0) {
        $directoryOperations->directoryRemove("$target/styles/uikit");
        // remove files like GEM, Gruntfile.js etc
        foreach ($uikit_tools as $tool) {
          unlink("$target/$tool");
        }
        // Remove all SASS map files and references.
        array_map('unlink', glob("$target/styles/css/components/*.map"));
        $component_css_files = $directoryOperations->directoryScan("$target/styles/css/components");
        foreach ($component_css_files as $component_file_key => $component_file) {
          $map_string = '/*# sourceMappingURL=' . str_replace('.css', '.css.map', $component_file) . ' */';
          $fileOperations->fileStrReplace("$target/styles/css/components/$component_file", $map_string, '');
        }
      }

      // Color.
      if ($color === 0) {
        $directoryOperations->directoryRemove("$target/color");
      }
    }

    // UIKit skins.
    //if ($subtheme_type === 'skin') {
    //  $directoryOperations->directoryRemove("$target/styles/uikit");
    //}

    // Templates.
    if ($subtheme_type === 'standard') {
      $fileOperations->fileStrReplace("$target/templates/generated/page.html.twig", 'THEMENAME', $machine_name);
      if ($templates === 1) {
        $directoryOperations->directoryRecursiveCopy("$path/templates", "$target/templates");
      }
    }
    if ($subtheme_type === 'clone') {
      $cloned_templates = $directoryOperations->directoryScan("$target/templates/generated");
      foreach ($cloned_templates as $cloned_template) {
        $fileOperations->fileStrReplace("$target/templates/generated/$cloned_template", $source_theme, $machine_name);
      }
    }

    //if ($subtheme_type === 'skin') {
    //  $directoryOperations->directoryRemove("$target/templates");
    //}

    // .theme
    if ($subtheme_type === 'standard') {
      $fileOperations->fileRename("$target/$source_theme.theme", "$target/$machine_name.theme");
      $fileOperations->fileStrReplace("$target/$machine_name.theme", 'HOOK', $machine_name);
    }
    if ($subtheme_type === 'clone') {
      $fileOperations->fileRename("$target/$source_theme.theme", "$target/$machine_name.theme");
      $fileOperations->fileStrReplace("$target/$machine_name.theme", $source_theme, $machine_name);
    }
//    if ($subtheme_type === 'skin') {
//      $fileOperations->fileRename("$target/$source_theme.theme", "$target/$machine_name.theme");
//      $skin_theme = file_get_contents("$at_generator_path/components/starterkit_skin/SKIN.theme");
//      $fileOperations->fileReplace($skin_theme, "$target/$machine_name.theme");
//      $fileOperations->fileStrReplace("$target/$machine_name.theme", 'HOOK', $machine_name);
//    }

    // libraries
    $fileOperations->fileRename("$target/$source_theme.libraries.yml", $library_file);

//    if ($subtheme_type === 'skin') {
//      $skin_libraries = file_get_contents("$at_generator_path/components/starterkit_skin/SKIN.libraries.yml");
//      $fileOperations->fileReplace($skin_libraries, "$target/$library_file");
//      $directoryOperations->directoryRemove("$target/styles/css/components");
//      $directoryOperations->directoryRecursiveCopy("$at_generator_path/components", "$target/styles/css");
//    }

    // theme-settings.php
    if ($subtheme_type === 'standard') {
      if ($theme_settings_file === 1) {
        $fileOperations->fileStrReplace("$target/theme-settings.php", 'HOOK', $machine_name);
      }
      else {
        $directoryOperations->directoryRemove("$target/theme-settings.php");
      }
    }
    if ($subtheme_type === 'clone') {
      $fileOperations->fileStrReplace("$target/theme-settings.php", $source_theme, $machine_name);
    }
//    if ($subtheme_type === 'skin') {
//      $directoryOperations->directoryRemove("$target/theme-settings.php");
//    }

    // Config.
    foreach ($configuration_files as $old_config_file) {
      $new_config_file = str_replace($source_theme, $machine_name, $old_config_file);
      $fileOperations->fileRename("$target/config/install/$old_config_file", "$target/config/install/$new_config_file");
      $fileOperations->fileStrReplace("$target/config/install/$new_config_file", 'TARGET', $target);
      $fileOperations->fileStrReplace("$target/config/install/$new_config_file", $source_theme, $machine_name);
    }
    // Skin and clone need their source themes configuration.
    //if ($subtheme_type === 'skin' || $subtheme_type === 'clone') {
    if ($subtheme_type === 'clone') {
      $source_config = \Drupal::config($source_theme . '.settings')->get();
      // Empty if the source theme has never been installed, in which case it
      // should be safe to assume there is no new configuration worth saving.
      if (!empty($source_config)) {
        $old_config = "$target/config/install/$machine_name.settings.yml";
        $new_config = Yaml::encode($source_config);
        $find_generated_files = "themes/$source_theme/styles/css/generated";
        $replace_generated_files = "themes/$machine_name/styles/css/generated";
        $new_config = str_replace($find_generated_files, $replace_generated_files, $new_config);
        $fileOperations->fileReplace($new_config, $old_config);
        $fileOperations->fileStrReplace("$target/config/install/$new_config_file", $source_theme, $machine_name);
      }
    }

    // Info.
    $fileOperations->fileRename("$target/$source_theme.info.yml", $info_file);

    // Shortcodes.
    $fileOperations->fileRename("$target/$source_theme.shortcodes.yml", $shortcodes_file);

    // Parse, rebuild and save the themes info.yml file.
    $theme_info_data = \Drupal::service('info_parser')->parse($info_file);

    // Name and theme type.
    $theme_info_data['name'] = "$friendly_name";
    $theme_info_data['type'] = "theme";

    // Base theme.
//    if ($subtheme_type === 'skin') {
//      $theme_info_data['base theme'] = $source_theme;
//    }

    // Description.
    $base_theme = $theme_info_data['base theme'];
    if ($subtheme_type === 'clone') {
      $description = $description ?: 'Clone of ' . $source_theme;
    }
//    if ($subtheme_type === 'skin') {
//      $base_theme = $source_theme;
//      $description = $description ?: 'Skin theme';
//    }
    $description = $description ?: $generic_description;
    $theme_info_data['description'] = "$description (base theme: $base_theme).<br /> Generated: $datetime";

    // alt text.
    $theme_info_data['alt text'] = "Screenshot for $friendly_name";

    // Version.
    $theme_info_data['version'] = $version ? str_replace(' ', '-', trim($version)) : '8.0.x';

    // Regions.
    foreach($theme_info_data['regions'] as $region_key => $region_name) {
      $theme_info_data['regions'][$region_key] = "$region_name";
    }

    // Unset stuff we don't want or need.
    unset($theme_info_data['hidden']);
    unset($theme_info_data['project']);
    unset($theme_info_data['datestamp']);

    // Libraries.
    if (isset($theme_info_data['libraries-extend']['quickedit/quickedit'])) {
      $theme_info_data['libraries-extend']['quickedit/quickedit'] = array($machine_name . '/quickedit');
    }

    // Save the info file.
    $rebuilt_info = $fileOperations->fileBuildInfoYml($theme_info_data);
    $fileOperations->fileReplace($rebuilt_info, $info_file);


    // Set messages, however we may need more validation?
    //----------------------------------------------------------------------
    // system message for Reports.
    $logger_message = t('A new theme <b>@theme_name</b>, with then machine name: <code><b>@machine_name</b></code>, has been generated.', array(
      '@theme_name'   => $friendly_name,
      '@machine_name' => $machine_name));
    \Drupal::logger('at_generator')->notice($logger_message);

    // Message for the user.
    drupal_set_message(t("<p>A new theme <b>@theme_name</b>, with then machine name: <code><b>@machine_name</b></code>, has been generated.</p><p>You can find your theme here: <code><b>@theme_path</b></code> </p>", array(
      '@theme_name'   => $friendly_name,
      '@machine_name' => $machine_name,
      '@theme_path'   => $target,
      '@performance_settings' => base_path() . 'admin/config/development/performance')),
      'status');

    // Refresh data.
    system_list_reset();
    \Drupal::service('theme_handler')->rebuildThemeData();
  }
  else {
    drupal_set_message(t('Bummer, something went wrong, please try again.'));
  }
}
