/**
 * @file
 * Center element vertically.
 *
 * ## Usage
 * Call the plugin on the element which needs to be vertically centered in it's
 * parent.
 *
 * <script>
 *   $(document).ready(function() {
 *     $('#element-to-be-centered').atVerticalCenter();
 *   });
 * </script>
 *
 * This will take the parents height, the elements own height and calculate the
 * distance the element should have from the parents top to be vertically
 * centered. This value is applied to the top margin of the element by default.
 *
 * ## Options
 * You can pass an options hash to the plugin.
 * - cssAttribute - the css attribute that the value should be set on
 *  (default: 'margin-top')
 * - verticalOffset - the number of pixels to offset the vertical alignment by,
 *   ie. 10, "50px", -100 (default: 0)
 * - parentSelector - a selector representing the parent to vertically center
 *   this element within, i.e. ".parent" (default: the element's immediate parent)
 * - debounceTimeout - in milliseconds, used to rate-limit the vertical
 *   centering when resizing the browser window (default: 25)
 * - deferTilWindowLoad - if true, no action will be taken until jQuery's
 *   window.load event fires. If false, the vertical centering will take place
 *   immediately, and then once again on window.load (default: false)
 * - complete - takes an anonymous function that gets run after the centering
 *   is complete.
 *
 *  ## Examples:
 * <script>
 *   $(document).ready(function() {
 *     $('#element-to-be-centered').atVerticalCenter();
 *     $('#element-to-be-centered').atVerticalCenter({ cssAttribute: 'padding-top', verticalOffset: '50px' });
 *     $('#element-to-be-centered').atVerticalCenter({ cssAttribute: 'padding-top', parentSelector: '.parent' });
 *     $('#element-to-be-centered').atVerticalCenter({ cssAttribute: 'padding-top', parentSelector: '.parent', complete: function(){ alert('I'm in the middle.... Vertically') } });
 *   });
 * </script>
 */
(function( $ ){
  $.fn.atVerticalCenter = function(options) {
    var settings = $.extend({
      cssAttribute:      'margin-top',  // the attribute to apply the calculated value to
      verticalOffset:     0,            // the number of pixels to offset the vertical alignment by
      parentSelector:     null,         // a selector representing the parent to vertically center this element within
      debounceTimeout:    25,           // a default debounce timeout in milliseconds
      deferTilWindowLoad: false         // if true, nothing will take effect until the $(window).load event
    }, options || {});

    return this.each(function(){
      var $this   = $(this); // store the object
      var debounce;

      // recalculate the distance to the top of the element to keep it centered
      var resizer = function () {
        var parentHeight = (settings.parentSelector && $this.parents(settings.parentSelector).length) ?
          $this.parents(settings.parentSelector).first().height() : $this.parent().height();

        $this.css(
          settings.cssAttribute, ( ( ( parentHeight - $this.height() ) / 2 ) + parseInt(settings.verticalOffset) )
        );
        if (settings.complete !== undefined) {
          settings.complete();
        }
      };

      // Call on resize. Opera debounces their resize by default.
      $(window).resize(function () {
        clearTimeout(debounce);
        debounce = setTimeout(resizer, settings.debounceTimeout);
      });

      if (!settings.deferTilWindowLoad) {
        // call it once, immediately.
        resizer();
      }

      // Call again to set after window (frames, images, etc) loads.
      $(window).load(function () {
        resizer();
      });
    });
  };
})( jQuery );
