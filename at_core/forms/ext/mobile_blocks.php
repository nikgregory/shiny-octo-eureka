<?php

/**
 * @file
 * Generate settings for Mobile blocks.
 */

$form['mobile-blocks'] = array(
  '#type' => 'details',
  '#title' => t('Mobile Blocks'),
  '#group' => 'extension_settings',
  '#description' => t('<h3>Mobile Blocks</h3><p>Show or hide blocks in mobile devices (tablets, mobile phones etc).</p><ul><li><b>Show:</b> block shows in mobile, otherwise hidden.</li><li><b>Hide:</b> block is hidden in mobile, otherwise shows.</li></ul>'),
);

// Menu blocks
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
