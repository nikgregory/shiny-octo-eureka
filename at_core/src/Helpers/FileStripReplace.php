<?php

namespace Drupal\at_core\Helpers;

class FileStripReplace {

  /**
   * Replace strings in a file.
   *
   * @param $file_path
   *   The file to be processed (haystack).
   *
   * @param $find
   *   The target string (needle).
   *
   * @param $replace
   *   The replacement string.
   */
  public function fileStrReplace($file_path, $find, $replace) {
    if (file_exists($file_path)) {
      $file_contents = file_get_contents($file_path);
      $file_contents = str_replace($find, $replace, $file_contents);
      file_put_contents($file_path, $file_contents);
    }
  }

} // end class
