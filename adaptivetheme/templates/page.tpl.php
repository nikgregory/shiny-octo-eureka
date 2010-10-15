<?php
// $Id$
?>
<div id="page" class="container">
  <?php if (!$in_overlay): ?>
    <?php print render($page['leaderboard']); ?>
    <header id="banner" class="clearfix">
      <?php if ($linked_site_logo || $linked_site_name || $site_slogan): ?>
        <div id="branding">
          <?php if ($linked_site_logo): ?><div id="logo"><?php print $linked_site_logo; ?></div><?php endif; ?>
          <?php if ($title): ?>
            <div id="brand-elements">
              <?php if ($linked_site_name): ?><div id="site-name"><strong><?php print $linked_site_name; ?></strong></div><?php endif; ?>
              <?php if ($site_slogan): ?><div id="site-slogan"><?php print $site_slogan; ?></div><?php endif; ?>
            </div>
          <?php else: ?>
            <hgroup id="brand-elements">
              <?php if ($linked_site_name): ?><h1 id="site-name"><?php print $linked_site_name; ?></h1><?php endif; ?>
              <?php if ($site_slogan): ?><h2 id="site-slogan"><?php print $site_slogan; ?></h2><?php endif; ?>
            </hgroup>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php print render($page['header']); ?>
    </header>
    <?php print render($page['menu_bar']); ?>
    <?php if ($primary_navigation): print $primary_navigation; endif; ?>
    <?php if ($secondary_navigation): print $secondary_navigation; endif; ?>
  <?php endif; ?>
  <?php if ($breadcrumb): ?>
    <section id="breadcrumb"><?php print $breadcrumb; ?></section>
  <?php endif; ?>
  <?php print $messages; ?>
  <?php print render($page['help']); ?>
  <?php if (!$in_overlay): ?>
    <?php print render($page['secondary_content']); ?>
  <?php endif; ?>
  <div id="columns"><div class="columns-inner clearfix">
    <div id="content-column"><div class="content-inner">
      <?php print render($page['highlight']); ?>
      <div id="main-content">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 id="page-title"><?php print $title; ?></h1><?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if ($primary_local_tasks || $secondary_local_tasks || $action_links): ?>
          <div id="tasks">
            <?php if ($primary_local_tasks): ?><ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul><?php endif; ?> <!-- /primary local tasks -->
            <?php if ($secondary_local_tasks): ?><ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul><?php endif; ?> <!-- /secondary local tasks -->
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?> <!-- /action links -->
          </div>
        <?php endif; ?>
        <div id="content">
          <?php print render($page['content']); ?>
        </div>
        <?php print $feed_icons; ?>
      </div>
      <?php print render($page['content_aside']); ?>
    </div></div>
    <?php print render($page['sidebar_first']); ?>
    <?php print render($page['sidebar_second']); ?>
  </div></div>
  <?php if (!$in_overlay): ?>
    <?php print render($page['tertiary_content']); ?>
    <?php if ($page['footer']): ?>
      <footer id="page-footer">
        <?php print render($page['footer']); ?>
      </footer>
    <?php endif; ?>
  <?php endif; ?>
</div>
