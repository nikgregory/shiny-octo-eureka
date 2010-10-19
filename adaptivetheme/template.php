<?php
// $Id$

include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.theme.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.js.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.process.inc');
include_once(drupal_get_path('theme', 'adaptivetheme') . '/inc/template.alter.inc');

//hook_html_head_alter().
function adaptivetheme_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8'
  );
}

// Changes the search form to use the "search" input element of HTML5 (from the Boron theme).
function adaptivetheme_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}
