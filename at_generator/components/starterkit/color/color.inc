<?php

/**
 * @file
 * Lists available colors and color schemes for the AT Starterkit.
 * By default the color scheme relies almost entirely on shift, so the user
 * sets the base color which shifts unmatched colors.
 */

$info = array(
  // Available colors and color labels used in theme.
  'fields' => array(
    'base' => t('Base'),
    'text' => t('Text color'),
    'link' => t('Link color'),
  ),
  // Pre-defined color schemes.
  'schemes' => array(
    'default' => array(
      'title' => t('Carbon Neutral'),
      'colors' => array(
        'base' => '#000000',
        'text' => '#555555', // $text-color
        'link' => '#0066cc', // $link-color
      ),
    ),
    'goodearth' => array(
      'title' => t('The Good Earth'),
      'colors' => array(
        'base' => '#4ba943',
        'text' => '#555555',
        'link' => '#8dd087',
      ),
    ),
    'tippingpoint' => array(
      'title' => t('Tipping Point'),
      'colors' => array(
        'base' => '#e65200',
        'text' => '#777777',
        'link' => '#ff792f',
      ),
    ),
  ),

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => array(
    'styles/css/colors.css',
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
      'colors' => array('base', 'text'),
    ),
  ),
);
