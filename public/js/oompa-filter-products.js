(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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


	 //Load products on document ready
	 get_products();

	 //If PAGINATION is clicked, load correct posts
	 $(document).on('click', '.oompa-filter-pagination a', function(e) {
			 e.preventDefault();
	 //Scroll to filter on pagination click
	 $('html, body').animate({
		 scrollTop: $(".oompa-filter-container").offset().top -75
	 }, 500);

			 var url = $(this).attr('href'); //Grab the URL destination as a string
			 var paged = url.split('&paged='); //Split the string at the occurance of &paged=
			 get_products(paged[1]); //Load Posts (feed in paged value)

	 });


		 //Get all id of dynamic created select dropdowns
		 //oompa-filter-select-id-10
 		 var oompaFilterDynamicIds = [];
 			$('.oompa-filter-item-dynamic select').each(function () {
 			    oompaFilterDynamicIds.push(this.id);
 			});
 			//console.log(oompaFilterDynamicIds);

			//Set all variables to match dynamic dropdowns. Unique select name + id
			//example oompa-filter-select-id-10
			var firstParameter = 	oompaFilterDynamicIds[0];
			var secondParameter = oompaFilterDynamicIds[1];
		  var thirdParameter = 	oompaFilterDynamicIds[2];
		  var forthParameter = 	oompaFilterDynamicIds[3];
			//console.log(firstParameter);

			//Get selected category. This is default main filter option and it is related to product_cat default product category
 		 function selected_category(){
 				 var category_id = []; //Setup empty array
 				 $("#oompa-filter-category-select-id option:selected").each(function() {
 						 var val = $(this).val();
 						 category_id.push(val); //Push value onto array. Category ID is the key
 				 });
 				 ///console.log(product_categories);
 				 return category_id;
 		}


			function selected_first_param(){
					var firstParam = []; //Setup empty array
					$("#" + firstParameter + " option:selected").each(function() {
							var val = $(this).val();
							firstParam.push(val); //Push value onto array. Category ID is the key
					});
					//console.log(firstParam);
					return firstParam;
				}


			function selected_second_param(){
					var secondParam = []; //Setup empty array
					$("#" + secondParameter + " option:selected").each(function() {
							var val = $(this).val();
							secondParam.push(val); //Push value onto array. Category ID is the key
					});
					///console.log(product_categories);
					return secondParam;
				}

			function selected_third_param(){
					var thirdParam = []; //Setup empty array
					$("#" + thirdParameter + " option:selected").each(function() {
							var val = $(this).val();
							thirdParam.push(val); //Push value onto array. Category ID is the key
					});
					///console.log(product_categories);
					return thirdParam;
				}

			function selected_forth_param(){
					var forthParam = []; //Setup empty array
					$("#" + forthParameter + " option:selected").each(function() {
							var val = $(this).val();
							forthParam.push(val); //Push value onto array. Category ID is the key
					});
					///console.log(product_categories);
					return forthParam;
				}


	 //Get params based on another
	 $('#oompa-filter-category-select-id').on('change', function() {

			 var category_id = this.value;
			 var ajax_url = oompa_filter_products_ajax.ajax_url;
			 $.ajax({
				 type: "GET",
				 url: ajax_url,
				 data:{
					 action: 'get_first_param',
					 category_id: selected_category
				 },
				 success: function(data){
					 //Reset always on change

					 $("#" + firstParameter).prop('selectedIndex',0);
					 $("#" + secondParameter).prop('selectedIndex',0);
					 $("#" + thirdParameter).prop('selectedIndex',0);
					 $("#" + forthParameter).prop('selectedIndex',0);

					 $("#" + firstParameter + " option:not(:selected)").remove();
					 $("#" + secondParameter + " option:not(:selected)").remove();
					 $("#" + thirdParameter + " option:not(:selected)").remove();
					 $("#" + forthParameter + " option:not(:selected)").remove();


					 $("#" + firstParameter).html(data);

					 get_products();
				 }
			 });
	 });

	 //Get params based on another
	 $("#" + firstParameter).on('change', function() {
		 var first_param_id = this.value;

			 var ajax_url = oompa_filter_products_ajax.ajax_url;
			 $.ajax({
				 type: "GET",
				 url: ajax_url,
				 data:{
					 action: 'get_second_param',
					 category_id: selected_category,
					 first_param: first_param_id
				 },
				 success: function(data){
					 $("#" + secondParameter).html(data);
					 get_products();
				 }
			 });
	 });

	 //Get params based on another
	 $("#" + secondParameter).on('change', function() {
		 var second_param_id = this.value;
			 var ajax_url = oompa_filter_products_ajax.ajax_url;
			 $.ajax({
				 type: "GET",
				 url: ajax_url,
				 data:{
					 action: 'get_third_param',
					 category_id: selected_category,
					 first_param: selected_first_param,
					 second_param: second_param_id
				 },
				 success: function(data){
					 $("#" + thirdParameter).html(data);
					 get_products();
				 }
			 });
	 });

	 //Get params based on another
	 $("#" + thirdParameter).on('change', function() {
		  var third_param_id = this.value;
			 var ajax_url = oompa_filter_products_ajax.ajax_url;
			 $.ajax({
				 type: "GET",
				 url: ajax_url,
				 data:{
					 action: 'get_forth_param',
					 category_id: selected_category,
					 first_param: selected_first_param,
					 second_param: selected_second_param,
					 third_param: third_param_id
				 },
				 success: function(data){
					 $("#" + forthParameter).html(data);
					 get_products();
				 }
			 });
	 });

	 //Get products on last param change
	 $("#" + forthParameter).on('change', function() {
					 get_products();
	 });


	 //Main ajax function
	 function get_products(paged){
			 var paged_value = paged; //Store the paged value if it's being sent through when the function is called
			 var ajax_url = oompa_filter_products_ajax.ajax_url; //Get ajax url (added through wp_localize_script)
			 $.ajax({
					 type: 'GET',
					 url: ajax_url,
					 data: {
							 action: 'load_products',//Added to functions.php action wp_ajax_product_filter
							 category_id : selected_category, //Can be a function
							 first_param : selected_first_param,
							 second_param : selected_second_param,
							 third_param : selected_third_param,
							 forth_param : selected_forth_param,
							 paged: paged_value //If paged value is being sent through with function call, store here
					 },
					 beforeSend: function ()
					 {
					//You could show a loader here
					//$('.lds-ellipsis').addClass('active');
					//$('.onlineshop-products-container').addClass('ajax-loading');
					 },
					 success: function(data)
					 {
					 //Hide loader here
					//$('.lds-ellipsis').removeClass('active');
					//$('.onlineshop-products-container').removeClass('ajax-loading');
					$('.oompa-filter-products-list').html(data);
					ScrollReveal().reveal('.reveal-item', {
						 distance: '150%',
						 origin: 'bottom',
						 opacity: null
						});
					 },
					 error: function()
					 {
					 //If an ajax error has occured, do something here...
					 $(".oompa-filter-products-list").html('<h2>Trenutno nemate proizvoda</h2>');
					 }
			 });
	 }




})( jQuery );
