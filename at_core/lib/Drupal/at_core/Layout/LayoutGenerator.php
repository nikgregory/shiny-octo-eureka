<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\Layouts.
 */

namespace Drupal\at_core\Layout;

use Drupal\at_core\Layout\PageLayout;
use Drupal\at_core\Helpers\BuildInfoFile;
use Drupal\at_core\Helpers\FileSavePrepare;
use Symfony\Component\Yaml\Parser;

// Fired during theme settings submit.
class LayoutGenerator extends PageLayout {

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

  // Save the info file with new regions list and create a backup.
  public function saveLayoutRegionsList($target, $enable_backups = '') {
    $path = drupal_get_path('theme', $target);
    $info_file = $target . '.info.yml';
    $file_path = $path . '/' . $info_file;

    // Create a backup.
    if ($enable_backups == TRUE) {
      $fileSavePrepare = new FileSavePrepare();
      $backup_path = $fileSavePrepare->prepareDirectories($backup_file_path = array($path, 'backup', 'info'));

      //Add a date time string to make unique and for easy identification, save as .txt to avoid conflicts.
      $backup_file =  $info_file . '.'. date(DATE_ISO8601) . '.txt';

      $file_paths = array(
       'copy_source' => $file_path,
       'copy_dest' => $backup_path . '/' . $info_file,
       'rename_oldname' => $backup_path . '/' . $info_file,
       'rename_newname' => $backup_path . '/' . $backup_file,
      );
      $backupInfo = $fileSavePrepare->copyRename($file_paths);
    }

    // Parse the current info file.
    //$theme_info_data = drupal_parse_info_file($file_path);
    $parser = new Parser();
    $theme_info_data = $parser->parse(file_get_contents($file_path));

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

    $messages = '  {{ messages }}';
    if (\Drupal::moduleHandler()->moduleExists('at_blocks')) {
      $messages = '  {# /* AT Blocks module installed, messages variable omitted. TODO: Remove this comment if messages becomes a block: https://drupal.org/node/507488 */ #}';
    }

    // Format twig markup.
    foreach ($layouts['rows'] as $row => $values) {
      foreach ($values['regions'] as $region_name => $region_value) {
        $row_regions[$row][] = '      {{ page.' . $region_name . ' }}';
      }
      $wrapper_element = 'div';
      //if ($row == 'header' || $row == 'main' || $row == 'footer') {
      if ($row == 'header' || $row == 'footer') {
        $wrapper_element = $row;
      }
      $output[$row]['prefix'] = '  {% if '. $row . '__regions.active == true %}';
      $output[$row]['wrapper_open'] =  '  <'. $wrapper_element . '{{ ' .  $row . '__attributes }}>';
      $output[$row]['container_open'] = '    <div class="regions regions__' . $row . '">';
      $output[$row]['regions'] = implode("\n", $row_regions[$row]);
      $output[$row]['container_close'] = '    </div>';
      $output[$row]['wrapper_close'] = '  </' . $wrapper_element . '>';
      $output[$row]['suffix'] = '  {% endif %}' . "\n";
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
    $page_rows[] = '<div{{ attributes }}>' . "\n";
    $page_rows[] = $messages . "\n"; // TODO Remove if messages becomes a block: https://drupal.org/node/507488
    foreach ($output as $row_output) {
      $page_rows[] = implode("\n", $row_output);
    }
    $page_rows[] = '</div>';

    return $page_rows;
  }

  // Save the output of formatPageMarkup()
  public function savePageTemplate($target, $suggestion = '', $enable_backups = '') {
    // Path to target theme where the template will be saved.
    $path = drupal_get_path('theme', $target);

    // Set the template file, either it's page or a page suggestion.
    if (!empty($suggestion)) {
      $template_file = 'page--' . $suggestion . '.html.twig';
    }
    else {
      $template_file = 'page.html.twig';
    }

    // Set the template path.
    $template_path = $path . '/templates/'. $template_file;

    // Create a backup.
    if ($enable_backups == TRUE) {
      $fileSavePrepare = new FileSavePrepare();
      $backup_path = $fileSavePrepare->prepareDirectories($backup_file_path = array($path, 'backup', 'templates'));

      //Add a date time string to make unique and for easy identification, save as .txt to avoid conflicts.
      $backup_file =  $template_file . '.' . date(DATE_ISO8601) . '.txt';

      $file_paths = array(
       'copy_source' => $template_path,
       'copy_dest' => $backup_path . '/' . $template_file,
       'rename_oldname' => $backup_path . '/' . $template_file,
       'rename_newname' => $backup_path . '/' . $backup_file,
      );
      $backupTemplate = $fileSavePrepare->copyRename($file_paths);
    }

    // Write the template file.
    $page_markup = self::formatPageMarkup();
    file_unmanaged_save_data($page_markup, $template_path, FILE_EXISTS_REPLACE);
  }

}  // end class
