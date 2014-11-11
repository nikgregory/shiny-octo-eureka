<?php

use Drupal\at_core\Layout\LayoutCompatible;
use Drupal\at_core\Theme\ThemeSettingsInfo;

use Drupal\Component\Utility\Xss;
use Symfony\Component\Yaml\Parser;

$layout_data = new LayoutCompatible($theme);
$layout_compatible_data = $layout_data->getCompatibleLayout();
$layout_config = $layout_compatible_data['layout_config'];

// TODO: search base themes, we need all declarations from all base themes, they can all potentially work.
$classitis_yml = $subtheme_path . '/' . $theme . '.classitis.yml';
if (file_exists($classitis_yml)) {
  $classitis_parser = new Parser();
  $classitis = $classitis_parser->parse(file_get_contents($classitis_yml));
}

/**
 * @file
 * Generate settings for the Custom CSS form.
 */

$form['classitis'] = array(
  '#type' => 'details',
  '#title' => t('Classitis'),
  '#group' => 'at_settings',
  '#description' => t('<h3>Add Classes</h3><p>Enter comma seperated lists of class names. <b>Clear the cache</b> after adding classes.</p>'),
);

$form['classitis']['page_classes'] = array(
  '#type' => 'details',
  '#title' => t('Page'),
);

// Rows
$form['classitis']['page_classes']['rows'] = array(
  '#type' => 'details',
  '#title' => t('Rows'),
);
foreach ($layout_config['rows'] as $row_data_key => $row_data_value) {
  $form['classitis']['page_classes']['rows']['settings_page_classes_row_' . $row_data_key] = array(
    '#type' => 'textfield',
    '#title' => t('page-row__' . $row_data_key),
    '#default_value' => Xss::filterAdmin(theme_get_setting('settings.page_classes_row_' . $row_data_key, $theme)),
  );
}

// Regions
$form['classitis']['page_classes']['regions'] = array(
  '#type' => 'details',
  '#title' => t('Regions'),
);
foreach ($theme_regions as $region_key => $region_value) {
  $form['classitis']['page_classes']['regions']['settings_page_classes_region_' . $region_key] = array(
    '#type' => 'textfield',
    '#title' => t($region_value),
    '#default_value' => Xss::filterAdmin(theme_get_setting('settings.page_classes_region_' . $region_key, $theme)),
  );
}

// Blocks
$form['classitis']['block_classes'] = array(
  '#type' => 'details',
  '#title' => t('Block'),
);
foreach ($theme_blocks as $block_key => $block_value) {
  $block_label = $block_value->label() . ' <span>(' . $block_key . ')</span>';
  $form['classitis']['block_classes']['settings_block_classes_' . $block_key] = array(
    '#type' => 'textfield',
    '#title' => t($block_label),
    '#default_value' => Xss::filterAdmin(theme_get_setting('settings.block_classes_' . $block_key, $theme)),
  );
}

// Node types
$form['classitis']['nodetype_classes'] = array(
  '#type' => 'details',
  '#title' => t('Content types'),
);
foreach ($node_types as $nt) {
  $node_type = $nt->type;
  $node_type_name = $nt->name;
  $form['classitis']['nodetype_classes']['settings_nodetype_classes_' . $node_type] = array(
    '#type' => 'textfield',
    '#title' => t($node_type_name),
    '#default_value' => Xss::filterAdmin(theme_get_setting('settings.nodetype_classes_' . $node_type, $theme)),
  );
}


// Actual classes you can apply that are included in the theme.
if (!empty($classitis)) {
  $form['classitis']['classes'] = array(
    '#type' => 'details',
    '#title' => t('Classes'),
  );
  $class_output = array();
  foreach ($classitis as $class_type => $class_values) {

    $form['classitis']['classes'][$class_type] = array(
      '#type' => 'fieldset',
      '#title' => t($class_values['name']),
      '#markup' => t('<h3>' . $class_values['name'] . '</h3><p>'. $class_values['description'] .'</p>'),
    );

    foreach ($class_values['classes'] as $class_key => $class_data) {
      $class_name =  Xss::filterAdmin($class_data['class']);
      $class_output[$class_type][] = '<dt>' . $class_name . '</dt><dd>' . t($class_data['description']) . '</dd>';
    }

    $form['classitis']['classes'][$class_type]['classlist'] = array(
      '#markup' => '<dl class="class-list ' . $class_type . '">' . implode('', $class_output[$class_type]) . '</dl>',
    );
  }

}
