<?php

/**
 * @file
 * Contains \Drupal\at_core\File\FileOperations
 */

namespace Drupal\at_core\File;

use Drupal\Component\Serialization\Yaml;

class FileOperations implements FileOperationsInterface {

  /**
   * {@inheritdoc}
   */
  public function fileRename($old_file, $new_file) {
    if (file_exists($old_file)) {
      rename($old_file, $new_file);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fileStrReplace($file_path, $find, $replace) {
    if (file_exists($file_path)) {
      $file_contents = file_get_contents($file_path);
      $file_contents = str_replace($find, $replace, $file_contents);
      file_put_contents($file_path, $file_contents);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fileCopyRename($file_paths) {
    if (file_exists($file_paths['copy_source'])) {
      copy($file_paths['copy_source'], $file_paths['copy_dest']);
    }
    if (file_exists($file_paths['rename_oldname'])) {
      rename($file_paths['rename_oldname'], $file_paths['rename_newname']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fileReplace($data, $file_path) {
    if (file_exists($file_path)) {
      file_unmanaged_save_data($data, $file_path, FILE_EXISTS_REPLACE);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fileBuildInfoYml(array $data) {
    $info = Yaml::encode($data);
    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function fileCompare($afile, $bfile) {
    // Check if filesize is different
    if(filesize($afile) !== filesize($bfile))
      return false;

    // Check if content is different
    $aopen = fopen($afile, 'rb');
    $bopen = fopen($bfile, 'rb');

    $result = true;
    while(!feof($aopen)) {
      if(fread($aopen, 8192) != fread($bopen, 8192)) {
        $result = false;
        break;
      }
    }

    fclose($aopen);
    fclose($bopen);

    return $result;
  }

}
