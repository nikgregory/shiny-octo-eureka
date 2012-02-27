<div class="at-panel panel-panel three-inset-right clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <div class="region region-three-inset-right-sidebar">
    <?php print $content['three_inset_right_sidebar']; ?>
  </div>

  <div class="inset-wrapper clearfix">

    <?php if ($content['three_inset_right_top']): ?>
      <div class="region region-three-inset-right-top region-conditional-stack">
        <?php print $content['three_inset_right_top']; ?>
      </div>
    <?php endif; ?>

    <div class="region region-three-inset-right-middle">
      <?php print $content['three_inset_right_middle']; ?>
    </div>

    <div class="region region-three-inset-right-inset">
      <?php print $content['three_inset_right_inset']; ?>
    </div>

    <?php if ($content['three_inset_right_bottom']): ?>
      <div class="region region-three-inset-right-bottom region-conditional-stack">
        <?php print $content['three_inset_right_bottom']; ?>
      </div>
    <?php endif; ?>

  </div>

</div>