<?php //$Id$

/**
 * Clean a string of unwanted characters.
 *
 * @param $string
 *   The string
 * @return
 *   The converted string
 */
function safe_string($string) {
$string = strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', $string));
  if (!ctype_lower($string{0})) {
    $string = 'id'. $string;
  }
  return $string;
}

/**
 * Limit the length of a string.
 *
 * @param $string
 *   The string
 * @param $lenght
 *   The lenght of the trimmed string
 * @$ellipsis
 *   
 * @return
 *   The converted string
 */
function wordlimit($string, $length = 50, $ellipsis = "...") {
  $words = explode(' ', strip_tags($string));
  if (count($words) > $length)
    return implode(' ', array_slice($words, 0, $length)) . $ellipsis;
  else
    return $string;
}

/**
 * Set a default color scheme and set a cookie.
 */
function get_at_colors() {
  $scheme = theme_get_setting('color_schemes');
  if (!$scheme) {
    $scheme = 'colors-default.css';
  }
  if (isset($_COOKIE["atcolors"])) {
    $scheme = $_COOKIE["atcolors"];
  }
  return $scheme;
}

// Modify the theme settings form for our theme.
function adaptivetheme_system_settings_form($form) {
  // Theme the crap out of the theme settings fieldsets.
  $form['theme_settings']['#title'] = t('Drupal core theme settings');
  $form['theme_settings']['#collapsible'] = TRUE;
  $form['theme_settings']['#collapsed']   = TRUE;
  $form['theme_settings']['#prefix'] = '<div class="theme-settings-settings-wrapper">';
  $form['theme_settings']['#suffix'] = '</div>';
  $form['logo']['#collapsible'] = TRUE;
  $form['logo']['#collapsed']   = TRUE;
  $form['logo']['#prefix'] = '<div class="logo-settings-wrapper">';
  $form['logo']['#suffix'] = '</div>';
  $form['favicon']['#collapsible'] = TRUE;
  $form['favicon']['#collapsed']   = TRUE;
  $form['favicon']['#prefix'] = '<div class="favicon-settings-wrapper">';
  $form['favicon']['#suffix'] = '</div>';
  $form['node_info']['#collapsible'] = TRUE;
  $form['node_info']['#collapsed']   = TRUE;
  $form['node_info']['#prefix'] = '<div class="node-info-settings-wrapper">';
  $form['node_info']['#suffix'] = '</div>';
  $form['theme_specific']['#title'] = t('Advanced theme settings');
  $form['theme_specific']['#collapsible'] = TRUE;
  $form['theme_specific']['#collapsed']   = TRUE;
  //print '<pre>'; print_r($form); print '</pre>';
  return drupal_render($form);
}
