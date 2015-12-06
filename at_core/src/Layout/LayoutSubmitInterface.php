<?php

/**
 * @file
 * Contains \Drupal\at_core\Layout\LayoutSubmitInterface
 */

namespace Drupal\at_core\Layout;

interface LayoutSubmitInterface {

  /**
   * Build and save the suggestions layout css files.
   */
  public function saveLayoutSuggestionsCSS();

  /**
   * Update the themes info file with new regions.
   */
  public function saveLayoutRegions();

  /**
   * Build and save twig templates.
   * Save each suggestion template, these are saved every time the layout
   * settings are saved because the rows and regions might change, so we re-save
   * every template, every time the form is submitted.
   */
  public function saveLayoutSuggestionsMarkup();

}
