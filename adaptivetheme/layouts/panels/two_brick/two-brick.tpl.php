<div class="at-panel panel-panel two-brick clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <?php if ($content['two_brick_top']): ?>
    <div class="region region-two-brick-top region-conditional-stack">
      <?php print $content['two_brick_top']; ?>
    </div>
  <?php endif; ?>

  <div class="brick-wrapper clearfix">
    <div class="region region-two-brick-left-above">
      <?php print $content['two_brick_left_above']; ?>
    </div>

    <div class="region region-two-brick-right-above">
      <?php print $content['two_brick_right_above']; ?>
    </div>
  </div>

  <?php if ($content['two_brick_middle']): ?>
    <div class="region region-two-brick-middle region-conditional-stack">
      <?php print $content['two_brick_middle']; ?>
    </div>
  <?php endif; ?>

  <div class="brick-wrapper clearfix">
    <div class="region region-two-brick-left-below">
      <?php print $content['two_brick_left_below']; ?>
    </div>

    <div class="region region-two-brick-right-below">
      <?php print $content['two_brick_right_below']; ?>
    </div>
  </div>
  
  <?php if ($content['two_brick_bottom']): ?>
    <div class="region region-two-brick-bottom region-conditional-stack">
      <?php print $content['two_brick_bottom']; ?>
    </div>
  <?php endif; ?>

</div>
