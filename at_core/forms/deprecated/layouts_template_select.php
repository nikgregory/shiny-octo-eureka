<?php

// Set options for layout type.
if (theme_get_setting('settings.template_suggestion_page', $theme)) {
  $type_options = array(
    'disable_layout_generation' => t('-- select type --'),
    'default_layout' => t('Default (page.html.twig)'),
    'template_suggestion' => t('Template Suggestion'),
  );

  $default_page_layout = TRUE;
}
else {
  $type_options = array(
    'disable_layout_generation' => t('-- select type --'),
    'default_layout' => t('Default (page.html.twig)'),
    'default_not_set' => t('Template Suggestion'),
  );

  $default_page_layout = FALSE;
}


// Modify or create new Layout
$form['layouts']['template_select'] = array(
  '#type' => 'fieldset',
  '#title' => t('Manage Templates'),
  '#attributes' => array('class' => array('layouts-column', 'layouts-column-one')),
  '#states' => array(
    'visible' => array('input[name="settings_layouts_enable"]' => array('checked' => TRUE)),
  ),
);


// Select Layout Type container.
$form['layouts']['template_select']['select_type'] = array(
  '#type' => 'container',
);

$form['layouts']['template_select']['select_type']['layout_type_select'] = array(
  '#type' => 'select',
  '#title' => t('<h3>Create new layout</h3>'),
  '#options' => $type_options,
);



// Default Layout container and Help.

$form['layouts']['template_select']['select_type']['default_set'] = array(
  '#type' => 'container',
  '#markup' => t('
    <div class="description">
    <label>Default Layout for page.html.twig</label>
    <ol>
      <li>Select layouts for each row in your theme.</li>
      <li>Configure "Options".</li>
      <li>Save the layout settings.</li>
    </ol>
    <p>The default layout is used globally unless overridden by a template suggestion.</p></div>'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_layout')),
  ),
);


// Default Layout "not set" message.

$form['layouts']['template_select']['select_type']['default_not_set'] = array(
  '#type' => 'container',
  '#markup' => t('First set the default page layout - no default page layout detected.'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'default_not_set')),
  ),
);




// Suggestions container.

$form['layouts']['template_select']['select_type']['suggestions'] = array(
  '#type' => 'container',
  '#markup' => t('<label>Add or Modify a Suggestion</label>'),
  '#states' => array(
    'visible' => array('select[name="layout_type_select"]' => array('value' => 'template_suggestion')),
  ),
);


// Suggestions input and help.
$suggestion_plugin_message = isset($default_plugin) ? $default_plugin : '-- not set --';
$form['layouts']['template_select']['select_type']['suggestions']['template_suggestion_name'] = array(
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

















//$real_default_layout = theme_get_setting('settings.master_layout');

//var_dump($real_default_layout);

// Default layout and Plugin message.

if ($default_layout = theme_get_setting('settings.template_suggestion_page', $theme)) {


  //var_dump($default_layout);

  // Dig deep for the current default layout plugin key
  if ($settings_data) {
    foreach ($settings_data as $theme_name => $layouts) {
      foreach ($layouts as $layout_key => $layout_values) {
        foreach ($layout_values['css_layouts'] as $layout_variant => $layout_variant_values) {
          if ($default_layout == $layout_variant) {
            $default_plugin = $layout_values['name'];
            $default_provider = str_replace('_', ' ', mb_strtoupper($theme_name));
            $default_plugin_key = $layout_key; // used in the layout select section
            break;
          }
        }
      }
    }
    $default_plugin_markup = array();
    $default_plugin_markup = '<h5>' . t('Current default page.html.twig layout:') . '</h5>';
    $default_plugin_markup .= '<dl class="layout-meta current-default-layout-meta">';
    //$default_plugin_markup .= '<dt>' . t('Template') . '</dt><dd>page.html.twig</dd>';
    $default_plugin_markup .= '<dt>' . t('Plugin') . '</dt><dd>'. mb_strtoupper($default_plugin) . '</dd>';
    $default_plugin_markup .= '<dt>' . t('Variant') . '</dt><dd>'. mb_strtoupper($default_layout) . '</dd>';
    $default_plugin_markup .= '<dt>' . t('Provider') . '</dt><dd>'. $default_provider . '</dd>';
    $default_plugin_markup .= '</dl>';
  }
}
else {
  $plugin_not_set_markup = t('Current default layout: No default set. Choose a CSS layout for the default layout and save this form.');
  $form['layouts']['template_select']['select_type']['default_layout'] = array(
    '#type' => 'container',
    '#markup' => $plugin_not_set_markup,
  );
}


//kpr($layout_values);

// -- none -- message.
/*
$form['layouts']['template_select']['select']['select_type']['none_set'] = array(
  '#type' => 'container',
  '#markup' => t('No new page layout will be generated. Select "Default" or "Template Suggestion" to configure a layout.'),
  '#states' => array(
    'visible' => array(
      'select[name="layout_type_select"]' => array('value' => 'disable_layout_generation'),
    ),
  ),
);
*/



















// Print the default layout and plugin message above the table.
/*
if (isset($default_plugin_markup)) {
  $form['layouts']['template_select']['select_type']['default_layout'] = array(
    '#type' => 'container',
    '#markup' => $default_plugin_markup,
  );
}
*/


// Print out generated suggestions
// Prepare table select headers.
$manage_suggestions_header = array(
  'suggestion' => array('data' => t('Suggestion'), 'class' => array('field-suggestion')),
  'variant'    => array('data' => t('Variant'), 'class' => array('field-variant')),
);

// Prepare table data
$manage_suggestions_data = array();

foreach ($config as $key => $value) {
  if (substr($key, 0, 26) == 'template_suggestion_page__') {
    $template_suggestion = drupal_substr($key, 20);
    $variant = ucfirst($value);
    $manage_suggestions_data[$key] = array(
      'suggestion' => array('data' => $template_suggestion, 'class' => array('field-suggestion')),
      'variant'    => array('data' => $variant, 'class' => array('field-variant')),
    );
  }
}

if (!empty($manage_suggestions_data)) {
  $form['layouts']['template_select']['select_type']['manage_suggestions'] = array(
    '#type' => 'fieldset',
  );

  $form['layouts']['template_select']['select_type']['manage_suggestions']['description'] = array(
    '#markup' => t('<h5>Generated Suggestions</h5>'),
  );

  $form['layouts']['template_select']['select_type']['manage_suggestions']['delete_suggestions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Manage Suggestions'),
    '#default_value' => FALSE,
    '#description' => t('Delete suggestions by checking the box next to the suggestion and click "Delete Suggestions".'),
  );

  $form['layouts']['template_select']['select_type']['manage_suggestions']['wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('delete_suggestions-container')),
    '#states' => array(
      'enabled' => array('input[name="delete_suggestions"]' => array('checked' => TRUE)),
    ),
  );

  // Print the layouts table select data.
  $form['layouts']['template_select']['select_type']['manage_suggestions']['wrapper']['delete_suggestions_table'] = array(
    '#title' => t('Manage Suggestions'),
    '#type' => 'tableselect',
    '#header' => $manage_suggestions_header,
    '#options' => $manage_suggestions_data,
    '#multiple' => TRUE,
    '#attributes' => array('class' => array('table-suggestions')),
    '#states' => array(
      'enabled' => array('input[name="delete_suggestions"]' => array('checked' => TRUE)),
    ),
  );


}

