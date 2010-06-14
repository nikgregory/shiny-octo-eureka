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
    <div class="article-submitted">
      <?php
        print t('Submitted by !username on !datetime',
        array('!username' => $name, '!datetime' => $date));
      ?>
    </div>
  <?php endif; ?>

  <div class="article-content"<?php print $content_attributes; ?>>
    <?php
      // Hide comments and links and render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <?php
    // Remove the "Add new comment" link on the teaser page or if the comment
    // form is being displayed on the same page.
    if ($teaser || !empty($content['comments']['comment_form'])) {
      unset($content['links']['comment']['#links']['comment-add']);
    }
    // Only display the wrapper div if there are links.
    $links = render($content['links']);
    if ($links):
  ?>
    <div class="article-links">
      <?php print $links; ?>
    </div>
  <?php endif; ?>

  <?php if ($page['article_aside'] && !$teaser): ?>
    <div id="article-aside" class="aside"><?php print render($page['article_aside']) ?></div>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</div> <!-- /article -->
