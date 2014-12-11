(function ($) {

  "use strict";

  /**
   * Renders a widget for displaying the current width of the browser.
   * Adapted from the Omega 7.x-4.x theme.
   */
  Drupal.behaviors.atWindowSize = {
    attach: function (context) {
      $(document.body, context).once('window-size-indicator', function () {
        var $indicator_px = $('<div class="window-size-indicator wsi_px" />').appendTo(this);
        var $indicator_em = $('<div class="window-size-indicator wsi_em" />').appendTo(this);
        // Bind to the window.resize event to continuously update the width.
        $(window).bind('resize.window-size-indicator', function () {
          $indicator_px.text($(this).width() + 'px');
          $indicator_em.text($(this).width() /16 + 'em');
        }).trigger('resize.window-size-indicator');
      });
    }
  };

})(jQuery);
