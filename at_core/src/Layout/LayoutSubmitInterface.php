<?php

namespace Drupal\at_core\Layout;

interface LayoutSubmitInterface {

  // Update the themes info file with new regions.
  public function saveLayoutRegions();

  // Build and save the suggestions layout css files.
  public function saveLayoutSuggestionsCSS();

  // Build and save the suggestions twig templates.
  public function saveLayoutSuggestionsMarkup();

}
