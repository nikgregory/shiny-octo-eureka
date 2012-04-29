<?php

/**
 * Validatation for theme settings.
 *
 * @param $form
 * @param $form_state
 */
function at_core_settings_validate($form, &$form_state) {

  $values = $form_state['values'];
  $theme_name = $form_state['build_info']['args'][0];

  // Validate our form #state required fields, #states are UI only.
  if ($values['bigscreen_set_max_width'] === 1) {
    if (empty($values['bigscreen_max_width'])) {
      form_set_error('bigscreen_max_width', t('Standard layout max-width is empty - enter a value or deselect "Set a max width".'));
    }
  }
  if ($values['smartphone_landscape_layout'] === 'one_col_vert') {
    if (empty($values['smartphone_landscape_sidebar_first'])) {
      form_set_error('smartphone_landscape_sidebar_first', t('Smartphone First Sidebar width is empty - enter a value or choose another layout.'));
    }
    if (empty($values['smartphone_landscape_sidebar_second'])) {
      form_set_error('smartphone_landscape_sidebar_second', t('Smartphone Second Sidebar width is empty - enter a value or choose another layout.'));
    }
  }

  // See if we have any settings for panels layouts
  $devices = array(
    'tablet_landscape',
    'tablet_portrait',
    'smartphone_landscape',
  );
  $rpl = responsive_panels_data_structure();
  if (isset($rpl['one'])) {
    unset($rpl['one']);
  }
  $missing_panels_settings = array();
  foreach ($devices as $device) {
    if (isset($rpl)) {
      foreach ($rpl as $group => $layouts) {
        foreach ($layouts as $layout) {
          foreach ($layout as $key => $value) {
            if (empty($values[$device .'_'. $value['theme']])) {
              $missing_panels_settings[] = str_replace('_', ' ', drupal_ucfirst($device)) . ' ' . $value['title'];
            }
          }
        }
      }
    }
  }
  if (!empty($missing_panels_settings)) {
    $missing_panels_settings = implode(', ', $missing_panels_settings);
    drupal_set_message(t('<p>There are settings missing for the Panels layouts. Dont worry, you just need to add the default settings for Panels layouts to your themes info file and clear the cache, please see <a href="!link" target="_blank">this issue</a>. You can also try selecting a layout for each Panel layout and resaving, which will fix the issue and clear this warning, however, if you migrate you will want the settings in the info file. Fallback values for each missing setting are being used but these might not be optimal for your site.</p><p>Settings are missing for: ', array('!link' => 'http://drupal.org/node/1497224#at_build_panels_layout_css')) . $missing_panels_settings . '</p>', 'warning');
  }

  // Apple touch icons
  if(at_get_setting('enable_extensions') === 1) {
    if (isset($values['enable_apple_touch_icons']) && $values['enable_apple_touch_icons'] === 1) {
      // low
      // if (empty($values['apple_touch_icon_path_l'])) {
        // form_set_error('apple_touch_icon_path_l', t('Ops! The Apple touch 57x57 icon path is missing, you must enter a path or disable touch icons.'));
      // }
      if (!empty($values['apple_touch_icon_path_l'])) {
        $l = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_l'];
        if (!file_exists($l)) {
          form_set_error('apple_touch_icon_path_l', t('Ops! The Apple touch 57x57 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
      // medium
      if (!empty($values['apple_touch_icon_path_m'])) {
        $m = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_m'];
        if (!file_exists($m)) {
          form_set_error('apple_touch_icon_path_m', t('Ops! The Apple touch 72x72 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
      // high
      if (!empty($values['apple_touch_icon_path_h'])) {
        $h = drupal_get_path('theme', $theme_name) .'/'. $values['apple_touch_icon_path_h'];
        if (!file_exists($h)) {
          form_set_error('apple_touch_icon_path_h', t('Ops! The Apple touch 114x114 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
    }
  }
}
