<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutInterface
 */

namespace Drupal\at_core\Layout;

interface LayoutInterface {

  // Interface constants
  const LayoutMarkup = 'markup';
  const LayoutCSS = 'css';

  /**
   * Returns layout configuration of a type (normally markup or css yml config).
   * looks for cached config first, if none we parse the respective yml file.
   * @param $type
   * @return array|mixed
   */
  public function LayoutConfig($type);

  /**
   * Returns layout markup.
   * @return array|mixed
   */
  public function getLayoutMarkup();

  /**
   * Returns layout CSS.
   * @return array|mixed
   */
  public function getLayoutCSS();

  /**
   * Return a layouts name.
   * @return mixed
   */
  public function getLayoutName();

}
