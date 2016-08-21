<?php

use Symfony\Component\Yaml\Parser;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;
use Drupal\layout_plugin\Layout;

/**
 * Alter attachments (typically assets) to a page before it is rendered.
 *
 * Use this hook when you want to remove or alter attachments on the page, or
 * add attachments to the page that depend on another module's attachments (this
 * hook runs after hook_page_attachments().
 *
 * @param array &$page
 *   An empty renderable array representing the page.
 *
 * @see hook_page_attachments_alter()
 */
function at_core_page_attachments_alter(array &$page) {
  $active_theme = \Drupal::theme()->getActiveTheme();
  $theme = $active_theme->getName();
  $theme_path = $active_theme->getPath();
  $config = \Drupal::config($theme . '.settings')->get('settings');
  $generated_files_path = NULL;

  if ($theme === 'seven') {
    return;
  }

  if (!empty($config['generated_files_path'])) {
    $generated_files_path = $config['generated_files_path'];
  }
  else {
    drupal_set_message(t('The path to generated CSS files is not saved in configuration, try saving your themes Appearance settings.'), 'error');
  }

  // Attach at.settings, we need the ajaxPageState theme name.
  $page['#attached']['library'][] = 'at_core/at.settings';

  // Attach the JS layout script. Always run this to cleanup potentially
  // incorrect layout classes in the markup due to placeholders.
  // See: https://www.drupal.org/node/953034
  if (isset($config['layouts_enable']) && $config['layouts_enable'] === 1) {
    $page['#attached']['library'][] = 'at_core/at.layout';
  }

  // Attach Drupal Core module dependant libraries.
  // These libraries are declared in your themeName.libraries.yml and we only
  // load if the module is installed.
  $module_libraries = array(
    'aggregator',
    'book',
    'comment',
    'contact',
    'forum',
    'language',
    'search',
    'taxonomy',
    'toolbar',
  );
  $theme_libraries = \Drupal::service('library.discovery')->getLibrariesByExtension($theme);
  foreach ($module_libraries as $module_library) {
    if (array_key_exists($module_library, $theme_libraries) && \Drupal::moduleHandler()->moduleExists($module_library) === TRUE) {
      $page['#attached']['library'][] = "$theme/$module_library";
    }
  }

  // Process extension settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {

    // Mimic is active, load the ckeditor.css.
    if (isset($config['enable_ckeditor']) && $config['enable_ckeditor'] === 1) {
      if (isset($config['mimic_enabled']) && $config['mimic_enabled'] === 1) {
        // BC for older sub-themes that may have info file entires that mess up Mimic.
        $mimic_good_to_go = TRUE;
        $theme_extension = $active_theme->getExtension();
        if (isset($theme_extension->info['ckeditor_stylesheets'])) {
          $mimic_good_to_go = FALSE;
        }
        if (isset($theme_extension->info['libraries-override']['ckeditor/drupal.ckeditor'])) {
          $mimic_good_to_go = FALSE;
        }
        if ($mimic_good_to_go === TRUE) {
          $page['#attached']['library'][] = "$theme/ckeditor";
        }
      }
    }

    // Fonts.
    if (isset($config['enable_fonts']) && $config['enable_fonts'] === 1) {
      // Fonts generated CSS.
      if (file_exists($generated_files_path . '/fonts.css')) {
        $page['#attached']['library'][] = "$theme/fonts";
      }
      // Google font URL.
      if (isset($config['font_use_google_fonts']) && $config['font_use_google_fonts'] === TRUE) {
        $page['#attached']['library'][] = "$theme/google_fonts";
      }
      // Typekit ID and JS.
      if (isset($config['font_use_typekit']) && $config['font_use_typekit'] === TRUE) {
        $page['#attached']['library'][] = "$theme/typekit_id";
        $page['#attached']['library'][] = 'at_core/at.typekit';
      }
    }

    // Titles.
    if (isset($config['enable_titles']) && $config['enable_titles'] === 1) {
      if (file_exists($generated_files_path . '/title-styles.css')) {
        $page['#attached']['library'][] = "$theme/title_styles";
      }
    }

    // Mobile blocks
    if (isset($config['enable_mobile_blocks']) && $config['enable_mobile_blocks'] === 1) {
      if (file_exists($generated_files_path . '/mobile-blocks.css')) {
        $page['#attached']['library'][] = "$theme/mobile_blocks";
      }
    }

    // Custom CSS.
    if (isset($config['enable_custom_css']) && $config['enable_custom_css'] === 1) {
      if (file_exists($generated_files_path . '/custom-css.css')) {
        $page['#attached']['library'][] = "$theme/custom_css";
      }
    }

    // Markup Overrides
    if (isset($config['enable_markup_overrides']) && $config['enable_markup_overrides'] === 1) {

      // Responsive tables
      if (isset($config['responsive_tables']) && $config['responsive_tables'] === 1) {
        $page['#attached']['library'][] = "$theme/responsive_tables";
      }

      // Breadcrumbs.
      if (!empty($config['breadcrumb_separator'])) {
        if (file_exists($generated_files_path . '/breadcrumb.css')) {
          $page['#attached']['library'][] = "$theme/breadcrumb";
        }
      }

      // Login block.
      // Just load the login block CSS without the currentUser check.
      if (isset($config['horizontal_login_block']) && $config['horizontal_login_block'] === 1) {
        $page['#attached']['library'][] = "$theme/login_block";
      }
    }

    // Devel assets.
    if (isset($config['enable_devel']) && $config['enable_devel'] === 1) {

      // Attach Windowsize library
      if (isset($config['show_window_size']) && $config['show_window_size'] === 1) {
        $page['#attached']['library'][] = 'at_core/at.windowsize';
      }

      // Attach devel_layout CSS file.
      if (isset($config['devel_layout']) && $config['devel_layout'] === 1) {
        $page['#attached']['library'][] = 'at_core/at.devel_debug_layout';
      }

      // Attach devel_colorize-regions CSS file.
      if ((isset($config['devel_color_regions']) && $config['devel_color_regions'] === 1) && (isset($config['devel_layout']) && $config['devel_layout'] === 0)) {
        $page['#attached']['library'][] = 'at_core/at.devel_colorize_regions';
      }

      // Attach show_grid.
      if (isset($config['show_grid']) && $config['show_grid'] === 1) {
        $page['#attached']['library'][] = "$theme/show_grid";
      }

      // Attach nuke_toolbar CSS file.
      if (isset($config['nuke_toolbar']) && $config['nuke_toolbar'] === 1) {
        $page['#attached']['library'][] = 'at_core/at.devel_nuke_toolbar';
      }

      // Live Reload
      if (isset($config['enable_live_reload']) && $config['enable_live_reload'] === 1) {
        $page['#attached']['library'][] = 'at_core/at.livereload';
      }
    }

    // Shortcodes
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      $shortcodes_yml = $theme_path . '/' . $theme . '.shortcodes.yml';
      if (file_exists($shortcodes_yml)) {
        $shortcodes_parser = new Parser();
        $shortcodes = $shortcodes_parser->parse(file_get_contents($shortcodes_yml));
        unset($shortcodes['animate']);
        foreach ($shortcodes as $class_type => $class_values) {
          if (isset($config['shortcodes_' . $class_type . '_enable']) && $config['shortcodes_' . $class_type . '_enable'] === 1) {
            $page['#attached']['library'][] = $theme . '/shortcodes_' . str_replace('-', '_', $class_type);
          }
        }
      }

      // Animate has its own naming convention, being a vendor library.
      if (isset($config['shortcodes_animate_enable']) && $config['shortcodes_animate_enable'] === 1) {
        $page['#attached']['library'][] = "$theme/animate";
      }
    }

    // Add the responsive menu styles settings.
    if (isset($config['enable_responsive_menus']) && $config['enable_responsive_menus'] === 1) {
      $responsivemenu_settings = array();
      $load_accordion = FALSE;
      $click_menus_enabled = FALSE;
      $click_menu_settings['acd_load'] = FALSE;

      if (isset($config['click_menus_enabled']) && $config['click_menus_enabled'] === 1) {
        $click_menus_enabled = TRUE;
      }

      // Breakpoint
      if (isset($config['responsive_menu_breakpoint'])) {
        $responsivemenu_settings['bp'] = $config['responsive_menu_breakpoint'];
      }

      // Loop the config settings to find selected menu styles.
      foreach (array('default', 'responsive') as $style) {
        if (isset($config['responsive_menu_' . $style . '_style'])) {

          // Load the library for each selected menu style.
          $page['#attached']['library'][] = "$theme/responsive_menus_" . $config['responsive_menu_' . $style . '_style'];

          // Accordion click menu settings.
          if ($click_menus_enabled == TRUE) {
            $click_menu_settings['acd_' . $style] = FALSE;
            if (in_array($config['responsive_menu_' . $style . '_style'], ['vertical', 'slidedown', 'offcanvas', 'overlay'])) {
              $click_menu_settings['acd_' . $style] = TRUE;
              $click_menu_settings['acd_load'] = TRUE;
              $load_accordion = TRUE;
            }
          }

          // Set the menu option for each style.
          $responsivemenu_settings[$style] = 'ms-' . $config['responsive_menu_' . $style . '_style'];
        }
      }

      // Accordion click menu settings.
      if ($click_menus_enabled == TRUE) {
        $click_menu_settings['acd_both'] = FALSE;
        if ($click_menu_settings['acd_default'] == $click_menu_settings['acd_responsive']) {
          $click_menu_settings['acd_both'] = TRUE;
        }
      }
      $responsivemenu_settings['acd'] = $click_menu_settings;

      // Load Responsive menu dependencies.
      $page['#attached']['library'][] = "$theme/responsive_menus";
      $page['#attached']['library'][] = "at_core/at.responsivemenus";
      if ($load_accordion == TRUE) {
        $page['#attached']['library'][] = "$theme/responsive_menus_accordion";
        $page['#attached']['library'][] = "at_core/at.accordion";
      }
      $page['#attached']['library'][] = "at_core/at.orientationchangereload";

      // Attach JS settings.
      $page['#attached']['drupalSettings'][$theme]['at_responsivemenus'] = $responsivemenu_settings;
    }

    // Attach poly-fills to support IE8.
    if (isset($config['enable_legacy_browsers']) && $config['enable_legacy_browsers'] === 1) {
      if (isset($config['legacy_browser_polyfills']) && $config['legacy_browser_polyfills'] === 1) {
        $page['#attached']['library'][] = 'at_core/at.html5shiv';
        $page['#attached']['library'][] = 'at_core/at.respond';
        $page['#attached']['library'][] = 'at_core/at.selectivizr';
      }
    }

    // Load slideshow files
    if (isset($config['enable_slideshows']) && $config['enable_slideshows'] === 1) {

      // Get config settings and jam them into drupalSettings.
      if (isset($config['slideshow_count']) && $config['slideshow_count'] >= 1) {

        $basic_slider_settings = array(
          'animation',
          'direction',
          'smoothheight',
          'slideshowspeed',
          'animationspeed',
          'controlnav',
          'directionnav',
        );

        $carousel_settings = array(
          'as_carousel',
          'itemwidth',
          'itemmargin',
          'minitems',
          'maxitems',
          'move',
        );

        $advanced_slider_settings = array(
          'pauseonaction',
          'pauseonhover',
          'animationloop',
          'reverse',
          'randomize',
          'autostart', // Flexslider calls this "slideshow"
          'initdelay',
          'easing',
          'usecss',
          'touch',
          'video',
          'prevtext',
          'nexttext',
          'slideshow_class',
          'selector',
        );

        $slider_settings = array();
        for ($i = 0; $i < $config['slideshow_count']; $i++) {

          // Set a key
          $ss_key = Html::cleanCssIdentifier($theme . '-slideshow-' . $i);

          if (isset($config['slideshow_' . $i . '_enable']) && $config['slideshow_' . $i . '_enable'] === 1) {

            // Basic settings
            foreach ($basic_slider_settings as $basic_slider_setting) {
              if (isset($config['slideshow_' . $i . '_' . $basic_slider_setting])) {
                $slider_settings[$ss_key][$basic_slider_setting] = $config['slideshow_' . $i . '_' . $basic_slider_setting];
              }
            }

            // As Carousel
            if (isset($config['slideshow_' . $i . '_as_carousel']) && $config['slideshow_' . $i . '_as_carousel'] === 1) {
              foreach ($carousel_settings as $carousel_setting) {
                if (isset($config['slideshow_' . $i . '_' . $carousel_setting])) {
                  $slider_settings[$ss_key][$carousel_setting] = $config['slideshow_' . $i . '_' . $carousel_setting];
                }
              }
              // Reset options for carousels, fade won't work and vertical causes issues with Flexslider.
              $slider_settings[$ss_key]['animation'] = 'slide';
              $slider_settings[$ss_key]['direction'] = 'horizonal';
            }

            // Advanced options
            foreach ($advanced_slider_settings as $advanced_slider_setting) {
              if (isset($config['slideshow_' . $i . '_' . $advanced_slider_setting])) {
                $slider_settings[$ss_key][$advanced_slider_setting] = $config['slideshow_' . $i . '_' . $advanced_slider_setting];
              }
            }
          }
        }

        // Attach JS settings.
        if (!empty($slider_settings)) {
          $page['#attached']['drupalSettings'][$theme]['at_slideshows'] = $slider_settings;
          $page['#attached']['library'][] = 'at_core/at.slideshow_settings';
          $page['#attached']['library'][] = "$theme/slideshow_styles";
        }
      }
    }
  }

  // Custom CSS file for novice users.
  if (file_exists($theme_path . '/styles/css/custom.css')) {
    $page['#attached']['library'][] = "$theme/custom_css_file";
  }

  // Finally we load our jquery.ui overides.
  if (file_exists($theme_path . '/styles/css/components/jquery-ui.css')) {
    $page['#attached']['library'][] = "$theme/jquery_ui";
  }
}

