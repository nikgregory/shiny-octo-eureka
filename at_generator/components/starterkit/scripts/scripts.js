// theme scripts is a starter JS file for things like intializing plugins/polyfills etc.
(function ($, Drupal) {

  Drupal.behaviors.atSubthemeScripts = {
    attach: function (context) {

      // Initialize fastclick with jQuery
      FastClick.attach(document.body);

    }
  };

}(jQuery, Drupal));
