<?php
/**
 * @file
 * Adativetheme block.tpl.php
 *
 * The block template in Adaptivetheme is a little different to most other themes.
 * Instead of hard coding its markup Adaptivetheme generates most of it in
 * adaptivetheme_process_block(), conditionally printing outer and inner wrappers. 
 * 
 * This allows the core theme to have just one template instead of five.
 *
 * You can override this in your sub-theme with a normal block suggestion and use 
 * a standard block template if you prefer, or use your own themeName_process_block()
 * function to control the markup.
 *
 * - $outer_prefix: Holds a conditional element such as nav, section or div and 
 *                  includes the block id, classes and attributes.
 * - $outer_suffix: Closing element.
 * - $inner_prefix: Inner div with .block-inner and .clearfix classes.
 * - $inner_suffix: Closing div.
 *
 * @see adaptivetheme_process_block()
 */
?>
<?php print $outer_prefix . $inner_prefix; ?>
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
  <h2<?php print $title_attributes; ?>><?php print $title; ?></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php print $content ?>
<?php print $inner_suffix . $outer_suffix; ?>
