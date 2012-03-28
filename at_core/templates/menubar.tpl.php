<?php
/**
 * @file
 * Adativetheme implementation to display a menubar.
 *
 * The contents is normally one of Drupals magic menu variables, 
 * such as the Main menu or User menu (secondary menu), but could
 * be any menu you wish to wrap in navigation menu type markup
 * and classes.
 *
 * Available variables:
 * - $menubar_id: CSS id for theming the menubar
 * - $menu: Holds the themed menu (normally using theme_links())
 * - the usual $classes, $attributes, $content attributes etc
 *
 * @see adaptivetheme_preprocess_menubar()
 * @see adaptivetheme_process_menubar()
 */
?>
<div id="<?php print $menubar_id; ?>" class="<?php print $classes; ?>" <?php print $attributes; ?>>
  <nav <?php print $content_attributes; ?>>
    <?php print $menu; ?>
  </nav>
</div>