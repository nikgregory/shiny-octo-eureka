// Initialize fastclick with jQuery
(function ($) {
  Drupal.behaviors.ATfastclickInitialize = {
    attach: function (context) {
      FastClick.attach(document.body);
    }
  };
}(jQuery));

// without
/*
if ('addEventListener' in document) {
  document.addEventListener('DOMContentLoaded', function() {
    FastClick.attach(document.body);
  }, false);
}
*/
