/**
 * Double Tap To Go
 *
 * Fork: by Jeff Burnz https://github.com/jmburnz/DoubleTapToGo
 * - Remove win7 mobile check.
 * - Rewrite to Drupal coding standards.
 *
 * Originally by Osvaldas Valutis, www.osvaldas.info
 * unbind & other improvements by https://github.com/zenopopovici/DoubleTapToGo
 * License MIT & GPL 2.0
 *
 * TODO: upstream request to include the zenopopovici version in cdnjs.com:
 * https://github.com/cdnjs/cdnjs/issues/8439
 */
;(function($, window, document) {

	"use strict";

	$.fn.doubleTapToGo = function(action) {

		if (action === 'unbind') {
			this.each(function() {
				$(this).off();
				$(document).off('click touchstart MSPointerDown', handleTouch);
			});
		}
		else {
			this.each(function() {
				var currentItem = false;

				$(this).on('click', function(e) {
					var item = $(this);

					if (item[0] != currentItem[0]) {
						e.preventDefault();
            currentItem = item;
					}
				});

				$(document).on('click touchstart MSPointerDown', handleTouch);

				function handleTouch(e) {
          var resetItem = true,
            parents = $(e.target).parents();

          for (var i = 0; i < parents.length; i++) {
						if (parents[i] == currentItem[0]) {
							resetItem = false;
						}
					}

          if (resetItem) {
            currentItem = false;
          }
				}
			});
		}

		return this;
	};
})(jQuery, window, document);
