<div class="at-panel panel-display three-25-50-25 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['three_25_50_25_top']): ?>
    <div class="region region-three-25-50-25-top region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_25_50_25_top']; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="region region-three-25-50-25-first">
    <div class="region-inner clearfix">
      <?php print $content['three_25_50_25_first']; ?>
    </div>
  </div>
  <div class="region region-three-25-50-25-second">
    <div class="region-inner clearfix">
      <?php print $content['three_25_50_25_second']; ?>
    </div>
  </div>
  <div class="region region-three-25-50-25-third">
    <div class="region-inner clearfix">
      <?php print $content['three_25_50_25_third']; ?>
    </div>
  </div>
  <?php if ($content['three_25_50_25_bottom']): ?>
    <div class="region region-three-25-50-25-bottom region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_25_50_25_bottom']; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
