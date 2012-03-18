<?php

  // Search result settings
  $form['at']['search-results'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results'),
    '#description' => t('<h3>Search Result Display</h3><p>Modify what is displayed below each search result</p>'),
    '#weight' => -60,
  );
  $form['at']['search-results']['srs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results Display'),
  );

  // Snippet
  $form['at']['search-results']['srs']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );

  // Type
  $form['at']['search-results']['srs']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );

  // User info
  $form['at']['search-results']['srs']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );

  // Posted by date
  $form['at']['search-results']['srs']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );

  // Comment count
  $form['at']['search-results']['srs']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );

  // Attachment count
  $form['at']['search-results']['srs']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );

  // Search info separator
  $form['at']['search-results']['srs']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Modify the separator between each of the above items. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );