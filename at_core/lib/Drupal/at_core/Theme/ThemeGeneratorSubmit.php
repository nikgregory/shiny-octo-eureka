<?php

namespace Drupal\at_core\Theme;

use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\Helpers\RecursiveCopy;
use Drupal\at_core\Helpers\FileRename;
use Drupal\at_core\Helpers\FileSavePrepare;
use Drupal\at_core\Helpers\FileStripReplace;
use Drupal\at_core\Helpers\RemoveDirectory;
use Drupal\at_core\Helpers\BuildInfoFile;
use Drupal\Component\Uuid;
use Symfony\Component\Yaml\Parser;

class ThemeGeneratorSubmit {

  /**
   * Generate a sub-theme.
   *
   * Need to validate some of this stuff and write proper error handling for when things
   * go wrong, rather than just saying hooray, it worked, when no, it did not...
   */
  public function generateTheme($values) {

    // Instantiate helpers
    $recursiveCopy   = new RecursiveCopy();
    $renameFile      = new FileRename();
    $fileStrReplace  = new FileStripReplace();
    $fileSavePrepare = new FileSavePrepare();
    $removeDirectory = new RemoveDirectory();
    $rebuildInfo     = new BuildInfoFile();

    // Prepare form values and set them into variables.
    $machine_name    = $values['generate']['generate_machine_name'];
    $friendly_name   = check_plain($values['generate']['generate_friendly_name']);
    $subtheme_type   = $values['generate']['generate_type'];
    $skin_base_theme = $values['generate']['generate_skin_base'] ?: 0;
    $clone_source    = $values['generate']['generate_clone_source'] ?: '';

    // Optional extras
    $templates     = $values['generate']['options']['generate_templates'];
    $uikit         = $values['generate']['options']['generate_uikit'];
    $color         = $values['generate']['options']['generate_color'];
    $dottheme_file = $values['generate']['options']['generate_themefile'];
    $settings_file = $values['generate']['options']['generate_themesettingsfile'];
    $description   = preg_replace('/[^A-Za-z0-9. ]/', '', $values['generate']['options']['generate_description']);
    $version       = $values['generate']['options']['generate_version'];

    // Path to at core.
    $path = drupal_get_path('theme', 'at_core');

    // Path to where we will save the cloned theme.
    $target = $path . '/../../' . $machine_name;

    // Variables for kit sourced themes.
    if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {

      $source = $path . '/../at_starterkits/' . $subtheme_type;

      $uikit_dir_path = $path . '/../at_starterkits/optional_components/uikit';
      $color_dir_path = $path . '/../at_starterkits/optional_components/color';

      $theme_file_path = $path . '/../at_starterkits/optional_components/THEMENAME.theme';
      $theme_settings_file_path = $path . '/../at_starterkits/optional_components/theme-settings.php';
    }

    // Variables for Clones.
    else if ($subtheme_type == 'at_clone') {
      $clone_source_theme = drupal_get_path('theme', $clone_source);
      $source = $clone_source_theme;

      $theme_file_path = "$target/$machine_name.theme";
      $theme_settings_file_path = "$target/theme-settings.php";
    }

    // Begin generation
    //------------------------------------------------------------------------------------------------
    if (is_dir($source)) {

      // Copy theme to new directory
      $recursiveCopy->recursiveCopy($source, $target);

      // Set paths to each file we need to modify or delete.
      $info_file  = "$target/$machine_name.info.yml";

      // Config
      $settings_file = "$target/config/$machine_name.settings.yml"; // used in skins

      // Standard, Minimal and Clones
      if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal' || $subtheme_type == 'at_clone') {

        $configuration_files = array(
          $subtheme_type . '.settings.yml',
        );

        // Set variables and perform operations depending on the type and options.
        if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {

          $source_theme = $subtheme_type;
          $generic_decription = 'Sub theme of AT Core';

          // Templates.
          if ($templates == 1) {
            $recursiveCopy->recursiveCopy("$path/templates", "$target/templates");
          }

          // UI Kit.
          if ($uikit == 1) {
            $recursiveCopy->recursiveCopy($uikit_dir_path, "$target/css/uikit");
          }

          // Color.
          if ($color == 1) {
            $recursiveCopy->recursiveCopy($color_dir_path, "$target/color");
          }

          // THEMENAME.theme
          if ($dottheme_file == 1) {
            $file_paths['copy_source'] = $theme_file_path;
            $file_paths['copy_dest']   = "$target/THEMENAME.theme";
            $file_paths['rename_oldname'] = "$target/THEMENAME.theme";
            $file_paths['rename_newname'] = "$target/$machine_name.theme";

            $fileSavePrepare->copyRename($file_paths);
            $fileStrReplace->fileStrReplace("$target/$machine_name.theme", 'THEMEMNAME', $machine_name);
          }

          // theme-settings.php
          if ($settings_file == 1) {
            copy($theme_settings_file_path, "$target/theme-settings.php");
            $fileStrReplace->fileStrReplace("$target/theme-settings.php", 'THEMEMNAME', $machine_name);
          }
        }

        // Clone only
        else if ($subtheme_type == 'at_clone') {
          $source_theme = $clone_source;
          $generic_decription = "Clone of $clone_source";

          // Rename dot theme file.
          $renameFile->fileRename("$target/$source_theme.theme", $theme_file);

          // Strip replace machine names.
          $fileStrReplace->fileStrReplace($theme_file_path, $source_theme, $machine_name);
          $fileStrReplace->fileStrReplace($theme_settings_file_path, $source_theme, $machine_name);
        }

        // Rename and strip replace strings in all config files.
        foreach ($configuration_files as $old_file) {
          $new_file = str_replace($source_theme, $machine_name, $old_file);
          $renameFile->fileRename("$target/config/$old_file", "$target/config/$new_file");
          $fileStrReplace->fileStrReplace("$target/config/$new_file", $source_theme, $machine_name);
        }

        // Info file
        $renameFile->fileRename("$target/$source_theme.info.yml", $info_file);

        // Check and set description and version.
        $description = $description ?: $generic_decription;
        $version = $version ?: '8.x-1.0';

        // Parse, rebuild and save the themes info.yml file.
        $parser = new Parser();
        $theme_info_data = $parser->parse(file_get_contents($info_file));

        $theme_info_data['name']        = "'" . $friendly_name . "'";
        $theme_info_data['type']        = 'theme';
        $theme_info_data['description'] = "'" . $description . "'";
        $theme_info_data['version']     = $version;

        foreach($theme_info_data['regions'] as $region_key => $region_name) {
          $theme_info_data['regions'][$region_key] = "'" . $region_name . "'";
        }

        // don't hide this new sub-theme
        unset($theme_info_data['hidden']);

        $rebuilt_info = $rebuildInfo->buildInfoFile($theme_info_data);
        file_unmanaged_save_data($rebuilt_info, $info_file, FILE_EXISTS_REPLACE);
      }

      // Skins
      // Skins are unique in that they are half clone half starterkit. First we copy the
      // at_skins starterkit then overwrite files with those from the base theme. We need
      // to do this so regions, page template and so on are preserved.
      if ($subtheme_type == 'at_skin') {

        // Set the base theme path, we need to it later.
        $base_theme_path = drupal_get_path('theme', $skin_base_theme);

        // Set the description.
        $themeInfo = new ThemeSettingsInfo($skin_base_theme);
        $baseThemeInfo = ($themeInfo->baseThemeInfo('info'));
        $description = $description ?: 'Sub theme of ' . $baseThemeInfo['name'];
        $version = $version ?: '8.x-1.0';

        // Rename files
        $renameFile->fileRename("$target/at_skin.info.yml", $info_file);
        $renameFile->fileRename("$target/config/at_skin.settings.yml", $settings_file);

        // Parse the source base themes info.yml file and extract regions
        $base_theme_info = $base_theme_path . "/$skin_base_theme.info.yml";
        $base_theme_info_data = drupal_parse_info_file($base_theme_info);

        // Parse, rebuild and save the themes info.yml file.
        $parser = new Parser();
        $theme_info_data = $parser->parse(file_get_contents($info_file));

        $theme_info_data['name']        = "'" . $friendly_name . "'";
        $theme_info_data['type']        = 'theme';
        $theme_info_data['base theme']  = $skin_base_theme;
        $theme_info_data['description'] = "'" . $description . "'";
        $theme_info_data['regions']     = $base_theme_info_data['regions'];
        $theme_info_data['features']    = $base_theme_info_data['features'];
        $theme_info_data['version']     = $version;

        foreach($theme_info_data['regions'] as $region_key => $region_name) {
          $theme_info_data['regions'][$region_key] = "'" . $region_name . "'";
        }

        unset($theme_info_data['hidden']);

        $rebuilt_info = $rebuildInfo->buildInfoFile($theme_info_data);
        file_unmanaged_save_data($rebuilt_info, $info_file, FILE_EXISTS_REPLACE);

        // TODO: this is potentially inadequate or not even required?
        // E.g. Source themes could have many page suggestions, we might
        // need to copy in the entire templates directory to make sure
        // everything works - needs testing.
        // Copy the page.html.twig file from the source base theme.
        //$base_theme_page_template = "$base_theme_path/templates/page.html.twig";
        //$skin_theme_page_template = "$target/templates/page.html.twig";
        //file_unmanaged_save_data($base_theme_page_template, $skin_theme_page_template, FILE_EXISTS_REPLACE);

        // Copy and replace the breakpoints and settings files.
        //$base_theme_breakpoints = file_get_contents("$base_theme_path/config/$skin_base_theme.breakpoints.yml");
        //file_unmanaged_save_data($base_theme_breakpoints, $breakpoints_file, FILE_EXISTS_REPLACE);

        $base_theme_settings = file_get_contents("$base_theme_path/config/$skin_base_theme.settings.yml");
        file_unmanaged_save_data($base_theme_settings, $settings_file, FILE_EXISTS_REPLACE);
      }

      // Reset the themes list, see if we can get this to show up without having the clear the cache manually.
      system_list_reset();

      // Set messages, however we may need more validation?
      //----------------------------------------------------------------------
      $generated_path = drupal_get_path('theme', $machine_name);

      //<p>Before you can use your new theme go to <a href=\"!performance_settings\" target=\"_blank\">Performance Settings</a> and clear all caches.</p>

      drupal_set_message(t("<p>A new theme <b>!theme_name</b> (machine name: <em>\"!machine_name\"</em>) has been generated.</p>", array(
        '!theme_name' => $friendly_name,
        '!machine_name' => $machine_name,
        '!theme_path' => $generated_path,
        '!performance_settings' => base_path() . 'admin/config/development/performance',
        )), 'status');

        // Warn about stylesheets in the new skin theme
        if ($subtheme_type == 'at_skin') {
          drupal_set_message(t('Skin themes do not inherit theme <em>settings<em>, this is important for things like Layout and Library settings you may need - after you enable your new theme be sure to configure it\'s settings. You may need to update your info file: <b>!theme_path/!machine_name.info.yml</b>. Please see the Help tab section <b>Updating Skin Type Sub-themes</b>.', array(
            '!theme_path' => $generated_path,
            '!machine_name' => $machine_name,
            )), 'warning');
        }
      }

      else {
        // TODO check if this is validated, really this should be in validation.

        kpr($source);

        $source = $path . '/../at_starterkits/' . $subtheme_type;
        drupal_set_message(t("An error occurred and processing did not complete. The source directory '!dir' does not exist or is not readable.", array('!dir' => $source)), 'error');
      }
    }

} // end class
