(function ($, Drupal) {
  Drupal.behaviors.atCoreLayoutVisualThingee = {
    attach: function () {
      $('#edit-layout-select select[id*="edit-settings-page-"]').change(function(){
        var id = $(this).attr('id');
        $('#' + id).parent().next().children().removeClass().addClass(this.value);
      });
    }
  };
}(jQuery, Drupal));