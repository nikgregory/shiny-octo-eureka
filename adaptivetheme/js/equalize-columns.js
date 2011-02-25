(function ($) {
  Drupal.behaviors.adaptivetheme_equalizecolumns = {
    attach: function(context) {
      $('#content-column, .sidebar').equalHeight();
    }
  };
})(jQuery);