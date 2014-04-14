
(function ($) {

  Drupal.behaviors.ATmenuToggle = {
    attach: function (context, settings) {

      // This may become a setting in theme settings, or at least an option?
      var mobile_width = '(max-width: 45em)';

      !function(breakPoint, query){
        // Run the callback on current viewport
        menutoggle({
          media: query,
          matches: matchMedia(query).matches
        });
        // Subscribe to breakpoint changes
        matchMedia(query).addListener(menutoggle);
      }(name, mobile_width);

      // Callback
      function menutoggle(data){
        console.log(data.matches);

        // theres some issue with the toggle when logged in, not sure what.
        if (data.matches) {
      	  $('.navbar-menu__menu').addClass('menu_closed');
          $(".navbar-menu__toggle-link").click(function() {
            $('.navbar-menu__menu').toggleClass('menu_closed');
          });
        } else {
          $('.navbar-menu__menu').removeClass('menu_closed');
        }
      }

    }
  };

})(jQuery);
