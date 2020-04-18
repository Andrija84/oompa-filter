(function($) {

var table = $( '#oompa-filter-taxonomy-table tbody' );
var ajax_url = oompa_filter_taxonomy_reorder.ajax_url;

function get_taxonomy_ids(){
    var ids = []; //Setup empty array
    $(".ui-sortable-handle").each(function() {
        var val = $(this).attr('id');
        ids.push(val); //Push value onto array. Category ID is the key
    });
    return ids;
  }

table.sortable({
  cursor: 'move',
  items:  '> tr',

  update: function( event, ui ) {

    //ID of reordered item
    var id = ui.item.attr("id");
    var order = ui.item.data('order'); //getter
    //alert (position);

    $.ajax({
      url: ajaxurl,
      type: 'GET',
      dataType: 'json',
      data: {
        action: 'save_sort',
        //id: id,
        //order: order,
        reorder: table.sortable( 'toArray' ),
        security: oompa_filter_taxonomy_reorder.security  //wp_create_nonce
      },
      success: function( response ) {
        //$( 'div#message' ).remove();
        //animation.hide();
        if( true === response.success ) {
          console.log('SUCCESS');
          location.reload();
        } else {
          //pageTitle.after( '<div id="message" class="error"><p>' + WP_PROJEKTE_LISTING.failure + '</p></div>' );
          console.log('FAIL');
        }


      },
      error: function( error ) {
        //$( 'div#message' ).remove();
        //animation.hide();
        console.log('ERROR');
        //pageTitle.after( '<div id="message" class="error"><p>' + WP_JOB_LISTING.failure + '</p></div>' );
      }
    });
  }
}).disableSelection();

})( jQuery );
