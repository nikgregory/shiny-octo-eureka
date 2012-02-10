<div class="at-panel three-50-25-25 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['three_50_25_25_top']): ?>
    <div class="region region-three-50-25-25-top">
      <?php print $content['three_50_25_25_top']; ?>
    </div>
  <?php endif; ?>

  <div class="region region-three-50-25-25-first">
    <?php print $content['three_50_25_25_first']; ?>
  </div>

  <div class="region region-three-50-25-25-second">
    <?php print $content['three_50_25_25_second']; ?>
  </div>

  <div class="region region-three-50-25-25-third">
    <?php print $content['three_50_25_25_third']; ?>
  </div>

  <?php if ($content['three_50_25_25_bottom']): ?>
    <div class="region region-three-50-25-25-bottom">
      <?php print $content['three_50_25_25_bottom']; ?>
    </div>
  <?php endif; ?>

</div>