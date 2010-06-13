<?php
// $Id$

/**
 * @file 3col-3x33.php
 * Gpanel code snippet to display 3x33% width regions as columns.
 *
 * GPanels are drop in multi-column snippets for displaying blocks in
 * vertical columns, such as 2 columns, 3 columns or 4 columns.
 *
 * How to use a Gpanel:
 * 1. Copy and paste a Gpanel into your page.tpl.php file.
 * 2. Uncomment the matching regions in your subthemes info file.
 * 3. Clear the cache (in Performance settings) to refresh the theme registry.
 *
 * Now you can add blocks to the regions as per normal. The layout CSS for
 * these regions is already set up so there is nothing more to do.
 *
 * Gpanels are fluid, meaning they stretch and compress with the page width.
 *
 * Region variables:
 * $page['three_col_3x33_first']  outputs the "3col 3x33 Gpanel col 1" region.
 * $page['three_col_3x33_second'] outputs the "3col 3x33 Gpanel col 2" region.
 * $page['three_col_3x33_third']  outputs the "3col 3x33 Gpanel col 3" region.
 */
?>
<!-- Three column 3x33 Gpanel -->
<?php if ($page['three_col_3x33_first'] || $page['three_col_3x33_second'] || $page['three_col_3x33_third']): ?>
  <div class="three-col-3x33 gpanel clearfix">
    <div class="section region col-1 first"><div class="inner">
      <?php if ($page['three_col_3x33_first']): print render($page['three_col_3x33_first']); endif; ?>
    </div></div>
    <div class="section region col-2"><div class="inner">
      <?php if ($page['three_col_3x33_second']): print render($page['three_col_3x33_second']); endif; ?>
    </div></div>
    <div class="section region col-3 last"><div class="inner">
      <?php if ($page['three_col_3x33_third']): print render($page['three_col_3x33_third']); endif; ?>
    </div></div>
  </div>
<?php endif; ?>
<!--/end Gpanel-->
