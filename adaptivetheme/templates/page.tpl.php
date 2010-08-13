<?php
// $Id$

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 * - $in_overlay: TRUE if the page is in the overlay.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlight']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>
<div id="page">

  <?php if (!$in_overlay): //hide in overlay ?>

    <?php print render($page['leaderboard']); ?> <!-- /leaderboard -->

    <div id="header" class="clearfix">

      <?php if ($linked_site_logo || $linked_site_name || $site_slogan): ?>
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

          <?php if ($site_slogan): ?>
            <div id="site-slogan"><?php print $site_slogan; ?></div> <!-- /slogan -->
          <?php endif; ?>

        </div> <!-- /branding -->
      <?php endif; ?>

      <?php print render($page['header']); ?> <!-- /header region -->

    </div> <!-- /header -->

    <?php print render($page['menu_bar']); ?> <!-- /menu bar -->
    <?php print $primary_navigation; ?> <!-- /main menu -->
    <?php print $secondary_navigation; ?> <!-- /secondary menu links -->

  <?php endif; // end hide in overaly ?>

  <?php print $breadcrumb; ?> <!-- /breadcrumb -->
  <?php print $messages; ?> <!-- /message -->
  <?php print render($page['help']); ?> <!-- /help -->

  <?php if (!$in_overlay): //hide in overlay ?>
    <?php print render($page['secondary_content']); ?> <!-- /secondary-content -->
  <?php endif; // end hide in overaly ?>

  <div id="columns"><div class="columns-inner clearfix">
    <div id="content-column"><div class="content-inner">

      <?php print render($page['highlight']); ?> <!-- /highlight -->

      <div id="main-content">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?><h1 id="page-title"><?php print $title; ?></h1><?php endif; ?> <!-- /page title -->
        <?php print render($title_suffix); ?>

        <?php if ($primary_local_tasks): ?><ul class="tabs primary"><?php print render($primary_local_tasks); ?></ul><?php endif; ?> <!-- /primary local tasks -->
        <?php if ($secondary_local_tasks): ?><ul class="tabs secondary"><?php print render($secondary_local_tasks); ?></ul><?php endif; ?> <!-- /secondary local tasks -->
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?> <!-- /action links -->

        <?php print render($page['content_aside']); ?> <!-- /content-aside -->
        <?php print render($page['content']); ?> <!-- /content -->
        <?php print $feed_icons; ?> <!-- /feed icons -->
      </div> <!-- /main-content -->

      <?php print render($page['content_bottom']); ?> <!-- /content-bottom -->

    </div></div> <!-- /content-column -->

    <?php print render($page['sidebar_first']); ?>
    <?php print render($page['sidebar_second']); ?>

  </div></div> <!-- /columns -->

  <?php if (!$in_overlay): //hide in overlay ?>

    <?php print render($page['tertiary_content']); ?> <!-- /tertiary-content -->

    <?php if ($page['footer']): ?>
      <div id="footer">
        <?php print render($page['footer']); ?> <!-- /footer region -->
      </div> <!-- /footer -->
    <?php endif; ?>

  <?php endif; // end hide in overaly ?>

</div> <!-- /page -->
