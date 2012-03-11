<div class="at-panel panel-display three-25-25-50 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['three_25_25_50_top']): ?>
    <div class="region region-three-25-25-50-top region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_25_25_50_top']; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="region region-three-25-25-50-first">
    <div class="region-inner clearfix">
      <?php print $content['three_25_25_50_first']; ?>
    </div>
  </div>
  <div class="region region-three-25-25-50-second">
    <div class="region-inner clearfix">
      <?php print $content['three_25_25_50_second']; ?>
    </div>
  </div>
  <div class="region region-three-25-25-50-third">
    <div class="region-inner clearfix">
      <?php print $content['three_25_25_50_third']; ?>
    </div>
  </div>
  <?php if ($content['three_25_25_50_bottom']): ?>
    <div class="region region-three-25-25-50-bottom region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_25_25_50_bottom']; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
