(function ($) {
  Drupal.behaviors.ATmenuToggle = {
    attach: function (context, settings) {
      $("#primary-menu-bar, #menu-bar").addClass('at-menu-toggle');
      $(".at-menu-toggle h2").removeClass('element-invisible').addClass('at-menu-toggle-button').wrapInner('<a href="#menu-toggle" class="at-menu-toggle-button-link" />');
      $(".at-menu-toggle nav > ul[class*=menu]:nth-of-type(1)").wrap('<div id="menu-toggle" />');
      $(".at-menu-toggle h2 .at-menu-toggle-button-link").click(function() {
        $(".at-menu-toggle #menu-toggle").slideToggle('fast');
        return false;
      });
    }
  };
})(jQuery);
