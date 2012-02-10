<div class="at-panel three-3x33 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['three_33_top']): ?>
    <div class="region region-three-33-top conditional-stack-top">
      <?php print $content['three_33_top']; ?>
    </div>
  <?php endif; ?>

  <div class="region region-three-33-first">
    <?php print $content['three_33_first']; ?>
  </div>

  <div class="region region-three-33-second">
    <?php print $content['three_33_second']; ?>
  </div>

  <div class="region region-three-33-third">
    <?php print $content['three_33_third']; ?>
  </div>

  <?php if ($content['three_33_bottom']): ?>
    <div class="region region-three-33-bottom conditional-stack-bottom">
      <?php print $content['three_33_bottom']; ?>
    </div>
  <?php endif; ?>

</div>
