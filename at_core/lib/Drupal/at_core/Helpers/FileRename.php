<?php

namespace Drupal\at_core\Helpers;

class FileRename {

  /**
   * Rename a file.
   *
   * @param $old_file
   *   Source file to be renamed.
   *
   * @param $new_file
   *   The new file name.
   */
  public function fileRename($old_file, $new_file) {
    if (file_exists($old_file)) {
      rename($old_file, $new_file);
    }
  }

} // end class
