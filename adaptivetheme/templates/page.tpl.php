<?php
// $Id$
?>
<div id="page" class="container">
  <?php if (!$in_overlay): ?>
    <?php print render($page['leaderboard']); ?>
    <header id="banner" class="clearfix">
      <?php if ($linked_site_logo || $linked_site_name || $site_slogan): ?>
        <div id="branding">
          <?php if ($title): ?>
            <div class="brand-elements"><strong>
              <?php if ($linked_site_logo): ?><span id="logo"><?php print $linked_site_logo; ?></span><?php endif; ?>
              <?php if ($linked_site_name): ?><span id="site-name"><?php print $linked_site_name; ?></span><?php endif; ?>
              <?php if ($site_slogan): ?><span id="site-slogan"><?php print $site_slogan; ?></span><?php endif; ?>
            </strong></div>
          <?php else: ?>
            <h1 class="brand-elements">
              <?php if ($linked_site_logo): ?><span id="logo"><?php print $linked_site_logo; ?></span><?php endif; ?>
              <?php if ($linked_site_name): ?><span id="site-name"><?php print $linked_site_name; ?></span><?php endif; ?>
              <?php if ($site_slogan): ?><span id="site-slogan"><?php print $site_slogan; ?></span><?php endif; ?>
            </h1>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php print render($page['header']); ?>
    </header>
    <?php if ($page['menu_bar']): ?>
      <nav id="menu-bar"><?php print render($page['menu_bar']); ?></nav>
    <?php endif; ?>
    <?php if ($primary_navigation): ?>
      <nav id="primary-nav"><?php print $primary_navigation; ?></nav>
    <?php endif; ?>
    <?php if ($secondary_navigation): ?>
      <nav id="secondary-nav"><?php print $secondary_navigation; ?></nav>
    <?php endif; ?>
  <?php endif; ?>
  <?php if($breadcrumb): ?>
    <nav id="breadcrumb"><?php print $breadcrumb; ?></nav>
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
          <nav id="tasks">
            <?php if ($primary_local_tasks): ?><ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul><?php endif; ?> <!-- /primary local tasks -->
            <?php if ($secondary_local_tasks): ?><ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul><?php endif; ?> <!-- /secondary local tasks -->
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?> <!-- /action links -->
          </nav>
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
