<?php

namespace Drupal\at_core\Helpers;

class RemoveDirectory {

  /**
   * Delete a file, or a folder and its contents
   *
   * @param string $dirname Directory to delete
   * @return bool Returns TRUE on success, FALSE on failure
   */
  public function removeDirectory($directory) {
    if (!file_exists($directory)) {
      return false;
    }
    if (is_file($directory)) {
      return unlink($directory);
    }

    $dir = dir($directory);
    while (false !== $entry = $dir->read()) {
      if ($entry == '.' || $entry == '..') {
        continue;
      }
      self::removeDirectory("$directory/$entry");
    }

    $dir->close();
    return rmdir($directory);
  }

} // end class
