/**
 * @file
 * Center element vertically.
 *
 * ## Usage
 * Call the function on the element to be vertically centered in it's parent.
 * The param pS should be the next outer container that we need to get the
 * height for.
 *
 * <div class="outer-container">
 *   <div class="parent-container">
 *     <nav class="element">...</nav>
 *   </div>
 * </div>
 *
 * <script>
 *   $(document).ready(function() {
 *     $('.element').atVerticalCenter('.outer-container');
 *   });
 * </script>
 */
(function( $ ){
  $.fn.atVerticalCenter = function(pS) {
    return this.each(function(){
      var container_height = $(pS).height();
      $(this).parent().css({
        'position' : 'relative',
        'height'   : container_height,
      });
      $(this).addClass('is-vertically-centered');
      // $(this).css({
      //   'position' : 'absolute',
      //   'top' : '50%',
      //   'transform' : 'translateY(-50%)',
      //   //'margin-top' : function() {return -$(this).outerHeight()/2},
      // });
    });
  };
})( jQuery );
