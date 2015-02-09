<?php

use Drupal\at_core\Layout\LayoutCompatible;
use Drupal\at_core\Theme\ThemeSettingsInfo;
use Drupal\Component\Utility\String;
use Drupal\Component\Utility\Xss;
use Symfony\Component\Yaml\Parser;

$layout_data = new LayoutCompatible($theme);
$layout_compatible_data = $layout_data->getCompatibleLayout();
$layout_config = $layout_compatible_data['layout_config'];

// TODO: search base themes, we need all declarations from all base themes, they can all potentially work.
$shortcodes_yml = $subtheme_path . '/' . $theme . '.shortcodes.yml';
if (file_exists($shortcodes_yml)) {
  $shortcodes_parser = new Parser();
  $shortcodes = $shortcodes_parser->parse(file_get_contents($shortcodes_yml));
}

$page_elements = array(
  //'html' => 'html',
  'body' => 'body',
  'page' => '#page wrapper',
);

/**
 * @file
 * Generate settings for the Custom CSS form.
 */

$form['shortcodes'] = array(
  '#type' => 'details',
  '#title' => t('CSS Shortcodes'),
  '#group' => 'extension_settings',
  '#description' => t('<h3>Add Classes</h3><p>Enter comma seperated lists of class names. <b>Clear the cache</b> after adding classes.</p>'),
);

$form['shortcodes']['page_classes'] = array(
  '#type' => 'details',
  '#title' => t('Page'),
);

// Page
$form['shortcodes']['page_classes'] = array(
  '#type' => 'details',
  '#title' => t('Body, Page'),
);
foreach ($page_elements as $page_elements_key => $page_elements_value) {
  $form['shortcodes']['page_classes']['settings_page_classes_' . $page_elements_key] = array(
    '#type' => 'textfield',
    '#title' => t($page_elements_value),
    '#default_value' => String::checkPlain(theme_get_setting('settings.page_classes_' . $page_elements_key, $theme)),
  );
}

// Rows
$form['shortcodes']['row_classes'] = array(
  '#type' => 'details',
  '#title' => t('Page Rows'),
);
foreach ($layout_config['rows'] as $row_data_key => $row_data_value) {
  $form['shortcodes']['row_classes']['settings_page_classes_row_' . $row_data_key] = array(
    '#type' => 'textfield',
    '#title' => t('page-row__' . $row_data_key),
    '#default_value' => String::checkPlain(theme_get_setting('settings.page_classes_row_' . $row_data_key, $theme)),
  );
}

// Regions
$form['shortcodes']['region_classes'] = array(
  '#type' => 'details',
  '#title' => t('Regions'),
);
foreach ($theme_regions as $region_key => $region_value) {
  $form['shortcodes']['region_classes']['settings_page_classes_region_' . $region_key] = array(
    '#type' => 'textfield',
    '#title' => t($region_value),
    '#default_value' => String::checkPlain(theme_get_setting('settings.page_classes_region_' . $region_key, $theme)),
  );
}

// Blocks
$form['shortcodes']['block_classes'] = array(
  '#type' => 'details',
  '#title' => t('Block'),
);
foreach ($theme_blocks as $block_key => $block_value) {
  $block_label = $block_value->label() . ' <span>(' . $block_key . ')</span>';
  $form['shortcodes']['block_classes']['settings_block_classes_' . $block_key] = array(
    '#type' => 'textfield',
    '#title' => t($block_label),
    '#default_value' => String::checkPlain(theme_get_setting('settings.block_classes_' . $block_key, $theme)),
  );
}

// Node types
$form['shortcodes']['nodetype_classes'] = array(
  '#type' => 'details',
  '#title' => t('Content types'),
);
foreach ($node_types as $nt) {
  $node_type = $nt->get('type');
  $node_type_name = $nt->get('name');

  $form['shortcodes']['nodetype_classes']['settings_nodetype_classes_' . $node_type] = array(
    '#type' => 'textfield',
    '#title' => t($node_type_name),
    '#default_value' => String::checkPlain(theme_get_setting('settings.nodetype_classes_' . $node_type, $theme)),
  );
}


// Actual classes you can apply that are included in the theme.
if (!empty($shortcodes)) {
  $form['shortcodes']['classes'] = array(
    '#type' => 'details',
    '#title' => t('Classes'),
  );
  $class_output = array();
  foreach ($shortcodes as $class_type => $class_values) {

    if (isset($class_values['description'])) {
      $class_description = $class_values['description'];
    }
    else {
      $class_description = 'No description provided.';
    }

    if (isset($class_values['elements'])) {
      $class_elements = implode(', ', $class_values['elements']);
    }
    else {
      $class_elements = 'Unspecified';
    }

    $form['shortcodes']['classes'][$class_type] = array(
      '#type' => 'fieldset',
      '#title' => t($class_values['name']),
      '#markup' => t('<h3>' . $class_values['name'] . '</h3><p>'. $class_description .'</p><p><b>Apply to:</b> <i>' . $class_elements . '</i></p>' ),
    );

    foreach ($class_values['classes'] as $class_key => $class_data) {
      $class_name =  Xss::filterAdmin($class_data['class']);
      $class_output[$class_type][] = '<dt>' . $class_name . '</dt><dd>' . t($class_data['description']) . '</dd>';
    }

    $form['shortcodes']['classes'][$class_type]['classlist'] = array(
      '#markup' => '<dl class="class-list ' . $class_type . '">' . implode('', $class_output[$class_type]) . '</dl>',
    );
  }

}
