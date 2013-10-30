<?php

namespace Drupal\at_core\Generator;

use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\Helpers\RecursiveCopy;
use Drupal\at_core\Helpers\FileRename;
use Drupal\at_core\Helpers\FileStripReplace;
use Drupal\at_core\Helpers\RemoveDirectory;
use Drupal\at_core\Helpers\BuildInfoFile;

class Generator {

  /**
   * Generate a sub-theme.
   *
   * Need to validate some of this stuff and write proper error handling for when things
   * go wrong, rather than just saying hooray, it worked, when no, it did not...
   */
  public function generateSubtheme($values) {

    // Prepare form values and set them into variables.
    $machine_name      = $values['generate']['generate_machine_name'];
    $friendly_name     = check_plain($values['generate']['generate_friendly_name']);
    $subtheme_type     = $values['generate']['generate_type'];
    $skin_base_theme   = $values['generate']['generate_skin_base'] ?: 0;
    $clone_source      = $values['generate']['generate_clone_source'] ?: '';
    $include_templates = $values['generate']['generate_templates'];
    $description       = preg_replace('/[^A-Za-z0-9. ]/', '', $values['generate']['generate_description']);
    $version           = $values['generate']['generate_version'];

    // Path to at core.
    $path = drupal_get_path('theme', 'at_core');

    // Path to where we will save the cloned theme.
    $target = $path . '/../../' . $machine_name;

    // Instantiate helpers
    $recursiveCopy   = new RecursiveCopy();
    $renameFile      = new FileRename();
    $fileStrReplace  = new FileStripReplace();
    $removeDirectory = new RemoveDirectory();
    $rebuildInfo     = new BuildInfoFile();

    // Path to the source theme.
    if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal' || $subtheme_type == 'at_skin') {
      $source = $path . '/../at_starterkits/' . $subtheme_type;
    }
    // Clone is a copy of an existing theme.
    else if ($subtheme_type == 'at_clone') {
      $clone_source_theme = drupal_get_path('theme', $clone_source);
      $source = $clone_source_theme;
    }

    // Begin generation
    if (is_dir($source)) {

      // Copy theme to new directory
      $recursiveCopy->recursiveCopy($source, $target);

      // Set paths to each file we need to modify or delete.
      $info_file           = "$target/$machine_name.info.yml";
      $theme_file          = "$target/$machine_name.theme";
      $breakpoints_file    = "$target/config/$machine_name.breakpoints.yml";
      $settings_file       = "$target/config/$machine_name.settings.yml";
      $theme_settings_file = "$target/theme-settings.php";

      // Only Standard type themes have theme-settings.php
      if ($subtheme_type == 'at_standard') {
        $fileStrReplace->fileStrReplace($theme_settings_file, $subtheme_type, $machine_name);
      }

      // Standard, Minimal and Clones
      if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal' || $subtheme_type == 'at_clone') {

        // Set variables and perform operations depending on the type and options.
        if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {
          $source_theme = $subtheme_type;
          $generic_decription = 'Sub theme of AT Core';

          // Copy over templates if this option is checked.
          if ($include_templates == 1) {
            $templates = array_filter(glob("$path/templates/*.twig"), 'is_file');
            foreach ($templates as $key => $template_path) {
              $template_file = file_get_contents($template_path);
              $template_path_parts = explode('/', $template_path);
              $file_name = array_pop($template_path_parts);
              file_unmanaged_save_data($template_file, "$target/templates/$file_name", FILE_EXISTS_REPLACE);
            }
          }
        }
        elseif ($subtheme_type == 'at_clone') {
          $source_theme = $clone_source;
          $generic_decription = "Clone of $clone_source";
        }

        // Rename copied files.
        $renameFile->fileRename("$target/$source_theme.theme", $theme_file);
        $renameFile->fileRename("$target/$source_theme.info.yml", $info_file);
        $renameFile->fileRename("$target/config/$source_theme.breakpoints.yml", $breakpoints_file);
        $renameFile->fileRename("$target/config/$source_theme.settings.yml", $settings_file);

        // Strip replace strings in files (if they exist).
        $fileStrReplace->fileStrReplace($theme_file, $source_theme, $machine_name);
        $fileStrReplace->fileStrReplace($theme_settings_file, $source_theme, $machine_name);
        $fileStrReplace->fileStrReplace($settings_file, $source_theme, $machine_name);

        // Check and set description and version.
        $description = $description ?: $generic_decription;
        $version = $version ?: '8.x-1.0';

        // Parse, rebuild and save the themes info.yml file.
        $theme_info_data = drupal_parse_info_file($info_file);
        $theme_info_data['name'] = $friendly_name;
        $theme_info_data['type'] = 'theme';  // make dam sure this is set
        $theme_info_data['description'] = $description;
        $theme_info_data['version'] = $version;
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

        // Rename files
        $renameFile->fileRename("$target/at_skin.info.yml", $info_file);
        $renameFile->fileRename("$target/config/at_skin.breakpoints.yml", $breakpoints_file);
        $renameFile->fileRename("$target/config/at_skin.settings.yml", $settings_file);

        // Parse the source base themes info.yml file and extract regions
        $base_theme_info = $base_theme_path . "/$skin_base_theme.info.yml";
        $base_theme_info_data = drupal_parse_info_file($base_theme_info);

        // Parse, rebuild and save the themes info.yml file.
        $theme_info_data = drupal_parse_info_file($info_file);
        $theme_info_data['name'] = $friendly_name;
        $theme_info_data['type'] = 'theme'; // make dam sure this is set
        $theme_info_data['base theme'] = $skin_base_theme;
        $theme_info_data['description'] = $description;
        $theme_info_data['regions'] = $base_theme_info_data['regions'];
        $theme_info_data['version'] = $version;
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
        $base_theme_breakpoints = file_get_contents("$base_theme_path/config/$skin_base_theme.breakpoints.yml");
        file_unmanaged_save_data($base_theme_breakpoints, $breakpoints_file, FILE_EXISTS_REPLACE);

        $base_theme_settings = file_get_contents("$base_theme_path/config/$skin_base_theme.settings.yml");
        file_unmanaged_save_data($base_theme_settings, $settings_file, FILE_EXISTS_REPLACE);
      }

      // Set messages, however we may need more validation that we are doing
      // now before we say a definitive Congrats!
      //----------------------------------------------------------------------
      $generated_path = drupal_get_path('theme', $machine_name);

      drupal_set_message(t("Congrats! Your new theme <b>!theme_name</b> (machine name: <em>\"!machine_name\"</em>) has been generated in <em>\"!theme_path\"</em>. Enable your theme on the Appearance page (click the 'List' tab above), then click the settings link for your theme to configure it. There you will find a new Help tab to guide your setup and theme development.", array(
        '!theme_name' => $friendly_name,
        '!machine_name' => $machine_name,
        '!theme_path' => $generated_path,
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
      $source = 'adaptivetheme/at_starterkits/' . $subtheme_type;
      drupal_set_message(t("An error occurred and processing did not complete. The source directory '!dir' does not exist or is not readable.", array('!dir' => $source)), 'error');
    }
  }

} // end class
