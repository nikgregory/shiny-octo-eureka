<?php

$form['layouts']['layout_select'] = array(
  '#type' => 'fieldset',
  '#title' => t('Select Layouts'),
  '#attributes' => array('class' => array('layouts-column', 'layouts-column-two')),
  '#states' => array(
    'disabled' => array('select[name="layout_type_select"]' => array('value' => 'disable_layout_generation')),
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

// Page layout options.
foreach ($providers as $provider_key => $provider_name) {
  foreach ($options_data[$provider_key] as $plugin => $options) {
    $master_layout_options[$plugin] = $plugin;
  }
}

// Master layout plugin.
$form['layouts']['layout_select']['settings_master_layout'] = array(
  '#type' => 'select',
  '#title' => t('<h3>Layout</h3>'),
  '#options' => $master_layout_options,
  '#default_value' => theme_get_setting('settings.master_layout', $theme),
);

// Prepare table select headers.
$layout_plugin_header = array(
  'name'       => array('data' => t('Name'), 'class' => array('field-name')),
  'provider'   => array('data' => t('Provider'), 'class' => array('field-providers', 'field-hidden')),
  'plugin'     => array('data' => t('Plugin'), 'class' => array('field-plugin', 'field-hidden')),
  'screenshot' => array('data' => t('Screenshot'), 'class' => array('field-screenshot')),
);

// Prepare table select data.
foreach ($providers as $provider_key => $provider_name) {
  if (!empty($options_data[$provider_key])) {
    foreach ($options_data[$provider_key] as $plugin => $options) {

      if ($plugin == 'not-set') {
        drupal_set_message(t('Plugin not set for one or more layouts - this could cause issues when setting layouts for template suggestions. If you have set a plugin check the layout name is identical for both the layout folder and the layout.yml file.'), 'warning');
      }

      foreach ($options as $option_key => $options_data) {
        $row_class = 'table-row-'. drupal_html_class($options_data['name']);
        $name_key = str_replace(' ', '_', strtolower($options_data['name']));
        $provider_name = str_replace('_', ' ', ucfirst($provider_name));

        $meta = '<details id="variant--' . $name_key . '" class="form-wrapper">';
        $meta .= '<summary aria-controls="variant--' . $name_key . '" role="button">';
        $meta .= '<a href="#variant--' . $name_key . '" class="details-title">';
        $meta .= '<span class="details-summary-prefix visually-hidden"></span>';
        $meta .= 'Details';
        $meta .= '</a>';
        $meta .= '<span class="summary"></span>';
        $meta .= '</summary>';
        $meta .= '<div class="details-wrapper">';
        $meta .= '<dl class="layout-meta">';
        $meta .= '<dt>'. t('Plugin') . ':</dt><dd>' . $options_data['plugin'] . '</dd>';
        $meta .= '<dt>'. t('Version') . ':</dt><dd>' . $options_data['version'] . '</dd>';
        $meta .= '<dt>'. t('Provider') . ':</dt><dd>' . $provider_name . '</dd>';
        $meta .= '</dl>';
        $meta .= $options_data['desc'];
        $meta .= '</div>';
        $meta .= '</details>';

        $table_options_data[$plugin][$name_key] = array(
          'name'        => array('data' => '<h3>'. $options_data['name'] . '</h3>' . $meta, 'class' => array('field-name')),
          'provider'    => array('data' => $provider_key, 'class' => array('field-providers field-hidden')),
          'plugin'      => array('data' => $plugin, 'class' => array('field-plugin field-hidden')),
          'screenshot'  => array('data' => $options_data['screenshot'], 'class' => array('field-screenshot')),
          '#attributes' => array('class' => array($row_class)),
        );
      }

      $form['layouts']['layout_select']['variants_' . $plugin] = array(
        '#type' => 'container',
        '#attributes' => array('class' => array('variants-container')),
        '#states' => array(
          'visible' => array(
            'select[name="settings_master_layout"]' => array('value' => $plugin),
          ),
        ),
      );

      $form['layouts']['layout_select']['variants_' . $plugin]['title'] = array(
        '#type' => 'container',
        '#markup' => t('<h3>Layout Variants</h3>'),
      );

      // Print the layouts table select data.
      $form['layouts']['layout_select']['variants_' . $plugin]['settings_selected_layout_' . $plugin] = array(
        '#title' => t('Select Layout'),
        '#type' => 'tableselect',
        '#header' => $layout_plugin_header,
        '#options' => $table_options_data[$plugin],
        '#multiple' => FALSE,
        '#default_value' => theme_get_setting('settings.selected_layout_' . $plugin, $theme),
        '#attributes' => array('class' => array('table-layouts')),
      );
    }
  }
}
