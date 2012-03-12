<?php
// Important configuration for sub-systems, essentially
// these are config as data structures - they are not consistant
// because we don't need a generic system, what we need is a bespoke
// and highly optimised system that only supports what is needed.
// There is one missing, the layout sub system config, 
// because it does not exist...

// this should not load globally, only in the scopes that we need it.

// The panels array is used by the responsive panels sub-system
function responsive_panels() {
  $panels = array(
    'two' => array(
      'two_50' => array(
        'title' => 'two col 50/50',
        'setting' => 'two_50',
        'options' => array(
          'two-50' => 'default',
          'two-50-stack' => 'stack',
        )
      ),
      'two_33_66' => array(
        'title' => 'two col 33/66',
        'setting' => 'two_33_66',
        'options' => array(
          'two-33-66' => 'default',
          'two-33-66-stack' => 'stack',
        )
      ),
      'two_66_33' => array(
        'title' => t('two col 66/33'),
        'setting' => 'two_66_33',
        'options' => array(
          'two-66-33' => 'default',
          'two-66-33-stack' => 'stack',
        )
      ),
      'two_brick' => array(
        'title' => t('two col Brick'),
        'setting' => 'two_brick',
        'options' => array(
          'two-brick' => 'default',
          'two-brick-stack' => 'stack',
        )
      ),    
    ),
    'three' => array(
      'three_3x33' => array(
        'title' => 'three col 3x33',
        'setting' => 'three_3x33',
        'options' => array(
          'three-3x33' => 'default',
          'three-3x33-stack-top' => 'stack bottom',
          'three-3x33-stack-bottom' => 'stack top',
          'three-3x33-stack' => 'stack',
        )
      ), 
      'three_25_50_25' => array(
        'title' => 'three col 25-50-25',
        'setting' => 'three_25_50_25',
        'options' => array(
          'three-25-50-25' => 'default',
          'three-25-50-25-stack-top' => 'stack bottom',
          'three-25-50-25-stack-bottom' => 'stack top',
          'three-25-50-25-stack' => 'stack',
        )
      ),
      'three_25_25_50' => array(
        'title' => t('three col 25-25-50'),
        'setting' => 'three_25_25_50',
        'options' => array(
          'three-25-25-50' => 'default',
          'three-25-25-50-stack-top' => 'stack bottom',
          'three-25-25-50-stack-bottom' => 'stack top',
          'three-25-25-50-stack' => 'stack',
        )
      ),
      'three_50_25_25' => array(
        'title' => 'three col 50-25-25',
        'setting' => 'three_50_25_25',
        'options' => array(
          'three-50-25-25' => 'default',
          'three-50-25-25-stack-top' => 'stack bottom',
          'three-50-25-25-stack-bottom' => 'stack top',
          'three-50-25-25-stack' => 'stack',
        )
      ),
    ),
    'four' => array(
      'four_4x25' => array(
        'title' => 'four 4x25',
        'setting' => 'four_4x25',
        'options' => array(
          'four-4x25' => 'default',
          'four-4x25-right-stack' => 'stack right',
          'four-4x25-2x2-grid' => '2x2 grid',
          'four-4x25-stack' => 'stack',
        )
      ),
    ),
    'five' => array(
      'five_5x20' => array(
        'title' => 'five 5x20',
        'setting' => 'five_5x20',
        'options' => array(
          'five-5x20' => 'default',
          'five-5x20-2x3-grid' => '2/3 split grid',
          'five-5x20-1x2x2-grid' => 'stack',
          'five-5x20-stack-2' => 'Stack 50/50 bottom',
          'five-5x20-stack' => 'stack',
        )
      ),
    ),
    'six' => array(
      'six_6x16' => array(
        'title' => 'six 6x16',
        'setting' => 'six_6x16',
        'options' => array(
          'six-6x16' => 'default',
          'six-6x16-3x2-grid' => '3x2 grid',
          'six-6x16-2x3-grid' => '2x3 grid',
          'six-6x16-stack' => 'stack',
        )
      ),
    ),
    'inset' => array(
      'inset_left' => array(
        'title' => 'inset left',
        'setting' => 'inset_left',
        'options' => array(
          'three-inset-left' => 'default',
          'three-inset-left-wrap' => 'wrap',
          'three-inset-left-stack' => 'stack',
        )
      ),
      'inset_right' => array(
        'title' => 'inset right',
        'setting' => 'inset_right',
        'options' => array(
          'three-inset-right' => 'default',
          'three-inset-right-wrap' => 'wrap',
          'three-inset-right-stack' => 'stack',
        )
      ),
    ), 
  );
  return $panels;
}

