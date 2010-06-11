<?php // $Id$
// adaptivethemes.com

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

  <?php if (!$in_overlay): //hide in overlay ?>

    <?php if ($page['leaderboard']): ?>
      <div id="leaderboard" class="clearfix"><?php print render($page['leaderboard']); ?></div> <!-- /leaderboard -->
    <?php endif; ?>

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

      <?php if ($page['header']): ?>
        <div id="header-region"><?php print render($page['header']); ?></div> <!-- /header region -->
      <?php endif; ?>

    </div> <!-- /header -->

    <?php if ($page['menu_bar']): ?>
      <div id="menu-bar"><?php print render($page['menu_bar']); ?></div> <!-- /menu bar -->
    <?php endif; ?>

    <?php if (!empty($main_menu_links)): ?>
      <div id="main-menu" class="nav"><?php print $main_menu_links; ?></div> <!-- /main menu -->
    <?php endif; ?>

  <?php endif; // end hide in overaly ?>

    <?php if ($breadcrumb): ?>
      <div id="breadcrumb"><?php print $breadcrumb; ?></div> <!-- /breadcrumb -->
    <?php endif; ?>

    <?php if ($messages || $page['help']): ?>
      <div id="messages-and-help">
        <?php print $messages; ?>
        <?php print render($page['help']); ?>
      </div> <!-- /messages/help -->
    <?php endif; ?>

    <?php if (!$in_overlay): //hide in overlay ?>
      <?php if ($page['secondary_content']): ?>
        <div id="secondary-content"><?php print render($page['secondary_content']); ?></div> <!-- /secondary-content -->
      <?php endif; ?>
    <?php endif; // end hide in overaly ?>

    <div id="columns"><div class="columns-inner clearfix">
      <div id="content-column"><div class="content-inner">

        <?php if ($page['highlight']): ?>
          <div id="highlight"><?php print render($page['highlight']); ?></div> <!-- /highlight -->
        <?php endif; ?>

        <div id="main-content">

          <?php print render($title_prefix); ?>
          <?php if ($title): ?>
            <h1 id="page-title"><?php print $title; ?></h1> <!-- /page title -->
          <?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php if ($tabs): ?>
            <div class="local-tasks"><?php print render($tabs); ?></div> <!-- /local task tabs -->
          <?php endif; ?>
          <?php if ($action_links): ?>
            <ul class="action-links"><?php print render($action_links); ?></ul> <!-- /action links -->
          <?php endif; ?>
          <?php if ($page['content_aside']): ?>
            <div id="content-aside"><?php print render($page['content_aside']); ?></div> <!-- /content-aside -->
          <?php endif; ?>
          <?php print render($page['content']); ?> <!-- /content -->
        </div> <!-- /main-content -->

        <?php if ($page['content_bottom']): ?>
          <div id="content-bottom"><?php print render($page['content_bottom']); ?></div> <!-- /content-bottom -->
        <?php endif; ?>

      </div></div> <!-- /content-column -->

      <?php if ($page['sidebar_first']): ?>
        <div id="sidebar-first" class="sidebar"><?php print render($page['sidebar_first']); ?></div>
      <?php endif; ?>

      <?php if ($page['sidebar_second']): ?>
        <div id="sidebar-second" class="sidebar"><?php print render($page['sidebar_second']); ?></div>
      <?php endif; ?>

    </div></div> <!-- /columns -->

  <?php if (!$in_overlay): //hide in overlay ?>

    <?php if ($page['tertiary_content']): ?>
      <div id="tertiary-content"><?php print render($page['tertiary_content']); ?></div> <!-- /tertiary-content -->
    <?php endif; ?>

    <?php if ($page['footer'] || $secondary_menu || $feed_icons): ?>
      <div id="footer">

        <?php if ($page['footer']): ?>
          <div id="footer-region"><?php print render($page['footer']); ?></div> <!-- /footer region -->
        <?php endif; ?>

        <?php if (!empty($secondary_menu_links)): ?>
          <div id="secondary" class="nav"><?php print $secondary_menu_links; ?></div> <!-- /secondary menu links -->
        <?php endif; ?>

        <?php if ($feed_icons): ?>
          <div id="feed-icons"><?php print $feed_icons; ?></div> <!-- /feed icons -->
        <?php endif; ?>

      </div> <!-- /footer -->
    <?php endif; ?>

  <?php endif; // end hide in overaly ?>
