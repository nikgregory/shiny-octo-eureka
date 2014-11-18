<?php

namespace Drupal\at_core\Layout;

interface LayoutInterface {

  // Interface constants
  const LayoutMarkup = 'markup';
  const LayoutCSS = 'css';

  // Returns layout configuration of a type (normally markup or css yml config).
  public function getLayoutConfig($type);

  // Returns a layout markup object.
  public function getLayoutMarkup();

  // Returns a layout CSS object.
  public function getLayoutCSS();

}
