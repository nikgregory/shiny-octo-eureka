/**
 * @file
 * Responsive menus.
 */
(function ($, document, window) {

  "use strict";

  Drupal.behaviors.atrM = {
    attach: function (context, settings) {

      $('.rm-block').removeClass('js-hide');

      // Verify that the user agent understands media queries.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      var at   = settings['ajaxPageState']['theme'],
          rm   = settings[at]['at_responsivemenus'],
          def  = rm['default'],
          resp = rm['responsive'],
          tl   = '.rm-block .rm-toggle__link',
          exp  = $(tl).attr("aria-expanded") == "false";

      // Toggle handler.
      function toggleClick(e) {
        e.preventDefault();
        e.stopPropagation();

        // The toggle class is on <body> because we must account for menu types
        // that style block parent elements, e.g. off-canvas will transform the
        // .page element. Toggle aria attributes for accessibility on block
        // elements.
        $(document.body).toggleClass('rm-is-open');
        $(tl).attr("aria-expanded", exp ? "true" : "false");

        $(document).one('click', function(e) {
          if($('.rm-block').has(e.target).length === 0){
            $(document.body).removeClass('rm-is-open');
            $(tl).attr("aria-expanded", "false");
          }
        });
      }

      // Toggle.
      $(tl, context).on('click', toggleClick);

      // Enquire is a fancy wrapper for matchMedia.
      enquire
      .register(rm['bp'], {
        // Setup fires strait away.
        setup: function() {
          $(document.body).addClass(def);
          $('.rm-block').parent('.l-r').addClass('rm-region');
          $('.rm-block').parent().parent('.l-rw').addClass('rm-row');
        },
        // The resp menu system only uses one breakpoint, if it matches this
        // fires strait after setup.
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
}(jQuery, document, window));
