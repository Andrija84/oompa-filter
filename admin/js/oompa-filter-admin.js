(function( $ ) {

  'use strict';

	//console.log('ADMIN JS LOADED!');


	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 //https://codex.wordpress.org/AJAX_in_Plugins


	 // add row
	 $(".addRow").click(function () {
			 var html = '';

			 html += '<div class="new-taxonomy-row-container">';
			 html += '<input type="text" name="taxonomy_name[]" class="oompa-filter-taxonomy-name-input" placeholder="Taxonomy name" autocomplete="off">';
       html += '<input type="text" name="taxonomy_label[]" class="oompa-filter-taxonomy-label-input" placeholder="Taxonomy label" autocomplete="off">';
			 html += '<button class="removeRow button-error" type="button">Remove</button>';
			 html += '</div>';


			 $('.newRow').append(html);
	 });

	 // remove row
	 $(document).on('click', '.removeRow', function () {
			 $(this).closest('.new-taxonomy-row-container').remove();
	 });




})( jQuery );
