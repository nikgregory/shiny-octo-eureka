<?php

$form['layouts']['adv_options'] = array(
  '#type' => 'fieldset',
  '#title' => t('Options'),
  '#attributes' => array('class' => array('layouts-column', 'layouts-column-three')),
  '#states' => array(
    //'disabled' => array('select[name="layout_type_select"]' => array('value' => 'disable_layout_generation')),
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

$form['layouts']['adv_options']['description'] = array(
  '#markup' => t('<h3>Options</h3>'),
);

// Max width.
$form['layouts']['adv_options']['select']['max_width'] = array(
  '#type' => 'details',
  '#title' => t('Max Width'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#description' => t('<p>Adaptivetheme supplied layouts have a max-width set in em. You can override the max-width value and unit. Percent (%) will give a fluid layout, all other units result in an elastic type layout. This is a global setting that applies to all templates.</p>'),
);

$form['layouts']['adv_options']['select']['max_width']['settings_max_width_enable'] = array(
  '#type' => 'checkbox',
  '#title' => t('Override max-width'),
  '#default_value' => theme_get_setting('settings.max_width_enable'),
);

$form['layouts']['adv_options']['select']['max_width']['settings_max_width_value'] = array(
  '#type' => 'textfield',
  '#title' => t('Value'),
  '#size' => 4,
  '#maxlength' => 4,
  '#default_value' => check_plain(theme_get_setting('settings.max_width_value')),
  '#states' => array(
    'disabled' => array('input[name="settings_max_width_enable"]' => array('checked' => FALSE)),
  ),
);

$form['layouts']['adv_options']['select']['max_width']['settings_max_width_unit'] = array(
  '#type' => 'select',
  '#title' => t('Unit'),
  '#options' => array('em' => 'em', 'rem' => 'rem', '%' => '%', 'px' => 'px'),
  '#default_value' => theme_get_setting('settings.max_width_unit'),
  '#states' => array(
    'disabled' => array('input[name="settings_max_width_enable"]' => array('checked' => FALSE)),
  ),
);

// Internet Explorer Options.
$form['layouts']['adv_options']['select']['options'] = array(
  '#type' => 'details',
  '#title' => t('IE Options'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Help message for IE8 options.
$form['layouts']['adv_options']['select']['options']['no_mq_css'] = array(
  '#type' => 'container',
  '#markup' => t('<p>If you require support for IE8 check the option for your chosen layout or layouts. Only layouts that include support for IE8 are listed here.</p>'),
);

// Add an IE8 togggle setting foreach layout.
if ($settings_data) {
  foreach ($settings_data as $theme_name => $layouts) {
    foreach ($layouts as $layout_key => $layout_values) {
      foreach ($layout_values['css_layouts'] as $css_layout => $css_layout_values) {
        if (isset($css_layout_values['css']['no_mq'])) {
          $layout_title = drupal_ucfirst($css_layout);
          $form['layouts']['adv_options']['select']['options']['no_mq_css']["settings_layouts_no_mq_$css_layout"] = array(
            '#type' => 'checkbox',
            '#title' => t('!layout', array('!layout' => $layout_title)),
            '#default_value' => theme_get_setting("settings.layouts_no_mq_$css_layout", $theme),
            '#attributes' => array('class' => array('no-mq-file-checkbox')),
          );
        }
      }

      // Piggy back on the loop logic and build the selectors lists, we use them later in the form.
      $selectors[$layout_key] = $layout_config[$theme_name]->formatSelectors($layout_key);
    }
  }
}

// Backups.
$form['layouts']['adv_options']['backups'] = array(
  '#type' => 'details',
  '#title' => t('Backups'),
  '#description' => t('Adaptivetheme can automatically save backups for page templates and your themes info.yml file, since both of these can change when you save a layout. Backups are saved to your themes "backup" folder.'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Disable backups.
$form['layouts']['adv_options']['backups']['settings_enable_backups'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable backups'),
  '#default_value' => theme_get_setting("settings.enable_backups", $theme),
  '#description' => t('Warning: unchecking this option will disable backups.'),
);

// Layout Selectors.
$form['layouts']['adv_options']['selectors'] = array(
  '#type' => 'details',
  '#title' => t('Layout Selectors'),
  '#description' => t('This shows the unique row selectors for each row in the Plugin template. See the Help tab section "Building and Modifying Layouts" for more information on CSS selectors and building layouts.'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
);

// Loop selectors and implode values.
foreach ($selectors as $layout_name => $css_selectors) {
  foreach($css_selectors as $thiskey => $thesevalues) {
    foreach ($thesevalues as $key => $values) {
      $these_selectors[$layout_name][$key] = implode("\n", $values);
    }
  }
}

// Print selectors foreach layout in a details element.
foreach ($these_selectors as $plugin_name => $selector_strings) {
  $clean_plugin_name = str_replace('_', ' ', $plugin_name);
  $form['layouts']['adv_options']['selectors'][$plugin_name] = array(
    '#type' => 'details',
    '#title' => t($clean_plugin_name),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
  );
  $css = implode("\n\n", $selector_strings);
  $form['layouts']['adv_options']['selectors'][$plugin_name]['css'] = array(
    '#type' => 'container',
    '#markup' => '<pre>' . $css . '</pre>' . "\n",
  );
}

