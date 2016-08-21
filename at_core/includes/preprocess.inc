<?php

use Drupal\Core\Url;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;
use Drupal\at_core\Layout\LayoutLoad;

/**
 * Preprocess variables for html templates.
 * @param $variables
 */
function at_core_preprocess_html(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Add theme variables, we use these to set a class and provide a very easy
  // way for themers to link to files in the theme, such as background images
  // or other files.
  $variables['theme']['name'] = Html::escape($theme);
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Set the skip navigation target ID
  $variables['skip_link_target'] = '#block-' . $theme . '-content';

  // Defaults for Appearance setting variables.
  $variables['touch_icons'] = FALSE;
  // BC, deprecated.
  $variables['googlefonts_url'] = '';
  $variables['typekit_id'] = '';

  // Initialize prefetch variables
  $variables['google_dns_prefetch'] = FALSE;
  $variables['false_dns_prefetch'] = FALSE;

  // We use this to set body classes based in the URI.
  $request = \Drupal::request();

  // Set a class for query pages, e,g, pager page 1, page 2 etc.
  $request_uri = parse_url($request->getRequestUri());
  if (isset($request_uri['query'])) {
    $query = isset($request_uri['query']) ? Html::cleanCssIdentifier(ltrim($request_uri['query'], '/')) : NULL;
    $variables['path_info']['query'] = (strlen($query) > 25) ? substr($query, 0, 25) : $query;
  }
  else {
    $variables['path_info']['query'] = NULL;
  }

  // We use this to replicate Drupal 7's path-[root_path]-[id] type classes.
  $variables['path_info']['args'] = FALSE;
  $path = $request->getPathInfo();
  $path_args = explode('/', $path);
  if (count($path_args) >= 3) {
    $variables['path_info']['args'] = Html::cleanCssIdentifier(ltrim($path, '/'));
  }

  // Extensions
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {

    // Skip navigation target setting.
    if (isset($config['skip_link_target'])) {
      $variables['skip_link_target'] = '#' . Html::cleanCssIdentifier($config['skip_link_target']);
    }

    // Apple touch icons - low, medium and high (see the Apple docs).
    if (isset($config['enable_touch_icons']) && $config['enable_touch_icons'] === 1) {
      $variables['touch_icons'] = TRUE;
      $variables['touch_rel'] = 'apple-touch-icon';

      if (isset($config['apple_touch_icon_precomposed']) && $config['apple_touch_icon_precomposed'] === 1) {
        $variables['touch_rel'] = 'apple-touch-icon-precomposed';
      }

      // Apple default icon and Nokia shortcut icon.
      if (isset($config['icon_path_default']) && !empty($config['icon_path_default'])) {
        $default_icon = file_create_url($variables['directory'] . '/' . Html::escape($config['icon_path_default']));
        $variables['touch_icon_nokia'] = $default_icon;
        $variables['touch_icon_default'] = $default_icon;
      }

      // iPad (standard display).
      if (isset($config['apple_touch_icon_path_ipad']) && !empty($config['apple_touch_icon_path_ipad'])) {
        $variables['touch_icon_ipad'] = file_create_url($variables['directory'] . '/' . $config['apple_touch_icon_path_ipad']);
      }

      // iPhone retina.
      if (isset($config['apple_touch_icon_path_iphone_retina']) && !empty($config['apple_touch_icon_path_iphone_retina'])) {
        $variables['touch_icon_iphone_retina'] = file_create_url($variables['directory'] . '/' . $config['apple_touch_icon_path_iphone_retina']);
      }

      // iPad retina.
      if (isset($config['apple_touch_icon_path_ipad_retina']) && !empty($config['apple_touch_icon_path_ipad_retina'])) {
        $variables['touch_icon_ipad_retina'] = file_create_url($variables['directory'] . '/' . $config['apple_touch_icon_path_ipad_retina']);
      }
    }

    // Shortcodes.
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      if (!empty($config['page_classes_body'])) {
        $shortcodes = Tags::explode($config['page_classes_body']);
        foreach ($shortcodes as $class) {
          $variables['attributes']['class'][] = Html::cleanCssIdentifier($class);
        }
      }
    }

    // Prefetch Font provider DNS.
    if (isset($config['enable_fonts']) && $config['enable_fonts'] === 1) {
      if (isset($config['font_use_google_fonts']) && $config['font_use_google_fonts'] === 1) {
        $variables['google_dns_prefetch'] = TRUE;
      }
      if (isset($config['font_use_typekit']) && $config['font_use_typekit'] === 1) {
        $variables['typekit_dns_prefetch'] = TRUE;
      }
    }
  }

  // Add all breakpoints to drupalSettings (key:media query).
  $breakpoints_module = \Drupal::moduleHandler()->moduleExists('breakpoint');
  if ($breakpoints_module == TRUE) {
    $breakpoints_array = array();
    $breakpoint_groups[] = isset($config['breakpoint_group_layout']) ? $config['breakpoint_group_layout']: 'at_core.simple';
    // Push 'at_core.simple' onto the array, out Layout Plugin layouts always
    // require these breakpoints to be set.
    if ($breakpoint_groups !== 'at_core.simple') {
      $breakpoint_groups[] = 'at_core.simple';
    }
    foreach ($breakpoint_groups as $breakpoint_group) {
      $breakpoints = \Drupal::service('breakpoint.manager')->getBreakpointsByGroup($breakpoint_group);
      foreach ($breakpoints as $breakpoint_key => $breakpoint_values) {
        $breakpoint_label = strtolower($breakpoint_values->getLabel()->getUntranslatedString());
        $clean_breakpoint_key = str_replace('.', '_', $breakpoint_key);
        $breakpoints_array[$clean_breakpoint_key]['breakpoint'] = $breakpoint_label;
        $breakpoints_array[$clean_breakpoint_key]['mediaquery'] = $breakpoint_values->getMediaQuery();
      }
      $variables['#attached']['drupalSettings'][$theme]['at_breakpoints'] = $breakpoints_array;
    }
  }
  else {
    drupal_set_message(t('This theme requires the <b>Breakpoint module</b> to be installed. Go to the <a href="@extendpage" target="_blank">Modules</a> page and install Breakpoint.', array('@extendpage' => base_path() . 'admin/modules')), 'error');
  }
}

