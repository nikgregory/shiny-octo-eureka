<div class="at-panel twocol-brick clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <div class="region region-twocol-brick-top">
    <?php print $content['twocol_brick_top']; ?>
  </div>

  <div class="brick-wrapper clearfix">
    <div class="region region-twocol-brick-left-above">
      <?php print $content['twocol_brick_left_above']; ?>
    </div>

    <div class="region region-twocol-brick-right-above">
      <?php print $content['twocol_brick_right_above']; ?>
    </div>
  </div>

  <div class="region region-twocol-brick-middle">
    <?php print $content['twocol_brick_middle']; ?>
  </div>

  <div class="brick-wrapper clearfix">
    <div class="region region-twocol-brick-left-below">
      <?php print $content['twocol_brick_left_below']; ?>
    </div>

    <div class="region region-twocol-brick-right-below">
      <?php print $content['twocol_brick_right_below']; ?>
    </div>
  </div>

  <div class="region region-twocol-brick-bottom">
    <?php print $content['twocol_brick_bottom']; ?>
  </div>

</div>