// The fonts array is used by the fonts sub-system to 
// generate forms, build stylesheets and load
// font styles. Note this has nothing what-so-ever to
// do with Drupal 6 hook_elements, the name is merely
// a coincendence because it suited my purpose.
function font_elements() {
  $elements = array(
    'base' => array(
      'type' => 'default',
      'subtype' => '',
      'element' => 'bf',
      'selector' => 'html',
      'setting' => 'base_font',
    ),
    'site_name' => array(
      'type' => 'title',
      'subtype' => 'branding',
      'element' => 'sn',
      'selector' => '#site-name',
      'setting' => 'site_name_font',
    ),
    'site_slogan' => array(
      'type' => 'title',
      'subtype' => 'branding',
      'element' => 'ss',
      'selector' => '#site-slogan',
      'setting' => 'site_slogan_font',
    ),
    'page_title' => array(
      'type' => 'title',
      'subtype' => '',
      'element' => 'pt',
      'selector' => '#page-title',
      'setting' => 'page_title_font',
    ),
    'node_title' => array(
      'type' => 'title',
      'subtype' => 'entity_title',
      'element' => 'nt',
      'selector' => '.node-title',
      'setting' => 'node_title_font',
    ),
    'comment_title' => array(
      'type' => 'title',
      'subtype' => 'entity_title',
      'element' => 'ct',
      'selector' => '.comment-title',
      'setting' => 'comment_title_font',
    ),
    'block_title' => array(
      'type' => 'title',
      'subtype' => '',
      'element' => 'bt',
      'selector' => '.block-title',
      'setting' => 'block_title_font',
    ),
    'content_headings_h1h4' => array(
      'type' => 'heading',
      'subtype' => 'heading_group',
      'element' => 'h1h4',
      'selector' => 'h1,h2,h3,h4',
      'setting' => 'content_headings_h1h4_font',
    ),
    'content_headings_h5h6' => array(
      'type' => 'heading',
      'subtype' => 'heading_group',
      'element' => 'h5h6',
      'selector' => 'h5,h6',
      'setting' => 'content_headings_h5h6_font',
    ),
    'content_headings_h1' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h1',
      'selector' => 'h1',
      'setting' => 'h1_font',
    ),
    'content_headings_h2' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h2',
      'selector' => 'h2',
      'setting' => 'h2_font',
    ),
    'content_headings_h3' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h3',
      'selector' => 'h3',
      'setting' => 'h3_font',
    ),
    'content_headings_h4' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h4',
      'selector' => 'h4',
      'setting' => 'h4_font',
    ),
    'content_headings_h5' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h5',
      'selector' => 'h5',
      'setting' => 'h5_font',
    ),
    'content_headings_h6' => array(
      'type' => 'heading',
      'subtype' => 'heading_level',
      'element' => 'h6',
      'selector' => 'h6',
      'setting' => 'h6_font',
    ),
    'main_menu' => array(
      'type' => 'menu',
      'subtype' => 'menu_variable',
      'element' => 'mm',
      'selector' => '#menu-bar ul,#primary-menu ul',
      'setting' => 'main_menu_font',
    ),
    'secondary_menu' => array(
      'type' => 'menu',
      'subtype' => 'menu_variable',
      'element' => 'sm',
      'selector' => '#secondary-menu ul',
      'setting' => 'secondary_menu_font',
    ),
    'block_menu' => array(
      'type' => 'menu',
      'subtype' => 'menu_block',
      'element' => 'bm',
      'selector' => 'nav.block ul',
      'setting' => 'block_menu_font',
    ),
    'selectors' => array(
      'type' => 'custom',
      'subtype' => '',
      'element' => 'css',
      'selector' => 'custom_css',
      'setting' => 'selectors_font',
    ),
  );

  return $elements;
}