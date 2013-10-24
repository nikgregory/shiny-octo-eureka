<?php

namespace Drupal\at_core\Helpers;

class RecursiveCopy {

  /**
   * Copy a directory recursively.
   *
   * @param $source
   *   The source directory to be copied.
   *
   * @param $target
   *   Name of the target directory (where files will be copied to).
   *
   * @param $ignore
   *   Regex to filter out files we don't want.
   */
  public function recursiveCopy($source, $target, $ignore = '/^(\.(\.)?|CVS|\.svn|\.git|\.DS_Store)$/') {
    $dir = opendir($source);
    @mkdir($target);
    while($file = readdir($dir)) {
      if (!preg_match($ignore, $file)) {
        if (is_dir($source . '/' . $file)) {
          self::recursiveCopy($source . '/' . $file, $target . '/' . $file, $ignore);
        }
        else {
          copy($source . '/' . $file, $target . '/' . $file);
        }
      }
    }

    closedir($dir);
  }

} // end class
