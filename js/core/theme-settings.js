// $Id$
// adaptivethemes.com

$(document).ready( function() {
  // Hide the breadcrumb details, if no breadcrumb.
  $('#edit-tnt-breadcrumb').change(
    function() {
      div = $('#div-breadcrumb-collapse');
      if ($('#edit-breadcrumb').val() == 'no') {
        div.slideUp('slow');
      } else if (div.css('display') == 'none') {
        div.slideDown('slow');
      }
    }
  );
  if ($('#edit-breadcrumb').val() == 'no') {
    $('#div-breadcrumb-collapse').css('display', 'none');
  }
  $('#edit-breadcrumb-title').change(
    function() {
      checkbox = $('#edit-breadcrumb-trailing');
      if ($('#edit-breadcrumb-title').attr('checked')) {
        checkbox.attr('disabled', 'disabled');
      } else {
        checkbox.removeAttr('disabled');
      }
    }
  );
  $('#edit-breadcrumb-title').change();
});