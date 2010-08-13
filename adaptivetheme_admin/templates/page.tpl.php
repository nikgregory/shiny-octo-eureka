<?php
// $Id$

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 */
?>
<div id="page"><div id="wrapper">

  <?php if (!$in_overlay): //hide in overlay ?>

    <div id="header" class="clearfix">

      <?php if ($linked_site_logo || $linked_site_name): ?>
        <div id="branding">

          <?php if ($linked_site_logo or $linked_site_name): ?>
            <?php if ($title): ?>
              <div class="logo-site-name"><strong>
                <?php if ($linked_site_logo): ?><span id="logo"><?php print $linked_site_logo; ?></span><?php endif; ?>
                <?php if ($linked_site_name): ?><span id="site-name"><?php print $linked_site_name; ?></span><?php endif; ?>
              </strong></div> <!-- /logo/site name -->
            <?php else: /* Use h1 when the content title is empty */ ?>
              <h1 class="logo-site-name">
                <?php if ($linked_site_logo): ?><span id="logo"><?php print $linked_site_logo; ?></span><?php endif; ?>
                <?php if ($linked_site_name): ?><span id="site-name"><?php print $linked_site_name; ?></span><?php endif; ?>
              </h1> <!-- /logo/site name -->
            <?php endif; ?>
          <?php endif; ?>

        </div> <!-- /branding -->
      <?php endif; ?>

      <?php if ($time_date): ?>
        <div id="time-and-date"><?php print $time_date; ?></div> <!-- /time and date -->
      <?php endif; ?>

      <?php print $primary_navigation; ?> <!-- /primary navigation -->

    </div> <!-- /header -->

  <?php endif; // end hide in overaly ?>

    <?php print $breadcrumb; ?> <!-- /breadcrumb -->
    <?php print $messages; ?> <!-- /message -->
    <?php print render($page['help']); ?> <!-- /help -->

    <div id="columns"><div class="columns-inner clearfix">
      <div id="content-column"><div class="content-inner">

        <?php print render($page['highlight']); ?> <!-- /highlight -->

        <div id="main-content">
          <div id="main-content-header">
            <?php print render($title_prefix); ?>
            <?php if ($title): ?><h1 id="page-title"><?php print $title; ?></h1><?php endif; ?> <!-- /page title -->
            <?php print render($title_suffix); ?>
            <?php if ($primary_local_tasks): ?><ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul><?php endif; ?> <!-- /primary local tasks -->
            <?php if ($secondary_local_tasks): ?><ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul><?php endif; ?> <!-- /secondary local tasks -->
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?> <!-- /action links -->
          </div>
          <?php print render($page['content']); ?> <!-- /content -->
        </div> <!-- /main-content -->

      </div></div> <!-- /content-column -->

      <?php print render($page['sidebar_first']); ?>
      <?php print render($page['sidebar_second']); ?>

    </div></div> <!-- /columns -->

  <?php if (!$in_overlay): //hide in overlay ?>

    <?php if ($page['footer'] || $secondary_navigation || $feed_icons): ?>
      <div id="footer">
        <?php print render($page['footer']); ?> <!-- /footer region -->
        <?php print $secondary_navigation; ?> <!-- /secondary navigation -->
      </div> <!-- /footer -->
    <?php endif; ?>

  <?php endif; // end hide in overaly ?>

</div></div> <!-- /page/wrapper -->
