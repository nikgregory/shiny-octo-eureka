(function ($) {

  "use strict";

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context, settings) {

      //console.log(drupalSettings);

      var activeTheme = drupalSettings["ajaxPageState"]["theme"];
      var responsiveMenuStyle = drupalSettings[activeTheme]["responsive_menu_style"];
      var offCanvasPosition = drupalSettings[activeTheme]["offcanvas_position"];
      var menu   = '.region__navbar .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = '.region__navbar .block-menu__title';   // the toggle link

      if (offCanvasPosition) { $('body').addClass(offCanvasPosition); }
      $('body').addClass('menu-style-' + responsiveMenuStyle);
      $(toggle, context).on('touchstart click', function(e) {
        $('body').toggleClass("menu-expanded");
        e.preventDefault();
      });
    }
  };
})(jQuery);