/**
 * Preprocess variables for page templates.
 * @param $variables
 */
function at_core_preprocess_page(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Page classes
  $variables['attributes']['class'][] = 'page';

  // Set attribution.
  $attribution_url = Url::fromUri('//adaptivethemes.com',
    $options = array(
      'attributes' => array(
        'class' => array('attribution__link'),
        'target' => '_blank',
      ),
      'absolute' => TRUE,
    )
  );
  $variables['attribution'] = array(
    '#type' => 'inline_template',
    '#template' => '<div class="l-pr attribution"><div class="l-rw">{{ attribution }}</div></div>',
    '#context' => array(
      'attribution' => \Drupal\Core\Link::fromTextAndUrl(t('Design by Adaptivethemes.com'), $attribution_url)->toString(),
    ),
  );

  // Disallow access if attribution link is toggled off.
  if (isset($config['attribution_toggle']) && $config['attribution_toggle'] === 0) {
    $variables['attribution']['#access'] = FALSE;
  }

  // Process extension settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      if (!empty($config['page_classes_page'])) {
        $shortcodes = Tags::explode($config['page_classes_page']);
        foreach ($shortcodes as $class) {
          $variables['attributes']['class'][] = Html::cleanCssIdentifier($class);
        }
      }
    }
  }

  //--- Layout -------------------------------------------------------------- //

  // BC for pre RC2 themes:
  // - allow the main content to render in at_generator so users can login if
  // they set it as default and blew their site up.
  // TODO remove at some stage.
  if ($theme === 'at_generator') {
    $variables['main']['has_regions'] = TRUE;
    return; // return early to avoid notices and errors.
  }

  if (isset($config['layouts_enable']) && $config['layouts_enable'] === 1) {
    // Add dynamic classes to each region wrapper (.regions).
    // This adds two classes to every wrapper:
    //  - "arc--[n]" active region count in this row, e.g. "arc--2".
    //  - "hr--[n-n]" has regions, by source order, e.g. "hr--1-3".
    $variables['attributes']['class'][] = 'js-layout';
    $regions = system_region_list($theme, REGIONS_VISIBLE);
    $active_regions = array();
    // Do a basic check for emptiness, however we can never be sure because of
    // placeholders: https://www.drupal.org/node/953034
    // After the page loads at.layout.js runs to fix incorrect layout classes.
    // Note the old render early code has been removed, i.e.:
    // if ($region = \Drupal::service('renderer')->render($variables['page'][$region_name])) {}
    foreach ($regions as $region_name => $region_label) {
      if (!empty($variables['page'][$region_name])) {
        $active_regions[] = $region_name;
      }
    }
    if (!empty($active_regions)) {
      $layout_load = new LayoutLoad($theme, $active_regions);
      if ($row_attributes = $layout_load->rowAttributes()) {
        // Append to $variables for backwards compatibility.
        $variables += $row_attributes;
      }

      // Rows.
      foreach ($active_regions as $key => $region) {
        // Get the row key for each region.
        $row_key = $layout_load->regionAttributes($region);
        if (!empty($row_key)) {
          // Prepare variables for each row template.
          $row_regions[$row_key]['#row'] = $row_key;
          $row_regions[$row_key][$region] = $variables['page'][$region];
          $row_regions[$row_key]['#wrapper_attributes'] = $row_attributes[$row_key]['wrapper_attributes'];
          $row_regions[$row_key]['#container_attributes'] = $row_attributes[$row_key]['container_attributes'];
          // Call the row template and pass in the processed variables.
          $variables['rows'][$row_key] = array(
            '#theme' => 'row',
            '#regions' => $row_regions[$row_key],
          );
        }
      }
    }
  }
}

