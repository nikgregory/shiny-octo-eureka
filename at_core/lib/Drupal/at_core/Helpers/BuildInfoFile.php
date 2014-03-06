<?php

namespace Drupal\at_core\Helpers;

class BuildInfoFile {

  /**
   * Generate an .info.yml file that can be parsed by drupal_parse_info_file().
   *
   * @param array $array
   *   The associative array data to build the .info.yml file.
   * @param string $prefix
   *   A string to prefix each entry with, usually spaces for indentation.
   * @return string
   *   A string corresponding to $array in the .yml format.
   *
   * @see drupal_parse_info_file()
   */
  public function buildInfoFile(array $array, $prefix = NULL) {
    $info = '';
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $info .= $prefix . "$key:\n";
        $info .= self::buildInfoFile($value, (!$prefix ? '  ' : $prefix = '  '));
      }
      else {
        $info .= is_int($key) ? $prefix . '  - ' . "$value\n" : $prefix . $key . ": $value\n";
      }
    }
    return $info;
  }

} // end class
