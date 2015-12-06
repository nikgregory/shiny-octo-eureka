<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutCompatibleInterface
 */

namespace Drupal\at_core\Layout;

interface LayoutCompatibleInterface {

  /**
   * Find and return the most compatible layout.
   * @return mixed
   */
  public function getCompatibleLayout();

}
