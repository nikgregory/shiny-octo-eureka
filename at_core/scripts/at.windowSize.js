(function ($) {

  "use strict";

  /**
   * Renders a widget for displaying the current width of the browser.
   * Also shows the current breakpoint name/label.
   * Adapted from the Omega 7.x-4.x theme.
   */
  Drupal.behaviors.atWindowSize = {
    attach: function (context, settings) {

      $(document.body, context).once('window-size-indicator', function () {
        // Get breakpoints from drupalSettings
        var bp = settings['at_bp'];

        var indicator = $('<div class="window-size-indicator wsi" />').appendTo(this),
            indicator_px = $('<div class="wsi__px" />').appendTo(indicator),
            indicator_em = $('<div class="wsi__em" />').appendTo(indicator),
            indicator_bp = $('<div class="wsi__bp" />').appendTo(indicator);

        // Bind to the window.resize event to continuously update the width.
        $(window).bind('resize.window-size-indicator', function () {
          indicator_px.html($(this).width() + 'px');
          indicator_em.html($(this).width() /16 + 'em');
        }).trigger('resize.window-size-indicator');

        // Buggy but still useful.
        function registerEnquire(breakpoint_label, breakpoint_query) {
          enquire.register(breakpoint_query, {
            match: function() {
              indicator_bp.html(breakpoint_label);
            },
            unmatch: function() {
              indicator_bp.html('');
            },
          });
        }

        for (var item in bp) {
          if (bp.hasOwnProperty(item)) {
            var breakpoint_label = item,
                breakpoint_query = bp[item];
            registerEnquire(breakpoint_label, breakpoint_query);
          }
        }
      });
    }
  };
})(jQuery);
