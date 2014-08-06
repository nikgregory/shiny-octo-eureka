<?php

/**
 * @file
 * Contains \Drupal\at_core\Library\SystemLibraries.
 */

namespace Drupal\at_core\Library;

// Methods to return libraries data
class SystemLibraries {

  //protected $data;
  //protected $library;

  /**
   * Construct
   */
  public function __construct() {
    $library_discovery = \Drupal::service('library.discovery');
    $this->data = $library_discovery->getLibrariesByExtension('core');
  }

  /**
   * Return a list of Drupal core libraries
   * with dependacies, this is not a complete list,
   * only what we are probably most interested in.
   *
   * @return
   *   Arrays of library names.
   */
  function getDrupalCoreSystemLibraries() {
    static $system_libraries;
    if (!isset($system_libraries)) {
      $system_libraries = $this->data;
      $items = array(
        'drupal',
        'drupalSettings',
        'drupal.batch',
        'drupal.states',
        'drupal.tabledrag',
        'drupal.tableresponsive',
        'drupal.collapse',
        'drupal.autocomplete',
        'drupal.displace',
        'drupal.tabbingmanager',
        'drupal.dropbutton',
        'drupal.vertical-tabs',
        'drupal.ajax',
        'drupal.announce',
        'drupal.progress',
        'drupal.form',
        'drupal.dialog',
        'drupal.dialog.ajax',
        'jquery.farbtastic',
        'html5shiv',
        'modernizr',
        'normalize',
        'drupal.base',
        'jquery.cookie',
        'drupal.tableselect',
        'drupal.tableheader',
        'drupal.timezone',
        'drupal.machine-name',
        'drupal.system',
        'drupal.system.modules',
      );
      foreach ($items as $item) {
        unset($system_libraries[$item]);
      }
    }
    return $system_libraries;
  }

} // end class
