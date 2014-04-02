<?php

/**
 * @file
 * Lists available colors and color schemes for the AT Starterkit.
 */

$info = array(
  // Available colors and color labels used in theme.
  'fields' => array(
    'header'   => t('Header'),
    'body'     => t('Body'),
    'borders'  => t('Borders'),
    'footer'   => t('Footer'),
    'text'     => t('Text'),
    'link'     => t('Links'),
    'branding' => t('Branding'),
  ),
  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Blue Lagoon (default)'),
      'colors' => array(
        'header'   => '#0779bf',
        'body'     => '#ffffff',
        'borders'  => '#f9f9f9',
        'footer'   => '#292929',
        'text'     => '#3b3b3b',
        'link'     => '#0071B3',
        'branding' => '#fffeff',
      ),
    ),
    'firehouse' => array(
      'title' => t('Firehouse'),
      'colors' => array(
        'header'   => '#cd2d2d',
        'body'     => '#ffffff',
        'borders'  => '#ededed',
        'footer'   => '#1f1d1c',
        'text'     => '#3b3b3b',
        'link'     => '#d6121f',
        'branding' => '#fffeff',
      ),
    ),
    'slate' => array(
      'title' => t('Slate'),
      'colors' => array(
        'header'   => '#4a4a4a',
        'body'     => '#ffffff',
        'borders'  => '#d0d0d0',
        'footer'   => '#161617',
        'text'     => '#3b3b3b',
        'link'     => '#0073b6',
        'branding' => '#ffffff',
      ),
    ),
  ),

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'css/colors.css',
  ),

  // Files to copy.
  'copy' => array(
    'logo.png',
  ),

  // Gradient definitions.
  'gradients' => array(
    array(
      // (x, y, width, height).
      'dimension' => array(0, 0, 0, 0),
      // Direction of gradient ('vertical' or 'horizontal').
      'direction' => 'vertical',
      // Keys of colors to use for the gradient.
      'colors' => array('header', 'body'),
    ),
  ),
);
