<?php
/**
 * Gpanels are drop in multi-column snippets for displaying blocks.
 * Most Gpanels are stacked, meaning they have top and bottom regions
 * by default, however you do not need to use them. You should always
 * use all the horizonal regions or you might experience layout issues.
 *
 * How to use:
 * 1. Copy and paste the code snippet into your page.tpl.php file.
 * 2. Copy and paste the region definitions to your themes .info file.
 * 3. Clear the cache (in Performance settings) to refresh the theme registry.

Region Deinitions:

; 2 col 2x50
regions[two_50_top]    = 2x50 Gpanel top
regions[two_50_first]  = 2x50 Gpanel left
regions[two_50_second] = 2x50 Gpanel right
regions[two_50_bottom] = 2x50 Gpanel bottom

 */
?>
<!-- Two column 2x50 -->
<?php if (
  $page['two_50_top'] ||
  $page['two_50_first'] || 
  $page['two_50_second'] ||
  $page['two_50_bottom']
  ): ?>
  <div class="two-50 at-panel gpanel clearfix">
    <?php print render($page['two_50_top']); ?>
    <?php print render($page['two_50_first']); ?>
    <?php print render($page['two_50_second']); ?>
    <?php print render($page['two_50_bottom']); ?>
  </div>
<?php endif; ?>
