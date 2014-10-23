(function ($) {

  /**
   * Renders a widget for displaying the current width of the browser.
   * Adapted from the Omega 7.x-4.x theme. Cheers big ears.
   */
  Drupal.behaviors.atWindowSize = {
    attach: function (context) {
      $('body', context).once('window-size-indicator', function () {
        var $indicator_px = $('<div class="window-size-indicator wsi_px" />').appendTo(this);
        var $indicator_em = $('<div class="window-size-indicator wsi_em" />').appendTo(this);

        // Bind to the window.resize event to continuously update the width.
        $(window).bind('resize.window-size-indicator', function () {
          $indicator_px.text($(this).width() + ' px');
          $indicator_em.text($(this).width() /16 + ' em');
        }).trigger('resize.window-size-indicator');

      });
    }
  };

})(jQuery);

//
//function jqUpdateSize(){
//    // Get the dimensions of the viewport
//    var width = $(window).width();
//    var height = $(window).height();
//
//    $('#jqWidth').html(width);
//    $('#jqHeight').html(height);
//};
//
//$(document).ready(jqUpdateSize);    // When the page first loads
//$(window).resize(jqUpdateSize);     // When the browser changes size


