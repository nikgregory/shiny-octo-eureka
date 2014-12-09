(function ($, drupalSettings) {

  "use strict";

  Drupal.behaviors.atrM = {
    attach: function (context) {

      // Verify that the user agent understands media queries.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      var rm = drupalSettings[drupalSettings['ajaxPageState']['theme']]['rm'];
      var def = rm['default'];
      var resp = rm['responsive'];

      // Toggle handler.
      function toggleClick(e) {
        e.preventDefault();
        e.stopPropagation();

        // The toggle class is on <body> because we must account
        // for menu types that style block parent elements,
        // e.g. offcanvas will transform the .page element.
        $(document.body).toggleClass('rm-is-open');
        $(document).one('click', function(e) {
          if($('.rm-block').has(e.target).length === 0){
            $(document.body).removeClass('rm-is-open');
          }
        });
      }

      // Toggle.
      $('.rm-block .block-menu__title', context).on('click', toggleClick);

      // Enquire is a fancy wrapper for matchMedia.
      enquire
      .register(rm['bp'], {
        // Setup fires strait away.
        setup: function() {
          $(document.body).addClass(def).removeClass('no-fouc');
          $('.rm-block').parent('.l-r').addClass('rm-region');
          $('.rm-block h2.block-menu__title').removeClass('visually-hidden');
        },
        // The resp menu system only uses one breakpoint,
        // if it matches this fires strait after setup.
        match: function() {
          if(resp !== 'ms-none') {
            if(resp !== def) {
              $(document.body).removeClass(def).addClass(resp);
            }
          }
        },
        // Unmatch fires the first time the media query is unmatched.
        unmatch : function() {
          $(document.body).addClass(def);
          if(resp !== def) {
            $(document.body).removeClass(resp);
          }
        }
      });
    }
  };
}(jQuery, drupalSettings));
