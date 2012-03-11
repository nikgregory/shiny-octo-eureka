<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */
  $form['at']['search-results'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results'),
    '#weight' => 50,
  );
  $form['at']['search-results']['srs'] = array(
    '#type' => 'fieldset',
    '#title' => t('Search Results Display'),
    '#description' => t('<h3>Search Result Display</h3><p>Modify what is displayed below each search result</p>'),
  );
  $form['at']['search-results']['srs']['search_snippet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display text snippet'),
    '#default_value' => theme_get_setting('search_snippet'),
  );
  $form['at']['search-results']['srs']['search_info_type'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display content type'),
    '#default_value' => theme_get_setting('search_info_type'),
  );
  $form['at']['search-results']['srs']['search_info_user'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display author name'),
    '#default_value' => theme_get_setting('search_info_user'),
  );
  $form['at']['search-results']['srs']['search_info_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display posted date'),
    '#default_value' => theme_get_setting('search_info_date'),
  );
  $form['at']['search-results']['srs']['search_info_comment'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display comment count'),
    '#default_value' => theme_get_setting('search_info_comment'),
  );
  $form['at']['search-results']['srs']['search_info_upload'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display attachment count'),
    '#default_value' => theme_get_setting('search_info_upload'),
  );
  // Search_info_separator
  $form['at']['search-results']['srs']['search_info_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Separator'),
    '#description' => t('Modify the separator between each of the above items. The default is a hypen with a space before and after.'),
    '#default_value' => theme_get_setting('search_info_separator'),
    '#size' => 8,
    '#maxlength' => 10,
  );