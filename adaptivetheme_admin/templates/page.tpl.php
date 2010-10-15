<?php
// $Id$
?>
<div id="page" class="container">
  <?php if (!$in_overlay): // hide in overlay ?>
    <header id="banner" class="clearfix">
      <section id="breadcrumb"><?php print $breadcrumb; ?></section>
      <?php if ($time_date): ?>
        <div id="time-and-date"><time datetime="<?php print $datetime; ?>"><?php print $time_date; ?></time></div>
      <?php endif; ?>
    </header>
  <?php endif; // end hide in overlay ?>

  <div id="main-content-header">
    <?php if ($in_overlay): print $breadcrumb; endif; ?>
    <?php if (!$in_overlay): // hide in overlay ?>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?><h1 id="page-title"><?php print $title; ?></h1><?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php if ($primary_local_tasks): ?>
        <div id="primary-tasks"><ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul></div>
      <?php endif; ?>
    <?php endif; // end hide in overlay ?>
  </div>

  <?php print render($page['help']); ?>

  <div id="columns"><div class="columns-inner clearfix">
    <div id="content-column"><div class="content-inner">
      <?php if ($secondary_local_tasks): ?>
        <div id="secondary-tasks"><ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul></div>
      <?php endif; ?>
      <?php print $messages; ?>
      <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
      <?php print render($page['highlight']); ?>
      <div id="main-content">
        <?php print render($page['content']); ?>
      </div>
    </div></div>
    <?php print render($page['sidebar_first']); ?>
    <?php print render($page['sidebar_second']); ?>
  </div></div>

  <?php if (!$in_overlay): // hide in overlay ?>
    <?php if ($page['footer']): ?>
      <footer id="page-footer">
        <?php print render($page['footer']); ?>
      </footer>
    <?php endif; ?>
  <?php endif; // end hide in overlay ?>

</div> <!-- /page -->
