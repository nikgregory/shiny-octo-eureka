<?php // $Id$
// adaptivethemes.com

/**
 * @file template.block-editing.inc
 */
/**
 * Add block editing variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function phptemplate_preprocess_block_editing(&$vars, $hook) {
  $block = $vars['block'];
  /**
   * Add block edit links. Credit to the Zen theme for this implimentation. The only
   * real difference is that the Zen theme wraps each link in span, whereas here we 
   * output the links as an item-list. Also I have omitted the Views links as these 
   * seem redundant because Views has its own set of hover links.
   */
    if ($block->module == 'block') {
      $edit_links[] = l(t('Edit Block'), 'admin/build/block/configure/'. $block->module .'/'. $block->delta, 
        array(
          'attributes' => array(
            'class' => 'block-edit',
          ),
          'query' => drupal_get_destination(),
          'html' => TRUE,
        )
      );
    }
    // Display 'Configure' for other blocks.
    else {
      $edit_links[] = l(t('Configure'), 'admin/build/block/configure/'. $block->module .'/'. $block->delta,
        array(
          'attributes' => array(
            'class' => 'block-edit',
          ),
          'query' => drupal_get_destination(),
          'html' => TRUE,
        )
      );
    }
    // Display 'Edit Menu' for menu blocks.
    if (($block->module == 'menu' || ($block->module == 'user' && $block->delta == 1)) && user_access('administer menu')) {
      $menu_name = ($block->module == 'user') ? 'navigation' : $block->delta;
      $edit_links[] = l( t('Edit Menu'), 'admin/build/menu-customize/'. $menu_name, 
        array(
          'attributes' => array(
            'class' => 'block-edit',
          ),
          'query' => drupal_get_destination(),
          'html' => TRUE,
        )
      );
    }
  // Theme links as an item list.
  $vars['edit_links_array'] = $edit_links;
  $vars['edit_links'] = '<div class="block-edit">'. theme('item_list', $edit_links) .'</div>';
}