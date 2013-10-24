<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\Layouts.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Layout\PageLayout;
use Drupal\at_core\Helpers\BuildInfoFile;

// Fired during theme settings submit.
class LayoutGenerator extends PageLayout {

  // Prepare directories for backups, cause yeh know, we really oughta.
  public function backupPrepareDirs($path, $directory, $subdirectory) {
    $backup_path = $path . '/' . $directory . '/' . $subdirectory;

    if (!file_exists($backup_path)) {
      file_prepare_directory($backup_path, FILE_CREATE_DIRECTORY);
    }

    return $backup_path;
  }

  // Format the regions array for printing in info.yml files
  public function formatLayoutRegions() {
    $regions = array();
    $layouts = self::buildLayoutDataArrays();

    foreach ($layouts['rows'] as $row => $values) {
      foreach ($values['regions'] as $region_name => $region_value) {
        $regions[$region_name] = $region_value;
      }
    }
    $regions['page_top'] = 'Page top';
    $regions['page_bottom'] = 'Page bottom';

    return $regions;
  }

  // TODO remove if we never use hasChangedLayout
  public function hasLayoutChanged() {
    $hasChanged = FALSE;
    $current_layout = theme_get_setting('settings.layout_master_layout', $this->theme);

    if ($current_layout != $this->selected_layout) {
      $hasChanged = TRUE;
    }

    return $hasChanged;
  }

  // Save the info file with new regions list
  public function saveLayoutRegionsList($disable_backups = '') {
    $path = drupal_get_path('theme', $this->theme);
    $info_file = $this->theme . '.info.yml';
    $file_path = $path . '/' . $info_file;

    // Create a backup.
    if ($disable_backups != TRUE) {
      $backup_path = self::backupPrepareDirs($path, 'backup', 'info');
      $backup_file =  $info_file . '.'. date(DATE_ISO8601) . '.txt';

      if (file_exists($file_path)) {
        copy($file_path, $backup_path . '/' . $info_file);
        rename($backup_path . '/' . $info_file, $backup_path . '/' . $backup_file);
      }
    }

    // Parse the current info file.
    $theme_info_data = drupal_parse_info_file($file_path);

    // Get the regions list and insert them into the info array.
    $regions = self::formatLayoutRegions();
    $theme_info_data['regions'] = $regions;

    // Prepare the array for printing in yml format.
    $buildInfo = new BuildInfoFile();
    $rebuilt_info = $buildInfo->buildInfoFile($theme_info_data);

    // Replace the existing info.yml file.
    file_unmanaged_save_data($rebuilt_info, $file_path, FILE_EXISTS_REPLACE);
  }

  // Prepare markup for page.html.twig
  public function formatPageMarkup() {
    $layouts = self::buildLayoutDataArrays();
    $output = array();

    // Format twig markup.
    foreach ($layouts['rows'] as $row => $values) {
      foreach ($values['regions'] as $region_name => $region_value) {
        $row_regions[$row][] = '    {{ page.' . $region_name . ' }}';
      }
      $wrapper_element = 'div';
      if ($row == 'header' || $row == 'main' || $row == 'footer') {
        $wrapper_element = $row;
      }
      $output[$row]['prefix'] = '{% if '. $row . '__regions.active == true %}';
      $output[$row]['wrapper_open'] =  '<'. $wrapper_element . '{{ ' .  $row . '__attributes }}>';
      $output[$row]['container_open'] = '  <div class="container">';
      $output[$row]['regions'] = implode("\n", $row_regions[$row]);
      $output[$row]['container_close'] = '  </div>';
      $output[$row]['wrapper_close'] = '</' . $wrapper_element . '>';
      $output[$row]['suffix'] = '{% endif %}' . "\n";
    }

    // Doc block
    $doc = array();
    $doc[] = '{#';
    $doc[] = '/**';
    $doc[] = ' * ' . ucfirst($this->selected_layout) . ' Layout';
    $doc[] = ' * Generated on: ' . date(DATE_RFC822);
    $doc[] = ' */';
    $doc[] = '#}' . "\n";
    $docblock = implode("\n", $doc);

    // Final preparations.
    $page_rows[] = $docblock;
    $page_rows[] = '{{ messages }}' . "\n"; // TODO Remove if messages becomes a block: https://drupal.org/node/507488
    foreach ($output as $row_output) {
      $page_rows[] = implode("\n", $row_output);
    }

    return $page_rows;
  }

  // Save the output of formatPageMarkup()
  public function savePageTemplate($suggestion = '', $disable_backups = '') {
    $path = drupal_get_path('theme', $this->theme);

    // Set the template file, either it's page or a page suggestion.
    if (!empty($suggestion)) {
      $template_file = 'page--' . $suggestion . '.html.twig';
    }
    else {
      $template_file = 'page.html.twig';
    }

    // Set the template path know we know what template file to use.
    $template_path = $path . '/templates/'. $template_file;

    // Create a backup.
    if ($disable_backups != TRUE) {
      $backup_path = self::backupPrepareDirs($path, 'backup', 'templates');
      $backup_file =  $template_file . '.' . date(DATE_ISO8601) . '.txt';

      if (file_exists($template_path)) {
        copy($template_path, $backup_path . '/'. $template_file);
        rename($backup_path . '/' . $template_file, $backup_path . '/' . $backup_file);
      }
    }

    $page_markup = self::formatPageMarkup();
    file_unmanaged_save_data($page_markup, $template_path, FILE_EXISTS_REPLACE);
  }

}  // end class
