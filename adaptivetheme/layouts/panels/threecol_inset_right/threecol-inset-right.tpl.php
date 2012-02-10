<div class="at-panel threecol-inset-right clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <div class="region region-threecol-inset-right-sidebar">
    <?php print $content['threecol_inset_right_sidebar']; ?>
  </div>

  <div class="inset-wrapper clearfix">

    <?php if ($content['threecol_inset_right_top']): ?>
      <div class="region region-threecol-inset-right-top">
        <?php print $content['threecol_inset_right_top']; ?>
      </div>
    <?php endif; ?>

    <div class="region region-threecol-inset-right-middle">
      <?php print $content['threecol_inset_right_middle']; ?>
    </div>

    <div class="region region-threecol-inset-right-inset">
      <?php print $content['threecol_inset_right_inset']; ?>
    </div>

    <?php if ($content['threecol_inset_right_bottom']): ?>
      <div class="region region-threecol-inset-right-bottom">
        <?php print $content['threecol_inset_right_bottom']; ?>
      </div>
    <?php endif; ?>

  </div>

</div>