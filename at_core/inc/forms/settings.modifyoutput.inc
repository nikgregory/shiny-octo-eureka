<?php

  // Modify HTML Output
  $form['at']['modify-output'] = array(
    '#type' => 'fieldset',
    '#title' => t('Markup Overrides'),
    '#weight' => -20,
  );

  // Add markup
  $form['at']['modify-output']['add'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add Markup'),
    '#description' => t('<h3>Add Markup</h3>'),
  );

  // Use the page full width wrappers template
  $form['at']['modify-output']['add']['page_full_width_wrappers'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use page--full-width-wrappers.tpl.php by default'),
    '#description' => t('This setting will load a page template will full width DIV wrappers - perfect for designs with 100% width sections, header or footer. Note that if you choose to use other page template suggestions you should use copies of page--full-width-wrappers.tpl.php, you will find this in adaptivetheme/at-core/templates.'),
    '#default_value' => theme_get_setting('page_full_width_wrappers'),
  );

  // Add spans to theme_links
  $form['at']['modify-output']['add']['menu_item_span_elements'] = array(
    '#type' => 'checkbox',
    '#title' => t('Wrap menu item text in SPAN tags - useful for certain theme or design related techniques'),
    '#description' => t('Note: this does not work for <a href="!link" target="_blank">Superfish menus</a>, which includes its own feature for doing this.', array('!link' => 'http://drupal.org/project/superfish')),
    '#default_value' => theme_get_setting('menu_item_span_elements'),
  );
  
  // Hide or Remove
  $form['at']['modify-output']['hide-remove'] = array(
    '#type' => 'fieldset',
    '#title' => t('Hide or Remove'),
    '#description' => t('<h3>Hide or Remove</h3>'),
  );
  
  // Hide comment title
  $form['at']['modify-output']['hide-remove']['comments_hide_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide the comment title'),
    '#default_value' => theme_get_setting('comments_hide_title'),
    '#description' => t('Checking this setting will hide comment titles using element-invisible. Hiding rather than removing titles maintains accessibility and semantic structure while not showing titles to sighted users.'),
  );

  // Hide RSS icons
  $form['at']['modify-output']['hide-remove']['feed_icons_hide'] = array(
    '#type' => 'checkbox',
    '#title' => t('Remove RSS feed icons'),
    '#default_value' => theme_get_setting('feed_icons_hide'),
    '#description' => t('Checking this setting will remove RSS feed icons. This will not affect the Syndicate block icon.'),
  );

  // Unset block system main front
  $form['at']['modify-output']['hide-remove']['unset_block_system_main_front'] = array(
    '#type' => 'checkbox',
    '#title' => t('Do not display the Main content block on the front page'),
    '#default_value' => theme_get_setting('unset_block_system_main_front'),
    '#description' => t('Checking this setting will remove the Main content block from the front page only - useful for removing the welcome message and allowing use of another block.'),
  );

  // Extra CSS Classes
  $form['at']['modify-output']['extra-classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Extra Classes'),
    '#description' => t('<h3>Extra CSS Classes</h3>'),
  );

  // Page
  $form['at']['modify-output']['extra-classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Page classes'),
    '#description' => t('page-path, add/edit/delete (for workflow states), content type classes, section classes and a language class (i18n)'),
    '#default_value' => theme_get_setting('extra_page_classes') ? theme_get_setting('extra_page_classes') : 1,
  );

  // Node
  $form['at']['modify-output']['extra-classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Node classes'),
    '#description' => t('promoted, sticky, preview, language, odd/even classes, user picture handling, and build mode classes'),
    '#default_value' => theme_get_setting('extra_article_classes') ? theme_get_setting('extra_article_classes') : 1,
  );

  // Comment
  $form['at']['modify-output']['extra-classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Comment classes '),
    '#description' => t('anonymous, author, viewer, new, odd/even classes and classes for hidden titles, user picture and signature handling'),
    '#default_value' => theme_get_setting('extra_comment_classes') ? theme_get_setting('extra_comment_classes') : 1,
  );

  // Block
  $form['at']['modify-output']['extra-classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Blocks classes'),
    '#description' => t('odd/even (zebra classes), block region, delta and block count classes'),
    '#default_value' => theme_get_setting('extra_block_classes') ? theme_get_setting('extra_block_classes') : 1,
  );

  // Menu
  $form['at']['modify-output']['extra-classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Menus classes'),
    '#description' =>  t('menu link ID (mlid) and menu level (depth)'),
    '#default_value' => theme_get_setting('extra_menu_classes') ? theme_get_setting('extra_menu_classes') : 1,
  );

  // Item lists
  $form['at']['modify-output']['extra-classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Item-lists classes'),
    '#description' => t('first, last and odd/even (zebra classes)'),
    '#default_value' => theme_get_setting('extra_item_list_classes') ? theme_get_setting('extra_item_list_classes') : 1,
  );

  // Accessibility
  $form['at']['modify-output']['a11y'] = array(
    '#type' => 'fieldset',
    '#title' => t('Accessibility'),
    '#description' => t('<h3>Accessibility</h3>'),
  );

  // Skip link target
  $form['at']['modify-output']['a11y']['skip_link_target'] = array(
    '#type' => 'textfield',
    '#title' => t('Skip to navigation target ID'),
    '#description' => t('If your main content is not in the Main Content region you can change the skip link target ID to match.'),
    '#default_value' => theme_get_setting('skip_link_target'),
  );