/**
 * Implements hook_js_settings_alter().
 * Perform necessary alterations to the JavaScript settings (drupalSettings).
 *
 * @param array &$settings
 *   An array of all JavaScript settings (drupalSettings) being presented on the
 *   page.
 * @param \Drupal\Core\Asset\AttachedAssetsInterface $assets
 *   The assets attached to the current response.
 *
 * @see \Drupal\Core\Asset\AssetResolver
 */
function at_core_js_settings_alter(array &$settings, \Drupal\Core\Asset\AttachedAssetsInterface $assets) {
  $mimic = FALSE;
  $mimic_good_to_go = TRUE;

  // Alter CKEditor JS settings.
  if (\Drupal::moduleHandler()->moduleExists('ckeditor') == TRUE) {
    $active_theme = \Drupal::theme()->getActiveTheme();
    $theme = $active_theme->getName();
    $theme_path = $active_theme->getPath();
    $config = \Drupal::config($theme . '.settings')->get('settings');

    if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
      if (isset($config['enable_ckeditor']) && $config['enable_ckeditor'] === 1) {
        // BC for older sub-themes that may have info file entires that mess up Mimic.
        $theme_extension = $active_theme->getExtension();
        if (isset($theme_extension->info['ckeditor_stylesheets'])) {
          $mimic_good_to_go = FALSE;
        }
        if (isset($theme_extension->info['libraries-override']['ckeditor/drupal.ckeditor'])) {
          $mimic_good_to_go = FALSE;
        }
        if ($mimic_good_to_go === TRUE) {
          if (isset($config['mimic_enabled']) && $config['mimic_enabled'] === 1) {
            $mimic = TRUE;
            global $base_url;
            // AT Cores "Mimic" ckeditor skin.
            $editor_skin = $base_url . base_path() . drupal_get_path('theme', 'at_core') . '/ckeditor/skins/mimic/';
            if (isset($settings['editor']['formats'])) {
              foreach (array_keys($settings['editor']['formats']) as $text_format_id) {
                if ($settings['editor']['formats'][$text_format_id]['editor'] === 'ckeditor') {
                  $settings['editor']['formats'][$text_format_id]['editorSettings']['skin'] = 'mimic, ' . $editor_skin;
                }
              }
            }
          }
        }
      }
    }

    if (isset($settings['editor']['formats'])) {
      // Fontawesome + webfont CSS.
      if (file_exists($theme_path . '/styles/css/components/font-awesome.css')) {
        $styles[] = $theme_path . '/styles/css/components/font-awesome.css';
      }
      if (file_exists($theme_path . '/styles/css/components/webfonts.css')) {
        $styles[] = $theme_path . '/styles/css/components/webfonts.css';
      }
      // Component base, image & tables.
      $base_styles = array('base', 'image', 'tables');
      foreach ($base_styles as $base_style) {
        if (file_exists("$theme_path/styles/css/components/$base_style.css")) {
          $styles[] = "$theme_path/styles/css/components/$base_style.css";
        }
      }
      // Generated font & text styles.
      if (!empty($config['generated_files_path'])) {
        $generated_files_path = $config['generated_files_path'];
        if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
          if (isset($config['enable_fonts']) && $config['enable_fonts'] === 1) {
            if (file_exists($generated_files_path . '/fonts.css')) {
              $styles[] = $generated_files_path . '/fonts.css';
            }
          }
          // Google fonts.
          if (isset($config['font_use_google_fonts']) && $config['font_use_google_fonts'] === TRUE) {
            $styles[] = Xss::filter($config['font_google']);
          }
          if (isset($config['enable_titles']) && $config['enable_titles'] === 1) {
            if (file_exists($generated_files_path . '/title-styles.css')) {
              $styles[] = $generated_files_path . '/title-styles.css';
            }
          }
        }
      }
      foreach ($settings['editor']['formats'] as $format_key => $format_values) {
        if ($mimic === TRUE) {
          // Alter ckeditor-iframe CSS.
          $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'][] = file_create_url("$theme_path/styles/css/components/ckeditor-iframe.css");
          // Remove cores version, it just gets in the way.
          $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'] = array_merge(
            array_diff(
              $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'],
              array('/core/modules/ckeditor/css/ckeditor-iframe.css')
            )
          );
        }
        if (!empty($styles)) {
          foreach ($styles as $stylesheet) {
            $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'][] = file_create_url($stylesheet);
          }
        }
      }
      // Color styles.
      if ($mimic === TRUE) {
        if (\Drupal::moduleHandler()->moduleExists('color') == TRUE) {
          $color_paths = \Drupal::config('color.theme.' . $theme)->get('stylesheets');
          foreach ($settings['editor']['formats'] as $format_key => $format_values) {
            // Add Color module generated stylesheets.
            if (!empty($color_paths)) {
              // Add the color module saved files.
              foreach ($color_paths as $color_key => $color_path) {
                if (basename($color_path) === 'color.css') {
                  $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'][] = file_create_url($color_path);
                }
              }
            }
            // Else add the themes color component stylesheet.
            else {
              $settings['editor']['formats'][$format_key]['editorSettings']['contentsCss'][] = file_create_url("$theme_path/styles/css/components/color.css");
            }
          }
        }
      }
    }
  }
}

