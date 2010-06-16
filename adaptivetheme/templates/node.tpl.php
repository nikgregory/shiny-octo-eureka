<?php
// $Id$

/**
 * @file node.tpl.php
 */
?>
<div<?php print $article_id ? ' id="' . $article_id . '"' : ''; ?> class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if (!$page && $title): ?>
    <?php print render($title_prefix); ?>
    <h2<?php print $title_attributes; ?>>
      <a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a>
    </h2>
    <?php print render($title_suffix); ?>
  <?php endif; ?>

  <?php print $user_picture; ?>

  <?php if ($display_submitted): ?>
    <p class="article-submitted">
      <?php
        print t('Submitted by !username on !datetime',
        array('!username' => $name, '!datetime' => $date));
      ?>
    </p>
  <?php endif; ?>

  <?php
    // Hide comments and links and render them later.
    hide($content['comments']);
    hide($content['links']);
    print render($content);
  ?>

  <?php
    // Remove the "Add new comment" link on the teaser page or if the comment
    // form is being displayed on the same page.
    if ($teaser || !empty($content['comments']['comment_form'])) {
      unset($content['links']['comment']['#links']['comment-add']);
    }
    $links = render($content['links']);
    if ($links) {
      print $links;
    }
  ?>

  <?php if ($page && $page['article_aside']): ?>
    <?php print render($page['article_aside']) ?>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</div> <!-- /article -->
