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

  public function __construct($theme, $layout = '', $active_regions = array()) {
    parent::__construct();
  }

  public function saveCSSLayout($theme, $css_data, $css_config, $compatible_layout, $generated_files_path) {
    $output = array();
    $css_rows = array();
    $theme_path = drupal_get_path('theme', $theme) . '/layout/' . $compatible_layout;
    $path_to_css_files = $theme_path . '/' . $css_config['css_files_path'];

    foreach ($css_data as $suggestion => $breakpoints) {
      foreach ($breakpoints as $breakpoint_keys => $breakpoint_values) {
        foreach ($breakpoint_values['rows'] as $row_keys => $row_values) {
          foreach ($css_config['css'] as $css_key => $css_values) {
            $css_file[$suggestion][$breakpoint_keys][$row_keys] .= file_get_contents($path_to_css_files . '/' . $css_key . '/' . $row_values . '.css');
            $replace_class = 'page-row__' . $row_keys . ' ';
            $file = str_replace($row_values, $replace_class, $css_file[$suggestion][$breakpoint_keys][$row_keys]);
            $css_rows[$suggestion][$breakpoint_keys][$breakpoint_keys . '_' . $row_keys] = $file;
          }
        }
        if (!empty($css_rows[$suggestion][$breakpoint_keys])) {
          $output[$suggestion][] = '@media ' . $breakpoint_values['query'] . ' {';
          $output[$suggestion][] =  implode('', $css_rows[$suggestion][$breakpoint_keys]);
          $output[$suggestion][] = '}';
        }
      }
    }

    foreach ($output as $suggestion => $css) {
      $file_content = implode("\n", $css);
      if (!empty($file_content)) {
        $file_name = $theme . '-' . $suggestion . '--layout.css';
        $filepath = "$generated_files_path/$file_name";
        file_unmanaged_save_data($file_content, $filepath, FILE_EXISTS_REPLACE);
      }
    }
  }

  // Format the regions array for printing in info.yml files
  public function formatLayoutRegions() {
    $regions = array();
    $layouts = self::buildLayoutDataArrays();

    foreach ($layouts['rows'] as $row => $values) {
      foreach ($values['regions'] as $region_name => $region_value) {
        $regions[$region_name] = "'" . $region_value . "'";
      }
    }
    $regions['page_top'] = "'" . 'Page top' . "'";
    $regions['page_bottom'] = "'" . 'Page bottom' . "'";

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

    // During the parse get contents single quotes are stripped from
    // strings, we have to add them back because they might have spaces.
    $theme_info_data['name'] = "'" . $theme_info_data['name'] . "'";
    $theme_info_data['description'] = "'" . $theme_info_data['description'] . "'";

    // Prepare the array for printing in yml format.
    $buildInfo = new BuildInfoFile();
    $rebuilt_info = $buildInfo->buildInfoFile($theme_info_data);

    // Replace the existing info.yml file.
    file_unmanaged_save_data($rebuilt_info, $file_path, FILE_EXISTS_REPLACE);
  }





  // Prepare markup for page.html.twig
  public function formatPageMarkup() {

    $layout = self::buildLayoutDataArrays();
    $output = array();

    // Doc block
    $doc = array();
    $doc[] = '{#';
    $doc[] = '/**';
    $doc[] = ' * ' . ucfirst($this->layout) . ' Layout';
    $doc[] = ' * Generated on: ' . date(DATE_RFC822);
    $doc[] = ' */';
    $doc[] = '#}' . "\n";
    $docblock = implode("\n", $doc);

    // Template path
    $template_file = $this->layout_path . $this->layout . '/' . $layout['template'];

    kpr($template_file);

    // Get the template file, if not found attempt to generate template code programmatically.
    if (file_exists($template_file)) {
      $template = file_get_contents($template_file);
      //kpr($template);
    }
    else {

      foreach ($layout['rows'] as $row => $values) {
        foreach ($values['regions'] as $region_name => $region_value) {
          $row_regions[$row][] = '      {{ page.' . $region_name . ' }}';
        }
        $wrapper_element = 'div';
        if ($row == 'header' || $row == 'footer') {
          $wrapper_element = $row;
        }


        // Temporarily add tabs, we can remove this later when the tabs become a block.
        if ($row == 'main') {
          $output[$row]['prefix'] = '  {% if tabs %}<div class="page-row__temporary-tabs"><div class="regions">{{ tabs }}</div></div>{% endif %}'  . "\n\n" . '{% if '. $row . '__regions.active == true %}';
        }
        else {
          $output[$row]['prefix'] = '  {% if '. $row . '__regions.active == true %}';
        }

        /*
        $output[$row]['wrapper_open'] =  '  <'. $wrapper_element . '{{ ' .  $row . '__attributes }}>';
        $output[$row]['container_open'] = '    <div class="regions regions__' . $row . '">';
        $output[$row]['regions'] = implode("\n", $row_regions[$row]);
        $output[$row]['container_close'] = '    </div>';
        $output[$row]['wrapper_close'] = '  </' . $wrapper_element . '>';
        $output[$row]['suffix'] = '  {% endif %}' . "\n";
        */

        // move the dynamic region classes to the regions wrapper, hard code the page-row class

        $output[$row]['wrapper_open'] =  '  <'. $wrapper_element . ' class="page-row__' . $row . '">';

        $output[$row]['container_open'] = '    <div{{ ' .  $row . '__attributes }}>';

        $output[$row]['regions'] = implode("\n", $row_regions[$row]);

        $output[$row]['container_close'] = '    </div>';

        $output[$row]['wrapper_close'] = '  </' . $wrapper_element . '>';

        $output[$row]['suffix'] = '  {% endif %}' . "\n";

      }

      $generated[] = "{# No template file found - template code programmatically generated. #}" . "\n";
      $generated[] = '<div{{ attributes }}>'. "\n";
      $generated[] = "  {# Remove messages variable when https://www.drupal.org/node/2289917 lands. #}" . "\n";
      $generated[] = "  {{ messages }}" . "\n";

      foreach ($output as $row_output) {
        $generated[] = implode("\n", $row_output);
      }
      $generated[] = "  {{ attribution }}" . "\n";
      $generated[] = '</div>';
      $template = implode($generated);
    }

    $template_markup[] = $docblock;
    $template_markup[] = $template;

    kpr($template_markup);

    return $template_markup;
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

    $template_directory = $path . '/templates/page';

    if (!file_exists($path . '/templates')) {
      drupal_mkdir($path . '/templates');
    }
    if (!file_exists($template_directory)) {
      drupal_mkdir($template_directory);
    }

    // Set the template path.
    $template_path = $template_directory . '/' . $template_file;

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
