/**
 * @file
 * Load layout.
 */
(function ($, window) {

  "use strict";

  Drupal.behaviors.atLayoutLoad = {
    attach: function () {

      // Verify that the user agent understands media queries.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      // Never run this on really small devices.
      var notSmartPhone = window.matchMedia('(min-width: 320px)');
      if (notSmartPhone.matches) {
        $('.regions').once().each(function() {
          // Remove empty regions first, otherwise classes will be wrong.
          $(this).children().filter(function() {
            return !($.trim($(this).text()).length);
          }).remove();

          // Add classes.
          var active_regions = $(this).children().map(function() {
            return $(this).attr('data-at-region');
          }).get().join('-');
          if (active_regions) {
            //$(this).addClass('arc--' + this.children.length).addClass('hr--' + active_regions).attr('data-at-regions', 'has-regions');
            var hr = 'hr--' + active_regions;
            var arc = 'arc--' + this.children.length;
            this.classList.add(hr, arc);
          }

          // Clean up empty parents.
          $(this).filter(function() {
            return !($.trim($(this).text()).length);
          }).parent().remove();
        });
      }

      //function layoutLoading() {
      //  return '.layout-not-loaded'.removeClass('layout-not-loaded').addClass('layout-loaded');
      //}
      //setTimeout(function(){layoutLoading();}, 1000);
    }
  };
}(jQuery, window));
