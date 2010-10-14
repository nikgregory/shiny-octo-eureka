<?php
// $Id$
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>" version="HTML+RDFa 1.1" <?php print $rdf_namespaces; ?>>
<head profile="<?php print $grddl_profile; ?>">
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if (!$in_overlay): ?>
    <div id="skip-nav" class="<?php print $skip_nav_class; ?>">
      <a href="#main-content"><?php print t('Skip to main content'); ?></a>
    </div>
  <?php endif; ?>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>