<?php
  // Fonts
  // Save the font settings in a CSS file

  // $font_styles_data holds all data for the stylesheet
  $font_styles_data = array();

  // Get the font elements array. Everything is built from the items in this
  // array, simply adding to it will generate new CSS, as long as the form
  // items exist to collect the values.
  $font_elements = font_elements();

  // Check if fontyourface is enabled, doing this in the loop will be expensive
  $fontyourface = FALSE;
  if (module_exists('fontyourface')) {
    static $fontyourface;
    $fontyourface = TRUE;
  }

  // How this works: first loop over the elements array, which is really a
  // list of html elements and selectors that we want to apply font styles
  // to. For each "element" it grabs the values from the form[values]
  // and sets these into variables. Initially it wants to know what
  // type we are looking at such as websafe, google, custom stack or
  // @font-your-face. For websafe, google and font-your-face it needs to
  // find a match - where the setting matches a font in one of those
  // arrays. Custom stack is easier, we just take the value we are given.
  // Once we know all the winners we format the values into CSS
  // using at_build_font_families(). In short this was not built for
  // performance and its pretty slow, but it does the job.
  foreach ($font_elements as $key => $value) {

    // Each item in $font_elements has 3 key value pairs
    $element  = $value['element']  ? $value['element'] : '';  // a key to use later
    $selector = $value['selector'] ? $value['selector'] : ''; // the selector to use when building the CSS
    $setting  = $value['setting']  ? $value['setting'] : '';  // the theme setting used to retrieve the font values

    // We need to retrive this, its in a theme setting not in the font elements array
    if ($selector == 'custom_css' && !empty($values['selectors_css'])) {
      $selector = filter_xss($values['selectors_css']); // sanitize user entered data
    }

    // Get the font type if isset, not all font settings have a type
    if (isset($values[$setting . '_type'])) {
      $font_type = $values[$setting . '_type'];
    }
    else {
      $font_type = '<none>'; // this is an individual "in-content" heading
    }

    // Get the font size if isset, not all font settings have size
    if (isset($values[$setting . '_size'])) {
      $font_size = $values[$setting . '_size'];
    }
    else {
      $font_size = '<none>'; // this is a grouped "in-content" heading or custom selectors
    }

    // Get the font value (the font name or family) for the right font type if isset,
    // not all font settings have a value
    if (isset($values[$setting . (!empty($font_type) ? '_' . $font_type : '')])) {
      $font_value = $values[$setting . (!empty($font_type) ? '_' . $font_type : '')];
    }

    // Initialize the $font_values array variables
    $font_values['font_family'] = '';
    $font_values['font_size'] = '';
    $font_values['font_style'] = '';
    $font_values['font_weight'] = '';

    // Some Content Headings have no type or family, we add these first,
    // these are the h1 to h6 settings that only have a size
    if ($font_type === '<none>') {
      $font_values['font_size'] = $font_size;

      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Websafe Fonts
    if ($font_type === '') {
      // Get a list of websafe fonts
      $websafe_fonts = websafe_fonts_list($element);
      // Loop over the websafe fonts list and get a match
      foreach ($websafe_fonts as $k => $v) {
        if ($k == $font_value) {
          $font_family = $v;
        }
      }
      $font_values['font_family'] = $font_family;
      $font_values['font_size']   = $font_size;

      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Custom Font stacks (user entered data)
    if ($font_type === 'cfs') {
      $font_values['font_family'] = filter_xss($font_value); // check_plain can give weird encoding issues
      $font_values['font_size']   = $font_size;
      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Google Fonts
    if ($font_type === 'gwf') {
      // Get the Google font list
      $google_fonts = google_fonts_list($element);
      // Loop over the Google font list and get a match, this is slow...
      foreach ($google_fonts as $k => $v) {
        if ($k == $font_value) {
          $font_family = $v;
        }
      }
      $font_values['font_family'] = $font_family;
      $font_values['font_size']   = $font_size;
      // Add styles to the array for printing into the stylsheet
      $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
    }

    // Font Your Face
    if ($fontyourface === TRUE) {
      if ($font_type === 'fyf') {
        // pull the font list, we need to iterate over it
        $fyf_fonts = font_your_face_fonts_list($element); // this is a keyed array
        // loop over fyf_fonts list and get a match and retrive the font name
        foreach ($fyf_fonts as $k => $v) {
          if ($k == $font_value) {
            $font_value = $v;
          }
        }
        // Get the font objects from font-your-face, we need additional data out of each object
        $enabled_fonts = fontyourface_get_fonts('enabled = 1');
        foreach ($enabled_fonts as $font) {
          // we need to know if the $font_value matches a $font->name
          if ($font_value == $font->name) {
            // Now we need a buch of variables to get the font family, font style and font weight
            $font_values['font_family'] = $font->css_family ? $font->css_family : 'Arial';
            $font_values['font_style']  = $font->css_style  ? $font->css_style  : 'normal';
            $font_values['font_weight'] = $font->css_weight ? $font->css_weight : '400';
          }
        }
        // Load the font size
        $font_values['font_size'] = $font_size;
        // Add styles to the array for printing into the stylsheet
        $font_styles_data[] = at_build_font_families($element, $selector, $font_values);
      }
    }
  }
  // finally implode the data into a variable, ready for printing to file
  $font_styles = implode("\n", $font_styles_data);

  $file_name = $theme_name . '_font_families.css';
  $filepath = "$path/$file_name";
  file_save_data($font_styles, $filepath, FILE_EXISTS_REPLACE);