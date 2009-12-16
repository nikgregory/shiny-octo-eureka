<?php // $Id$
// adaptivethemes.com

/**
 * @file block-at_admin.tpl.php
 * Theme implementation to display a block in the blocks section.
 *
 * Available variables:
 * - $block->subject: Block title.
 * - $block->content: Block content.
 * - $block->module: Module that generated the block.
 * - $block->delta: This is a numeric id connected to each module.
 * - $block->region: The block region embedding the current block.
 *
 * Helper variables:
 * - $block_module_delta: Outputs a unique css id for each block.
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
<div class="block-at-admin <?php print $skinr; ?>"><div class="block-at-admin-inner">
  <div class="block-header">
    <h2 class="block-title clearfix">
      <span><a href="#" class="toggle-block" id="<?php print $block->module .'-'. $block->delta;?>">Toggle</a></span>
      <?php print $block->subject ? $block->subject : t('No title set'); ?>
    </h2>
  </div>
  <div class="block-at-admin-content content clearfix toggle-<?php print $block->module .'-'. $block->delta; ?>">
    <fieldset class="collapsible collapsed fieldset-block-info">
      <legend class="collapse">
  	    <a href="#"><?php print t('Block Info'); ?></a>
      </legend>
      <dl>
        <dt><?php print t('Block Module'); ?></dt>
          <dd><?php print $block->module; ?></dd>
        <dt><?php print t('Block Delta'); ?></dt>
          <dd><?php print $block->delta; ?></dd>
        <dt><?php print t('Block Region ID'); ?></dt>
          <dd><?php print '#'. safe_string($block->region); ?></dd>
        <dt><?php print t('Block ID'); ?></dt>
          <dd>
            <?php 
              if (!empty($block_module_delta)) {
                print '#'. $block_module_delta;
              } 
              else {
                print t('Block ID\'s are not enabled. <br /><a href="!link">Enable Block ID\'s</a>.', array('!link' => url('admin/build/themes/settings/'. $GLOBALS['theme'])));
              } 
            ?>
          </dd>
        <dt><?php print t('Block Classes'); ?></dt>
          <dd>
            <?php print '.'. $block_at_admin_classes; ?><br />
            <?php print t('<a href="!link">Add or remove block classes</a>.', array('!link' => url('admin/build/themes/settings/'. $GLOBALS['theme']))); ?>
          </dd>
      </dl>
    </fieldset>
    <fieldset class="collapsible collapsed fieldset-block-content">
      <legend class="collapse">
  	    <a href="#"><?php print t('Block Content'); ?></a>
      </legend>
      <div class="block-content"><?php print $block->content ?></div>
    </fieldset>
    <?php if ($edit_links): ?>
      <div class="at-admin-block-edit-links">
  	    <?php print $edit_links; ?>
      </div>
    <?php endif; ?>
  </div>
</div></div> <!-- /block-at_admin -->
