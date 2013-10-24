<?php

namespace Drupal\at_core\Generator;

use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\at_core\Helpers\RecursiveCopy;
use Drupal\at_core\Helpers\FileStripReplace;
use Drupal\at_core\Helpers\RemoveDirectory;

class Generator {

  /**
   * Generate a sub-theme.
   *
   * Need to validate some of this stuff and write proper error handling for when things
   * go wrong, rather than just saying hooray, it worked, when no, it did not...
   */
  public function generateSubtheme($values) {

    $machine_name  = $values['generate']['generate_machine_name'];
    $friendly_name = check_plain($values['generate']['generate_friendly_name']);
    $subtheme_type = $values['generate']['generate_type'];
    $base_theme    = $values['generate']['generate_base'];
    $description   = preg_replace('/[^A-Za-z0-9. ]/', '', $values['generate']['generate_description']);

    $path   = drupal_get_path('theme', 'at_core');
    $target = $path . '/../../' . $machine_name;

    // Instantiate helpers
    $recursiveCopy   = new RecursiveCopy();
    $fileStrReplace  = new FileStripReplace();
    $removeDirectory = new RemoveDirectory();

    // Set the path to the source theme to be cloned
    if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {
      $source = $path . '/../at_starterkits/' . $subtheme_type;
    }
    else if ($subtheme_type == 'at_skin') {
      $skin_base_theme_path = drupal_get_path('theme', $base_theme);
      $source = $skin_base_theme_path;
    }

    // Begin generation
    if (is_dir($source)) {

      // Copy theme to new directory
      $recursiveCopy->recursiveCopy($source, $target);

      // Files
      $info_file           = "$target/$machine_name.info.yml";
      $theme_file          = "$target/$machine_name.theme";
      $breakpoints_file    = "$target/config/$machine_name.breakpoints.yml";
      $settings_file       = "$target/config/$machine_name.settings.yml";
      $theme_settings_file = "$target/theme-settings.php";

      // Standard
      //----------------------------------------------------------------------
      if ($subtheme_type == 'at_standard') {
        $friendly_name_needle = 'AT Standard';
        $description_needle   = 'Practical starter theme for site builders, themers, and rapid prototyping.';

        // Only standard based themes have theme-settings.php
        $fileStrReplace->fileStrReplace($theme_settings_file, $subtheme_type, $machine_name);
      }

      // Minimal
      //----------------------------------------------------------------------
      if ($subtheme_type == 'at_minimal') {
        $friendly_name_needle = 'AT Minimal';
        $description_needle   = 'Just the basics - a clean start for custom themes.';
      }

      // Standard or Minimal
      //----------------------------------------------------------------------
      if ($subtheme_type == 'at_standard' || $subtheme_type == 'at_minimal') {

        if (file_exists("$target/$subtheme_type.theme")) {
          rename("$target/$subtheme_type.theme", $theme_file);
        }
        if (file_exists("$target/$subtheme_type.info.yml")) {
          rename("$target/$subtheme_type.info.yml", $info_file);
        }
        if (file_exists("$target/config/$subtheme_type.breakpoints.yml")) {
          rename("$target/config/$subtheme_type.breakpoints.yml", $breakpoints_file);
        }
        if (file_exists("$target/config/$subtheme_type.settings.yml")) {
          rename("$target/config/$subtheme_type.settings.yml", $settings_file);
        }

        $fileStrReplace->fileStrReplace($theme_file, $subtheme_type, $machine_name);
        $fileStrReplace->fileStrReplace($info_file, $friendly_name_needle, $friendly_name);

        $fileStrReplace->fileStrReplace($info_file, 'hidden: true', 'hidden: false');

        $description = $description ?: 'Sub theme of AT Core';
        $fileStrReplace->fileStrReplace($info_file, $description_needle, $description);
      }

      // Skin (clone of an existing sub-theme)
      //----------------------------------------------------------------------
      if ($subtheme_type == 'at_skin') {

        $themeInfo = new ThemeSettingsInfo($base_theme);
        $baseThemeInfo = ($themeInfo->baseThemeInfo('info'));
        $description = $description ?: 'Sub theme of ' . $baseThemeInfo['name'];

        if (file_exists("$target/$base_theme.info.yml")) {
          $fileStrReplace->fileStrReplace("$target/$base_theme.info.yml", $baseThemeInfo['name'], $friendly_name);
          $fileStrReplace->fileStrReplace("$target/$base_theme.info.yml", $baseThemeInfo['base theme'], $base_theme);
          $fileStrReplace->fileStrReplace("$target/$base_theme.info.yml", $baseThemeInfo['description'], $description);
          rename("$target/$base_theme.info.yml", $info_file);
        }
        if (file_exists("$target/config/$base_theme.breakpoints.yml")) {
          rename("$target/config/$base_theme.breakpoints.yml", $breakpoints_file);
        }
        if (file_exists("$target/config/$base_theme.settings.yml")) {
          rename("$target/config/$base_theme.settings.yml", $settings_file);
        }

        // Delete some files we just don't need in a skin
        if (file_exists($theme_settings_file)) {
          unlink($theme_settings_file);
        }

        // TODO: do we need this for Libraries inheritance?
        /*
        if (file_exists("$target/$base_theme.theme")) {
          unlink("$target/$base_theme.theme");
        }
        */

        // Now delete some directories also, we don't need libraries or templates
        if (is_dir("$target/templates")) {
          $directories[] = "$target/templates";
        }
        if (is_dir("$target/libraries")) {
          $directories[] = "$target/libraries";
        }
        foreach ($directories as $directory) {
          $removeDirectory->removeDirectory($directory);
        }

        // Recreate the templates directory, if the user wants to create new layout overrides.
        mkdir("$target/templates", 0775);
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