/**
 * Preprocess variables for row templates.
 * @param $variables
 */
function at_core_preprocess_row(&$variables) {
  // Set easy to print variables for attributes.
  $variables['wrapper_attributes'] = $variables['regions']['#wrapper_attributes'];
  $variables['container_attributes'] = $variables['regions']['#container_attributes'];
}

/**
 * Preprocess variables for region templates.
 * @param $variables
 */
function at_core_preprocess_region(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Set source order data attribute, used to set the layout classes.
  $layout_loaded = new LayoutLoad($theme, $active_regions = NULL);
  $region_source_order = $layout_loaded->regionSourceOrder($variables['region']);
  $variables['attributes']['data-at-region'] = $region_source_order[$variables['region']];

  // Set variable for the row this region belongs to.
  $region_row = $layout_loaded->regionAttributes($variables['region']);
  if (!empty($region_row)) {
    $variables['region_row'] = $region_row;
  }

  // Set wrapper element. Required for BC. Deprecated.
  $variables['html_element'] = 'div';

  // Extension settings
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      if (!empty($config['page_classes_region_' . $variables['region']])) {
        $shortcodes = Tags::explode($config['page_classes_region_' . $variables['region']]);
        foreach ($shortcodes as $class) {
          $variables['attributes']['class'][] = Html::cleanCssIdentifier($class);
        }
      }
    }
  }
}

/**
 * Preprocess variables for node templates.
 * @param $variables
 */
