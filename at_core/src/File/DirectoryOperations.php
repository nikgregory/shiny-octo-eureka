<?php

namespace Drupal\at_core\File;

class DirectoryOperations implements DirectoryOperationsInterface {

  /**
   * {@inheritdoc}
   */
  public function directoryPrepare($file_path) {
    $directory_path = implode('/', $file_path);
    if (!file_exists($directory_path)) {
      file_prepare_directory($directory_path, FILE_CREATE_DIRECTORY);
    }

    return $directory_path;
  }

  /**
   * {@inheritdoc}
   */
  public function directoryRecursiveCopy($source, $target, $ignore = '/^(\.(\.)?|CVS|\.sass-cache|\.svn|\.git|\.DS_Store)$/') {
    $dir = opendir($source);
    @mkdir($target);
    while($file = readdir($dir)) {
      if (!preg_match($ignore, $file)) {
        if (is_dir($source . '/' . $file)) {
          self::directoryRecursiveCopy($source . '/' . $file, $target . '/' . $file, $ignore);
        }
        else {
          copy($source . '/' . $file, $target . '/' . $file);
        }
      }
    }

    closedir($dir);
  }

  /**
   * {@inheritdoc}
   */
  public function directoryRemove($directory) {
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
      self::directoryRemove("$directory/$entry");
    }

    $dir->close();
    return rmdir($directory);
  }

}
