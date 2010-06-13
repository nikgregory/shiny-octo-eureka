<?php
// $Id$

/**
 * @file 5col-5x20.php
 * Gpanel code snippet to display 5x20% width regions as columns.
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
 * Region Variables:
 * $page['five_col_first']  outputs the "5col Gpanel col 1" region.
 * $page['five_col_second'] outputs the "5col Gpanel col 2" region.
 * $page['five_col_third']  outputs the "5col Gpanel col 3" region.
 * $page['five_col_fourth'] outputs the "5col Gpanel col 4" region.
 * $page['five_col_fifth']  outputs the "5col Gpanel col 5" region.
 */
?>
<!-- Five column Gpanel -->
<?php if ($page['five_col_first'] || $page['five_col_second'] || $page['five_col_third'] || $page['five_col_fourth'] || $page['five_col_fifth']): ?>
  <div class="five-col-5x20 gpanel clearfix">
    <div class="section region col-1 first"><div class="inner">
      <?php if ($page['five_col_first']): print render($page['five_col_first']); endif; ?>
    </div></div>
    <div class="section region col-2"><div class="inner">
      <?php if ($page['five_col_second']): print render($page['five_col_second']); endif; ?>
    </div></div>
    <div class="section region col-3"><div class="inner">
      <?php if ($page['five_col_third']): print render($page['five_col_third']); endif; ?>
    </div></div>
    <div class="section region col-4"><div class="inner">
      <?php if ($page['five_col_fourth']): print render($page['five_col_fourth']); endif; ?>
    </div></div>
    <div class="section region col-5 last"><div class="inner">
      <?php if ($page['five_col_fifth']): print render($page['five_col_fifth']); endif; ?>
    </div></div>
  </div>
<?php endif; ?>
<!--/end Gpanel-->