function at_core_preprocess_node(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');
  $node = $variables['node'];

  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Extension settings
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      if ($node_type = $node->getType()) {
        if (!empty($config['nodetype_classes_' . $node_type])) {
          $shortcodes = Tags::explode($config['nodetype_classes_' . $node_type]);
          foreach ($shortcodes as $class) {
            $variables['attributes']['class'][] = Html::cleanCssIdentifier($class);
          }
        }
      }
    }
  }

  // Header and Footer attributes.
  $variables['header_attributes'] = new Attribute(array('class' => array()));
  $variables['footer_attributes'] = new Attribute(array('class' => array()));

  // SEE https://drupal.org/node/2004252 or a follow up issue.
  if ($variables['display_submitted']) {

    // Add a class to the header if submitted is active, so we can theme dynamically.
    $variables['header_attributes']['class'][] = 'node__header--has-meta';

    // Initialize new attributes arrays.
    $variables['meta_attributes'] = new Attribute(array('class' => array()));
    $variables['meta_attributes']['class'][] = 'node__meta';

    $variables['submitted_attributes'] = new Attribute(array('class' => array()));
    $variables['submitted_attributes']['class'][] = 'node__submitted';

    // Add a class if author picture is printing.
    if ($author_picture = \Drupal::service('renderer')->render($variables['author_picture'])) {
      // If twig debug is on $author_picture is not empty.
      $twig_debug = \Drupal::service('twig')->isDebug();
      if ($twig_debug == TRUE) {
        $ap = _at_core_cleanup_twig_debug_output($author_picture);
      }
      else {
        $ap = $author_picture;
      }
      if (!empty($ap)) {
        $variables['meta_attributes']['class'][] = 'node__meta--has-author-picture';
      }
    }
  }
}

/**
 * Preprocess variables for comment templates.
 * @param $variables
 */
function at_core_preprocess_comment(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Initialize new attributes arrays.
  $variables['meta_attributes'] = new Attribute(array('class' => array()));
  $variables['meta_attributes']['class'][] = 'comment__meta';

  $variables['submitted_attributes'] = new Attribute(array('class' => array()));
  $variables['submitted_attributes']['class'][] = 'comment__submitted';

  // Add a class if user picture is printing. Render early.
  if ($user_picture = \Drupal::service('renderer')->render($variables['user_picture'])) {

    // If twig debug is on $author_picture is not empty.
    $twig_debug = \Drupal::service('twig')->isDebug();
    if ($twig_debug == TRUE) {
      $up = _at_core_cleanup_twig_debug_output($user_picture);
    }
    else {
      $up = $user_picture;
    }
    if (!empty($up)) {
      $variables['meta_attributes']['class'][] = 'comment__meta--has-user-picture';
    }
  }

  // Use permalink URI as the title link.
  $comment = $variables['elements']['#comment'];
  if (!isset($comment->in_preview)) {
    $uri = $comment->permalink();
    $attributes = $uri->getOption('attributes') ?: array();
    $attributes += array('class' => array('permalink'), 'rel' => 'bookmark');
    $uri->setOption('attributes', $attributes);
    $variables['title'] = \Drupal\Core\Link::fromTextAndUrl($comment->getSubject(), $uri);
  }

  // Hide comment titles.
  $variables['title_visibility'] = TRUE;
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_markup_overrides']) && $config['enable_markup_overrides'] === 1) {
      if (isset($config['comments_hide_title']) && $config['comments_hide_title'] === 1) {
        $variables['title_visibility'] = FALSE;
      }
    }
  }
}

/**
 * Preprocess variables for block templates.
 * @param $variables
 */
function at_core_preprocess_block(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  if (\Drupal::currentUser()->isAnonymous()) {
    if ($variables['base_plugin_id'] === 'system_branding_block') {
      $variables['#attached']['library'][] = "$theme/login_block";
    }
  }

  // Extension settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {

    // Add bool for click menus enabled.
    if ($variables['base_plugin_id'] === 'system_menu_block') {
      $variables['click_menus_enabled'] = FALSE;
      if (isset($config['click_menus_enabled']) && $config['click_menus_enabled'] === 1) {
        $variables['click_menus_enabled'] = TRUE;
      }
    }

    // Markup overrides
    if (isset($config['enable_markup_overrides']) && $config['enable_markup_overrides'] === 1) {

      // Use PNG logo in branding block
      if ($variables['base_plugin_id'] === 'system_branding_block') {
        if (isset($config['png_logo']) && $config['png_logo'] === 1) {
          $variables['site_logo'] = '';
          if ($variables['content']['site_logo']['#access'] && $variables['content']['site_logo']['#uri']) {
            $variables['site_logo'] = str_replace('.svg', '.png', $variables['content']['site_logo']['#uri']);
          }
        }
      }

      // Remove login block links.
      if ($variables['base_plugin_id'] === 'user_login_block') {
        if ((isset($config['login_block_remove_links']) && $config['login_block_remove_links'] === 1) || (isset($config['horizontal_login_block']) && $config['horizontal_login_block'] === 1)) {
          unset($variables['content']['user_links']);

          // Add class for horizontal login.
          if (isset($config['horizontal_login_block']) && $config['horizontal_login_block'] === 1) {
            $variables['attributes']['class'][] = 'is-horizontal-login-block';
          }
        }
      }
    }

    // Shortcodes classes.
    if (isset($config['enable_shortcodes']) && $config['enable_shortcodes'] === 1) {
      if ($id = $variables['elements']['#id']) {
        if (!empty($config['block_classes_' . $id])) {
          $shortcodes = Tags::explode($config['block_classes_' . $id]);
          foreach ($shortcodes as $class) {
            $variables['attributes']['class'][] = Html::cleanCssIdentifier($class);
          }
        }
      }
    }
  }
}

