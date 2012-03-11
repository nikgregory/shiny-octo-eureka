<?php
  // CLASSES
  $form['at']['classes'] = array(
    '#type' => 'fieldset',
    '#weight' => 102,
    '#title' => t('CSS Classes'),
  );
  $form['at']['classes']['extra-classes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Extra Classes'),
    '#description' => t('<h3>Extra CSS Classes</h3>'),
  );
  $form['at']['classes']['extra-classes']['extra_page_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pages: ') . '<span class="description">' . t('add page-path, add/edit/delete (for workflow states), content type classes, section classes and a language class (i18n).') . '</span>',
    '#default_value' => theme_get_setting('extra_page_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_article_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Articles: ') . '<span class="description">' . t('add promoted, sticky, preview, language, odd/even classes, user picture handling, and build mode classes such as .article-teaser and .article-full.') . '</span>',
    '#default_value' => theme_get_setting('extra_article_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_comment_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Comments: ') . '<span class="description">' . t('add anonymous, author, viewer, new, odd/even classes and classes for hidden titles, user picture and signature handling.') . '</span>',
    '#default_value' => theme_get_setting('extra_comment_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_block_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Blocks: ') . '<span class="description">' . t('add odd/even (zebra classes), block region and block count classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_block_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_menu_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Menus: ') . '<span class="description">' . t('add extra classes based on the menu link ID (mlid) and menu level (depth).') . '</span>',
    '#default_value' => theme_get_setting('extra_menu_classes'),
  );
  $form['at']['classes']['extra-classes']['extra_item_list_classes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Item-lists: ') . '<span class="description">' . t('add first, last and odd/even classes.') . '</span>',
    '#default_value' => theme_get_setting('extra_item_list_classes'),
  );