<?php

/**
 * @file
 * Generate settings for the Custom CSS form.
 */

use Drupal\at_core\File\FileOperations;

$fileOperations = new FileOperations();

$editor_messages = array();
if (isset($theme_extension->info['ckeditor_stylesheets'])) {
  $editor_messages['cks']['ckeditor_stylesheets'] = $theme_extension->info['ckeditor_stylesheets'];
}
if (isset($theme_extension->info['libraries-override']['ckeditor/drupal.ckeditor'])) {
  $editor_messages['lo']['libraries-override'] = $theme_extension->info['libraries-override'];
}

$form['ckeditor'] = array(
  '#type' => 'details',
  '#title' => t('CKEditor Skins'),
  '#group' => 'extension_settings',
  '#description' => t('Adaptivetheme includes a CKEditor skin called Mimic. Mimic automatically inherits your themes colors, backgrounds, fonts, heading and other text styles to give you are more realistic WYSIWYG experience. Mimic requires FontAwesome for icons - FontAwesome is included in every Adaptivetheme sub-theme by default, so just be aware of that if you choose to remove FA from your sub-theme.'),
);

if (isset($editor_messages['cks'])) {
  $form['ckeditor']['warnings_ckeditor_stylesheets'] = array(
    '#type' => 'container',
    '#markup' => '<div class="messages messages--warning">'
      . t('Before using Mimic you must remove the <b>ckeditor_stylesheets</b> settings from <code>@themename</code>, then clear the Drupal cache.', array('@themename' => "$theme.info.yml" ))
      . '<br /><br /><pre>'  .  $fileOperations->fileBuildInfoYml($editor_messages['cks'])  .  '</pre>
      </div>',
  );
}

if (isset($editor_messages['lo'])) {
  $form['ckeditor']['warnings_libraries-override'] = array(
    '#type' => 'container',
    '#markup' => '<div class="messages messages--warning">'
      . t('Before using Mimic you must remove any <b>libraries-override</b> settings relevant to ckeditor from <code>@themename</code>, then clear the Drupal cache.', array('@themename' => "$theme.info.yml" ))
      . '<br /><br /><pre>'  .  $fileOperations->fileBuildInfoYml($editor_messages['lo'])  .  '</pre>
      </div>',
  );
}

$form['ckeditor']['settings_ckeditor'] = array(
  '#type' => 'checkbox',
  '#title' => t('Use Mimic'),
  '#default_value' => !empty($editor_messages) ? FALSE : theme_get_setting('settings.ckeditor'),
  '#description' => t('Uncheck to use Drupals default CKEditor skin (moono) with yummy buttons and old school gradients... &#x1f631;'),
);

if (!empty($editor_messages)) {
  $form['ckeditor']['settings_ckeditor']['#disabled'] = TRUE;
  drupal_set_message(t('CKEditor skin settings require attention - please see the CKEditor skin tab and follow the instructions.'), 'warning');
}

