<?php

namespace Drupal\at_core\Layout;

interface LayoutLoadInterface {

  // Returns the active regions when a page is loaded.
  public function activeRegions();

  // Builds and returns layout attributes when a page is loaded.
  public function rowAttributes();

}
