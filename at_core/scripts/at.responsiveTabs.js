/**
 * @file
 * Responsive navigation tabs. This is pretty much a ripoff
 * of the Seven themes implimentation, although we don't use
 * a button so it's much simplified. Cheers Lewis and you
 * Seven theme cats :)
 */
(function ($, Drupal) {

  'use strict';

  function init(i, tab) {
    var $tab = $(tab);

    function handleResize(e) {
      $tab.addClass('is-horizontal');
      var $tabs = $tab.find('.tabs');
      var isHorizontal = $tabs.outerHeight() <= $tabs.find('.tabs__tab').outerHeight();
      if (!isHorizontal) {
        $tab.removeClass('is-horizontal').addClass('is-vertical');
      } else {
        $tab.removeClass('is-vertical').addClass('is-horizontal');
      }
    }

    $(window).on('resize.tabs', Drupal.debounce(handleResize, 150)).trigger('resize.tabs');
  }

  Drupal.behaviors.navTabs = {
    attach: function (context, settings) {
      var $tabs = $(context).find('[data-at-nav-tabs]');
      if ($tabs.length) {
        var notSmartPhone = window.matchMedia('(min-width: 300px)');
        if (notSmartPhone.matches) {
          $tabs.once('nav-tabs').each(init);
        }
      }
    }
  };

})(jQuery, Drupal);
