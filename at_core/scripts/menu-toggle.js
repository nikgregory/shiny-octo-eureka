(function ($) {
  Drupal.behaviors.ATmenuToggle = {
    attach: function (context, settings) {
      $("#primary-menu-bar, #menu-bar").addClass('at-menu-toggle');
      $(".at-menu-toggle h2").removeClass('element-invisible').wrapInner('<a href="#menu-toggle" class="at-menu-toggle-link" />');
      $(".at-menu-toggle nav > ul[class*=menu]:nth-of-type(1)").wrap('<div id="menu-toggle" />');
      $(".at-menu-toggle h2 .at-menu-toggle-link").click(function() {
        $(".at-menu-toggle #menu-toggle").slideToggle('fast');
        return false;
      });
    }
  };
})(jQuery);
