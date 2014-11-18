<?php

namespace Drupal\at_core\Layout;

interface LayoutCompatibleInterface {

  // Find and return the most compatible layout.
  public function getCompatibleLayout();

}
