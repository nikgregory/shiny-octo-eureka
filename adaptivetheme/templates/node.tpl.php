<?php
// $Id$
?>
<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if ($title || $display_submitted): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if (!$page && $title): ?>
        <h2<?php print $title_attributes; ?>>
          <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
        </h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $user_picture; ?>
      <?php if ($display_submitted): ?>
        <div class="submitted">
          <?php
            print t('Submitted by !username on !datetime',
            array('!username' => '<span class="author">' . $name . '</span>', '!datetime' => '<time datetime="' . $datetime . '">' . $date . '</time>'));
          ?>
        </div>
      <?php endif; ?>
    </header>
  <?php endif; ?>
  <div<?php print $content_attributes; ?>>
  <?php
    hide($content['comments']);
    hide($content['links']);
    print render($content);
  ?>
  </div>
  <?php if (!empty($content['links'])): ?>
    <footer>
      <?php print render($content['links']); ?>
    </footer>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</article>
