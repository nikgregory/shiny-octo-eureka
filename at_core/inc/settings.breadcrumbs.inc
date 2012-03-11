<?php 
  $form['at']['breadcrumb'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Breadcrumbs'),
    '#weight' => 30,
  );
  $form['at']['breadcrumb']['bd'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumbs'),
    '#description' => t('<h3>Breadcrumb Settings</h3>'),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_display'] = array(
    '#type' => 'select',
    '#title' => t('Show breadcrumbs'),
    '#default_value' => theme_get_setting('breadcrumb_display'),
    '#options' => array(
      'yes' => t('Yes'),
      'no' => t('No'),
    ),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_separator'] = array(
    '#type'  => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Text only. Dont forget to include spaces.'),
    '#default_value' => theme_get_setting('breadcrumb_separator'),
    '#size' => 8,
    '#maxlength' => 10,
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array('value' => 'yes'),
      ),
    ),
  );
  $form['at']['breadcrumb']['bd']['breadcrumb_home'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the homepage link'),
    '#default_value' => theme_get_setting('breadcrumb_home'),
    '#states' => array(
      'visible' => array(
          '#edit-breadcrumb-display' => array(
            'value' => 'yes',
          ),
      ),
    ),
  );