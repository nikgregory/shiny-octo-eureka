/**
 * @file
 * Responsive navigation list_items. This is pretty much a ripoff
 * of the Seven themes responsive tabs JS, except I have generalized
 * it so we can use it for any list. In AT we use this for both
 * tabs (local tasks) and breadcrumbs. See the breadcrumbs template
 * and SCSS partial to see how this works.
 */
(function ($, Drupal) {

  'use strict';

  function init(i, list_item) {

    var $list_item = $(list_item);

    function handleResize(e) {
      $list_item.addClass('is-horizontal');
      var $list_items = $list_item.find('.is-responsive__list');
      var isHorizontal = $list_items.outerHeight() <= $list_items.find('.is-responsive__item').outerHeight();
      if (isHorizontal) {
        $list_item.removeClass('is-vertical');
      } else {
        $list_item.removeClass('is-horizontal').addClass('is-vertical');
      }
    }

    $(window).on('resize.list_item', Drupal.debounce(handleResize, 150)).trigger('resize.list_item');
  }

  Drupal.behaviors.atRL = {
    attach: function (context, settings) {
      var $list_items = $(context).find('[data-at-responsive-list]');
      if ($list_items.length) {
        $list_items.once().each(init);
      }
    }
  };
})(jQuery, Drupal);
