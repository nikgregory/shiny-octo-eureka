<?php

  // Image styles
  $form['at']['images'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image Settings'),
    '#description' => '<h3>Image Settings</h3>',
    '#weight' => -80,
  );

  // Alignment
  $form['at']['images']['alignment'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image Alignment'),
  );
  $form['at']['images']['alignment']['image_alignment'] = array(
    '#type' => 'radios',
    '#title' => t('<strong>Image field alignment</strong>'),
    '#default_value' => theme_get_setting('image_alignment'),
    '#description' => t('This will only affect images added using an image-field. If you use another method such as embedding images directly into text areas this will not affect those images.'),
    '#options' => array(
      'ia-n' => t('None'),
      'ia-l' => t('Left'),
      'ia-c' => t('Centered'),
      'ia-r' => t('Right'),
    ),
  );

  // Captions
  $form['at']['images']['captions'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image Captions'),
    '#description' => t('<strong>Display the image title as a caption</strong>'),
  );
  $form['at']['images']['captions']['image_caption_teaser'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show captions on teaser view'),
    '#default_value' => theme_get_setting('image_caption_teaser'),
  );
  $form['at']['images']['captions']['image_caption_full'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show captions on full view'),
    '#description' => t('Captions use the title attribute text. You must enable tiles in the field management options for your image fields <em>(Enable Title field)</em>.'),
    '#default_value' => theme_get_setting('image_caption_full'),
  );