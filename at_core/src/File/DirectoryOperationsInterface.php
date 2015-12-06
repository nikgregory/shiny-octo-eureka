<?php

/**
 * @file
 * Contains \Drupal\at_core\File\DirectoryOperationsInterface
 */

namespace Drupal\at_core\File;

interface DirectoryOperationsInterface {

  /**
   * Build, prepare and return the path for generated files.
   *
   * @param array $file_path
   *   Numeric array of path parts (directories).
   * @return string
   *   Path to the prepared directory/s.
   */
  public function directoryPrepare($file_path);

  /**
   * Copy a directory recursively.
   *
   * @param $source
   *   The source directory.
   * @param $target
   *   The target directory.
   * @param $ignore
   *   Regex to filter out unwanted files and directories.
   */
  public function directoryRecursiveCopy($source, $target, $ignore = '/^(\.(\.)?|CVS|\.sass-cache|\.svn|\.git|\.DS_Store)$/');

  /**
   * Delete a folder and all files recursively.
   *
   * @param $directory
   * @return bool Returns TRUE on success, FALSE on failure
   * Returns TRUE on success, FALSE on failure
   */
  public function directoryRemove($directory);

  /**
   * Scan directorys.
   *
   * @return array
   *   Directories below the path.
   */
  public function directoryScan($path);

  /**
   * Recursively glob files below the path
   * of a specified type.
   *
   * @return array globbed files
   */
  public function directoryGlob($path, array $types);

}
