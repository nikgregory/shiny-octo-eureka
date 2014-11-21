<?php

namespace Drupal\at_generator\Theme;

use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;

use Drupal\Component\Uuid;
use Drupal\Component\Utility\String;
use Drupal\Component\Serialization\Yaml;

use Symfony\Component\Yaml\Parser;


class ThemeGeneratorSubmit {

  /**
   * Generate a sub-theme.
   *
   * Need to validate some of this stuff and write proper error handling for when things
   * go wrong, rather than just saying hooray, it worked, when no, it did not...
   */
  public function generateTheme($values) {

    $fileOperations      = new FileOperations();
    $directoryOperations = new DirectoryOperations();


    // Prepare form values and set them into variables
    $machine_name    = $values['generate']['generate_machine_name'];
    $friendly_name   = String::checkPlain($values['generate']['generate_friendly_name']);
    $subtheme_type   = $values['generate']['generate_type'];
    $skin_base_theme = $values['generate']['generate_skin_base'] ?: 0;
    $clone_source    = $values['generate']['generate_clone_source'] ?: '';

    $templates           = $values['generate']['options']['generate_templates'];
    $uikit               = $values['generate']['options']['generate_uikit'];
    $color               = $values['generate']['options']['generate_color'];
    $dottheme_file       = $values['generate']['options']['generate_themefile'];
    $theme_settings_file = $values['generate']['options']['generate_themesettingsfile'];
    $description         = preg_replace('/[^A-Za-z0-9. ]/', '', $values['generate']['options']['generate_description']);
    $version             = $values['generate']['options']['generate_version'];


    // Generic description if the desc field is not set
    $generic_decription = 'Sub theme of AT Core';


    // Path to themes
    $path = drupal_get_path('theme', 'at_core');
    $at_generator_path = drupal_get_path('theme', 'at_generator');


    // Path to where we will save the cloned theme
    $target = $path . '/../../' . $machine_name;


    // Standard and Minimal variables
    if ($subtheme_type === 'standard' || $subtheme_type === 'minimal') {
      $source_theme = 'THEMENAME';
      $source = $at_generator_path . '/components/starterkit';
    }


    // Minimal variables
    if ($subtheme_type === 'minimal') {
      $info_file = $at_generator_path . '/components/starterkit_minimal/THEMENAME.info.yml';
    }


    // Clone variables
    if ($subtheme_type === 'clone') {
      $source_theme = $clone_source;
      $source = drupal_get_path('theme', $source_theme);
    }


    // Skin variables
    if ($subtheme_type === 'skin') {
      $source_theme = $skin_base_theme;
      $source = drupal_get_path('theme', $source_theme);
    }


    // All themes have configuration
    $configuration_files = $directoryOperations->directoryScan("$source/config/install");


    // Begin generation
    //------------------------------------------------------------------------------------------------


    // Recursively copy the source theme
    if (is_dir($source)) {
      $directoryOperations->directoryRecursiveCopy($source, $target);
    }

    // Info file
    if ($subtheme_type === 'minimal') {
      $minimal_info_filer_old = "$target/THEMENAME.info.yml";
      $minimal_info_file_new = file_get_contents("$at_generator_path/components/starterkit_minimal/THEMENAME.info.yml");
      $fileOperations->fileReplace($minimal_info_file_new, $minimal_info_filer_old);
    }

    // Layout
    if ($subtheme_type === 'minimal') {
      $site_builder_minimal_old = "$target/layout/site-builder/site-builder.markup.yml";
      $site_builder_minimal_new = file_get_contents("$at_generator_path/components/starterkit_minimal/site-builder.markup.yml");
      $fileOperations->fileReplace($site_builder_minimal_new, $site_builder_minimal_old);
    }


    // Genearated CSS files
    $generated_css = $directoryOperations->directoryScan("$source/styles/css/generated");
    foreach ($generated_css as $old_css_file) {
      $new_css_file = str_replace($source_theme, $machine_name, $old_css_file);
      $fileOperations->fileRename("$target/styles/css/generated/$old_css_file", "$target/styles/css/generated/$new_css_file");
    }
    if ($subtheme_type === 'minimal') {
      $page_layout_minimal_old = "$target/styles/css/generated/$machine_name--layout__page.css";
      $page_layout_minimal_new = file_get_contents("$at_generator_path/components/starterkit_minimal/minimal--layout__page.css");
      $fileOperations->fileReplace($page_layout_minimal_new, $page_layout_minimal_old);
    }


    // UIKit and Color
    if ($subtheme_type === 'standard') {

      // UIKit
      if ($uikit === 0) {
        $directoryOperations->directoryRemove("$target/styles/uikit");
      }

      // Color
      if ($color === 0) {
        $directoryOperations->directoryRemove("$target/color");
        $directoryOperations->directoryRemove("$target/styles/css/colors.css");

        // If UIKit and not color
        if ($uikit === 1) {
          $directoryOperations->directoryRemove("$target/styles/uikit/colors.scss");
          $directoryOperations->directoryRemove("$target/styles/uikit/components/_colors.scss");
        }
      }
    }
    if ($subtheme_type === 'minimal') {
      $directoryOperations->directoryRemove("$target/color");
      $directoryOperations->directoryRemove("$target/styles/css/colors.css");
      $directoryOperations->directoryRemove("$target/styles/uikit");
    }
    if ($subtheme_type === 'skin') {
      $directoryOperations->directoryRemove("$target/styles/uikit");
    }


    // Templates
    if ($subtheme_type === 'standard' || $subtheme_type === 'minimal') {
      if ($templates === 1) {
        $directoryOperations->directoryRecursiveCopy("$path/templates", "$target/templates");
      }
    }
    if ($subtheme_type === 'minimal') {
      $minimal_page_old = "$target/templates/page/page.html.twig";
      $minimal_page_new = file_get_contents("$at_generator_path/components/starterkit_minimal/page.html.twig");
      $fileOperations->fileReplace($minimal_page_old, $minimal_page_new);
    }
    if ($subtheme_type === 'skin') {
      $directoryOperations->directoryRemove("$target/templates");
    }


    // THEMENAME.theme
    if ($subtheme_type === 'standard' || $subtheme_type === 'minimal') {
      if ($dottheme_file === 1) {
        $fileOperations->fileRename("$target/$source_theme.theme", "$target/$machine_name.theme");
        $fileOperations->fileStrReplace("$target/$machine_name.theme", 'HOOK', $machine_name);
      }
      else {
        $directoryOperations->directoryRemove("$target/$source_theme.theme");
      }
    }
    if ($subtheme_type === 'clone') {
      $fileOperations->fileRename("$target/$source_theme.theme", "$target/$machine_name.theme");
      $fileOperations->fileStrReplace("$target/$machine_name.theme", $source_theme, $machine_name);
    }
    if ($subtheme_type === 'skin') {
      $directoryOperations->directoryRemove("$target/$source_theme.theme");
    }


    // theme-settings.php
    if ($subtheme_type === 'standard' || $subtheme_type === 'minimal') {
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
    if ($subtheme_type === 'skin') {
      $directoryOperations->directoryRemove("$target/theme-settings.php");
    }


    // Config
    foreach ($configuration_files as $old_config_file) {
      $new_config_file = str_replace($source_theme, $machine_name, $old_config_file);
      $fileOperations->fileRename("$target/config/install/$old_config_file", "$target/config/install/$new_config_file");
      $fileOperations->fileStrReplace("$target/config/install/$new_config_file", $source_theme, $machine_name);
    }
    if ($subtheme_type === 'minimal') {
      $minimal_settings_old = "$target/config/install/$machine_name.settings.yml";
      $minimal_settings_new = file_get_contents("$at_generator_path/components/starterkit_minimal/THEMENAME.settings.yml");
      $minimal_settings_new = str_replace($source_theme, $machine_name, $minimal_settings_new);
      $fileOperations->fileReplace($minimal_settings_new, $minimal_settings_old);
    }
    // Skin and clone need their source themes configuration
    if ($subtheme_type === 'skin' || $subtheme_type === 'clone') {
      $source_config = \Drupal::config($source_theme . '.settings')->get();
      // Empty if the source theme has never been installed, in which case it should be safe to assume
      // there is no new configuration worth saving.
      if (!empty($source_config)) {
        $old_config = "$target/config/install/$machine_name.settings.yml";
        $new_config = Yaml::encode($source_config);
        $find_generated_files = "themes/$source_theme/styles/css/generated";
        $replace_generated_files = "themes/$machine_name/styles/css/generated";
        $new_config = str_replace($find_generated_files, $replace_generated_files, $new_config);
        $fileOperations->fileReplace($new_config, $old_config);
      }
    }


    // Files to strip replace strings
    $info_file      = "$target/$machine_name.info.yml";
    $library_file   = "$target/$machine_name.libraries.yml";
    $classitis_file = "$target/$machine_name.classitis.yml";


    // Info
    $fileOperations->fileRename("$target/$source_theme.info.yml", $info_file);


    // Classitis
    $fileOperations->fileRename("$target/$source_theme.classitis.yml", $classitis_file);
    if ($subtheme_type === 'minimal') {
      $directoryOperations->directoryRemove($classitis_file);
    }


    // libraries
    $fileOperations->fileRename("$target/$source_theme.libraries.yml", $library_file);
    if ($subtheme_type === 'standard' || $subtheme_type === 'minimal') {
      if ($color === 0) {
        $fileOperations->fileStrReplace($library_file, 'styles/css/colors.css: {}', '');
      }
    }
    if ($subtheme_type === 'minimal') {
      $styles_minimal_old = "$target/styles/css/styles.css";
      $styles_minimal_new = '/* Styles for ' . $machine_name . ' */';
      $fileOperations->fileReplace($styles_minimal_new, $styles_minimal_old);
    }
    if ($subtheme_type === 'skin') {
      $fileOperations->fileStrReplace($library_file, 'styles/css/styles.css: {}', "styles/css/$machine_name-styles.css: {}");
      $directoryOperations->directoryRemove("$target/styles/css/styles.css");
      $fileOperations->fileRename("$target/styles/css/styles.css", "$target/styles/css/$machine_name-styles.css");
      $skin_styles = '/* Styles for ' . $machine_name . ' */';
      $fileOperations->fileReplace($skin_styles, "$target/styles/css/$machine_name-styles.css");
    }


    // Parse, rebuild and save the themes info.yml file.
    $parser = new Parser();
    $theme_info_data = $parser->parse(file_get_contents($info_file));


    // Name and theme type
    $theme_info_data['name'] = "'$friendly_name'";
    $theme_info_data['type'] = "theme";


    // Base theme
    if ($subtheme_type === 'skin') {
      $theme_info_data['base theme'] = $source_theme;
    }


    // Description
    $base_theme = $theme_info_data['base theme'];
    if ($subtheme_type === 'clone') {
      $description = $description ?: 'Clone of ' . $source_theme;
    }
    if ($subtheme_type === 'skin') {
      $base_theme = $source_theme;
      $description = $description ?: 'Skin theme';
    }
    $description = $description ?: $generic_decription;
    $theme_info_data['description'] = "'$description (base theme: $base_theme)'";


    // Version
    $version = $version ?: '8.0.x';
    $theme_info_data['version']     = $version;


    // Libraries
    $theme_info_data['libraries'] = array(
      "$machine_name/base",
    );


    // Regions
    foreach($theme_info_data['regions'] as $region_key => $region_name) {
      $theme_info_data['regions'][$region_key] = "'$region_name'";
    }


    // Unset hidden
    unset($theme_info_data['hidden']);


    // Unset stylesheets-remove
    if ($subtheme_type === 'skin') {
      unset($theme_info_data['stylesheets-remove']);
    }


    // Save the info file
    $rebuilt_info = $fileOperations->fileBuildInfoYml($theme_info_data);
    $fileOperations->fileReplace($rebuilt_info, $info_file);


    // Reset the themes list, see if we can get this to show up without having the clear the cache manually.
    system_list_reset();


    // Set messages, however we may need more validation?
    //----------------------------------------------------------------------
    $generated_path = drupal_get_path('theme', $machine_name);


    drupal_set_message(t("<p>A new theme <b>!theme_name</b> (machine name: <em>\"!machine_name\"</em>) has been generated.</p>", array('!theme_name' => $friendly_name, '!machine_name' => $machine_name, '!theme_path' => $generated_path, '!performance_settings' => base_path() . 'admin/config/development/performance')), 'status');


/*
    // Warn about stylesheets in the new skin theme
    if ($subtheme_type === 'at_skin') {
      drupal_set_message(t('Skin themes do not inherit theme <em>settings</em>, this is important for things like Layout and Library settings. After you enable your new theme be sure to check and configure it\'s settings.', array('!theme_path' => $generated_path, '!machine_name' => $machine_name)), 'warning');
    }
*/
  }

/*
  else {
    // TODO check if this is validated, really this should be in validation.
    $source = $path . '/../at_starterkits/' . $subtheme_type;
    drupal_set_message(t("An error occurred and processing did not complete. The source directory '!dir' does not exist or is not readable.", array('!dir' => $source)), 'error');
  }
*/
}


