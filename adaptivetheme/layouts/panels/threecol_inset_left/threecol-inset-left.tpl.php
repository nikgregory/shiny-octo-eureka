<div class="at-panel threecol-inset-left clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <div class="region region-threecol-inset-left-sidebar">
    <?php print $content['threecol_inset_left_sidebar']; ?>
  </div>

  <div class="inset-wrapper clearfix">

    <?php if ($content['threecol_inset_left_top']): ?>
      <div class="region region-threecol-inset-left-top">
        <?php print $content['threecol_inset_left_top']; ?>
      </div>
    <?php endif; ?>

    <div class="region region-threecol-inset-left-middle">
      <?php print $content['threecol_inset_left_middle']; ?>
    </div>

    <div class="region region-threecol-inset-left-inset">
      <?php print $content['threecol_inset_left_inset']; ?>
    </div>

    <?php if ($content['threecol_inset_left_bottom']): ?>
      <div class="region region-threecol-inset-left-bottom">
        <?php print $content['threecol_inset_left_bottom']; ?>
      </div>
    <?php endif; ?>

  </div>

</div>
