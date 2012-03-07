<div class="at-panel panel-display two-66-33 clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <?php if ($content['two_66_33_top']): ?>
    <div class="region region-two-66-33-top region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['two_66_33_top']; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="region region-two-66-33-first">
    <div class="region-inner clearfix">
      <?php print $content['two_66_33_first']; ?>
    </div>
  </div>
  <div class="region region-two-66-33-second">
    <div class="region-inner clearfix">
      <?php print $content['two_66_33_second']; ?>
    </div>
  </div>
  <?php if ($content['two_66_33_bottom']): ?>
    <div class="region region-two-66-33-bottom region-conditional-stack">
      <div class="region-inner clearfix">
        <?php print $content['two_66_33_bottom']; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
