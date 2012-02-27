<div class="at-panel panel-panel three-inset-left clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <div class="region region-three-inset-left-sidebar">
    <?php print $content['three_inset_left_sidebar']; ?>
  </div>

  <div class="inset-wrapper clearfix">

    <?php if ($content['three_inset_left_top']): ?>
      <div class="region region-three-inset-left-top region-conditional-stack">
        <?php print $content['three_inset_left_top']; ?>
      </div>
    <?php endif; ?>

    <div class="region region-three-inset-left-middle">
      <?php print $content['three_inset_left_middle']; ?>
    </div>

    <div class="region region-three-inset-left-inset">
      <?php print $content['three_inset_left_inset']; ?>
    </div>

    <?php if ($content['three_inset_left_bottom']): ?>
      <div class="region region-three-inset-left-bottom region-conditional-stack">
        <?php print $content['three_inset_left_bottom']; ?>
      </div>
    <?php endif; ?>

  </div>

</div>
