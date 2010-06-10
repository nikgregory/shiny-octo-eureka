// $Id$
(function ($) {
  /**
   * Insert WAI-ARIA Landmark Roles (Roles for Accessible Rich Internet Applications)
   *
   * http://www.w3.org/TR/2006/WD-aria-role-20060926/
   * 
   * Due to validation errors with WAI-ARIA roles we use JavaScript to 
   * insert the roles. This is a stop-gap measure while the W3C sort 
   * out the validator.
   *
   * This is a starting point, you can copy this and use it in your subtheme for more granular control.
   */
  Drupal.behaviors.adaptivetheme = {
    attach: function(context) {

      // Set role=banner on #branding wrapper div.
      $("#branding").attr("role","banner");

      // Set role=main on #main-content div.
      $("#main-content").attr("role","main");

      // Set role=search on search block and box.
      $("#search-theme-form").attr("role","search");

      // Set role=contentinfo on the footer message.
      $("#footer-message").attr("role","contentinfo");

      // Set role=article on nodes.
      $(".article").attr("role","article");

      // Set role=nav on navigation-like blocks.
      $("#nav, .admin-panel, #breadcrumb, .block-menu, #block-user-1, .local-tasks").attr("role","navigation");

      // Horizontal login block settings
      $('#edit-horizontal-login-block-overlabel').attr('disabled', true);
      $('#edit-horizontal-login-block').change(function() {
        if ($(this).is(':checked')) {
          $('#edit-horizontal-login-block-overlabel').attr('disabled', false);
        } else {
          $('#edit-horizontal-login-block-overlabel').attr('disabled', true);
        }
      }); 
    }
  };

  /**
   * In most instances this will be called using the built in theme settings.
   * However, if you want to use this manually you can call this file
   * in the info file and user the ready function e.g.:
   * 
   * This will set sidebars and the main content column all to equal height:
   *  (function ($) {
   *    Drupal.behaviors.adaptivetheme = {
   *      attach: function(context) {
   *        $('#content-column, .sidebar').equalHeight();
   *      }
   *    };
   *  })(jQuery);
   */
  jQuery.fn.equalHeight = function () {
    var height = 0;
    var maxHeight = 0;

    // Store the tallest element's height
    this.each(function () {
      height = jQuery(this).outerHeight();
      maxHeight = (height > maxHeight) ? height : maxHeight;
    });

    // Set element's min-height to tallest element's height
    return this.each(function () {
      var t = jQuery(this);
      var minHeight = maxHeight - (t.outerHeight() - t.height());
      var property = jQuery.browser.msie && jQuery.browser.version < 7 ? 'height' : 'min-height';

      t.css(property, minHeight + 'px');
   });
  };

})(jQuery);