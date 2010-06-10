// $Id$
(function ($) {
  Drupal.behaviors.adaptivetheme_equalizegpanels = {
    attach: function(context) {
      $('.gpanel .block-inner').equalHeight();
    }
  };
})(jQuery);