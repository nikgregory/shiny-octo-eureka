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

  // TODO: some of this validates radion buttons and was added to workaround
  // Druapl 7's "validation error" bug if there was nothing set for a radio. I'll
  // review these over time and see how it plays out. While technically we don't
  // need this validation as a workaround post Drupal 7.14 it is a nice UX
  // improvement and does prevent the user saving settings without something
  // set and wondering why (wtf?) something isn't working as expected.
  // Certain things must be set so AT Core can build the layout

  // Design approach uses radios, if this becomes unset for some reason bad
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
            if (empty($values[$device . '_' . $value['theme']])) {
              $missing_panels_settings[] = str_replace('_', ' ', drupal_ucfirst($device)) . ' ' . $value['title'];
            }
          }
        }
      }
    }
  }
  if (!empty($missing_panels_settings)) {
    // Format missing panels settings as an item list, for better readability
    $missing_panels_settings = theme('item_list', array('items' => $missing_panels_settings, 'title' => '', 'type' => 'ul'));
    // Set a helpful message, this is far better than throwing a form error and
    // potentially leaving the users site disabled
    drupal_set_message(t('<p>There are settings missing for the Panels layouts. Fallback values for each missing setting are being used but these might not be optimal for your site.</p><p>Don\'t worry, you just need to add the default settings for Panels layouts to your themes info file and clear the cache, please see <a href="@issue_link" target="_blank">this issue</a>. You can also select a layout for each Panel layout and resave the theme settings, which will fix the issue and clear this warning.</p><p>Settings are missing for:</p>', array('@issue_link' => 'http://drupal.org/node/1497224#at_build_panels_layout_css')) . filter_xss_admin($missing_panels_settings), 'warning');
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