/**
 * Implements hook_library_info_alter().
 *
 * @param array $libraries
 *   An associative array of libraries registered by $extension. Keyed by
 *   internal library name and passed by reference.
 * @param string $extension
 *   Can either be 'core' or the machine name of the extension that registered
 *   the libraries.
 */
function at_core_library_info_alter(&$libraries, $extension) {
  $theme_path = \Drupal::theme()->getActiveTheme()->getPath();
  // Replace layout_plugin CSS auto-magically. This avoids the whole issue
  // of absolute paths in libraries-override in the info.yml file.
  if (file_exists($theme_path . '/styles/layout_plugin') && $extension == 'layout_plugin') {
    $at_layout_plugin_libraries = array(
      'twocol-2-10',
      'twocol-3-9',
      'twocol-4-8',
      'twocol-5-7',
      'twocol-6-6',
      'twocol-7-5',
      'twocol-8-4',
      'twocol-9-3',
      'twocol-10-2',
      'threecol-2-8-2',
      'threecol-2-2-8',
      'threecol-8-2-2',
      'threecol-3-6-3',
      'threecol-3-3-6',
      'threecol-6-3-3',
      'threecol-4-4-4',
      'fourcol-3-3-3-3',
      'grid-2x2',
      'grid-3x3',
      'grid-4x4',
    );
    foreach ($libraries as $lib_key => $library) {
      if (in_array($lib_key, $at_layout_plugin_libraries)) {
        $new_css = $theme_path . '/styles/layout_plugin/css/' . $lib_key .  '.css';
        if (file_exists($new_css)) {
          $libraries[$lib_key]['css']['theme'] = array('/' . $new_css => array());
        }
      }
    }
  }

  // Blow away Social Media Links (module) Font Awesome library.
  if ($extension == 'social_media_links') {
    if (isset($libraries['fontawesome.component'])) {
      unset($libraries['fontawesome.component']);
    }
  }
}

