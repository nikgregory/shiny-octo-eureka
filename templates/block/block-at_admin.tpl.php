<?php // $Id$
// adaptivethemes.com

/**
 * @file block.tpl.php
 * Theme implementation to display a block.
 *
 * Available variables:
 * - $block->subject: Block title.
 * - $block->content: Block content.
 * - $block->module: Module that generated the block.
 * - $block->delta: This is a numeric id connected to each module.
 * - $block->region: The block region embedding the current block.
 *
 * Helper variables:
 * - $block_id: Outputs a unique id for each block.
 * - $classes: Outputs dynamic classes for advanced themeing.
 * - $edit_links: Outputs hover style links for block configuration and editing.
 * - $block_zebra: Outputs 'odd' and 'even' dependent on each block region.
 * - $zebra: Same output as $block_zebra but independent of any block region.
 * - $block_id: Counter dependent on each block region.
 * - $id: Same output as $block_id but independent of any block region.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_block()
 * @see genesis_preprocess_block()
 */

/**
 * Block Edit Links
 * To disable block edit links remove or comment out the $edit_links variable 
 * then unset the block-edit.css in your subhtemes .info file.
 */
?>
<div id="<?php print $block_id; ?>" class="block-at-admin">

  <?php if ($block->subject): ?>
    <div class="block-header">
      <h2 class="block-title"><?php print $block->subject; ?></h2>
    </div>
  <?php endif; ?>

  <div class="block-inner content clear-block">
    <fieldset class="collapsible collapsed fieldset-block-info">
      <legend class="collapse">
  	    <a href="#"><?php print t('Block Info'); ?></a>
      </legend>
      <div class="fieldset-wrapper">
        <dl>
          <dt>Block Module</dt>
            <dd><?php print $block->module; ?></dd>
          <dt>Block Delta</dt>
            <dd><?php print $block->delta; ?></dd>
          <dt>Block Region</dt>
            <dd><?php print $block->region; ?></dd>
          <dt>Block ID</dt>
            <dd><?php print $block_id; ?></dd>
          <dt>Block Classes</dt>
            <dd><?php print $classes; ?></dd>
        </dl>
      </div>
    </fieldset>

    <fieldset class="collapsible collapsed fieldset-block-content">
      <legend class="collapse">
  	    <a href="#"><?php print t('Block Content'); ?></a>
      </legend>
      <div class="fieldset-wrapper">
        <div class="block-content"><?php print $block->content ?></div>
      </div>
    </fieldset>

    <?php if ($edit_links): ?>
      <div class="at-admin-block-edit-links">
  	    <?php print $edit_links; ?>
      </div>
    <?php endif; ?>

  </div>
</div> <!-- /block-at_admin -->