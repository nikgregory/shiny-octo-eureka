// Initialize fastclick with jQuery
(function ($) {
  Drupal.behaviors.ATfastclickInitialize = {
    attach: function (context) {
      FastClick.attach(document.body);
    }
  };
}(jQuery));