/**
 * Implements hook_theme_registry_alter().
 * @param $theme_registry
 */
function at_core_theme_registry_alter(&$theme_registry) {
  if (\Drupal::moduleHandler()->moduleExists('layout_plugin') == TRUE) {
    $layouts = Layout::layoutPluginManager()->getDefinitions();
    $layout_theme_hooks = array();
    foreach ($layouts as $info) {
      if ($info['provider'] == 'at_core') {
        $layout_theme_hooks[] = $info['theme'];
      }
    }
    // Only add preprocess functions if entity exposes theme function, and this
    // layout is provided by at_core.
    if (!empty($layout_theme_hooks)) {
      foreach ($theme_registry as $theme_hook => $info) {
        if (in_array($theme_hook, $layout_theme_hooks) || (!empty($info['base hook']) && in_array($info['base hook'], $layout_theme_hooks))) {
          $theme_registry[$theme_hook]['preprocess functions'][] = 'at_core_preprocess_at_layout';
        }
      }
    }
  }
}

/**
 * Alter the element type information returned from modules.
 * TODO Review/remove after https://www.drupal.org/node/2409083 lands.
 * @param array $info
 * @see \Drupal\Core\Render\ElementInfoManager
 * @see \Drupal\Core\Render\Element\ElementInterface
 */
function at_core_element_info_alter(array &$info) {
  // Remove the html5 shiv. Add it later if legacy browser support is enabled.
  if (in_array('core/html5shiv', $info['html']['#attached']['library'])) {
    $info['html']['#attached']['library'] = array_merge(array_diff($info['html']['#attached']['library'], array('core/html5shiv')));
  }
}
