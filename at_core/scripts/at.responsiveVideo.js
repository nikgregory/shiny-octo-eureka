// This sort of works OK, in reality the CSS method using a div wrapper with the class
// embed-contianer works better.
(function ($) {

  "use strict";

  Drupal.behaviors.atrV = {
    attach: function (context, settings) {

      // Verify that the user agent understands media queries.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      // Find all YouTube videos
      var $allVideos = $("iframe[src*='//www.youtube.com']"),

          // The element that is fluid width
          $fluidEl = $("body");

      // Figure out and save aspect ratio for each video
      $allVideos.each(function() {

        $(this)
          .data('aspectRatio', this.height / this.width)

          // and remove the hard coded width/height
          .removeAttr('height')
          .removeAttr('width');

      });

      // When the window is resized
      $(window).resize(function() {

        var newWidth = $fluidEl.width();

        // Resize all videos according to their own aspect ratio
        $allVideos.each(function() {

          var $el = $(this);
          $el
            .width(newWidth)
            .height(newWidth * $el.data('aspectRatio'));

        });

      // Kick off one resize to fix all videos on page load
      }).resize();

    }
  };
}(jQuery));
