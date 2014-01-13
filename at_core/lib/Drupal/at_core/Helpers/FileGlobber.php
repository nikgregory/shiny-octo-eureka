<?php

namespace Drupal\at_core\Helpers;

class FileGlobber {

  protected $path;  // path to the root directory, globFiles() will go one level deep into this directory only, it's not an iterator.
  protected $types; // an array of file extension, e.g. yml, jpg, css

  public function __construct($path, $types) {
    $this->path = $path;
    $this->types = $types;
  }

  /**
   * Scan dirs and glob files, return array or arrays of files
   * by type (extension).
   *
   * @return array globbed files
   */
  public function scanDirs() {
    $scan_directories = array();
    if (file_exists($this->path)) {
      $scan_directories = preg_grep('/^([^.])/', scandir($this->path));
    }
    return $scan_directories;
  }

  /**
   * Scan dirs and glob files, return array or arrays of files
   * by type (extension).
   *
   * @return array globbed files
   */
  public function globFiles() {
    $scan_directories = self::scanDirs();

    if (isset($scan_directories)) {
      foreach ($scan_directories as $directory) {
        $glob_path = $this->path . $directory;
        if (is_dir($glob_path)) {
          if (isset($this->types)) {
            foreach ($this->types as $type) {
              $files[$directory][$type] = array_filter(glob($glob_path . "/*.$type"), 'is_file');
              //$files[$directory][$type] = new \GlobIterator($glob_path . "/*.$type");
            }
          }
        }
      }
    }
    return $files;
  }

} // end class
