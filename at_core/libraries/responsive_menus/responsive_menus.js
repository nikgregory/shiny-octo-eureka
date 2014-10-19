(function ($) {

  "use strict";

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context, settings) {

      //console.log(drupalSettings);

      var activeTheme = drupalSettings['ajaxPageState']['theme'];
      var responsiveMenuStyle = drupalSettings[activeTheme]['responsive_menu_style'];
      var offCanvasPosition = drupalSettings[activeTheme]['offcanvas_position'];
      var menu   = '.region__navbar .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = '.region__navbar .block-menu__title';   // the toggle link

      // Add off canvas position class.
      if (offCanvasPosition) { $('body').addClass(offCanvasPosition); }

      // Clicking outside the menu will hide it.
      $('.page-root', context).on('click', function(){
        $('body').removeClass('menu-expanded');
      });

      // Add the menu style class - tiles, offcanvas or slidedown menu.
      $('body').addClass('menu-style-' + responsiveMenuStyle);

      // Toggle the menu open/closed.
      $(toggle, context).on('touchstart click', function(e) {
        $('body').toggleClass('menu-expanded');
        e.stopPropagation();
      });
    }
  };
})(jQuery);
