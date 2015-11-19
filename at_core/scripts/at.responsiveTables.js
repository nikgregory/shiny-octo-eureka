/**
 * @file
 * Responsive tables.
 *
 */
(function ($, Drupal) {

  "use strict";

  $('table.is-responsive').wrap('<div class="responsive-table"><div class="responsive-table__scroll"></div></div>');

  if ($('table.is-responsive').prop('scrollWidth') > $(".responsive-table").width() ) {
    var overflowmessage = Drupal.t('Scroll to view');
    $('.responsive-table__scroll').append('<div class="responsive-table__message"><em>' + overflowmessage + '</em></div>');
    $('table.is-responsive').addClass('has-overflow');
  }

})(jQuery, Drupal);
