<?php

/**
 * @file
 * Output formatted CSS for fonts.
 */



    //  // Google font.
    //  if (!empty($config['font_google'])) {
    //    $google_font_path = check_url($config['font_google']);
    //    $google_font_path_clean = '//' . str_replace('&amp;', '&', $google_font_path);
    //    $page['#attached']['css'][] = array(
    //      'data' => $google_font_path_fixed,
    //      'type' => 'external',
    //    );
    //  }


    ///   @import url(http://fonts.googleapis.com/css?family=Slabo+27px);

/*
<script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Pacifico::latin', 'Open+Sans:400,700:latin,latin-ext' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();
</script>
*/


// Typekit
// http://help.typekit.com/customer/portal/articles/6852
// Typically you probably don't need to worry about this because on most
// AT Themes there are default fallback fonts already set globally and AT
// will use the .wf-active selector in all styles set in Appearance settings
// that use Typekit fonts.
// styles to use before Typekit is loaded
//.wf-loading {
//}

// styles to use after Typekit is loaded
//.wf-active {
//}



function at_core_submit_fonts($values, $theme, $generated_files_path) {

  // Websafe fonts.
  $websafe_fonts = websafe_fonts();

  // Elements to apply fonts to.
  $font_elements = font_elements();

  // Initialize some variables.
  $fonts = array();
  $size = '';
  $base_size = '16'; // 16px default
  $px_size = '';
  $rem_size = '';

/*
  $line_height_multiplier = $values['font_lineheight_multiplier_default'];
  $values['font_lineheight_multiplier_large'];
  $values['font_lineheight_multiplier_large_size'];
*/

  foreach ($font_elements as $font_key => $font_values) {

    $font_styles = array();


    if (isset($values['settings_font_google'])) {
      //$google_font_path = check_url($config['font_google']);
      //$google_font_path_clean = '//' . str_replace('&amp;', '&', $google_font_path);
      $font_styles[] = $values['settings_font_google'] . "\n";

    }

    // Get the selectors for each element.
    $fonts[$font_key]['selectors'] = $font_values['selector'];

    // Custom selectors, reset the selectors variable if we have custom selectors.
    if ($font_key == 'custom_selectors' && !empty($values['settings_font_custom_selectors']) && !empty($values['settings_custom_selectors'])) {
      $fonts[$font_key]['selectors'] = $values['settings_custom_selectors']; // ? $values['settings_custom_selectors'] : 'ruby ruby'
    }

    // Size/Line height
    if (!empty($values['settings_font_size_' . $font_key])) {
      $px_size = $values['settings_font_size_' . $font_key];
      $rem_size = $values['settings_font_size_' . $font_key] / $base_size;

      // line-height multipliers are a bit magical, but "pretty good" defaults.
      $line_height_multiplier = $values['settings_font_lineheight_multiplier_default'];
      if ($px_size >= $values['settings_font_lineheight_multiplier_large_size']) {
        $line_height_multiplier = $values['settings_font_lineheight_multiplier_large'];
      }

      $fonts[$font_key]['size'] = 'font-size:' . ceil($px_size) . 'px; font-size:' . round($rem_size, 3) . 'rem;';
      $fonts[$font_key]['lineheight'] = 'line-height:' . ceil($px_size * $line_height_multiplier) . 'px; line-height:' . round($rem_size * $line_height_multiplier, 3) . 'rem;';
    }

    // Websafe
    if ($values['settings_font_' . $font_key] == 'websafe') {
      $fonts[$font_key]['family'] = 'font-family:' . $websafe_fonts[$values['settings_font_websafe']] . ';';
    }

    // Customstack
    if ($values['settings_font_' . $font_key] == 'customstack') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_customstack'] . ';';
    }

    // Google
    if ($values['settings_font_' . $font_key] == 'google') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_google_' . $font_key] . ';';
    }

    // Typekit
    if ($values['settings_font_' . $font_key] == 'typekit') {
      $fonts[$font_key]['family'] = 'font-family:' . $values['settings_font_typekit_' . $font_key] . ';';
    }
  }

  // Output data to file
  if (!empty($fonts)) {
    foreach ($fonts as $key => $values) {
      if (isset($values['family']) || isset($values['size'])) {
        $font_style = $values['selectors'] . '{';

        if (isset($values['family'])) {
          $font_style .= $values['family'];
        }

        if (isset($values['size'])) {
          $font_style .= $values['size'];
        }

        if (isset($values['lineheight'])) {
          $font_style .= $values['lineheight'];
        }

        $font_style .= '}';
        $font_styles[] = $font_style;
      }
    }

    $output = implode("\n", $font_styles);
  }

  $output = $output ? $output : '/** No fonts styles set **/';

  //$file_name = $theme . '.fonts.css';

  $file_name = 'fonts.css';
  $filepath = "$generated_files_path/$file_name";
  file_unmanaged_save_data($output, $filepath, FILE_EXISTS_REPLACE);
}
