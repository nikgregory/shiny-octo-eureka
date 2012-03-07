<div class="at-panel panel-display three-inset-right clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>
  <div class="region region-three-inset-right-sidebar">
    <div class="region-inner clearfix">
      <?php print $content['three_inset_right_sidebar']; ?>
    </div>
  </div>
  <div class="inset-wrapper clearfix">
    <?php if ($content['three_inset_right_top']): ?>
      <div class="region region-three-inset-right-top region-conditional-stack">
        <div class="region-inner clearfix">
          <?php print $content['three_inset_right_top']; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="region region-three-inset-right-middle">
      <div class="region-inner clearfix">
        <?php print $content['three_inset_right_middle']; ?>
      </div>
    </div>
    <div class="region region-three-inset-right-inset">
      <div class="region-inner clearfix">
        <?php print $content['three_inset_right_inset']; ?>
      </div>
    </div>
    <?php if ($content['three_inset_right_bottom']): ?>
      <div class="region region-three-inset-right-bottom region-conditional-stack">
        <div class="region-inner clearfix">
          <?php print $content['three_inset_right_bottom']; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
