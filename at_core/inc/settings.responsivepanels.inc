<?php 
  // AT PANELS LAYOUTS
    $form['at-layout']['rp'] = array(
    '#type' => 'fieldset',
    '#title' => t('Responsive Panels'),
  );
  $form['at-layout']['rp']['rpw'] = array(
    '#type' => 'fieldset',
    '#attributes' => array('class' => array('panel-option-lists')),
    '#description' => t('<h3>Responsive Panels</h3><p>These settings apply to both <a href="!gpanels_link" target="_blank">Gpanels</a> and <a href="!panels_link" target="_blank">Panels module</a> layouts.</p><p><strong>Usage:</strong> select layout options for each mobile device orientation.</p>', array('!panels_link' => 'http://drupal.org/project/panels', '!gpanels_link' => 'http://adaptivethemes.com/documentation/using-gpanels')),
  );
  // TABLET landscape
  $form['at-layout']['rp']['rpw']['tl'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet landscape'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['tl']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('tablet_landscape_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('tablet_landscape_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('tablet_landscape_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['tl']['twocol']['tablet_landscape_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('tablet_landscape_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL
  $form['at-layout']['rp']['rpw']['tl']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('tablet_landscape_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('tablet_landscape_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('tablet_landscape_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['tl']['threecol']['tablet_landscape_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('tablet_landscape_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['tl']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['fourcol']['tablet_landscape_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('tablet_landscape_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['tl']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['fivecol']['tablet_landscape_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('tablet_landscape_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['tl']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tl']['sixcol']['tablet_landscape_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('tablet_landscape_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['tl']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['tl']['inset']['tablet_landscape_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('tablet_landscape_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['tl']['inset']['tablet_landscape_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('tablet_landscape_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );
  // TABLET portrait
  $form['at-layout']['rp']['rpw']['tp'] = array(
    '#type' => 'fieldset',
    '#title' => t('Tablet portrait'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['tp']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('tablet_portrait_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('tablet_portrait_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('tablet_portrait_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['tp']['twocol']['tablet_portrait_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('tablet_portrait_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL TABLET PORTRAIT
  $form['at-layout']['rp']['rpw']['tp']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('tablet_portrait_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('tablet_portrait_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('tablet_portrait_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['tp']['threecol']['tablet_portrait_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('tablet_portrait_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['tp']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['fourcol']['tablet_portrait_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('tablet_portrait_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['tp']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['fivecol']['tablet_portrait_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('tablet_portrait_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['tp']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['tp']['sixcol']['tablet_portrait_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('tablet_portrait_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['tp']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['tp']['inset']['tablet_portrait_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('tablet_portrait_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['tp']['inset']['tablet_portrait_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('tablet_portrait_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );
  // SMARTPHONE landscape
  $form['at-layout']['rp']['rpw']['sl'] = array(
    '#type' => 'fieldset',
    '#title' => t('Smartphone landscape'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 COL
  $form['at-layout']['rp']['rpw']['sl']['twocol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Two column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 2 50
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_50'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 50/50'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_50'),
    '#options' => array(
      'two-50'       => t('Two col 50 default'),
      'two-50-stack' => t('Two col 50 stack'),
    ),
  );
  // 2 33 66
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_33_66'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 33/66'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_33_66'),
    '#options' => array(
      'two-33-66'       => t('Two col 33/66 default'),
      'two-33-66-stack' => t('Two col 33/66 stack'),
    ),
  );
  // 2 66 33
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_66_33'] = array(
    '#type' => 'radios',
    '#title' => t('Two col 66/33'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_66_33'),
    '#options' => array(
      'two-66-33'       => t('Two col 66/33 default'),
      'two-66-33-stack' => t('Two col 66/33 stack'),
    ),
  );
  // 2 brick
  $form['at-layout']['rp']['rpw']['sl']['twocol']['smartphone_landscape_two_brick'] = array(
    '#type' => 'radios',
    '#title' => t('Two col brick'),
    '#default_value' => theme_get_setting('smartphone_landscape_two_brick'),
    '#options' => array(
      'two-brick'       => t('Two col brick default'),
      'two-brick-stack' => t('Two col brick stack'),
    ),
  );
  // 3 COL
  $form['at-layout']['rp']['rpw']['sl']['threecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Three column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // 3x33
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_3x33'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 3x33'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_3x33'),
    '#options' => array(
      'three-3x33'              => t('3x33 default'),
      'three-3x33-stack-top'    => t('Top stack'),
      'three-3x33-stack-bottom' => t('Bottom stack'),
      'three-3x33-stack'        => t('Full stack'),
    ),
  );
  // 25-50-25
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_25_50_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-50-25'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_25_50_25'),
    '#options' => array(
      'three-25-50-25'              => t('25/50/25 default'),
      'three-25-50-25-stack-top'    => t('Top stack'),
      'three-25-50-25-stack-bottom' => t('Bottom stack'),
      'three-25-50-25-stack'        => t('Full stack'),
    ),
  );
  // 25-25-50
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_25_25_50'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 25-25-50'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_25_25_50'),
    '#options' => array(
      'three-25-25-50'              => t('25/25/50 default'),
      'three-25-25-50-stack-top'    => t('Top stack'),
      'three-25-25-50-stack-bottom' => t('Bottom stack'),
      'three-25-25-50-stack'        => t('Full stack'),
    ),
  );
  // 50-25-25
  $form['at-layout']['rp']['rpw']['sl']['threecol']['smartphone_landscape_three_50_25_25'] = array(
    '#type' => 'radios',
    '#title' => t('Three col 50-25-25'),
    '#default_value' => theme_get_setting('smartphone_landscape_three_50_25_25'),
    '#options' => array(
      'three-50-25-25'              => t('50/25/25 default'),
      'three-50-25-25-stack-top'    => t('Top stack'),
      'three-50-25-25-stack-bottom' => t('Bottom stack'),
      'three-50-25-25-stack'        => t('Full stack'),
    ),
  );
  // 4 COL
  $form['at-layout']['rp']['rpw']['sl']['fourcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Four column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['fourcol']['smartphone_landscape_four_4x25'] = array(
    '#type' => 'radios',
    '#title' => t('Four 4x25'),
    '#default_value' => theme_get_setting('smartphone_landscape_four_4x25'),
    '#options' => array(
      'four-4x25'             => t('4x25 default'),
      'four-4x25-right-stack' => t('Right column stack'),
      'four-4x25-2x2-grid'    => t('2x2 Grid'),
      'four-4x25-stack'       => t('Full stack'),
    ),
  );
  // 5 COL
  $form['at-layout']['rp']['rpw']['sl']['fivecol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Five column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['fivecol']['smartphone_landscape_five_5x20'] = array(
    '#type' => 'radios',
    '#title' => t('Five 5x20'),
    '#default_value' => theme_get_setting('smartphone_landscape_five_5x20'),
    '#options' => array(
      'five-5x20'            => t('5x20 default'),
      'five-5x20-2x3-grid'   => t('2/3 Split grid'),
      'five-5x20-1x2x2-grid' => t('Top stack'),
      'five-5x20-stack-2'    => t('Stack 50/50 bottom'),
      'five-5x20-stack'      => t('Full stack'),
    ),
  );
  // 6 COL
  $form['at-layout']['rp']['rpw']['sl']['sixcol'] = array(
    '#type' => 'fieldset',
    '#title' => t('Six column'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['at-layout']['rp']['rpw']['sl']['sixcol']['smartphone_landscape_six_6x16'] = array(
    '#type' => 'radios',
    '#title' => t('Six 6x16'),
    '#default_value' => theme_get_setting('smartphone_landscape_six_6x16'),
    '#options' => array(
      'six-6x16'          => t('6x16 default'),
      'six-6x16-3x2-grid' => t('3x2 Grid'),
      'six-6x16-2x3-grid' => t('2x3 Grid'),
      'six-6x16-stack'    => t('Full stack'),
    ),
  );
  // Inset
  $form['at-layout']['rp']['rpw']['sl']['inset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Inset'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  // inset left
  $form['at-layout']['rp']['rpw']['sl']['inset']['smartphone_landscape_inset_left'] = array(
    '#type' => 'radios',
    '#title' => t('Inset left'),
    '#default_value' => theme_get_setting('smartphone_landscape_inset_left'),
    '#options' => array(
      'three-inset-left'       => t('Inset left default'),
      'three-inset-left-wrap'  => t('Wrap inset'),
      'three-inset-left-stack' => t('Full stack'),
    ),
  );
  // inset right
  $form['at-layout']['rp']['rpw']['sl']['inset']['smartphone_landscape_inset_right'] = array(
    '#type' => 'radios',
    '#title' => t('Inset right'),
    '#default_value' => theme_get_setting('smartphone_landscape_inset_right'),
    '#options' => array(
      'three-inset-right'       => t('Inset right default'),
      'three-inset-right-wrap'  => t('Wrap inset'),
      'three-inset-right-stack' => t('Full stack'),
    ),
  );