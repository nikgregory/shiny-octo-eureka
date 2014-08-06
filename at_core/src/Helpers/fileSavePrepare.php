<?php

namespace Drupal\at_core\Helpers;

class FileSavePrepare {

  /**
   * Build, prepare and return the path for generated
   * files.
   *
   * @param array $path numeric array of path parts (directories).
   * @return string the path to the prepared directory/s.
   */
  public function prepareDirectories($file_path) {
    $directory_path = implode('/', $file_path);
    if (!file_exists($directory_path)) {
      file_prepare_directory($directory_path, FILE_CREATE_DIRECTORY);
    }
    return $directory_path;
  }

  /**
   * Save file to directory.
   *
   * @param $file_path path to the file being saved.
   */
  /*
  public function saveFile($file_path) {
  }
  */

  /**
   * Copy, rename and save file.
   *
   * @param $file_paths associative array of paths keyed by function_parameter:
   *  - copy_source => "path to the source file"
   *  - copy_dest => "the destination path"
   *  - rename_oldname => "the old file name"
   *  - rename_newname => "the new file name"
   */
  public function copyRename($file_paths) {
    if (file_exists($file_paths['copy_source'])) {
      copy($file_paths['copy_source'], $file_paths['copy_dest']);
    }
    if (file_exists($file_paths['rename_oldname'])) {
      rename($file_paths['rename_oldname'], $file_paths['rename_newname']);
    }
  }

} // end class
































