<?php

use Drupal\at_core\Layout\LayoutSettings;
use Drupal\at_core\Theme\ThemeSettingsInfo;

$themeInfo = new ThemeSettingsInfo($theme);
$providers = $themeInfo->baseThemeInfo('base_themes');

// Unset at_core, it has no layouts.
unset($providers['at_core']);

// Push the current theme into the array, if it has layouts we need them.
$providers[$theme] = $theme;

// Bit of a hack, the classes were built to handle one provider, later it was
// decided to allow sub-themes to inherit base theme layouts, so we need to
// loop and instantiate foreach provider. The classes could be modified to
// take an array of providers.
foreach ($providers as $key => $provider_name) {
  $layout_config[$key] = new LayoutSettings($key);
  $options_data[$key]  = $layout_config[$key]->layoutOptions();

  $layout_config[$key] = new LayoutSettings($key);
  $settings_data[$key] = $layout_config[$key]->settingsPrepareData();
}

$form['layouts'] = array(
  '#type' => 'details',
  '#title' => t('Layouts'),
  '#group' => 'atsettings',
);

// Enable layouts, this is a master setting that totally disables the page layout system.
$form['layouts']['enable']['settings_layouts_enable'] = array(
  '#type' => 'checkbox',
  '#title' => t('Enable Layouts'),
  '#default_value' => theme_get_setting('settings.layouts_enable', $theme),
);
$form['layouts']['enable']['settings_layouts_disabled'] = array(
  '#type' => 'container',
  '#markup' => t('Enable and configure layouts. Diabling this option assumes your theme will load it\'s own CSS layout. Previously generated templates will continure to be used by your theme, however no CSS layout will load for those templates.'),
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => FALSE)),
  ),
);

// Set options for layout type.
if (theme_get_setting('settings.template_suggestion_page', $theme)) {
  $type_options = array(
    'disable_layout_generation' => t('-- none --'),
    'default_layout' => t('Default'),
    'template_suggestion' => t('Template Suggestion'),
  );
}
else {
  $type_options = array(
    'disable_layout_generation' => t('-- none --'),
    'default_layout' => t('Default'),
    'default_not_set' => t('Template Suggestion'),
  );
}

// Wrapper to completely hide or show layout options.
$form['layouts']['select'] = array(
  '#type' => 'container',
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

// Select Layout Type container.
$form['layouts']['select']['select_type'] = array(
  '#type' => 'container',
);

$form['layouts']['select']['select_type']['layout_type_select'] = array(
  '#type' => 'select',
  '#title' => t('<h3>Select Page Type</h3>'),
  '#options' => $type_options,
);

// Default layout and Series message.
if ($default_layout = theme_get_setting('settings.template_suggestion_page', $theme)) {

  // Dig deep for the current default layout series key
  if ($settings_data) {
    foreach ($settings_data as $theme_name => $layouts) {
      foreach ($layouts as $layout_name => $layout_data) {
        if ($default_layout == $layout_name) {
          $default_series = $layout_data['series'];
          $default_provider = $theme_name;
          break;
        }
      }
    }

    $default_series_markup = t('<label>Current default layout:</label> <em>!default_layout</em> from the <em>!default_series</em> series.', array('!default_layout' => ucfirst($default_layout), '!default_series' => $default_series, '!default_provider' => $default_provider));
  }
}
else {
  $series_not_set_markup = t('Current default layout: No default set. Choose a layout for the default layout and save this form.');

  $form['layouts']['select']['select_type']['default_layout'] = array(
    '#type' => 'container',
    '#markup' => $series_not_set_markup,
  );
}

// -- none -- message.
$form['layouts']['select']['select_type']['none_set'] = array(
  '#type' => 'container',
  '#markup' => t('No new page layout will be generated. Select "Default" or "Template Suggestion" to configure a layout.'),
  '#states' => array(
    'visible' => array(
      'select[name="layout_type_select"]' => array('value' => 'disable_layout_generation'),
    ),
  ),
);

// Default Layout container and Help.
$form['layouts']['select']['select_type']['default_set'] = array(
  '#type' => 'container',
  '#markup' => t('
    <label>Set the Default Layout</label>
    <ol>
      <li>Select a layout.</li>
      <li>Configure Internet Explorer Options.</li>
      <li>Save the configuration.</li>
    </ol>
    <p>Note: the default layout applies to page.html.twig is used globally unless overridden by a template suggestion. See the Help tab section "Using Layouts" for more information.</p>'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_layout')),
  ),
);

