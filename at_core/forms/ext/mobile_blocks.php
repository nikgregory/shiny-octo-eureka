<?php

/**
 * @file
 * Generate settings for Mobile blocks.
 */

$mobile_blocks_breakpoint_group = theme_get_setting('settings.mobile_blocks_breakpoint_group', $theme) ?: 'at_core.simple';
$mobile_blocks_breakpoints = $breakpoints[$mobile_blocks_breakpoint_group];

// Breakpoints
foreach ($mobile_blocks_breakpoints as $mbs_key => $mbs_value) {
  $mbs_query = $mbs_value->getMediaQuery();
  $mbs_group_options[$mbs_query] = $mbs_value->getLabel() . ': ' . $mbs_query;
}

$form['mobile-blocks'] = array(
  '#type' => 'details',
  '#title' => t('Mobile Blocks'),
  '#group' => 'extension_settings',
  '#description' => t('<h3>Mobile Blocks</h3><p>Show or hide blocks in mobile devices (tablets, mobile phones etc).</p><ol><li>First select a breakpoint group and breakpoint.</li><li>Check hide or show. If nothing is set the block will always show.</li></ol></p>'),
);

// Breakpoints group
$form['mobile-blocks']['settings_mobile_blocks_breakpoint_group'] = array(
  '#type' => 'select',
  '#title' => t('Breakpoint group'),
  '#options' => $breakpoint_options,
  '#default_value' => $mobile_blocks_breakpoint_group,
);

// Breakpoint
$form['mobile-blocks']['settings_mobile_blocks_breakpoint'] = array(
  '#type' => 'select',
  '#title' => t('Breakpoint'),
  '#options' => $mbs_group_options,
  '#default_value' => theme_get_setting('settings.mobile_blocks_breakpoint', $theme) ?: 'all and (max-width: 45em)',
  '#states' => array(
    'enabled' => array('select[name="settings_mobile_blocks_breakpoint_group"]' => array('value' => $mobile_blocks_breakpoint_group)),
  ),
);

// Change message
$form['mobile-blocks']['mobile_blocks_breakpoint_group_haschanged'] = array(
  '#type' => 'container',
  '#markup' => t('<em>Save the extension settings to change the breakpoint group and update breakpoint options.</em>'),
  '#attributes' => array('class' => array('warning', 'messages', 'messages--warning')),
  '#states' => array(
    'invisible' => array('select[name="settings_mobile_blocks_breakpoint_group"]' => array('value' => $mobile_blocks_breakpoint_group)),
  ),
);

/*
$form['mobile-blocks']['show-hide-help'] = array(
  '#type' => 'container',
  '#markup' => t('<ul><li><b>Show:</b> block shows, otherwise hidden.</li><li><b>Hide:</b> block is hidden, otherwise shows.</li></ul><p>If nothing is checked the block will always show (if enabled).</p>'),
  '#attributes' => array('class' => array('mobile-blocks-show-hide-help')),
);
*/

// Blocks
if (!empty($theme_blocks)) {
  foreach ($theme_blocks as $block_key => $block_values) {

    $block_settings = $block_values->get('settings');
    $block_id = $block_values->id();

    $form['mobile-blocks'][$block_id] = array(
      '#type' => 'fieldset',
      '#title' => $block_settings['label'],
      '#markup' => '<h4 class="mobile-blocks-title layouts-column-threequarters align-left">' . $block_settings['label'] . ' <span>(' . $block_id . ')</span></h4>',
      '#attributes' => array('class' => array('clearfix')),
    );

    $form['mobile-blocks'][$block_id]['container'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => array('layouts-column-onequarter', 'align-right')),
    );

    $form['mobile-blocks'][$block_id]['container']['settings_mobile_block_show_' . $block_id] = array(
      '#type' => 'checkbox',
      '#title' =>  t('Show'),
      '#default_value' => theme_get_setting('settings.mobile_block_show_' . $block_id, $theme),
      '#states' => array(
        'disabled' => array('input[name="settings_mobile_block_hide_' . $block_id . '"]' => array('checked' => TRUE)),
      ),
    );

    $form['mobile-blocks'][$block_id]['container']['settings_mobile_block_hide_' . $block_id] = array(
      '#type' => 'checkbox',
      '#title' =>  t('Hide'),
      '#default_value' => theme_get_setting('settings.mobile_block_hide_' . $block_id, $theme),
      '#states' => array(
        'disabled' => array('input[name="settings_mobile_block_show_' . $block_id . '"]' => array('checked' => TRUE)),
      ),
    );
  }
}
