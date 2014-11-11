<?php

use Drupal\Component\Utility\SafeMarkup;

use Drupal\at_core\Layout\PageLayout;

$form['layouts']['layout_select'] = array(
  '#type' => 'fieldset',
  '#title' => t('Select Layouts'),
  '#attributes' => array('class' => array('layouts-column', 'layouts-column-twothirds', 'column-select-layouts')),
);

$template_suggestions = array();
$template_suggestions['page'] = 'page';

// push in this hidden setting, but check later if we still need it for debugging etc
$form['layouts']['layout_select']['settings_suggestion_page'] = array(
  '#type' => 'hidden',
  '#value' => 'page',
);

foreach ($config as $config_key => $config_value) {
  if (substr($config_key, 0, 16) == 'suggestion_page_') {
    if (!empty($config_value) && $config_value !== 'page') {
      $template_suggestions['page__' . $config_value] = 'page__' . $config_value;
    }
  }
}

// Push hidden settings into the form so they can be used during submit, to build the css output, saves us
// having to get this data again during submit.
$form['layouts']['layout_select']['settings_suggestions'] = array(
  '#type' => 'hidden',
  '#value' => $template_suggestions,
);
/*
$form['layouts']['layout_select']['settings_layoutconfig'] = array(
  '#type' => 'hidden',
  '#value' => $layout_config,
);
$form['layouts']['layout_select']['settings_cssconfig'] = array(
  '#type' => 'hidden',
  '#value' => $css_config,
);
*/
$form['layouts']['layout_select']['settings_compatible_layout'] = array(
  '#type' => 'hidden',
  '#value' => $compatible_layout,
);

foreach ($template_suggestions as $suggestion_key => $suggestions_name) {

  // Pretty titles
  if ($suggestions_name == 'page') {
    $suggestions_name = 'page (default)';
  }
  else {
    $suggestions_name = str_replace('__', ' ', $suggestions_name);
  }

  $form['layouts']['layout_select'][$suggestion_key] = array(
    '#type' => 'details',
    '#title' => t($suggestions_name),
    '#attributes' => array('class' => array('clearfix')),
  );

  if ($suggestions_name !== 'page') {
    $form['layouts']['layout_select'][$suggestion_key][$suggestion_key . '_delete'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete'),
      '#default_value' => FALSE,
      '#description' => t('Nuke this suggestion and all it\'s settings.'),
    );
  }

  foreach ($theme_breakpoints as $bpid => $bpvalue) {

    $form['layouts']['layout_select'][$suggestion_key][$bpvalue->getLabel()] = array(
      '#type' => 'details',
      '#title' => t($bpvalue->getLabel()),
      '#attributes' => array('class' => array('clearfix')),
    );

    $form['layouts']['layout_select'][$suggestion_key][$bpvalue->getLabel()]['info'] = array(
      '#markup' => '<p>' . $bpvalue->getLabel() . ': ' . $bpvalue->getMediaQuery() . '</p>',
    );

    foreach ($layout_config['rows'] as $row_key => $row_values) {

      // CSS files
      $reg_count[$row_key] = count($row_values['regions']);
      foreach ($css_config['css'] as $css_key => $css_values) {
        if ($css_values['regions'] == $reg_count[$row_key]) {
          foreach ($css_values['files'] as $css_file) {
            $css_options[$row_key][$css_file] = $css_file; // convert to associative array, we need the key
          }
        }
      }

      // Only print rows that have more than 1 region.
      if ($reg_count[$row_key] >= 2) {
        $form['layouts']['layout_select'][$suggestion_key][$bpvalue->getLabel()][$row_key] = array(
          '#type' => t('fielset'),
          '#title' => t($row_key),
        );

        $form['layouts']['layout_select'][$suggestion_key][$bpvalue->getLabel()][$row_key]['settings_' . $suggestion_key . '_' . $bpvalue->getLabel() . '_' . $row_key] = array(
          '#type' => t('select'),
          '#empty_option' => '--none--',
          '#title' => t($row_key),
          '#options' => $css_options[$row_key],
          '#default_value' => theme_get_setting('settings.' . $suggestion_key . '_' . $bpvalue->getLabel() . '_' . $row_key),
        );
      }
    }
  }
}

// Suggestions container.
$form['layouts']['layout_select']['suggestions'] = array(
  '#type' => 'details',
  '#title' => t('Add new Suggestion'),
);

// Suggestions input and help.
//$suggestion_plugin_message = isset($default_plugin) ? $default_plugin : '-- not set --';
$form['layouts']['layout_select']['suggestions']['ts_name'] = array(
  '#type' => 'textfield',
  '#size' => 20,
  '#field_prefix' => 'page--',
  '#field_suffix' => '.html.twig',
  '#description' => t('
    <ol>
      <li>Enter the template suggestion (new, or modify/overwrite an existing suggestion).</li>
      <li>Select layouts for each row in your theme.</li>
      <li>Configure "Options".</li>
      <li>Save the layout settings.</li>
    </ol>'),
);



/*
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

    //var_dump($options_data[$provider_key]);
    var_dump($compatible_layout);

    foreach ($options_data[$provider_key] as $plugin => $options) {

      if ($plugin == 'not-set') {
        drupal_set_message(t('Plugin not set for one or more layouts - this could cause issues when setting layouts for template suggestions. If you have set a plugin check the layout name is identical for both the layout folder and the layout.yml file.'), 'warning');
      }

      if ($plugin == $compatible_layout) {

        kpr($options);

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

        // SafeMarkup is a bit of a cheat here, but it was quick and effective.
        $table_options_data[$plugin][$name_key] = array(
          'name' => array(
            'data' => SafeMarkup::set('<h3>'. $options_data['name'] . '</h3>' . $meta),
            'class' => array('field-name'),
          ),
          'provider' => array(
            'data' => $provider_key,
            'class' => array('field-providers field-hidden'),
          ),
          'plugin' => array(
            'data' => $plugin,
            'class' => array('field-plugin field-hidden'),
          ),
          'screenshot' => array(
            'data' => SafeMarkup::set($options_data['screenshot']),
            'class' => array('field-screenshot'),
          ),
          '#attributes' => array(
            'class' => array($row_class),
          ),
        );
      }

        $form['layouts']['layout_select']['variants_' . $plugin] = array(
          '#type' => 'container',
          '#attributes' => array('class' => array('variants-container')),
          //'#states' => array(
          //  'visible' => array(
              //'select[name="settings_master_layout"]' => array('value' => $plugin),
          //  ),
          //),
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
          '#prefix' => '<div class="select-layout-table-wrapper">',
          '#suffix' => '</div>',
        );
      }
    }

  }
}
*/
