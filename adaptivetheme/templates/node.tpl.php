<?php
// $Id$
?>
<article id="article-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php if ($title && !$page): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1<?php print $title_attributes; ?>>
          <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
        </h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
    </header>
  <?php endif; ?>

  <?php print $user_picture; ?>

  <?php if ($display_submitted): ?>
    <footer>
      <?php
        print t('Submitted by !username on !datetime',
        array('!username' => $name, '!datetime' => '<time datetime="' . $datetime . '">' . $date . '</time>'));
      ?>
    </footer>
  <?php endif; ?>

  <div<?php print $content_attributes; ?>>
  <?php
    hide($content['comments']);
    hide($content['links']);
    print render($content);
  ?>
  </div>

  <?php if (!empty($content['links'])): ?>
    <nav><?php print render($content['links']); ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>
