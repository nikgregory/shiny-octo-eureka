<?php
// $Id$

/**
* @file maintenance-page.tpl.php
*
* Theme implementation to display a single Drupal page while off-line.
*
* Adaptivetheme maintenance page does not include sidebars by default, nor
* does it print messages, tabs or anything else that typically you would
* not see on a maintenance page. If you require any of these additional variables
* you will need to add them. Its designed to be lightweight and fast.
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <div id="container">
    <div id="header" class="clearfix">
      <?php if ($logo or $site_name or $site_slogan): ?>
        <div id="branding">
          <?php if ($logo or $site_name): ?>
            <div class="logo-site-name"><strong>
              <?php if (!empty($logo)): ?>
                <span id="logo">
                  <a href="<?php print $base_path; ?>" title="<?php print t('Home page'); ?>" rel="home">
                    <img src="<?php print $logo; ?>" alt="<?php print t('Home page'); ?>" />
                  </a>
                </span>
              <?php endif; ?>
              <?php if (!empty($site_name)): ?>
                <span id="site-name">
                  <a href="<?php print $base_path ?>" title="<?php print t('Home page'); ?>" rel="home">
                    <?php print $site_name; ?>
                  </a>
                </span>
              <?php endif; ?>
            </strong></div> <!-- /logo and site name -->
            <?php if ($site_slogan): ?>
              <div id="site-slogan"><?php print $site_slogan; ?></div>
            <?php endif; ?> <!-- /slogan -->
          <?php endif; ?>
        </div> <!-- /branding -->
      <?php endif; ?>
    </div> <!-- /header -->
    <div id="main-content">
      <?php if ($title): ?><h1 id="page-title"><?php print $title; ?></h1><?php endif; ?>
      <div id="content"><?php print $content; ?></div>
    </div> <!-- /main-content -->
  </div> <!-- /container -->
  <?php print $page_bottom ?>
</body>
</html>