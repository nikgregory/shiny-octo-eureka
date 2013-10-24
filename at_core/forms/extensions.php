<?php

$form['extensions'] = array(
  '#type' => 'details',
  '#title' => t('Extensions'),
  '#group' => 'atsettings',
);




// Enable layouts, this is a master setting that totally disables the page layout system.
$form['extensions']['layouts']['settings_layouts_enable'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable Layouts'),
  '#default_value' => theme_get_setting('settings.layouts_enable', $theme),
);
$form['extensions']['layouts']['settings_layouts_disabled'] = array(
  '#type' => 'container',
  '#markup' => t('Disabling layouts assumes you have written your own CSS layout or you are loading the layout some other way. <br />Templates will still be used by you theme since Drupal is directly handling those as per normal, only the CSS layouts assocatited with those layouts will no longer be loaded.'),
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => FALSE)),
  ),
);


