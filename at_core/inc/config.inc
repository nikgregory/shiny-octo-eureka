<?php
// Important configuration for sub-systems, essentially
// these are config as data structures - they are not consistant
// because we don't need a generic system, what we need is a bespoke
// and highly optimised system that only supports what is needed.


function page_layout() {
  $layout_variables = array(
    'layout',
    'media_query',
    'page_width',
    'page_unit',
    'sidebar_first',
    'sidebar_second',
    'sidebar_unit',
  );
  return $layout_variables;
}


// The panels array is used by the responsive panels sub-system

// type - can be..
// N[modifier], w = width, fl = float left, fr = float right, fn = float none, fdw = float none, display block, width 100%
// 100w
// 75w
// 66w
// 60w
// 50w
// 40w
// 33w
// 25w
// 75fl
// 75fr
// 50fl
// 33fl
// 25fr
// fn
// fdw
// fdwc - with clear:both
// selector => properties:values; can hold any number of property value pairs
function responsive_panels() {
  $panels = array(

    // Two
    'two' => array(
      'two_50' => array(
        'title' => 'two col 50/50',
        'setting' => 'two_50',
        'options' => array(
          'two-50' => 'default',
          'two-50-stack' => 'stack',
        ),
        'styles' => array(
          'two-50' => array(
            'css' => array(
              '50' => array('.two-50 .region' => 'width:50%'),
            ),
          ),
          'two-50-stack' => array(
            'css' => array(
              'fdw' => array('.two-50 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'two_33_66' => array(
        'title' => 'two col 33/66',
        'setting' => 'two_33_66',
        'options' => array(
          'two-33-66' => 'default',
          'two-33-66-stack' => 'stack',
        ),
        'styles' => array(
          'two-33-66' => array(
            'css' => array(
              '33' => array('.two-33-66 .region-two-33-66-first' => 'width:33.333333%'),
              '66' => array('.two-33-66 .region-two-33-66-second' => 'width:66.666666%'),
            ),
          ),
          'two-33-66-stack' => array(
            'css' => array(
              'fdw' => array('.two-33-66 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'two_66_33' => array(
        'title' => t('two col 66/33'),
        'setting' => 'two_66_33',
        'options' => array(
          'two-66-33' => 'default',
          'two-66-33-stack' => 'stack',
        ),
        'styles' => array(
          'two-66-33' => array(
            'css' => array(
              '66' => array('.two-66-33 .region-two-66-33-first' => 'width:66.666666%'),
              '33' => array('.two-66-33 .region-two-66-33-second' => 'width:33.333333%'),
            ),
          ),
          'two-66-33-stack' => array(
            'css' => array(
              'fdw' => array('.two-66-33 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'two_brick' => array(
        'title' => t('two col Brick'),
        'setting' => 'two_brick',
        'options' => array(
          'two-brick' => 'default',
          'two-brick-stack' => 'stack',
        ),

        'styles' => array(
          'two-brick' => array(
            'css' => array(
              'fn' => array('.two-brick .panel-row' => 'float:none'),
              '50' => array('.two-brick .panel-row .region' => 'width:50%'),
            ),
          ),
          'two-brick-stack' => array(
            'css' => array(
              'fdw' => array('.two-brick .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
    ),
    // Three
    'three' => array(
      'three_3x33' => array(
        'title' => 'three col 3x33',
        'setting' => 'three_3x33',
        'options' => array(
          'three-3x33' => 'default',
          'three-3x33-stack-top' => 'stack bottom',
          'three-3x33-stack-bottom' => 'stack top',
          'three-3x33-stack' => 'stack',
        ),
        'styles' => array(
          'three-3x33' => array(
            'css' => array(
              '33' => array('.three-3x33 .region' => 'width:33.333333%'),
            ),
          ),
           'three-3x33-stack-top' => array(
            'css' => array(
              '50' => array('.three-3x33 .region' => 'width:50%'),
              'fdw' => array('.three-3x33 .region-three-33-first' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-3x33-stack-bottom' => array(
            'css' => array(
              '50' => array('.three-3x33 .region' => 'width:50%'),
              'fdw' => array('.three-3x33 .region-three-33-third' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-3x33-stack' => array(
            'css' => array(
              'fdw' => array('.three-3x33 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'three_25_50_25' => array(
        'title' => 'three col 25-50-25',
        'setting' => 'three_25_50_25',
        'options' => array(
          'three-25-50-25' => 'default',
          'three-25-50-25-stack-top' => 'stack bottom',
          'three-25-50-25-stack-bottom' => 'stack top',
          'three-25-50-25-stack' => 'stack',
        ),
        'styles' => array(
          'three-25-50-25' => array(
            'css' => array(
              '25' => array('.three-25-50-25 .region' => 'width:25%'),
              '50' => array('.three-25-50-25 div.region-three-25-50-25-second' => 'width:50%'),
            ),
          ),
          'three-25-50-25-stack-top' => array(
            'css' => array(
              '50' => array('.three-25-50-25 .region' => 'width:50%'),
              'fdw' => array('.three-25-50-25 .region-three-25-50-25-first' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-25-50-25-stack-bottom' => array(
            'css' => array(
              '50' => array('.three-25-50-25 .region' => 'width:50%'),
              'fdw' => array('.three-25-50-25 .region-three-25-50-25-third' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-25-50-25-stack' => array(
            'css' => array(
              'fdw' => array('.three-25-50-25 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'three_25_25_50' => array(
        'title' => t('three col 25-25-50'),
        'setting' => 'three_25_25_50',
        'options' => array(
          'three-25-25-50' => 'default',
          'three-25-25-50-stack-top' => 'stack bottom',
          'three-25-25-50-stack-bottom' => 'stack top',
          'three-25-25-50-stack' => 'stack',
        ),
        'styles' => array(
          'three-25-25-50' => array(
            'css' => array(
              '25' => array('.three-25-25-50 .region' => 'width:25%'),
              '50' => array('.three-25-25-50 div.region-three-25-25-50-third' => 'width:50%'),
            ),
          ),
          'three-25-25-50-stack-top' => array(
            'css' => array(
              '50' => array('.three-25-25-50 .region' => 'width:50%'),
              'fdw' => array('.three-25-25-50 .region-three-25-25-50-first' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-25-25-50-stack-bottom' => array(
            'css' => array(
              '50' => array('.three-25-25-50 .region' => 'width:50%'),
              'fdw' => array('.three-25-25-50 .region-three-25-25-50-third' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-25-25-50-stack' => array(
            'css' => array(
              'fdw' => array('.three-25-25-50 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'three_50_25_25' => array(
        'title' => 'three col 50-25-25',
        'setting' => 'three_50_25_25',
        'options' => array(
          'three-50-25-25' => 'default',
          'three-50-25-25-stack-top' => 'stack bottom',
          'three-50-25-25-stack-bottom' => 'stack top',
          'three-50-25-25-stack' => 'stack',
        ),
        'styles' => array(
          'three-50-25-25' => array(
            'css' => array(
              '25' => array('.three-50-25-25 .region' => 'width:25%'),
              '50' => array('.three-50-25-25 div.region-three-50-25-25-first' => 'width:50%'),
            ),
          ),
          'three-50-25-25-stack-top' => array(
            'css' => array(
              '50' => array('.three-50-25-25 .region-three-50-25-25-second,.three-50-25-25 .region-three-50-25-25-third' => 'width:50%'),
              'fdw' => array('.three-50-25-25 .region-three-50-25-25-first' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-50-25-25-stack-bottom' => array(
            'css' => array(
              '50' => array('.three-50-25-25 .region' => 'width:50%'),
              'fdw' => array('.three-50-25-25 .region-three-50-25-25-third' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-50-25-25-stack' => array(
            'css' => array(
              'fdw' => array('.three-50-25-25 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
    ),
    // Four
    'four' => array(
      'four_4x25' => array(
        'title' => 'four 4x25',
        'setting' => 'four_4x25',
        'options' => array(
          'four-4x25' => 'default',
          'four-4x25-right-stack' => 'stack right',
          'four-4x25-2x2-grid' => '2x2 grid',
          'four-4x25-stack' => 'stack',
        ),
        'styles' => array(
          'four-4x25' => array(
            'css' => array(
              '50fl' => array('.four-4x25 .region,.four-4x25 .panel-row' => 'width:50%;float:left'),
            ),
          ),
          'four-4x25-right-stack' => array(
            'css' => array(
              '50fl' => array('.four-4x25 .panel-row,.four-4x25 .row-2 .region' => 'width:50%;float:left'),
              'fdw' => array('.four-4x25 .row-1 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'four-4x25-2x2-grid' => array(
            'css' => array(
              '50' => array('.four-4x25 .region' => 'width:50%'),
              'fdw' => array('.four-4x25 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'four-4x25-stack' => array(
            'css' => array(
              'fdw' => array('.four-4x25 .region,.four-4x25 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
    ),
    // Five
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
        ),
        'styles' => array(
          'five-5x20' => array(
            'css' => array(
              '40' => array('.five-5x20 .row-1' => 'width:40%'),
              '60' => array('.five-5x20 .row-2' => 'width:60%'),
              'fl' => array('.five-5x20 .panel-row' => 'float:left'),
              '50' => array('.five-5x20 .row-1 .region' => 'width:50%'),
              '33' => array('.five-5x20 .row-2 .region' => 'width:33.333333%'),
            ),
          ),
          'five-5x20-2x3-grid' => array(
            'css' => array(
              '33' => array('.five-5x20 .region' => 'width:33.333333%'),
              '50' => array('.five-5x20 .row-1 .region-five-first,.five-5x20 .row-1 .region-five-second' => 'width:50%'),
              'fdw' => array('.five-5x20 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'five-5x20-1x2x2-grid' => array(
            'css' => array(
              '50' => array('.five-5x20 .panel-row .region' => 'width:50%'),
              'fdw' => array('.five-5x20 .panel-row,.five-5x20 .panel-row .region-five-fifth' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'five-5x20-stack-2' => array(
            'css' => array(
              '50' => array('.five-5x20 .row-1 .region' => 'width:50%'),
              'fdw' => array('.five-5x20 .panel-row,.five-5x20 .row-2 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'five-5x20-stack' => array(
            'css' => array(
              'fdw' => array('.five-5x20 .panel-row .region,.five-5x20 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
    ),
    // Six
    'six' => array(
      'six_6x16' => array(
        'title' => 'six 6x16',
        'setting' => 'six_6x16',
        'options' => array(
          'six-6x16' => 'default',
          'six-6x16-3x2-grid' => '3x2 grid',
          'six-6x16-2x3-grid' => '2x3 grid',
          'six-6x16-stack' => 'stack',
        ),
        'styles' => array(
          'six-6x16' => array(
            'css' => array(
              '33fl' => array('.six-6x16 .panel-row' => 'width:33.333333%;float:left'),
              '50' => array('.six-6x16 .region' => 'width:50%'),
            ),
          ),
          'six-6x16-3x2-grid' => array(
            'css' => array(
              '33fl' => array('.six-6x16 .panel-row' => 'width:33.333333%;float:left'),
              'fdw' => array('.six-6x16 .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'six-6x16-2x3-grid' => array(
            'css' => array(
              'fdw' => array('.six-6x16 .panel-row' => 'float:none;display:block;width:100%;clear:both'),
              '50fl' => array('.six-6x16 .region' => 'width:50%;float:left'),
            ),
          ),
          'six-6x16-stack' => array(
            'css' => array(
              'fdw' => array('.six-6x16 .region,.six-6x16 .panel-row ' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
    ),
    // Inset
    'inset' => array(
      'inset_left' => array(
        'title' => 'inset left',
        'setting' => 'inset_left',
        'options' => array(
          'three-inset-left' => 'default',
          'three-inset-left-wrap' => 'wrap',
          'three-inset-left-stack' => 'stack',
        ),
        'styles' => array(
          'three-inset-left' => array(
            'css' => array(
              '25' => array('.three-inset-left .region-three-inset-left-sidebar' => 'width:25%'),
              '75fl' => array('.three-inset-left .inset-wrapper' => 'width:75%;float:left'),
              '33' => array('.three-inset-left .inset-wrapper div.region-three-inset-left-inset' => 'width:33.333333%'),
              '66' => array('.three-inset-left .inset-wrapper div.region-three-inset-left-middle' => 'width:66.666666%'),
              '100' => array('.three-inset-left .inset-wrapper .region' => 'width:100%'),
            ),
          ),
          'three-inset-left-wrap' => array(
            'css' => array(
              '25' => array('.three-inset-left .region-three-inset-left-sidebar' => 'width:25%'),
              '75fl' => array('.three-inset-left .inset-wrapper' => 'width:75%;float:left'),
              'fdw' => array('.three-inset-left .inset-wrapper .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-inset-left-stack' => array(
            'css' => array(
              'fdw' => array('.three-inset-left .inset-wrapper,.three-inset-left .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
      ),
      'inset_right' => array(
        'title' => 'inset right',
        'setting' => 'inset_right',
        'options' => array(
          'three-inset-right' => 'default',
          'three-inset-right-wrap' => 'wrap',
          'three-inset-right-stack' => 'stack',
        ),
        'styles' => array(
          'three-inset-right' => array(
            'css' => array(
              'fr' => array('.three-inset-right .region-three-inset-right-sidebar,.three-inset-right .inset-wrapper,.three-inset-right .region-three-inset-right-middle' => 'float:right'),
              '25' => array('.three-inset-right .region-three-inset-right-sidebar' => 'width:25%'),
              '75' => array('.three-inset-right .inset-wrapper' => 'width:75%'),
              '33' => array('.three-inset-right .inset-wrapper div.region-three-inset-right-inset' => 'width:33.333333%'),
              '66' => array('.three-inset-right .inset-wrapper div.region-three-inset-right-middle' => 'width:66.666666%'),
              '100' => array('.three-inset-right .inset-wrapper .region' => 'width:100%'),
            ),
          ),
          'three-inset-right-wrap' => array(
            'css' => array(
              '25fr' => array('.three-inset-right .region-three-inset-right-sidebar' => 'width:25%;float:right'),
              '75fr' => array('.three-inset-right .inset-wrapper' => 'width:75%;float:right'),
              'fdw' => array('.three-inset-right .inset-wrapper .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
          'three-inset-right-stack' => array(
            'css' => array(
              'fdw' => array('.three-inset-right .inset-wrapper,.three-inset-right .region' => 'float:none;display:block;width:100%;clear:both'),
            ),
          ),
        ),
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
      'subtype' => 'global',
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
      'subtype' => 'global',
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
      'subtype' => 'entity_title',
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