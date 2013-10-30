<?php

use Drupal\at_core\Layout\LayoutSettings;
use Drupal\at_core\Theme\ThemeSettingsInfo;

$themeInfo = new ThemeSettingsInfo($theme);
$providers = $themeInfo->baseThemeInfo('base_themes');

// Unset at_core, it has no layouts.
unset($providers['at_core']);

// Push the current theme into the array, if it has layouts we need them.
$providers[$theme] = $theme;

foreach ($providers as $key => $provider_name) {
  $layout_config[$key] = new LayoutSettings($key);
  $options_data[$key]  = $layout_config[$key]->layoutOptions();

  $layout_config[$key] = new LayoutSettings($key);
  $settings_data[$key] = $layout_config[$key]->settingsPrepareData();
}

//$options_data  = $layout_config->layoutOptions();
//dsm($options_data);
// Get the layout configuration for all available layouts.
//$layout_config = new LayoutSettings($theme);
//$settings_data = $layout_config->settingsPrepareData();
//dsm($settings_data);

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

// Select layout type (default, suggestion or disable layout generation)
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

    $default_series_markup = t('<label>Current default layout:</label> <em>!default_layout</em> from the <em>!default_series</em> series.<br>(Provided by !default_provider)', array('!default_layout' => ucfirst($default_layout), '!default_series' => $default_series, '!default_provider' => $default_provider));
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

// Default layout message.
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

// Default layout not set message.
$form['layouts']['select']['select_type']['default_not_set'] = array(
  '#type' => 'container',
  '#markup' => t('First set the default page layout - no default page layout detected.'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_not_set')),
  ),
);

// Enter the suggestion if default layout detected.
$form['layouts']['select']['select_type']['suggestions'] = array(
  '#type' => 'container',
  '#markup' => t('
    <label>Add or Change a Template Suggestion</label>
  '),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_layout')),
  ),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'template_suggestion')),
  ),
);

if (isset($default_series)) {
  $suggestion_series_message = $default_series;
}
else {
  $suggestion_series_message = '';
}

$form['layouts']['select']['select_type']['suggestions']['template_suggestion_name'] = array(
  '#type' => 'textfield',
  //'#title' => t('Enter Template Suggestion'),
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


if (isset($default_series_markup)) {
  $form['layouts']['select']['select_type']['default_layout'] = array(
    '#type' => 'container',
    '#markup' => $default_series_markup,
  );
}


// Layouts table
$form['layouts']['select']['layout'] = array(
  '#type' => 'container',
  '#states' => array(
    'disabled' => array('select[name="layout_type_select"]' => array('value' => 'disable_layout_generation')),
  ),
);
$layout_series_header = array(
  'name' => t('Name'),
  'series' => t('Series'),
  'provider' => t('Provider'),
  'version' =>  t('Version'),
  'desc' => t('Description'),
  'screenshot' => t('Screenshot'),
);
foreach ($providers as $key => $provider_name) {
  foreach ($options_data[$key] as $series => $options) {
    if ($series == 'not-set') {
      drupal_set_message(t('Series not set for one or more layouts - this could cause issues when setting layouts for template suggestions. If you have set a series check the layout name is identical for both the layout folder and the layout.yml file.'), 'warning');
    }
    foreach ($options as $option) {
      $row_class = 'table-row-'. drupal_html_class($option['name']);
      $name_key = str_replace(' ', '_', strtolower($option['name']));
      $table_options_data[$name_key] = array(
        'name' => array('data' => $option['name'], 'class' => array('field-name')),
        'series' => array('data' => $option['series'], 'class' => array('field-series')),

        'provider' => array('data' => $provider_name, 'class' => array('field-provider')),

        'version' => array('data' => $option['version'], 'class' => array('field-version')),
        'desc' => array('data' => $option['desc'], 'class' => array('field-desc')),
        'screenshot' => array('data' => $option['screenshot'], 'class' => array('field-screenshot')),
        '#attributes' => array('class' => array($row_class)),
      );
    }
  }
}

$form['layouts']['select']['layout']['title'] = array(
  '#type' => 'container',
  '#markup' => t('<h3>Select Layout</h3>'),
);
$form['layouts']['select']['layout']['settings_selected_layout'] = array(
  '#title' => t('Select Layout'),
  '#type' => 'tableselect',
  '#header' => $layout_series_header,
  '#options' => $table_options_data,
  '#multiple' => FALSE,
  '#default_value' => theme_get_setting('settings.selected_layout', $theme),
  '#attributes' => array('class' => array('table-layouts')),
);

// Options.
$form['layouts']['select']['options'] = array(
  '#type' => 'details',
  '#title' => t('Internet Explorer Options'),
  '#collapsed' => TRUE,
  '#collapsible' => TRUE,
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);

// Add a checkbox for each layouts IE8/No Media queries toggle.
$form['layouts']['select']['options']['no_mq_css'] = array(
  '#type' => 'container',
  '#markup' => t('If you require support for IE8 check the option for your chosen layout or layouts. Only layouts that include support for IE8 are listed here.</p>'),
);

//if ($settings_data) {
  //foreach ($settings_data as $layout_name => $values) {


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
          '#states' => array(
            'visible' => array(
               //':input[name="settings_selected_layout"]' => array('value' => $layout_name),
            ),
          ),
        );
      }

      // Build the selectors lists, we use them later in the form.
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
foreach ($selectors as $layout_name => $css_selectors) {
  foreach($css_selectors as $thiskey => $thesevalues) {
    foreach ($thesevalues as $key => $values) {
      $these_selectors[$layout_name][$key] = implode("\n", $values);
    }
  }
}
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

// Advanced settings
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
// their sites - Layouts in a series all have the same regions (or a subset, maybe, TBC).
$form['layouts']['select']['settings_template_suggestion_page'] = array(
  '#type' => 'hidden',
  '#default_value' => $default_layout,
);
