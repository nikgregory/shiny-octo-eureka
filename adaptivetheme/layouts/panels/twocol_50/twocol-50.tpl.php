<div class="at-panel two-50 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['twocol_50_top']): ?>
    <div class="region region-two-50-top">
      <?php print $content['twocol_50_top']; ?>
    </div>
  <?php endif; ?>

  <div class="region region-two-50-first">
    <?php print $content['twocol_50_first']; ?>
  </div>

  <div class="region region-two-50-second">
    <?php print $content['twocol_50_second']; ?>
  </div>

  <?php if ($content['twocol_50_bottom']): ?>
    <div class="region region-two-50-bottom">
      <?php print $content['twocol_50_bottom']; ?>
    </div>
  <?php endif; ?>

</div>
