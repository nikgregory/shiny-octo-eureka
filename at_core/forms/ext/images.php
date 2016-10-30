<?php

/**
 * Generate form elements for the Image styles settings.
 */

$image_alignment_options = [
  'none'   => t('None'),
  'left'   => t('Left'),
  'center' => t('Center'),
  'right'  => t('Right'),
];

$form['images'] = [
  '#type' => 'details',
  '#title' => t('Image Field Settings'),
  '#group' => 'extension_settings',
];

$form['images']['image-settings'] = [
  '#type' => 'fieldset',
  '#title' => t('Image Field Settings'),
  '#weight' => 0,
];

$form['images']['image-settings']['description'] = [
  '#markup' => '<h3>Image Field Settings</h3><p>Set alignment, caption display and image count per entity type and view mode. Entity types Node, Comment, Custom Blocks and Paragraphs are supported.</p>',
];

if (!empty($entity_types)) {
  foreach ($entity_types as $entity_type_key => $entity_type_values) {

    $form['images']['image-settings'][$entity_type_key] = [
      '#type' => 'details',
      '#title' => t($entity_type_key),
      '#collapsed'=> TRUE,
    ];

    foreach ($entity_type_values as $evk => $etv) {
      if ($entity_type_key === 'paragraphs' || $entity_type_key === 'comment' || $entity_type_key === 'block_content') {
        $entity_type_id = $etv->id();
        $entity_type_label = $etv->label();
      }
      elseif ($entity_type_key === 'node') {
        $entity_type_id = $etv->get('type');
        $entity_type_label = $etv->get('name');
      }

      $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id] = [
        '#type'      => 'details',
        '#title'     => t($entity_type_label),
        '#collapsed' => TRUE,
      ];

      // Alignment settings.
      $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['alignment'] = [
        '#type'      => 'details',
        '#title'     => t('Alignment'),
        '#collapsed' => TRUE,
      ];
      foreach ($view_modes[$entity_type_key] as $display_mode) {
        // View mode labels.
        $display_mode_label = t('Display mode: ') . $display_mode['label'];
        $display_mode_id = str_replace('.', '_', $display_mode['id']);

        // Alignment setting.
        $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['alignment'][$display_mode_id]['settings_image_alignment_' . $entity_type_id . '_' . $display_mode_id] = [
          '#type'          => 'radios',
          '#title'         => $display_mode_label,
          '#default_value' => theme_get_setting('settings.image_alignment_' . $entity_type_id . '_' . $display_mode_id),
          '#options'       => $image_alignment_options,
          '#attributes'    => ['class' => ['clearfix']],
        ];
      }

      // Caption setting.
      $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['captions'] = [
        '#type'        => 'details',
        '#title'       => t('Captions'),
        '#collapsed'   => TRUE,
        '#description' => t('Show captions per display mode. Captions use the "Title" option and must be enabled in the image field settings.'),
      ];
      foreach ($view_modes[$entity_type_key] as $display_mode) {
        // View mode labels.
        $display_mode_label = $display_mode['label'];
        $display_mode_id = str_replace('.', '_', $display_mode['id']);

        $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['captions'][$display_mode_id]['settings_image_captions_' . $entity_type_id . '_' . $display_mode_id] = [
          '#type'          => 'checkbox',
          '#title'         => $display_mode_label,
          '#default_value' => theme_get_setting('settings.image_captions_' . $entity_type_id . '_' . $display_mode_id),
        ];
      }

      // Image count settings.
      $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['count'] = [
        '#type'        => 'details',
        '#title'       => t('Image Count'),
        '#collapsed'   => TRUE,
        '#description' => t('Restrict to <b>one image</b> only in certain display modes - useful for teaser mode when you have multi-value or unlimited images.'),
      ];
      foreach ($view_modes[$entity_type_key] as $display_mode) {
        // View mode labels.
        $display_mode_label = $display_mode['label'];
        $display_mode_id = str_replace('.', '_', $display_mode['id']);

        $form['images']['image-settings'][$entity_type_key][$entity_type_id]['entity_type_' . $entity_type_id]['count'][$display_mode_id]['settings_image_count_' . $entity_type_id . '_' . $display_mode_id] = [
          '#type'          => 'checkbox',
          '#title'         => $display_mode_label,
          '#default_value' => theme_get_setting('settings.image_count_' . $entity_type_id . '_' . $display_mode_id),
        ];
      }
    }
  }
}
