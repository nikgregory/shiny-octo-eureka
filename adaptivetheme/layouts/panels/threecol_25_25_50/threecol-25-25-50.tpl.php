<div class="at-panel three-25-25-50 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['three_25_25_50_top']): ?>
    <div class="region region-three-25-25-50-top">
      <?php print $content['three_25_25_50_top']; ?>
    </div>
  <?php endif; ?>

  <div class="region region-three-25-25-50-first">
    <?php print $content['three_25_25_50_first']; ?>
  </div>

  <div class="region region-three-25-25-50-second">
    <?php print $content['three_25_25_50_second']; ?>
  </div>

  <div class="region region-three-25-25-50-third">
    <?php print $content['three_25_25_50_third']; ?>
  </div>

  <?php if ($content['three_25_25_50_bottom']): ?>
    <div class="region region-three-25-25-50-bottom">
      <?php print $content['three_25_25_50_bottom']; ?>
    </div>
  <?php endif; ?>

</div>