// Default Layout "not set" message.
$form['layouts']['select']['select_type']['default_not_set'] = array(
  '#type' => 'container',
  '#markup' => t('First set the default page layout - no default page layout detected.'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_not_set')),
  ),
);

// Suggestions container.
$form['layouts']['select']['select_type']['suggestions'] = array(
  '#type' => 'container',
  '#markup' => t('<label>Add or Change a Template Suggestion</label>'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'template_suggestion')),
  ),
);

// Suggestions input and help.
$suggestion_series_message = isset($default_series) ? $default_series : '-- not set --';
$form['layouts']['select']['select_type']['suggestions']['template_suggestion_name'] = array(
  '#type' => 'textfield',
  '#size' => 20,
  '#field_prefix' => 'page--',
  '#field_suffix' => '.html.twig',
  '#description' => t('
    <ol>
      <li>Enter the template suggestion.</li>
      <li>Select a layout - this must be from the <b>!suggestion_series_message Series</b> (suggestions must use the same series as the default layout).</li>
      <li>Configure Internet Explorer Options.</li>
      <li>Save the configuration.</li>
    </ol>
    <p>See the Help tab section "Using Layouts" for more information on template suggestions.</p>', array('!suggestion_series_message' => $suggestion_series_message)),
);

// Print the default layout and series message above the table.
if (isset($default_series_markup)) {
  $form['layouts']['select']['select_type']['default_layout'] = array(
    '#type' => 'container',
    '#markup' => $default_series_markup,
  );
}

// Layouts table container.
$form['layouts']['select']['layout'] = array(
  '#type' => 'container',
  '#states' => array(
    'disabled' => array('select[name="layout_type_select"]' => array('value' => 'disable_layout_generation')),
  ),
);

// Prepare table select headers.
$layout_series_header = array(
  'name' => array('data' => t('Name'), 'class' => array('field-name')),
  'provider' => array('data' => t('Provider'), 'class' => array('field-providers field-hidden')),
  'desc' => array('data' => t('Description'), 'class' => array('field-desc')),
  'screenshot' => array('data' => t('Screenshot'), 'class' => array('field-screenshot')),
);

// Prepare table select data.
foreach ($providers as $provider_key => $provider_name) {
  foreach ($options_data[$provider_key] as $series => $options) {
    if ($series == 'not-set') {
      drupal_set_message(t('Series not set for one or more layouts - this could cause issues when setting layouts for template suggestions. If you have set a series check the layout name is identical for both the layout folder and the layout.yml file.'), 'warning');
    }
    foreach ($options as $option) {
      $row_class = 'table-row-'. drupal_html_class($option['name']);
      $name_key = str_replace(' ', '_', strtolower($option['name']));

      $meta  = '<dl class="layout-meta">';
      $meta .= '<dt>Series:</dt><dd>' . $option['series'] . '</dd>';
      $meta .= '<dt>Version:</dt><dd>' . $option['version'] . '</dd>';
      $meta .= '<dt>Provider:</dt><dd>' . $provider_name . '</dd>';
      $meta .= '</dl>';

      $table_options_data[$name_key] = array(
        'name' => array('data' => '<h3>'. $option['name'] . '</h3>' . $meta, 'class' => array('field-name')),
        'provider' => array('data' => $provider_key, 'class' => array('field-providers field-hidden')),
        'desc' => array('data' => $option['desc'], 'class' => array('field-desc')),
        'screenshot' => array('data' => $option['screenshot'], 'class' => array('field-screenshot')),
        '#attributes' => array('class' => array($row_class)),
      );
    }
  }
}

// Captions do not appear to be supported for table select, use container to emulate.
$form['layouts']['select']['layout']['title'] = array(
  '#type' => 'container',
  '#markup' => t('<h3>Select Layout</h3>'),
);

// Print the layouts table select data.
$form['layouts']['select']['layout']['settings_selected_layout'] = array(
  '#title' => t('Select Layout'),
  '#type' => 'tableselect',
  '#header' => $layout_series_header,
  '#options' => $table_options_data,
  '#multiple' => FALSE,
  '#default_value' => theme_get_setting('settings.selected_layout', $theme),
  '#attributes' => array('class' => array('table-layouts')),
);

// Internet Explorer Options.
$form['layouts']['select']['options'] = array(
  '#type' => 'details',
  '#title' => t('Internet Explorer Options'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

// Help message for IE8 options.
$form['layouts']['select']['options']['no_mq_css'] = array(
  '#type' => 'container',
  '#markup' => t('If you require support for IE8 check the option for your chosen layout or layouts. Only layouts that include support for IE8 are listed here.</p>'),
);

// Add an IE8 togggle setting foreach layout.
if ($settings_data) {
  foreach ($settings_data as $theme_name => $layouts) {
    foreach ($layouts as $layout_name => $layout_data) {

      if (isset($layout_data['css']['no_mq'])) {
        $layout_title = drupal_ucfirst($layout_name);
        $form['layouts']['select']['options']['no_mq_css']["settings_layouts_no_mq_$layout_name"] = array(
          '#type' => 'checkbox',
          '#title' => t('!layout', array('!layout' => $layout_title)),
          '#default_value' => theme_get_setting("settings.layouts_no_mq_$layout_name", $theme),
          '#attributes' => array('class' => array('no-mq-file-checkbox')),
        );
      }

      // Piggy back on the loop logic and build the selectors lists, we use them later in the form.
      $selectors[$layout_name] = $layout_config[$theme_name]->formatSelectors($layout_name);
    }
  }
}

// Layout Selectors.
$form['layouts']['selectors'] = array(
  '#type' => 'details',
  '#title' => t('Layout Selectors'),
  '#description' => t('This shows the unique row selectors for each row in the layout. See the Help tab section "Building and Modifying Layouts" for more information on CSS selectors and building layouts.'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
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
foreach ($these_selectors as $layout_name => $selector_strings) {
  $form['layouts']['selectors'][$layout_name] = array(
    '#type' => 'details',
    '#title' => t($layout_name),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,
  );
  $css = implode("\n\n", $selector_strings);
  $form['layouts']['selectors'][$layout_name]['css'] = array(
    '#type' => 'container',
    '#markup' => '<pre>' . $css . '</pre>' . "\n",
  );
}

// Advanced settings - only Backup for now, could be more later.
$form['layouts']['advanced'] = array(
  '#type' => 'details',
  '#title' => t('Backups'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

// Disable backups.
$form['layouts']['advanced']['settings_disable_backups'] = array(
  '#type' => 'checkbox',
  '#title' => t('Disable backups'),
  '#default_value' => theme_get_setting("settings.disable_backups", $theme),
  '#description' => t('Checking this option will disable the page template and info file backups. Backups are saved in your themes backup folder.'),
);

// Hidden setting to pass the default page.html.twig setting into form_state. We
// need this to get the "series" key for the page.html.twig layout to compare it to
// the series key for any suggestions - these must match, aka a suggestion cannot select
// a layout from another series. This criteria is imposed to prevent users blowing up
// their sites - Layouts in a series all have the same regions.
$form['layouts']['select']['settings_template_suggestion_page'] = array(
  '#type' => 'hidden',
  '#default_value' => $default_layout,
);
