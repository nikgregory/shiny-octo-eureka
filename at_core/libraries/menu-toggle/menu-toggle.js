(function ($) {

  "use strict";

  Drupal.behaviors.ATmenutoggle = {
    attach: function (context) {

      var mobile_width = '(max-width: 60em)';         // media query
      var menu         = '.navbar-menu__menu';        // wrapper element around the root ul.menu
      var toggle_link  = '.navbar-menu__toggle-link'; // the toggle link

      function menutoggle(mql){

        //console.log(mql.matches);

        if (mql.matches) {
          $(menu).addClass('menu_closed');
          $(toggle_link).click(function() {
            $(menu).toggleClass('menu_closed');
          });
        }
        else {
          $(menu).removeClass('menu_closed');
        }
      }

      var mql = window.matchMedia(mobile_width)
      menutoggle(mql)             // call listener function explicitly at run time
      mql.addListener(menutoggle) // attach listener function to listen in on state changes

    }
  };
})(jQuery);


/*
// Callback
function menutoggle(data){
  console.log(data.matches);
  if (data.matches) {
	  $(menu).addClass('menu_closed');
	  $(toggle_link).click(function() {
      $(menu).toggleClass('menu_closed');
    });
  } else {
    $(menu).removeClass('menu_closed');
  }
}

!function(breakPoint, query){
  // Run the callback on current viewport
  menutoggle({
    media: query,
    matches: window.matchMedia(query).matches
  });
  // Subscribe to breakpoint changes
  matchMedia(query).addListener(menutoggle);
}(name, mobile_width);
*/