/**
 * Preprocess variables for field templates.
 * @param $variables
 */
function at_core_preprocess_field(&$variables) {
  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];
  // Field formatter variable.
  if (isset($variables['element']['#formatter'])) {
    $variables['field_formatter'] = $variables['element']['#formatter'];
  }
}

/**
 * Preprocess variables for entity reference field templates.
 * @param $variables
 */
function at_core_preprocess_field__entity_reference(&$variables) {
  $variables['field_entity_type'] = NULL;
  if (isset($variables['element']['#items']) && is_object($variables['element']['#items'])) {
    $variables['field_entity_type'] = $variables['element']['#items']->getSetting('target_type');
  }
}

/**
 * Preprocess variables for image field templates.
 * @param $variables
 */
function at_core_preprocess_field__image(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Extension settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_images']) && $config['enable_images'] === 1) {

      $entity_type = $variables['element']['#entity_type'];
      $node_type = $variables['element']['#bundle'];
      $view_mode = $variables['element']['#view_mode'];

      // Reset items array to first item only. This causes only the first image
      // to be shown, e.g. in teaser view mode.
      if (isset($config['image_count_' . $node_type . '_' . $entity_type . '_' . $view_mode]) && $config['image_count_' . $node_type . '_' . $entity_type . '_' . $view_mode] === 1) {
        $item = reset($variables['items']);
        $variables['items'] = array($item);
      }

      // Captions.
      if (isset($config['image_captions_' . $node_type . '_' . $entity_type . '_' . $view_mode]) && $config['image_captions_' . $node_type . '_' . $entity_type . '_' . $view_mode] === 1) {
        foreach ($variables['items'] as $delta => $item) {
          $values = $item['content']['#item']->getValue();
          if (!empty($values['title'])) {
            $variables['items'][$delta]['caption'] = array(
              'show' => TRUE,
              'title' => $values['title'],
            );
          }
          else {
            $variables['items'][$delta]['caption'] = array(
              'show' => FALSE,
            );
          }
        }
      }

      // Image align class, provide a variable for use in field template.
      $variables['image_align'] = 'align-none';

      // Preview mode default uses view mode default not full.
      if ($view_mode == 'default') {
        $view_mode = 'full';
      }

      if (!empty($config['image_alignment_' . $node_type . '_' . $entity_type . '_' . $view_mode])) {
        $variables['image_align'] = 'align-' . $config['image_alignment_' . $node_type . '_' . $entity_type . '_' . $view_mode];
      }

      // This is quite aggressive and it may be better to let the site fail?
      foreach ($variables['items'] as $delta => $item) {
        if (empty($item['content']['#image_style'])) {
          $variables['attributes']['class'][] = 'float-none';
        }
      }
    }
  }
}

/**
 * Preprocess variables for breadcrumb templates.
 * @param $variables
 */
