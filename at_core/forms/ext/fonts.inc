<?php

/**
 * Websafe fonts list.
 */
function websafe_fonts() {
  $websafe_fonts = array(
    'none'       => '-- none --',
    'arial'       => 'Arial, Helvetica, "Nimbus Sans L", sans-serif',
    'helvetica'   => 'Helvetica, Arial, "Nimbus Sans L", sans-serif',
    'verdana'     => 'Verdana, Tahoma, "DejaVu Sans", sans-serif',
    'tahoma'      => 'Tahoma, Verdana, "DejaVu Sans", sans-serif',
    'georgia'     => 'Georgia, "Times New Roman", "DejaVu Serif", serif',
    'times_roman' => '"Times New Roman", Times, Georgia, "DejaVu Serif", serif',
    'times'       => 'Times, "Times New Roman", Georgia, "DejaVu Serif", serif',
    'impact'      => 'Impact, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans-serif',
    'courier'     => '"Courier New", "DejaVu Sans Mono", monospace, sans-serif',
  );

  return $websafe_fonts;
}

/**
 * @file
 * Font Elements Config Array.
 *
 * The fonts array is used by the fonts sub-system to generate forms,
 * build stylesheets and load font styles.
 */
function font_elements() {
  $elements_array = &drupal_static(__FUNCTION__, array());
  if (empty($elements_array)) {
    $elements_array = array(
      'base' => array(
        'label' => 'Base',
        'selector' => 'body',
      ),
      'sitename' => array(
        'label' => 'Site name',
        'selector' => '.site-branding__name-link',
      ),
      'siteslogan' => array(
        'label' => 'Site slogan',
        'selector' => '.site-branding__slogan',
      ),
      'pagetitle' => array(
        'label' => 'Page title',
        'selector' => '.view-mode--full .node__title, .page__title, .user__title',
      ),
      'nodetitle' => array(
        'label' => 'Node title',
        'selector' => '.node__title, .node__title a',
      ),
      'commenttitle' => array(
        'label' => 'Comment title',
        'selector' => '.comment__title, .comment__title a',
      ),
      'blocktitle' => array(
        'label' => 'Block title',
        'selector' => '.block__title, .block-menu__title, .pane__title',
      ),
      'links' => array(
        'label' => 'Links',
        'selector' => 'a',
      ),
      'menus' => array(
        'label' => 'Menus',
        'selector' => '.menu a',
      ),
      'responsivemenu' => array(
        'label' => 'Responsive menu',
        'selector' => '.rm-block, .rm-block a',
      ),
      'buttons' => array(
        'label' => 'Buttons',
        'selector' => '.button',
      ),
      'tabs' => array(
        'label' => 'Tabs',
        'selector' => '.tabs a',
      ),
      'h1h4' => array(
        'label' => 'h1 to h4 headings',
        'selector' => 'h1,h2,h3,h4',
      ),
      'h5h6' => array(
        'label' => 'h5, h6 headings',
        'selector' => 'h5,h6',
      ),
      'h1' => array(
        'label' => 'h1',
        'selector' => 'h1',
      ),
      'h2' => array(
        'label' => 'h2',
        'selector' => 'h2',
      ),
      'h3' => array(
        'label' => 'h3',
        'selector' => 'h3',
      ),
      'h4' => array(
        'label' => 'h4',
        'selector' => 'h4',
      ),
      'h5' => array(
        'label' => 'h5',
        'selector' => 'h5',
      ),
      'h6' => array(
        'label' => 'h6',
        'selector' => 'h6',
      ),
      'custom_selectors' => array(
        'label' => 'Custom selectors',
        'selector' => 'custom_selectors',
      ),
    );
  }

  return $elements_array;
}

/**
 * Reusable style options for titles.
 *
 * @param $style
 * @param $element
 */
function title_style_options($style) {
  $options = &drupal_static(__FUNCTION__, array());
  switch ($style) {
    case ('case'):
      $options = array(
        'none'       => t('Normal'),
        'uppercase'  => t('Upper case'),
        'lowercase'  => t('Lower case'),
        'capitalize' => t('Capitalize'),
        'small-caps' => t('Small caps'),
        'inherit'    => t('Inherit'),
      );
    break;
    case ('weight'):
      $options = array(
        '400' => t('Normal'),
        '700' => t('Bold'),
        '600' => t('Semibold'),
        '300' => t('Light'),
      );
    break;
    case ('alignment'):
      $options = array(
        'left'   => t('Left'),
        'right'  => t('Right'),
        'center' => t('Centered'),
      );
    break;
  }

  return $options;
}

/**
 * Reusable valid type options for titles.
 */
function title_valid_type_options() {
  $options = array(
    'sitename',
    'siteslogan',
    'pagetitle',
    'nodetitle',
    'blocktitle',
    'commenttitle',
    'h1',
    'h2',
    'h3',
    'h4',
    'h5',
    'h6',
  );

  return $options;
}
