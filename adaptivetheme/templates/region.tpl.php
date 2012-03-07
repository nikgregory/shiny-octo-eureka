<?php
/**
 * @file
 * Adativetheme region.tpl.php
 *
 * The region template in Adaptivetheme is a little different to most other themes.
 * Instead of hard coding its markup Adaptivetheme generates it in
 * adaptivetheme_process_region(), conditionally printing outer and inner wrappers. 
 * 
 * This allows the core theme to have just one region template instead of four.
 *
 * You can override this in your sub-theme with a normal region suggestion and use 
 * a standard region template if you prefer, or use your own themeName_process_region()
 * function to control the markup.
 *
 * - $outer_prefix: Outer div with region classes.
 * - $outer_suffix: Closing element.
 * - $inner_prefix: Inner div with .region-inner and .clearfix classes.
 * - $inner_suffix: Closing div.
 *
 * @see adaptivetheme_process_region()
 */
?>
<?php if ($content): ?>
  <?php print $outer_prefix . $inner_prefix; ?>
    <?php print $content; ?>
  <?php print $inner_suffix . $outer_suffix; ?>
<?php endif; ?>
