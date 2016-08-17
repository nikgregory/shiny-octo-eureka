/**
 * @file
 * Load layout - taking out the thrash, oh hell yeah.
 */
(function ($, window) {

  "use strict";

  Drupal.behaviors.atLayoutLoad = {
    attach: function (context) {

      // Verify that the user agent understands media queries.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      $.fn.switchClass = function(remove, add) {
        var regex = new RegExp(
          '\\s' + remove
            .replace(/\*/g, '[A-Za-z0-9-_]+')
            .split(' ')
            .join('\\s|\\s')
          + '\\s', 'g'
        );
        this.each(function(i, it) {
          var classname = ' ' + it.className + ' ';
          while (regex.test(classname) ) {
            classname = classname.replace(regex, ' ');
          }
          it.className = $.trim(classname);
        });

        return !add ? this : this.addClass(add);
      };

      // Never run this on really small devices.
      var notSmartPhone = window.matchMedia('(min-width: 320px)');

      if (notSmartPhone.matches) {
        $(context).find('.regions').once('atLayoutLoad').each(function() {
          //Remove empty regions first, otherwise classes will be wrong.
          $(this).find('.region').filter(function() {
            return !($(this).find('*[class*="block"], .messages, .panel-panel')).length;
          }).remove();

          // data-at-region holds an int value corresponding to it's place in
          // the source order.
          var active_regions = $(this).find('.region').map(function() {
            return $(this).attr('data-at-region');
          }).get().join('-');
          //
          if (active_regions) {
            var hr = 'hr--' + active_regions;
            var arc = 'arc--' + $(this).children.length;
            if (!$(this).hasClass(hr)) {
              $(this).switchClass('arc-*', arc).switchClass('hr-*', hr);
            }
            $(this).attr('data-at-regions', 'has-regions');
          } else {
            $(this).parents('.page__row').remove();
          }
        });
      }
    }
  };
}(jQuery, window));
