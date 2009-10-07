// $Id$
// adaptivethemes.com

// Breadcrumb settings
$(document).ready( function() {
  // Hide the breadcrumb details, if no breadcrumb.
  $('#edit-breadcrumb-display-wrapper').change(
    function() {
      div = $('#div-breadcrumb-collapse');
      if ($('#edit-breadcrumb-display').val() == 'no') {
        div.slideUp('slow');
      } else if (div.css('display') == 'none') {
        div.slideDown('slow');
      }
    }
  );
  if ($('#edit-breadcrumb-display').val() == 'no') {
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

// Horizontal login block settings
$(document).ready( function() {
  $('#edit-horizontal-login-block-overlabel').attr('disabled', true);
  $('#edit-horizontal-login-block').change(function() {
    if ($(this).is(':checked')) {
      $('#edit-horizontal-login-block-overlabel').attr('disabled', false);
    } else {
      $('#edit-horizontal-login-block-overlabel').attr('disabled', true);
    }
  }); 
});
