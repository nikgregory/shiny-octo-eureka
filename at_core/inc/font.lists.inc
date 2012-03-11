<?php
/**
 * IMPORTANT WARNING: DO NOT MODIFY THIS FILE.
 */

// The fonts array is used by the fonts sub-system to 
// generate forms, build stylesheets and load
// font styles. Note this has nothing what-so-ever to
// do with Drupal 6 hook_elements, the name is merely
// a coincendence because it suited my purpose.
function font_elements() {
  $elements = array(
    // default
    'base' => array(
      'type' => 'default',
      'subtype' => '',
      'element' => 'bf',
      'selector' => 'html',
      'setting' => 'base_font',
    ),
    // titles
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
    // in-content headings
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
    // menus
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
    // custom css selector
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

 // Return lists of font names keyed by element
function font_list($type, $element) {
  $font_list = array();

  // websafe font list
  if ($type === 'wsf') {
    $font_list = websafe_fonts_list($element);
  }
  // Google font list
  if ($type === 'gwf') {
    $font_list = google_fonts_list($element);
  }
  // @font-your-face font list
  if ($type === 'fyf') {
    $font_list = font_your_face_fonts_list($element);
  }

  return array_unique($font_list);
}

// Websafe fonts list. These stacks are my own and I spent years working with these, testing and checking
// platform and browser support. The work very well accross platform.
function websafe_fonts_list($element) {
  $fonts = array(
    "$element-sss" => t("'Trebuchet MS', 'Helvetica Neue', Arial, Helvetica, sans-serif"),
    "$element-ssl" => t("Verdana, Geneva, 'DejaVu Sans', Arial, Helvetica, sans-serif"),
    "$element-a"   => t("Arial, Helvetica, sans-serif"),
    "$element-cc"  => t("Calibri, Candara, Arial, Helvetica, sans-serif"),
    "$element-m"   => t("'Segoe UI', 'Myriad Pro', Myriad, Arial, Helvetica, sans-serif"),
    "$element-l"   => t("'Lucida Sans Unicode', 'Lucida Sans', 'Lucida Grande', Verdana, Geneva, sans-serif"),
    "$element-i"   => t("Impact, Haettenschweiler, 'Franklin Gothic Bold', Charcoal, 'Helvetica Inserat', 'Bitstream Vera Sans Bold', 'Arial Black', sans-serif"),
    "$element-ss"  => t("Garamond, Perpetua, 'Times New Roman', serif"),
    "$element-sl"  => t("Georgia, Baskerville, Palatino, 'Palatino Linotype', 'Book Antiqua', 'Times New Roman', serif"),
    "$element-ms"  => t("Consolas, Monaco, 'Courier New', Courier, monospace, sans-serif"),
  );

  return $fonts;
}

/**
 * @font-your-face
 * This requires the @font-your-face module to be enabled
 * and at least one font enabled and set to "add selector in theme CSS".
 * Adaptivetheme will pull the enabled fonts and allow them to be used
 * for styling elements in the site via theme settings.
 */
function font_your_face_fonts_list($element) {
  $fonts = array();

  if (module_exists('fontyourface')) {
    $enabled_fonts = fontyourface_get_fonts('enabled = 1');
    foreach ($enabled_fonts as $font) {
      // <none> is "-- add selector in theme CSS --" in fontyourface settings
      if ($font->css_selector === '<none>') {
        $key = drupal_html_class("$element-$font->name");
        $value = $font->name;
        $fonts[$key] = $value;
      }
    }
  }

  return $fonts;
}

// Build an array of Google web fonts
function google_fonts_list($element) {
  $google_fonts = google_font_names();
  $fonts = array();
  foreach ($google_fonts as $font) {
    $key = drupal_html_class("$element-$font");
    $value = $font;
    $fonts[$key] = $value;
  }
  return $fonts;
}

/**
 * Basic Google Fonts
 * Google add new fonts on a regular basis and we will provide patches and updates
 * for this file periodically. For a more up to date approach use the @font-your-face 
 * module, especially if you need Greek, Cryillic or other variants.
 * Last update: 27/02/2012
 */
function google_font_names() {
  $array = array(
    'Abel',
    'Abril Fatface',
    'Aclonica',
    'Acme',
    'Actor',
    'Adamina',
    'Aguafina Script',
    'Aladin',
    'Aldrich',
    'Alegreya',
    'Alegreya SC',
    'Alex Brush',
    'Alfa Slab One',
    'Alice',
    'Alike',
    'Alike Angular',
    'Allan',
    'Allerta',
    'Allerta Stencil',
    'Allura',
    'Almendra',
    'Almendra SC',
    'Amaranth',
    'Amatic SC',
    'Amethysta',
    'Andada',
    'Andika',
    'Angkor',
    'Annie Use Your Telescope',
    'Anonymous Pro',
    'Antic',
    'Anton',
    'Arapey',
    'Arbutus',
    'Architects Daughter',
    'Arimo',
    'Arizonia',
    'Armata',
    'Artifika',
    'Arvo',
    'Asap',
    'Asset',
    'Astloch',
    'Asul',
    'Atomic Age',
    'Aubrey',
    'Bad Script',
    'Balthazar',
    'Bangers',
    'Basic',
    'Battambang',
    'Baumans',
    'Bayon',
    'Belgrano',
    'Bentham',
    'Bevan',
    'Bigshot One',
    'Bilbo',
    'Bilbo Swash Caps',
    'Bitter',
    'Black Ops One',
    'Bokor',
    'Bonbon',
    'Boogaloo',
    'Bowlby One',
    'Bowlby One SC',
    'Brawler',
    'Bree Serif',
    'Bubblegum Sans',
    'Buda',
    'Buenard',
    'Butcherman',
    'Butterfly Kids',
    'Cabin',
    'Cabin Condensed',
    'Cabin Sketch',
    'Caesar Dressing',
    'Cagliostro',
    'Calligraffitti',
    'Cambo',
    'Candal',
    'Cantarell',
    'Cardo',
    'Carme',
    'Carter One',
    'Caudex',
    'Cedarville Cursive',
    'Ceviche One',
    'Changa One',
    'Chango',
    'Chelsea Market',
    'Chenla',
    'Cherry Cream Soda',
    'Chewy',
    'Chicle',
    'Chivo',
    'Coda',
    'Coda Caption',
    'Comfortaa',
    'Coming Soon',
    'Concert One',
    'Condiment',
    'Content',
    'Contrail One',
    'Convergence',
    'Cookie',
    'Copse',
    'Corben',
    'Cousine',
    'Coustard',
    'Covered By Your Grace',
    'Crafty Girls',
    'Creepster',
    'Crete Round',
    'Crimson Text',
    'Crushed',
    'Cuprum',
    'Damion',
    'Dancing Script',
    'Dangrek',
    'Dawning of a New Day',
    'Days One',
    'Delius',
    'Delius Swash Caps',
    'Delius Unicase',
    'Devonshire',
    'Didact Gothic',
    'Diplomata',
    'Diplomata SC',
    'Doppio One',
    'Dorsa',
    'Dr Sugiyama',
    'Droid Sans',
    'Droid Sans Mono',
    'Droid Serif',
    'Duru Sans',
    'Dynalight',
    'EB Garamond',
    'Eater',
    'Electrolize',
    'Emblema One',
    'Engagement',
    'Enriqueta',
    'Erica One',
    'Esteban',
    'Euphoria Script',
    'Ewert',
    'Exo',
    'Expletus Sans',
    'Fanwood Text',
    'Fascinate',
    'Fascinate Inline',
    'Federant',
    'Federo',
    'Felipa',
    'Fjord One',
    'Flamenco',
    'Flavors',
    'Fondamento',
    'Fontdiner Swanky',
    'Forum',
    'Francois One',
    'Fredericka the Great',
    'Freehand',
    'Fresca',
    'Frijole',
    'Fugaz One',
    'GFS Didot',
    'GFS Neohellenic',
    'Galdeano',
    'Gentium Basic',
    'Gentium Book Basic',
    'Geo',
    'Geostar',
    'Geostar Fill',
    'Germania One',
    'Give You Glory',
    'Glass Antiqua',
    'Glegoo',
    'Gloria Hallelujah',
    'Goblin One',
    'Gochi Hand',
    'Goudy Bookletter 1911',
    'Gravitas One',
    'Gruppo',
    'Gudea',
    'Habibi',
    'Hammersmith One',
    'Handlee',
    'Hanuman',
    'Henny Penny',
    'Herr Von Muellerhoff',
    'Holtwood One SC',
    'Homemade Apple',
    'Homenaje',
    'IM Fell DW Pica',
    'IM Fell DW Pica SC',
    'IM Fell Double Pica',
    'IM Fell Double Pica SC',
    'IM Fell English',
    'IM Fell English SC',
    'IM Fell French Canon',
    'IM Fell French Canon SC',
    'IM Fell Great Primer',
    'IM Fell Great Primer SC',
    'Iceberg',
    'Iceland',
    'Inconsolata',
    'Inder',
    'Indie Flower',
    'Inika',
    'Irish Grover',
    'Istok Web',
    'Italianno',
    'Jim Nightshade',
    'Jockey One',
    'Josefin Sans',
    'Josefin Slab',
    'Judson',
    'Julee',
    'Junge',
    'Jura',
    'Just Another Hand',
    'Just Me Again Down Here',
    'Kameron',
    'Kaushan Script',
    'Kelly Slab',
    'Kenia',
    'Khmer',
    'Knewave',
    'Kotta One',
    'Koulen',
    'Kranky',
    'Kreon',
    'Kristi',
    'Krona One',
    'La Belle Aurore',
    'Lancelot',
    'Lato',
    'League Script',
    'Leckerli One',
    'Ledger',
    'Lekton',
    'Lemon',
    'Lilita One',
    'Limelight',
    'Linden Hill',
    'Lobster',
    'Lobster Two',
    'Lora',
    'Love Ya Like A Sister',
    'Loved by the King',
    'Luckiest Guy',
    'Lusitana',
    'Lustria',
    'Macondo',
    'Macondo Swash Caps',
    'Magra',
    'Maiden Orange',
    'Mako',
    'Marck Script',
    'Marko One',
    'Marmelad',
    'Marvel',
    'Mate',
    'Mate SC',
    'Maven Pro',
    'Meddon',
    'MedievalSharp',
    'Medula One',
    'Megrim',
    'Merienda One',
    'Merriweather',
    'Metal',
    'Metamorphous',
    'Metrophobic',
    'Michroma',
    'Miltonian',
    'Miltonian Tattoo',
    'Miniver',
    'Miss Fajardose',
    'Modern Antiqua',
    'Molengo',
    'Monofett',
    'Monoton',
    'Monsieur La Doulaise',
    'Montaga',
    'Montez',
    'Montserrat',
    'Moul',
    'Moulpali',
    'Mountains of Christmas',
    'Mr Bedfort',
    'Mr Dafoe',
    'Mr De Haviland',
    'Mrs Saint Delafield',
    'Mrs Sheppards',
    'Muli',
    'Neucha',
    'Neuton',
    'News Cycle',
    'Niconne',
    'Nixie One',
    'Nobile',
    'Nokora',
    'Norican',
    'Nosifer',
    'Nothing You Could Do',
    'Noticia Text',
    'Nova Cut',
    'Nova Flat',
    'Nova Mono',
    'Nova Oval',
    'Nova Round',
    'Nova Script',
    'Nova Slim',
    'Nova Square',
    'Numans',
    'Nunito',
    'Odor Mean Chey',
    'Old Standard TT',
    'Oldenburg',
    'Open Sans',
    'Open Sans Condensed',
    'Orbitron',
    'Original Surfer',
    'Oswald',
    'Over the Rainbow',
    'Overlock',
    'Overlock SC',
    'Ovo',
    'PT Sans',
    'PT Sans Caption',
    'PT Sans Narrow',
    'PT Serif',
    'PT Serif Caption',
    'Pacifico',
    'Parisienne',
    'Passero One',
    'Passion One',
    'Patrick Hand',
    'Patua One',
    'Paytone One',
    'Permanent Marker',
    'Petrona',
    'Philosopher',
    'Piedra',
    'Pinyon Script',
    'Plaster',
    'Play',
    'Playball',
    'Playfair Display',
    'Podkova',
    'Poller One',
    'Poly',
    'Pompiere',
    'Port Lligat Sans',
    'Port Lligat Slab',
    'Prata',
    'Preahvihear',
    'Princess Sofia',
    'Prociono',
    'Puritan',
    'Quantico',
    'Quattrocento',
    'Quattrocento Sans',
    'Questrial',
    'Quicksand',
    'Qwigley',
    'Radley',
    'Raleway',
    'Rammetto One',
    'Rancho',
    'Rationale',
    'Redressed',
    'Reenie Beanie',
    'Ribeye',
    'Ribeye Marrow',
    'Righteous',
    'Rochester',
    'Rock Salt',
    'Rokkitt',
    'Ropa Sans',
    'Rosario',
    'Rouge Script',
    'Ruda',
    'Ruge Boogie',
    'Ruluko',
    'Ruslan Display',
    'Ruthie',
    'Sail',
    'Salsa',
    'Sancreek',
    'Sansita One',
    'Sarina',
    'Satisfy',
    'Schoolbell',
    'Sevillana',
    'Shadows Into Light',
    'Shadows Into Light Two',
    'Shanti',
    'Share',
    'Shojumaru',
    'Short Stack',
    'Siemreap',
    'Sigmar One',
    'Signika',
    'Signika Negative',
    'Sirin Stencil',
    'Six Caps',
    'Slackey',
    'Smokum',
    'Smythe',
    'Sniglet',
    'Snippet',
    'Sofia',
    'Sonsie One',
    'Sorts Mill Goudy',
    'Special Elite',
    'Spicy Rice',
    'Spinnaker',
    'Spirax',
    'Squada One',
    'Stardos Stencil',
    'Stint Ultra Condensed',
    'Stint Ultra Expanded',
    'Stoke',
    'Sue Ellen Francisco',
    'Sunshiney',
    'Supermercado One',
    'Suwannaphum',
    'Swanky and Moo Moo',
    'Syncopate',
    'Tangerine',
    'Taprom',
    'Telex',
    'Tenor Sans',
    'Terminal Dosis',
    'The Girl Next Door',
    'Tienne',
    'Tinos',
    'Titan One',
    'Trade Winds',
    'Trochut',
    'Trykker',
    'Tulpen One',
    'Ubuntu',
    'Ubuntu Condensed',
    'Ubuntu Mono',
    'Ultra',
    'Uncial Antiqua',
    'UnifrakturCook',
    'UnifrakturMaguntia',
    'Unkempt',
    'Unlock',
    'Unna',
    'VT323',
    'Varela',
    'Varela Round',
    'Vast Shadow',
    'Vibur',
    'Vidaloka',
    'Viga',
    'Voces',
    'Volkhov',
    'Vollkorn',
    'Voltaire',
    'Waiting for the Sunrise',
    'Wallpoet',
    'Walter Turncoat',
    'Wellfleet',
    'Wire One',
    'Yanone Kaffeesatz',
    'Yellowtail',
    'Yeseva One',
    'Yesteryear',
    'Zeyada',
  );
  return $array;
}

// Font size lists, hard coded for total control, used in forms.
// While these values don't give super granular control over font
// scales they are "good enough" for most users and provide a
// useable tool that is easy to understand.
function font_sizes($type) {
  switch ($type) {
    case ('em'):
      $font_sizes = array(
        '<none>' => 'Not set',
        '0.1em' => '0.1em',
        '0.2em' => '0.2em',
        '0.3em' => '0.3em',
        '0.4em' => '0.4em',
        '0.5em' => '0.5em',
        '0.6em' => '0.6em',
        '0.7em' => '0.7em',
        '0.8em' => '0.8em',
        '0.9em' => '0.9em',
        '1em'   => '1em',
        '1.1em' => '1.1em',
        '1.2em' => '1.2em',
        '1.3em' => '1.3em',
        '1.4em' => '1.4em',
        '1.5em' => '1.5em',
        '1.6em' => '1.6em',
        '1.7em' => '1.7em',
        '1.8em' => '1.8em',
        '1.9em' => '1.9em',
        '2em'   => '2em',
        '2.1em' => '2.1em',
        '2.2em' => '2.2em',
        '2.3em' => '2.3em',
        '2.4em' => '2.4em',
        '2.5em' => '2.5em',
        '2.6em' => '2.6em',
        '2.7em' => '2.7em',
        '2.8em' => '2.8em',
        '2.9em' => '2.9em',
        '3em'   => '3em',
        '3.1em' => '3.1em',
        '3.2em' => '3.2em',
        '3.3em' => '3.3em',
        '3.4em' => '3.4em',
        '3.5em' => '3.5em',
        '3.6em' => '3.6em',
        '3.7em' => '3.7em',
        '3.8em' => '3.8em',
        '3.9em' => '3.9em',
        '4em'   => '4em',
        '4.1em' => '4.1em',
        '4.2em' => '4.2em',
        '4.3em' => '4.3em',
        '4.4em' => '4.4em',
        '4.5em' => '4.5em',
        '4.6em' => '4.6em',
        '4.7em' => '4.7em',
        '4.8em' => '4.8em',
        '4.9em' => '4.9em',
        '5em'   => '5em',
        '5.1em' => '5.1em',
        '5.2em' => '5.2em',
        '5.3em' => '5.3em',
        '5.4em' => '5.4em',
        '5.5em' => '5.5em',
        '5.6em' => '5.6em',
        '5.7em' => '5.7em',
        '5.8em' => '5.8em',
        '5.9em' => '5.9em',
        '6em'   => '6em',
        '6.1em' => '6.1em',
        '6.2em' => '6.2em',
        '6.3em' => '6.3em',
        '6.4em' => '6.4em',
        '6.5em' => '6.5em',
        '6.6em' => '6.6em',
        '6.7em' => '6.7em',
        '6.8em' => '6.8em',
        '6.9em' => '6.9em',
        '7em'   => '7em',
      );
    break;
    case ('p'):
      $font_sizes = array(
        '<none>' => 'Not set',
        '62.5%'  => '62.5% (10px)',
        '68.8%'  => '68.8% (11px)',
        '75%'    => '75%   (12px)',
        '81.3%'  => '81.3% (13px)',
        '87.5%'  => '87.5% (14px)',
        '93.8%'  => '93.8% (15px)',
        '100%'   => '100%  (16px)',
      );
    break;
  }

  return $font_sizes;
}

// Return a list of line-heights.
// Could add more types later (with value units)
function line_heights($type) {
  switch ($type) {
    case ('unitless'):
      $line_heights = array(
        '<none>' => 'Not set',
        '0'   => '0',
        '0.1' => '0.1',
        '0.2' => '0.2',
        '0.3' => '0.3',
        '0.4' => '0.4',
        '0.5' => '0.5',
        '0.6' => '0.6',
        '0.7' => '0.7',
        '0.8' => '0.8',
        '0.9' => '0.9',
        '1'   => '1',
        '1.1' => '1.1',
        '1.2' => '1.2',
        '1.3' => '1.3',
        '1.4' => '1.4',
        '1.5' => '1.5',
        '1.6' => '1.6',
        '1.7' => '1.7',
        '1.8' => '1.8',
        '1.9' => '1.9',
        '2'   => '2',
        '2.1' => '2.1',
        '2.2' => '2.2',
        '2.3' => '2.3',
        '2.4' => '2.4',
        '2.5' => '2.5',
        '2.6' => '2.6',
        '2.7' => '2.7',
        '2.8' => '2.8',
        '2.9' => '2.9',
        '3'   => '3',
        '3.1' => '3.1',
        '3.2' => '3.2',
        '3.3' => '3.3',
        '3.4' => '3.4',
        '3.5' => '3.5',
        '3.6' => '3.6',
        '3.7' => '3.7',
        '3.8' => '3.8',
        '3.9' => '3.9',
        '4'   => '4',
        '4.1' => '4.1',
        '4.2' => '4.2',
        '4.3' => '4.3',
        '4.4' => '4.4',
        '4.5' => '4.5',
        '4.6' => '4.6',
        '4.7' => '4.7',
        '4.8' => '4.8',
        '4.9' => '4.9',
        '5'   => '5',
      );
    break;
  }

  return $line_heights;
}

// Make some reusable lists of style options
function font_style_options($style, $element) {

  switch ($style) {
    case ('case'):
      $options = array(
        $element . 'c-n'  => t('Normal'),
        $element . 'c-uc' => t('Upper case'),
        $element . 'c-lc' => t('Lower case'),
        $element . 'c-c'  => t('Capitalize'),
        $element . 'c-sc' => t('Small caps'),
      );
    break;
    case ('weight'):
      $options = array(
        $element . 'w-n' => t('Normal'),
        $element . 'w-b' => t('Bold'),
      );
    break;
    case ('alignment'):
      $options = array(
        $element . 'a-l' => t('Left'),
        $element . 'a-r' => t('Right'),
        $element . 'a-c' => t('Centered'),
      );
    break;
    case ('shadow'):
      $options = array(
        $element . 's-n' => t('None'),
        $element . 's-l' => t('Light'),
        $element . 's-d' => t('Dark'),
        $element . 's-w' => t('White'),
      );
    break;
  }

  return $options;
}

// Backwards compatibility cruft for pre 7.x-3.x subthemes that have google fonts
// We dont want them to use this but if saves the site blowing up if a user tries...
// Return a list of options
function google_web_fonts_list_options() {
  $google_web_font_families = array();
  $fonts = google_font_names();
  foreach ($fonts as $font) {
    $google_web_font_families[] = $font;
  }
  return $google_web_font_families;
}

// Return the style name
function google_web_fonts_get_style_name($key, $font_type, $font_value) {
  $font_value = trim($font_value);
  $font_value = strtolower($font_value);
  $font_value = str_replace(' ', '-', $font_value);
  return $key . (!empty($font_type) ? '-' . $font_type :  "") . "-" . $font_value;
}

// Process font families, this is not specific to Google fonts but makes sense including it here
function get_font_families($fonts, $theme_name) {
  $google_font_families = array();
  $font_type = '';
  $font_families = array();
  foreach ($fonts as $key => $value) {
    $font_type = theme_get_setting($value . '_type', $theme_name);
    $font_value = theme_get_setting($value . (!empty($font_type) ? '_' . $font_type : ''), $theme_name);
    if ($font_type == '') {
      $font_families[] = $font_value;
    }
    else {
      if ($font_type == 'gwf') {
        $gff = str_replace(' ', '+', $font_value);
        $google_font_families[] = $gff;
        $font_value = preg_replace('/[^\w\d_ -]/si', '', $font_value);
        $style_name = google_web_fonts_get_style_name($key, $font_type, $font_value);
        $font_families[] = $style_name;
        switch ($key) {
          case 'bf':
            drupal_add_css("body.$style_name, .$style_name .form-text {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'snf':
            drupal_add_css("body.$style_name #site-name {font-family : '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'ssf':
            drupal_add_css("body.$style_name #site-slogan {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'mmf':
            drupal_add_css(".$style_name #nav-wrapper {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'ptf':
            drupal_add_css("body.$style_name #page-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'ntf':
            drupal_add_css("body.$style_name .article-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'ctf':
            drupal_add_css("body.$style_name .comment-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
          case 'btf':
            drupal_add_css("body.$style_name .block-title {font-family: '" . $font_value . "'}", array('group' => CSS_DEFAULT, 'type' => 'inline'));
            break;
        }
      }
    }
  }
  if (!empty($google_font_families)) {
    $array = array_unique($google_font_families);
    $google_fonts = trim(implode('|', $array));
    drupal_add_css('//fonts.googleapis.com/css?family=' . $google_fonts, array(
      'group' => CSS_THEME,
      'type' => 'external',
      'weight' => -1,
      'preprocess' => FALSE,
      )
    );
  }
  return $font_families;
}