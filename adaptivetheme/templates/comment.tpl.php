<?php
// $Id$
?>
<article class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <header>
    <?php print render($title_prefix); ?>
    <?php if ($title): ?>
      <h3<?php print $title_attributes; ?>><?php print $title ?></h3>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if ($new): ?>
      <em class="new"><?php print $new ?></em>
    <?php endif; ?>
    <?php print $picture; ?>
    <p class="submitted">
      <?php
        print t('Submitted by !username on !datetime',
        array('!username' => '<span class="author">' . $author . '</span>', '!datetime' => '<time datetime="' . $datetime . '">' . $created . '</time>'));
      ?>
    </p>
  </header>
  <div<?php print $content_attributes; ?>>
    <?php
      hide($content['links']);
      print render($content);
    ?>
  </div>
  <?php if ($signature): ?>
    <div class="user-signature"><?php print $signature ?></div>
  <?php endif; ?>
  <?php if (!empty($content['links'])): ?>
    <footer>
      <?php print render($content['links']) ?>
    </footer>
  <?php endif; ?>
</article>
