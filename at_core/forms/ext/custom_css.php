<?php

/**
 * @file
 * Generate settings for the Custom CSS form.
 */

use Drupal\Component\Utility\Xss;

$form['custom-styles'] = array(
  '#type' => 'details',
  '#title' => t('Custom CSS'),
  '#group' => 'extension_settings',
);

$form['custom-styles']['settings_custom_css'] = array(
  '#type' => 'textarea',
  '#title' => t('Custom CSS'),
  '#rows' => 20,
  '#default_value' => theme_get_setting('settings.custom_css') ? Xss::filterAdmin(theme_get_setting('settings.custom_css')) : '/* Custom CSS */',
  '#description' => t("<p>Styles entered here are saved to <code>@theme_path/styles/css/generated/custom-css.css</code>.</p><p>Manual changes to the generated file are overwritten when submitting this form. Consider using a sub-theme for major changes or a CSS file declared in the info file.</p><p>Note that for security reasons you cannot use the greater than symbol (>) as a child combinator selector.</p>", array('@theme_path' => $subtheme_path)),
);
