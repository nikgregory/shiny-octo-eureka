(function ($) {
  Drupal.behaviors.nopeMQ = {
    attach: function (context) {
      $("html").addClass("nope_mq");
      
      //$("p").addClass("myClass yourClass");
    });
  }
};