function at_core_preprocess_breadcrumb(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // New attributes array for breadcrumb title.
  $variables['title_attributes'] = new Attribute(array('class' => array()));

  // Set attributes.
  $variables['breadcrumb_label'] = FALSE;

  // Label value
  $variables['breadcrumb_label_value'] = isset($config['breadcrumb_label_value']) ? $config['breadcrumb_label_value'] : 'You are here:';

  // Theme settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_markup_overrides']) && $config['enable_markup_overrides'] === 1) {

      // Show the label.
      if (isset($config['breadcrumb_label']) && $config['breadcrumb_label'] === 1) {
        $variables['breadcrumb_label'] = TRUE;
      }

      // Remove the Home link.
      if (isset($config['breadcrumb_home']) && $config['breadcrumb_home'] === 1) {
        $first_item = array_values($variables['breadcrumb'])[0];
        if (isset($first_item['url']) && $first_item['url'] === base_path()) {
          array_shift($variables['breadcrumb']);
        }
      }

      // Add title to breadcrumbs.
      if (isset($config['breadcrumb_title']) && $config['breadcrumb_title'] === 1) {
        // Use a placeholder to inject dynamic content.
        $placeholder_title = array(
          '#lazy_builder' => ['_at_core_breadcrumb_title_placeholder', ['page_title']],
          '#create_placeholder' => TRUE,
        );
        $variables['breadcrumb'][] = ['text' => $placeholder_title];
      }

      // If home is the only item, remove it.
      if (isset($config['breadcrumb_home_alone']) && $config['breadcrumb_home_alone'] === 1) {
        $arr_length = count($variables['breadcrumb']);
        if ($arr_length === 1 && $variables['breadcrumb'][0]['url'] === base_path()) {
          unset($variables['breadcrumb'][0]);
        }
      }

      // Trim long items.
      $variables['breadcrumb_item_length'] = 0;
      if (isset($config['breadcrumb_item_length']) && $config['breadcrumb_item_length'] > 0) {
        $variables['breadcrumb_item_length'] = Html::escape($config['breadcrumb_item_length']);
      }
    }
  }
}

/**
 * Preprocess variables for user templates.
 * @param $variables
 */
function at_core_preprocess_user(&$variables) {
  // Path to theme.
  $variables['theme']['path'] = base_path() . $variables['directory'];

  // Current user.
  $user = $variables['elements']['#user'];

  // Label.
  $variables['label'] = Html::escape($user->getDisplayName());

  // Roles.
  $roles = $user->getRoles(FALSE);
  $variables['roles'] = array();
  // Playing it safe here because I don't trust Drupal to return an array...
  if (!empty($roles) && is_array($roles)) {
    foreach ($roles as $role) {
      $variables['roles'][] = 'has-role--' . Html::cleanCssIdentifier($role);
    }
  }

  // View mode.
  $variables['view_mode'] = $variables['elements']['#view_mode'];

  // Check if he current user has permission to access user profiles.
  $current_user = \Drupal::currentUser();
  $variables['access_profiles'] = FALSE;
  if ($current_user->hasPermission('access user profiles')) {
    $variables['access_profiles'] = TRUE;
  }

  // Base path, used to build a link to the profile in non-full view modes.
  $variables['base_path'] = base_path();
}

/**
 * Preprocess variables for user templates.
 * @param $variables
 */
function at_core_preprocess_views_view(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $config = \Drupal::config($theme . '.settings')->get('settings');

  // Theme settings.
  if (isset($config['enable_extensions']) && $config['enable_extensions'] === 1) {
    if (isset($config['enable_markup_overrides']) && $config['enable_markup_overrides'] === 1) {
      if (isset($config['views_hide_feedicon']) && $config['views_hide_feedicon'] === 1) {
        $variables['feed_icons'] = array();
      }
    }
  }
}

/**
 * Preprocess variables for links templates.
 * @param $variables
 */
