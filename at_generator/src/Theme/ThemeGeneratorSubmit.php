<?php

namespace Drupal\at_generator\Theme;

use Drupal\at_core\Theme\ThemeSettingsInfo;

use Drupal\at_core\File\FileOperations;
use Drupal\at_core\File\DirectoryOperations;

use Drupal\Component\Uuid;
use Drupal\Component\Utility\String;
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

    // Prepare form values and set them into variables.
    $machine_name    = $values['generate']['generate_machine_name'];
    $friendly_name   = String::checkPlain($values['generate']['generate_friendly_name']);
    $subtheme_type   = $values['generate']['generate_type'];
    $skin_base_theme = $values['generate']['generate_skin_base'] ?: 0;
    $clone_source    = $values['generate']['generate_clone_source'] ?: '';

    // Optional extras
    $templates           = $values['generate']['options']['generate_templates'];
    $uikit               = $values['generate']['options']['generate_uikit'];
    $color               = $values['generate']['options']['generate_color'];
    $dottheme_file       = $values['generate']['options']['generate_themefile'];
    $theme_settings_file = $values['generate']['options']['generate_themesettingsfile'];
    $description         = preg_replace('/[^A-Za-z0-9. ]/', '', $values['generate']['options']['generate_description']);
    $version             = $values['generate']['options']['generate_version'];
    $sass_partials       = $values['generate']['generate_skin_sass']; // used by skins only.

    // Path to at core.
    $path = drupal_get_path('theme', 'at_core');

    // Path to where we will save the cloned theme.
    $target = $path . '/../../' . $machine_name;

    // Source theme
    $source = $path . '/../at_starterkits/' . $subtheme_type;

    // Variables for kit sourced themes.
    if ($subtheme_type === 'at_standard' || $subtheme_type === 'at_minimal') {
      $uikit_dir_path = $path . '/../at_starterkits/optional_components/uikit';
      $color_dir_path = $path . '/../at_starterkits/optional_components/color';
      $theme_file_path = $path . '/../at_starterkits/optional_components/THEMENAME.theme';
      $theme_settings_file_path = $path . '/../at_starterkits/optional_components/theme-settings.php';
    }

    // Variables for Clones.
    else if ($subtheme_type === 'at_clone') {
      $clone_source_theme = drupal_get_path('theme', $clone_source);
      $source = $clone_source_theme;
      $theme_file_path = "$target/$machine_name.theme";
      $theme_settings_file_path = "$target/theme-settings.php";
    }

    // Begin generation
    //------------------------------------------------------------------------------------------------
    if (is_dir($source)) {

      // Copy theme to new directory
      $directoryOperations->directoryRecursiveCopy($source, $target);

      // Info file
      $info_file = "$target/$machine_name.info.yml";

      // Libraries file
      $library_file  = "$target/$machine_name.libraries.yml";

      // Classitis file.
      $classitis_file = "$target/$machine_name.classitis.yml";

      // Config
      $settings_file = "$target/config/install/$machine_name.settings.yml"; // used in skins

      // Standard, Minimal and Clones
      if ($subtheme_type === 'at_standard' || $subtheme_type === 'at_minimal' || $subtheme_type === 'at_clone') {

        $configuration_files = array(
          $subtheme_type . '.settings.yml',
          //'block.block.' . $subtheme_type . '_account_menu.yml',
          'block.block.' . $subtheme_type . '_branding.yml',
          'block.block.' . $subtheme_type . '_content.yml',
          'block.block.' . $subtheme_type . '_breadcrumbs.yml',
          'block.block.' . $subtheme_type . '_footer.yml',
          'block.block.' . $subtheme_type . '_help.yml',
          'block.block.' . $subtheme_type . '_login.yml',
          'block.block.' . $subtheme_type . '_main_menu.yml',
          'block.block.' . $subtheme_type . '_search.yml',
          'block.block.' . $subtheme_type . '_tools.yml',
        );

        // Set variables and perform operations depending on the type and options.
        if ($subtheme_type === 'at_standard' || $subtheme_type === 'at_minimal') {

          $source_theme = $subtheme_type;
          $generic_decription = 'Sub theme of AT Core';

          // Templates.
          if ($templates === 1) {
            $directoryOperations->directoryRecursiveCopy("$path/templates", "$target/templates");
          }

          // UI Kit.
          if ($uikit === 1) {
            $directoryOperations->directoryRecursiveCopy($uikit_dir_path, "$target/styles/uikit");
          }

          // Color.
          if ($color === 1) {
            $directoryOperations->directoryRecursiveCopy($color_dir_path, "$target/color");
          }
          if ($color === 0) {
            // Remove the colors.css file
            $directoryOperations->directoryRemove("$target/styles/css/colors.css");
            $directoryOperations->directoryRemove("$target/styles/css/colors-reference.css");

            // Remove the color scss files.
            if ($uikit == 1) {
              $directoryOperations->directoryRemove("$target/styles/uikit/colors.scss");
              $directoryOperations->directoryRemove("$target/styles/uikit/components/_colors.scss");
            }
          }

          // THEMENAME.theme
          if ($dottheme_file === 1) {
            $file_paths['copy_source'] = $theme_file_path;
            $file_paths['copy_dest']   = "$target/THEMENAME.theme";
            $file_paths['rename_oldname'] = "$target/THEMENAME.theme";
            $file_paths['rename_newname'] = "$target/$machine_name.theme";

            $fileOperations->fileCopyRename($file_paths);
            $fileOperations->fileStrReplace("$target/$machine_name.theme", 'HOOK', $machine_name);
          }

          // theme-settings.php
          if ($theme_settings_file === 1) {
            copy($theme_settings_file_path, "$target/theme-settings.php");
            $fileOperations->fileStrReplace("$target/theme-settings.php", 'HOOK', $machine_name);
          }
        }

        // Clone only
        else if ($subtheme_type === 'at_clone') {
          $source_theme = $clone_source;
          $generic_decription = "Clone of $clone_source";

          // Rename dot theme file.
          $fileOperations->fileRename("$target/$source_theme.theme", $theme_file);

          // Strip replace machine names.
          $fileOperations->fileStrReplace($theme_file_path, $source_theme, $machine_name);
          $fileOperations->fileStrReplace($theme_settings_file_path, $source_theme, $machine_name);
        }

        // Rename and strip replace strings in all config files.
        foreach ($configuration_files as $old_file) {
          $new_file = str_replace($source_theme, $machine_name, $old_file);
          $fileOperations->fileRename("$target/config/install/$old_file", "$target/config/install/$new_file");
          $fileOperations->fileStrReplace("$target/config/install/$new_file", $source_theme, $machine_name);
        }

        // Info file
        $fileOperations->fileRename("$target/$source_theme.info.yml", $info_file);

        // Classitis file
        $fileOperations->fileRename("$target/$source_theme.classitis.yml", $classitis_file);

        // libraries file
        $fileOperations->fileRename("$target/$source_theme.libraries.yml", $library_file);
        if ($color === 0) {
          $fileOperations->fileStrReplace("$target/$source_theme.libraries.yml", 'styles/css/colors.css: {}', '');
        }

        // Check and set description and version.
        $description = $description ?: $generic_decription;
        $version = $version ?: '8.0.x';

        // Parse, rebuild and save the themes info.yml file.
        $parser = new Parser();
        $theme_info_data = $parser->parse(file_get_contents($info_file));

        $theme_info_data['name']        = "'$friendly_name'";
        $theme_info_data['type']        = "theme";
        $theme_info_data['description'] = "'$description'";
        $theme_info_data['version']     = $version;


        $theme_info_data['libraries'] = array(
          "$machine_name/base",
        );


        // Build css file arrray
        /*
        $theme_info_data['stylesheets'] = array(
          'all' => array(
            "css/styles.css",
          ),
        );
        if ($color == 1) {
          $theme_info_data['stylesheets'] = array(
            'all' => array(
              "css/styles.css",
              "css/colors.css",
            ),
          );
        }
        */


        foreach($theme_info_data['regions'] as $region_key => $region_name) {
          $theme_info_data['regions'][$region_key] = "'$region_name'";
        }

        // don't hide this new sub-theme
        unset($theme_info_data['hidden']);

        $rebuilt_info = $fileOperations->fileBuildInfoYml($theme_info_data);
        file_unmanaged_save_data($rebuilt_info, $info_file, FILE_EXISTS_REPLACE);
      }

      // Skins
      // Skins are a sub-theme of an existing sub-theme (i.e. a sub-sub-theme). The existing sub-theme
      // becomes the "base theme". I have not tested with sub-sub-sub themes at all, but it will probably work.
      // Their CSS and/or SASS files are blank, they provide no style by default, merely
      // a way of overriding or adding styles to those inherited from the base theme.
      if ($subtheme_type == 'at_skin') {

        // Set the base theme path, we need to it later.
        $base_theme_path = drupal_get_path('theme', $skin_base_theme);

        // Get base theme info.
        $themeInfo = new ThemeSettingsInfo($skin_base_theme);
        $baseThemeInfo = ($themeInfo->baseThemeInfo('info'));

        // Set the description, version etc.
        $description = $description ?: 'Sub theme of ' . $baseThemeInfo['name'] . ' (Skin theme)';
        $version = $version ?: '8.0.x';

        // Rename files
        $fileOperations->fileRename("$target/at_skin.info.yml", $info_file);
        $fileOperations->fileRename("$target/config/install/at_skin.settings.yml", $settings_file);

        // Rename SASS and CSS files.
        $sass_file = "$target/css/sass/$machine_name.scss";
        $css_file = "$target/css/$machine_name.css";
        $fileOperations->fileRename("$target/css/sass/at_skin.scss", $sass_file);
        $fileOperations->fileRename("$target/css/at_skin.css", $css_file);

        // Parse the source base themes info.yml file and extract data.
        $base_theme_info = $base_theme_path . "/$skin_base_theme.info.yml";

        // Parse, rebuild and save the themes info.yml file.
        $parser = new Parser();
        $theme_info_data = $parser->parse(file_get_contents($base_theme_info));

        $theme_info_data['name']          = "'$friendly_name'";
        $theme_info_data['type']          = "theme";
        $theme_info_data['base theme']    = $skin_base_theme;
        $theme_info_data['subtheme type'] = "at_skin";
        $theme_info_data['description']   = "'$description'";
        $theme_info_data['version']       = $version;
        $theme_info_data['stylesheets']   = array('all' => array("css/$machine_name.css"));

        // Regions are not inherited
        foreach($theme_info_data['regions'] as $region_key => $region_name) {
          $theme_info_data['regions'][$region_key] = "'$region_name'";
        }

        // Unset stuff we either don't need or should be inherited from the base theme.
        unset($theme_info_data['hidden']);
        unset($theme_info_data['libraries']); // probably not required by sub-sub themes?
        unset($theme_info_data['stylesheets-remove']); // again, should not be reqiured, needs testing

        // Remove sass partials if not required.
        if ($sass_partials === 0) {
          $directoryOperations->directoryRemove("$target/css/sass");
          $directoryOperations->directoryRemove("$target/css/config.rb");
        }

        $rebuilt_info = $fileOperations->fileBuildInfoYml($theme_info_data);
        file_unmanaged_save_data($rebuilt_info, $info_file, FILE_EXISTS_REPLACE);

        $base_theme_settings = file_get_contents("$base_theme_path/config/install/$skin_base_theme.settings.yml");
        file_unmanaged_save_data($base_theme_settings, $settings_file, FILE_EXISTS_REPLACE);
      }

      // Reset the themes list, see if we can get this to show up without having the clear the cache manually.
      system_list_reset();

      // Set messages, however we may need more validation?
      //----------------------------------------------------------------------
      $generated_path = drupal_get_path('theme', $machine_name);

      drupal_set_message(t("<p>A new theme <b>!theme_name</b> (machine name: <em>\"!machine_name\"</em>) has been generated.</p>", array('!theme_name' => $friendly_name, '!machine_name' => $machine_name, '!theme_path' => $generated_path, '!performance_settings' => base_path() . 'admin/config/development/performance')), 'status');

      // Warn about stylesheets in the new skin theme
      if ($subtheme_type === 'at_skin') {
        drupal_set_message(t('Skin themes do not inherit theme <em>settings</em>, this is important for things like Layout and Library settings. After you enable your new theme be sure to check and configure it\'s settings.', array('!theme_path' => $generated_path, '!machine_name' => $machine_name)), 'warning');
      }
    }

    else {
      // TODO check if this is validated, really this should be in validation.
      $source = $path . '/../at_starterkits/' . $subtheme_type;
      drupal_set_message(t("An error occurred and processing did not complete. The source directory '!dir' does not exist or is not readable.", array('!dir' => $source)), 'error');
    }
  }
}
