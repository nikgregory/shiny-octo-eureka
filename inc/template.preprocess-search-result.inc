<?php // $Id$
// adaptivethemes.com
/**
 * Modify search results based on theme settings
 */
static $search_zebra = 'even';
$search_zebra = ($search_zebra == 'even') ? 'odd' : 'even';
$variables['search_zebra'] = $search_zebra;

$result = $variables['result'];
$variables['url'] = check_url($result['link']);
$variables['title'] = check_plain($result['title']);

// Check for existence. User search does not include snippets.
$variables['snippet'] = '';
if (isset($result['snippet']) && theme_get_setting('search_snippet')) {
  $variables['snippet'] = $result['snippet'];
}
  
$info = array();
if (!empty($result['type']) && theme_get_setting('search_info_type')) {
  $info['type'] = check_plain($result['type']);
}
if (!empty($result['user']) && theme_get_setting('search_info_user')) {
  $info['user'] = $result['user'];
}
if (!empty($result['date']) && theme_get_setting('search_info_date')) {
  $info['date'] = format_date($result['date'], 'small');
}
if (isset($result['extra']) && is_array($result['extra'])) {
  // $info = array_merge($info, $result['extra']);  Drupal bug?  [extra] array not keyed with 'comment' & 'upload'
  if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
    $info['comment'] = $result['extra'][0];
  }
  if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
    $info['upload'] = $result['extra'][1];
  }
}

// Provide separated and grouped meta information.
$variables['info_split'] = $info;
$variables['info'] = implode(' - ', $info);

// Provide alternate search result template.
$variables['template_files'][] = 'search-result-'. $variables['type'];
