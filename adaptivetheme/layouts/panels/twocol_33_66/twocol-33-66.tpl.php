<div class="at-panel two-33-66 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['two_33_66_top']): ?>
    <div class="region region-two-33-66-top">
      <?php print $content['two_33_66_top']; ?>
    </div>
  <?php endif; ?>

  <div class="region region-two-33-66-first">
    <?php print $content['two_33_66_first']; ?>
  </div>

  <div class="region region-two-33-66-second">
    <?php print $content['two_33_66_second']; ?>
  </div>

  <?php if ($content['two_33_66_bottom']): ?>
    <div class="region region-two-33-66-bottom">
      <?php print $content['two_33_66_bottom']; ?>
    </div>
  <?php endif; ?>

</div>
