<?php

/**
 * @file
 * Validatation for theme settings.
 *
 * @param $form
 * @param $form_state
 */

function at_core_settings_validate($form, &$form_state) {

  $values = $form_state['values'];
  $theme_name = $form_state['build_info']['args'][0];

  // "Design approach" uses radios, if this becomes unset for some reason bad
  // things can happen.
  if (empty($values['global_default_layout'])) {
    form_set_error('global_default_layout', t('No setting detected for the <b>design approach</b>. Please review "Settings > Mobile first or Desktop first" and select either Mobile first or Desktop first.'));
  }

  // For each breakpoint we need to have a layout selected
  if (empty($values['bigscreen_layout'])) {
    form_set_error('bigscreen_layout', t('No layout selection detected for the <b>Standard Layout</b>. Please review "Standard Layout > Choose sidebar positions" and make a selection.'));
  }
  if (empty($values['tablet_landscape_layout'])) {
    form_set_error('tablet_landscape_layout', t('No layout selection detected for the <b>Tablet Landscape Layout</b>. Please review "Tablet Layout > Landscape > Choose sidebar positions" and make a selection.'));
  }
  if (empty($values['tablet_portrait_layout'])) {
    form_set_error('tablet_portrait_layout', t('No layout selection detected for the <b>Tablet Portrait Layout</b>. Please review "Tablet Layout > Portrait > Choose sidebar positions" and make a selection.'));
  }
  if (empty($values['smartphone_landscape_layout'])) {
    form_set_error('smartphone_landscape_layout', t('No layout selection detected for the <b>Smartphone Landscape Layout</b>. Please review "Smartphone Layout > Landscape > Choose sidebar positions" and make a selection.'));
  }

  // Validate our form #state required fields, #states are UI only.

  // Bigscreen
  if (empty($values['bigscreen_sidebar_first'])) {
    form_set_error('bigscreen_sidebar_first', t('Standard Layout <em>First sidebar</em> is empty - please enter a value.'));
  }
  if (empty($values['bigscreen_sidebar_second'])) {
    form_set_error('bigscreen_sidebar_second', t('Standard Layout <em>Second sidebar</em> is empty - please enter a value.'));
  }
  if ($values['bigscreen_set_max_width'] === 1) {
    if (empty($values['bigscreen_max_width'])) {
      form_set_error('bigscreen_max_width', t('Standard layout max-width is empty - enter a value or deselect "Set a max width".'));
    }
  }

  // Tablet
  if (empty($values['tablet_landscape_sidebar_first'])) {
    form_set_error('tablet_landscape_sidebar_first', t('Tablet Landscape <em>First sidebar</em> is empty - please enter a value.'));
  }
  if ($values['tablet_landscape_layout'] === 'three_col_grail' || $values['tablet_landscape_layout'] === 'two_sidebars_left' || $values['tablet_landscape_layout'] === 'two_sidebars_right') {
    if (empty($values['tablet_landscape_sidebar_second'])) {
      form_set_error('tablet_landscape_sidebar_second', t('Tablet Landscape <em>First second</em> is empty - please enter a value, the layout you selected requires values for both sidebars.'));
    }
  }

  // Smartphone
  if ($values['smartphone_landscape_layout'] === 'one_col_vert') {
    if (empty($values['smartphone_landscape_sidebar_first'])) {
      form_set_error('smartphone_landscape_sidebar_first', t('Smartphone First Sidebar width is empty - enter a value or choose another layout.'));
    }
    if (empty($values['smartphone_landscape_sidebar_second'])) {
      form_set_error('smartphone_landscape_sidebar_second', t('Smartphone Second Sidebar width is empty - enter a value or choose another layout.'));
    }
  }

  // Validate extensions
  if (isset($values['enable_extensions']) && $values['enable_extensions'] === 1) {

    // Apple touch icon paths
    if (isset($values['enable_apple_touch_icons']) && $values['enable_apple_touch_icons'] === 1) {
      if (!empty($values['apple_touch_icon_path_l'])) {
        $l = drupal_get_path('theme', $theme_name) . '/' . $values['apple_touch_icon_path_l'];
        if (!file_exists($l)) {
          form_set_error('apple_touch_icon_path_l', t('Ops! The Apple touch 57x57 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
      if (!empty($values['apple_touch_icon_path_m'])) {
        $m = drupal_get_path('theme', $theme_name) . '/' . $values['apple_touch_icon_path_m'];
        if (!file_exists($m)) {
          form_set_error('apple_touch_icon_path_m', t('Ops! The Apple touch 72x72 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
      if (!empty($values['apple_touch_icon_path_h'])) {
        $h = drupal_get_path('theme', $theme_name) . '/' . $values['apple_touch_icon_path_h'];
        if (!file_exists($h)) {
          form_set_error('apple_touch_icon_path_h', t('Ops! The Apple touch 114x114 icon path is not right, check the file exists or the path to the file is correct.'));
        }
      }
    }

    // Warn users Global extensions are enabled, but no extensions are.
    if (
      $values['enable_font_settings'] == 0 &&
      $values['enable_heading_settings'] == 0 &&
      $values['enable_image_settings'] == 0 &&
      $values['enable_exclude_css'] == 0 &&
      $values['enable_apple_touch_icons'] == 0 &&
      $values['enable_custom_css'] == 0 &&
      $values['enable_markup_overides'] == 0
      ) {
      form_set_error('enable_extensions', t('No extensions enabled. You enabled the Extensions global setting but did not enable any extensions.'));
    }

  }
}
