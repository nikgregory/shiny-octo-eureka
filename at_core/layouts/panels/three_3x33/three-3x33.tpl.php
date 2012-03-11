<div class="at-panel panel-display three-3x33 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['three_33_top']): ?>
    <div class="region region-three-33-top region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_33_top']; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="region region-three-33-first">
    <div class="region-inner clearfix">
      <?php print $content['three_33_first']; ?>
    </div>
  </div>
  <div class="region region-three-33-second">
    <div class="region-inner clearfix">
      <?php print $content['three_33_second']; ?>
    </div>
  </div>
  <div class="region region-three-33-third">
    <div class="region-inner clearfix">
      <?php print $content['three_33_third']; ?>
    </div>
  </div>
  <?php if ($content['three_33_bottom']): ?>
    <div class="region region-three-33-bottom region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['three_33_bottom']; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