function at_core_preprocess_links(&$variables) {
  // We add lots of classes to link items, wrappers and links to support many
  // options for icons, backgrounds, borders etc.
  foreach ($variables['links'] as $key => $values) {
    $variables['links'][$key]['wrapper_attributes'] = new Attribute();
    $variables['links'][$key]['wrapper_attributes']->addClass(Html::cleanCssIdentifier($key . '__link-wrapper'));

    if (isset($variables['links'][$key]['link']['#options']['attributes'])) {
      $variables['links'][$key]['link']['#options']['attributes']['class'] = array(Html::cleanCssIdentifier($key . '__link'));
    }

    if (isset($variables['links'][$key]['link']['#options']['attributes']['class'])) {
      $class = $variables['links'][$key]['link']['#options']['attributes']['class'];
      if (is_string($class)) {
        $variables['links'][$key]['attributes']->addClass(Html::cleanCssIdentifier($key . '--item' . '--' . $class));
      }
    }
  }
}

/**
 * Preprocess variables for forums templates.
 * @param $variables
 */
function at_core_preprocess_forums(&$variables) {
  // Add a class to each forum topic table header td.
  if (isset($variables['topics']['#header'])) {
    foreach ($variables['topics']['#header'] as $topic_list_key => $topic_list_value) {
      $variables['topics']['#header'][$topic_list_key]['class'][] = 'forum-header__' . Html::cleanCssIdentifier(strtolower($variables['topics']['#header'][$topic_list_key]['data']));
    }
  }

  // Add BEM classes to row items to match the forum-list.html.twig BEM classes.
  if (isset($variables['topics']['#rows'])) {
    foreach ($variables['topics']['#rows'] as $row_key => $row_values) {
      foreach ($row_values as $row_values_key => $row_values_value) {
        foreach ($row_values_value['class'] as $class_key => $class_value) {
          $class_value = str_replace('forum__', '', $class_value);
          $variables['topics']['#rows'][$row_key][$row_values_key]['class'][] = 'forum-list__' . Html::cleanCssIdentifier(strtolower($class_value));
          unset($variables['topics']['#rows'][$row_key][$row_values_key]['class'][$class_key]);
        }
      }
    }
  }
}

/**
 * Implements hook_preprocess_HOOK() for maintenance-page.html.twig
 * @param $variables
 */
function at_core_preprocess_maintenance_page(&$variables) {
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  // Load the maintenance page library.
  $variables['#attached']['library'][] = $theme . '/maintenance_page';
}

/**
 * Preprocess variables for menu templates.
 * TODO Remove depending on if https://www.drupal.org/node/2755375
 * @param $variables
 */
function at_core_preprocess_menu(&$variables) {
  // Set a class on menu link item url.
  if ($variables['menu_name'] !== 'admin') {
    foreach ($variables['items'] as &$i) {
      _at_core_menu_link_classes($i);
    }
    // Items below.
    $iterator = new \RecursiveArrayIterator($variables['items']);
    $recursive = new \RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
    foreach ($recursive as $key => $values) {
      if ($key === 'below') {
        if (!empty($values)) {
          foreach ($values as &$ib)
          _at_core_menu_link_classes($ib);
        }
      }
    }
  }
}

/**
 * Preprocess variables for the select template.
 * @param $variables
 */
function at_core_preprocess_select(&$variables) {
  $variables['content_attributes']['class'][] = 'form-type-select__select-wrapper';
  if ($variables['element']['#multiple'] == TRUE) {
    $variables['content_attributes']['class'][] = 'is-multiple';
  }
}

/**
 * Preprocess variables for AT Layout Plugin templates.
 * This function is added in at_core_theme_registry_alter().
 * @param $variables
 */
function at_core_preprocess_at_layout(&$variables) {
  $attributes = _at_core_layout_plugin_attributes($variables);
  if (isset($attributes['role'])) {
    $variables['attributes']['role'] = $attributes['role'];
  }
  $variables['attributes']['class'] = $attributes['classes'];
  $variables['html_element'] = $attributes['html_element'];
}

/**
 * Preprocess variables for node add/edit templates.
 * @param $variables
 */
function at_core_preprocess_node_edit_form(&$variables) {
  $request = \Drupal::request();
  $route_match = \Drupal::routeMatch();
  $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
  $variables['title'] = $title ?: $variables['form']['#title'];
}
