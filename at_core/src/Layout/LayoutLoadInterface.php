<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutLoadInterface
 */

namespace Drupal\at_core\Layout;

interface LayoutLoadInterface {

  /**
   * Returns the active regions.
   * @return mixed
   */
  public function activeRegions();

  /**
   * Returns the row name for the region.
   * @param $region
   * @return mixed
   */
  public function regionAttributes($region);

  /**
   * Builds and returns layout attributes.
   * @return int|string|void
   */
  public function rowAttributes();

}
