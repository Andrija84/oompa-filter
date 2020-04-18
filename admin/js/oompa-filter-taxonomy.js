
(function( $ ) {

  $("#oompa-filter-main-form").submit(function(e) {

      e.preventDefault(); // avoid to execute the actual submit of the form.

      var ajax_url = oompa_filter_taxonomy_ajax.ajax_url;
      var form_data = $( "#oompa-filter-main-form" ).serialize();
      //var url = $( "#oompa-filter-main-form" ).attr('action');
      //console.log(form_data);

      $.ajax({
        url: ajax_url,
        type: "POST",
        data: 'action=insert_taxonomy&' + form_data ,  //Action is always first parameter and it is related to functions call
        beforeSend: function() {
          //$("#state-list").addClass("loader");
        },
        success: function(data){
          //console.log(data);
          //alert('Taxonomy inserted');
          //Clear fields
          $('.oompa-filter-taxonomy-name-input').val('');
          $('.oompa-filter-taxonomy-label-input').val('');
          //Reload page
          location.reload();

        },
        fail: function(xhr, textStatus, errorThrown){
           //alert('Taxonomy NOT inserted !');
        }
      });
});


$(".delete-taxonomy").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var ajax_url = oompa_filter_taxonomy_ajax.ajax_url;
    var id = $(this).attr('id');
    //alert(id);

    $.ajax({
      url: ajax_url,
      type: "POST",
      data: 'action=delete_taxonomy&id=' + id,  //Action is always first parameter and it is related to functions call
      beforeSend: function() {
        alert('Do you really want to delete this taxonomy?');
      },
      success: function(data){
        //alert('Taxonomy deleted !');
        location.reload();
      },
      fail: function(xhr, textStatus, errorThrown){
         //alert('Taxonomy NOT inserted !');
      }
    });

});




})( jQuery );
