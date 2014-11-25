<?php

use Drupal\Component\Utility\String;

/**
 * @file
 * Generate form elments for the Modify Output settings.
 */
$form['markup_overrides'] = array(
  '#type' => 'details',
  '#title' => t('Markup Overrides'),
  '#group' => 'extension_settings',
);

$form['markup_overrides']['markup_overrides_settings'] = array(
  '#type' => 'fieldset',
  '#title' => t('Markup Overrides'),
  '#weight' => 40,
);

// Breadcrumbs
$form['markup_overrides']['markup_overrides_settings']['breadcrumb'] = array(
  '#type' => 'details',
  '#title' => t('Breadcrumbs'),
);
$form['markup_overrides']['markup_overrides_settings']['breadcrumb']['wrapper'] = array(
  '#type' => 'fieldset',
  '#title' => t('Breadcrumbs'),
);
// Breadcrumbs Label?
$form['markup_overrides']['markup_overrides_settings']['breadcrumb']['wrapper']['settings_breadcrumb_label'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show the "You are here" label'),
  '#default_value' => theme_get_setting('settings.breadcrumb_label'),
);
// Breadcrumbs Home link?
$form['markup_overrides']['markup_overrides_settings']['breadcrumb']['wrapper']['settings_breadcrumb_home'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show the homepage link'),
  '#default_value' => theme_get_setting('settings.breadcrumb_home'),
);
// Breadcrumbs Page title?
$form['markup_overrides']['markup_overrides_settings']['breadcrumb']['wrapper']['settings_breadcrumb_title'] = array(
  '#type' => 'checkbox',
  '#title' => t('Append the page title to the breadcrumb trail'),
  '#default_value' => theme_get_setting('settings.breadcrumb_title'),
);
// Breadcrumbs Seperator
$form['markup_overrides']['markup_overrides_settings']['breadcrumb']['wrapper']['settings_breadcrumb_separator'] = array(
  '#type'  => 'textfield',
  '#title' => t('Separator'),
  '#description' => t('Set in CSS using the <a href="!content_property" target="_blank">content property</a>.', array('!content_property' => 'http://www.w3schools.com/cssref/pr_gen_content.asp')),
  '#default_value' => t(theme_get_setting('settings.breadcrumb_separator')),
  '#size' => 25,
  '#maxlength' => 60,
);

// Login block.
$form['markup_overrides']['markup_overrides_settings']['login-block'] = array(
  '#type' => 'details',
  '#title' => t('Login Block'),
);
// Login block placeholder labels.
$form['markup_overrides']['markup_overrides_settings']['login-block']['settings_login_block_placeholder_labels'] = array(
  '#type' => 'checkbox',
  '#title' => t('Placeholder labels'),
  '#default_value' => theme_get_setting('settings.login_block_placeholder_labels'),
  '#description' => t('Use html5 placeholder labels instead of real labels.'),
);
// Horizontal login block
$form['markup_overrides']['markup_overrides_settings']['login-block']['settings_horizontal_login_block'] = array(
  '#type' => 'checkbox',
  '#title' => t('Horizontal login block'),
  '#default_value' => theme_get_setting('settings.horizontal_login_block'),
  '#description' => t('Enable a horizontal style login block (all elements on one line). This setting automatically removes links.'),
);
// Login block links
$form['markup_overrides']['markup_overrides_settings']['login-block']['settings_login_block_remove_links'] = array(
  '#type' => 'checkbox',
  '#title' => t('Remove links'),
  '#default_value' => theme_get_setting('settings.login_block_remove_links'),
  '#description' => t('Remove the <em>Create new account</em> and <em>Request new password</em> links from the login block.'),
  '#states' => array(
    'checked' => array('input[name="settings_horizontal_login_block"]' => array('checked' => TRUE)),
    'disabled' => array('input[name="settings_horizontal_login_block"]' => array('checked' => TRUE)),
  ),
);

// Hide or Remove
$form['markup_overrides']['markup_overrides_settings']['hide-remove'] = array(
  '#type' => 'details',
  '#title' => t('Hide or Remove'),
);
$form['markup_overrides']['markup_overrides_settings']['hide-remove']['settings_comments_hide_title'] = array(
  '#type' => 'checkbox',
  '#title' => t('Hide comment titles'),
  '#default_value' => theme_get_setting('settings.comments_hide_title'),
  '#description' => t('Checking this setting will hide comment titles using element-invisible. Hiding rather than removing titles maintains accessibility and semantic structure while not showing titles to sighted users.'),
);

// Accessibility
$form['markup_overrides']['markup_overrides_settings']['a11y'] = array(
  '#type' => 'details',
  '#title' => t('Accessibility'),
);

// Skip link target
$form['markup_overrides']['markup_overrides_settings']['a11y']['settings_skip_link_target'] = array(
  '#type' => 'textfield',
  '#title' => t('Skip to navigation target ID'),
  '#description' => t('By default the skip link target is <code>#main-content</code>, you can alter that here if you need to.'),
  '#size' => 25,
  '#maxlength' => 60,
  '#default_value' => String::checkPlain(theme_get_setting('settings.skip_link_target')),
);

// Attribution
$form['markup_overrides']['markup_overrides_settings']['attribution'] = array(
  '#type' => 'details',
  '#title' => t('Attribution'),
);
$form['markup_overrides']['markup_overrides_settings']['attribution']['settings_attribution_toggle'] = array(
  '#type' => 'checkbox',
  '#title' => t('Show attribution message'),
  '#description' => t('Displays a message and link for Adaptivethemes.com'),
  '#default_value' => theme_get_setting('settings.attribution_toggle'),
);
