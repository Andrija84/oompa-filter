<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/public
 * @author     Andrija Nikolic <info@oompa.de>
 */
class OOMPA_Filter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $oompa_filter    The ID of this plugin.
	 */
	private $oompa_filter;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $oompa_filter       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $oompa_filter, $version ) {

		$this->oompa_filter = $oompa_filter;
		$this->version = $version;

		add_action( 'init', array($this, 'register_taxonomies'), 0 );
		// Add the shortcode for front-end form display
		add_action( 'init', array( $this, 'add_filter_shortcode'), 1 );


    //http://hookr.io/actions/wp_ajax_action/
		add_action( 'wp_ajax_load_products', array ($this,'load_products'), 2);
		add_action( 'wp_ajax_nopriv_load_products', array ($this,'load_products'), 2);

		add_action( 'wp_ajax_get_first_param', array ($this,'get_first_param'), 2);
		add_action( 'wp_ajax_nopriv_get_first_param', array ($this,'get_first_param'), 2);

		add_action( 'wp_ajax_get_second_param', array ($this,'get_second_param'), 2);
		add_action( 'wp_ajax_nopriv_get_second_param', array ($this,'get_second_param'), 2);

		add_action( 'wp_ajax_get_third_param', array ($this,'get_third_param'), 2);
		add_action( 'wp_ajax_nopriv_get_third_param', array ($this,'get_third_param'), 2);

		add_action( 'wp_ajax_get_forth_param', array ($this,'get_forth_param'), 2);
		add_action( 'wp_ajax_nopriv_get_forth_param', array ($this,'get_forth_param'), 2);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in OOMPA_Filter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The OOMPA_Filter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->oompa_filter, plugin_dir_url( __FILE__ ) . 'css/oompa-filter-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', false, '1.0.0' );



	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in OOMPA_Filter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The OOMPA_Filter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->oompa_filter, plugin_dir_url( __FILE__ ) . 'js/oompa-filter-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'oompa-filter-products-ajax', plugin_dir_url( __FILE__ ) . 'js/oompa-filter-products.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'oompa-filter-products-ajax', 'oompa_filter_products_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}




 function register_taxonomies() {

		global $wpdb;
		$table_name = $wpdb->prefix.'oompa_filter';
		$result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

		foreach ( $result as $print )   {
     $taxonomySlug = $print->taxonomySlug;
		 $taxonomyLabel = $print->taxonomyLabel;

		     $args = array(
		         'label'        => __( $taxonomyLabel, 'textdomain' ),
		         'public'       => true,
		         'rewrite'      => false,
		         'hierarchical' => true
		     );

		     register_taxonomy( $taxonomySlug , 'product', $args );

	 }
 }

 public function add_filter_shortcode() {
		add_shortcode( "oompa-filters", array( $this, "product_filters" ) );

}


 //Get product filters
 function product_filters() {

     $args = array(
       'taxonomy'   => "product_cat", //Build in product taxonomy
     //'parent'     => 0,
     //'number'     => $number,
     //'orderby'    => $orderby,
     //'order'      => $order,
       'hide_empty' => true,
       'include'    => $ids
   	);
	 	$terms = get_terms($args);
	     $filters_html = false;
	     if( $terms ):

				 $filters_html .= '<div class="oompa-filter-container">';

	       //Filter category
	 			$filters_html .= '<div class="oompa-filter-item">';
	 			$filters_html .= '<select id="oompa-filter-category-select-id" name="oompa-filter-category-select">'; //Build in product category. This is always first filter parameter
	 			$filters_html .= '<option value="" disabled selected>Category</option>';
	 			foreach( $terms as $term ){
	 			$filters_html .= ' <option value="'.$term->term_id.'">'.$term->name.'</option>';
	 			}
	 			$filters_html .= '</select>';
	 			$filters_html .= '</div>';


			 global $wpdb;
			 $table_name = $wpdb->prefix.'oompa_filter';
			 $taxonomies = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

			 foreach ( $taxonomies as $tax )  {
				$taxonomyId = $tax->id;
			  $taxonomySlug = $tax->taxonomySlug;
				$taxonomyLabel = $tax->taxonomyLabel;

				//Filter ausfuhrung
				$filters_html .= '<div class="oompa-filter-item-dynamic">';
				$filters_html .= '<select id="oompa-filter-select-id-'.$taxonomyId.'" name="oompa-filter-'.$taxonomySlug.'">';
				$filters_html .= '<option value="" disabled selected>'.$taxonomyLabel.'</option>';
				$filters_html .= '</select>';
				$filters_html .= '</div>';

			}
		   //Filter container END
			 $filters_html .= '</div>';
       //This is the place where AJAX will load all products
			 $filters_html .= '<div class="oompa-filter-products-list"></div>';


       endif;
			return $filters_html;

 }


 //Get first param based on category. Category is always first to filter products, product_cat
 function get_first_param(){
  global $wpdb;
  $table_name = $wpdb->prefix.'oompa_filter';
  $result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

  $taxonomy_id_0    = $result[0]->id;
  $taxonomy_slug_0  = $result[0]->taxonomySlug;
  $taxonomy_label_0 = $result[0]->taxonomyLabel;

  $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
  $first_param = isset($_GET['first_param']) ? $_GET['first_param'] : '';

  $tax_query_category = ($category_id) ? array( array(
 	 'taxonomy' => 'product_cat',
 	 'field' => 'term_id', //Category ID is the key
 	 'terms' => $category_id
  ) ) : false;


  $tax_query_taxonomy_slug_0 = ($first_param) ? array( array(
 	 'taxonomy' => $taxonomy_slug_0,
 	 'field' => 'term_id', //Category ID is the key
 	 'terms' => $first_param
  ) ) : false;

if($category_id){
	$args = array(
	 'post_type'   => 'product',
	 'post_status' => 'publish',
	 'fields'      => 'ids', // Only get post IDs
	 'showposts'   => -1,
	 'tax_query'   => array(
					'relation' => 'AND',
					array($tax_query_category, $tax_query_taxonomy_slug_0)
					)
	);
}

 $product_list_ids = get_posts( $args );

 $tax_0_list = array();
 foreach ($product_list_ids as $id){
 $current_id   = wp_get_object_terms( $id, $taxonomy_slug_0, array('fields' => 'ids'));
 $current_name = wp_get_object_terms( $id, $taxonomy_slug_0, array('fields' => 'names'));

 if (!empty($current_id)) {
 	 array_push($tax_0_list, ['term_id' => $current_id, 'name' => $current_name]);
 }
 }
 //echo '<pre>',print_r($ausfuhrung_list,1),'</pre>';

 $temp = array();
 foreach($tax_0_list as $item){
 array_push($temp, ['id' => $item['term_id'][0], 'name' => $item['name'][0] ]);
 }

 //https://vijayasankarn.wordpress.com/2017/02/20/array_unique-for-multidimensional-array/
 $terms = array_unique($temp, SORT_REGULAR);
 //echo '<pre>',print_r($temp,1),'</pre>';
 ?>

 <option value="" selected disabled><?php echo $taxonomy_label_0; ?></option>
 <?php foreach ($terms as $term) { ?>
  <option value="<?php echo $term['id']; ?>"><?php echo $term['name']; ?></option>
 <?php }

 }


 function get_second_param(){
		global $wpdb;
		$table_name = $wpdb->prefix.'oompa_filter';
		$result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

    $taxonomy_id_0    = $result[0]->id;
		$taxonomy_slug_0  = $result[0]->taxonomySlug;
		$taxonomy_label_0 = $result[0]->taxonomyLabel;

    $taxonomy_id_1    = $result[1]->id;
		$taxonomy_slug_1  = $result[1]->taxonomySlug;
		$taxonomy_label_1 = $result[1]->taxonomyLabel;

		$category_id  = isset($_GET['category_id']) ? $_GET['category_id'] : '';
		$first_param  = isset($_GET['first_param']) ? $_GET['first_param'] : '';
		$second_param = isset($_GET['second_param']) ? $_GET['first_param'] : '';


		$tax_query_category = ($category_id) ? array( array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id', //Category ID is the key
			'terms' => $category_id
		) ) : false;


		$tax_query_taxonomy_slug_0 = ($first_param) ? array( array(
			'taxonomy' => $taxonomy_slug_0,
			'field' => 'term_id', //Category ID is the key
			'terms' => $first_param
		) ) : false;

		$tax_query_taxonomy_slug_1 = ($second_param) ? array( array(
			'taxonomy' => $taxonomy_slug_1,
			'field' => 'term_id', //Category ID is the key
			'terms' => $second_param
		) ) : false;

    if($category_id || $first_param){
		$args = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'fields'      => 'ids', // Only get post IDs
			'showposts'   => -1,
			'tax_query'   => array(
						 'relation' => 'AND',
						 array($tax_query_category, $tax_query_taxonomy_slug_0, $tax_query_taxonomy_slug_1 )
						 )
		);
	}

	$product_list_ids = get_posts( $args );

	$tax_1_list = array();
	foreach ($product_list_ids as $id){
	$current_id   = wp_get_object_terms( $id, $taxonomy_slug_1, array('fields' => 'ids'));
	$current_name = wp_get_object_terms( $id, $taxonomy_slug_1, array('fields' => 'names'));

	 if (!empty($current_id)) {
			array_push($tax_1_list, ['term_id' => $current_id, 'name' => $current_name]);
	 }
	}
	//echo '<pre>',print_r($ausfuhrung_list,1),'</pre>';

	$temp = array();
	foreach($tax_1_list as $item){
	 array_push($temp, ['id' => $item['term_id'][0], 'name' => $item['name'][0] ]);
	}

	//https://vijayasankarn.wordpress.com/2017/02/20/array_unique-for-multidimensional-array/
	$terms = array_unique($temp, SORT_REGULAR);
	//echo '<pre>',print_r($temp,1),'</pre>';
	?>

	<option value="" selected disabled><?php echo $taxonomy_label_1; ?></option>
	<?php foreach ($terms as $term) { ?>
		<option value="<?php echo $term['id']; ?>"><?php echo $term['name']; ?></option>
	<?php }

	}


	function get_third_param(){
	 global $wpdb;
	 $table_name = $wpdb->prefix.'oompa_filter';
	 $result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

	 $taxonomy_id_0    = $result[0]->id;
	 $taxonomy_slug_0  = $result[0]->taxonomySlug;
	 $taxonomy_label_0 = $result[0]->taxonomyLabel;

	 $taxonomy_id_1    = $result[1]->id;
	 $taxonomy_slug_1  = $result[1]->taxonomySlug;
	 $taxonomy_label_1 = $result[1]->taxonomyLabel;

	 $taxonomy_id_2    = $result[2]->id;
	 $taxonomy_slug_2  = $result[2]->taxonomySlug;
	 $taxonomy_label_2 = $result[2]->taxonomyLabel;

	 $category_id  = isset($_GET['category_id']) ? $_GET['category_id'] : '';
	 $first_param  = isset($_GET['first_param']) ? $_GET['first_param'] : '';
	 $second_param = isset($_GET['second_param']) ? $_GET['second_param'] : '';
	 $third_param  = isset($_GET['third_param']) ? $_GET['third_param'] : '';


	 $tax_query_category = ($category_id) ? array( array(
		 'taxonomy' => 'product_cat',
		 'field' => 'term_id', //Category ID is the key
		 'terms' => $category_id
	 ) ) : false;


	 $tax_query_taxonomy_slug_0 = ($first_param) ? array( array(
		 'taxonomy' => $taxonomy_slug_0,
		 'field' => 'term_id', //Category ID is the key
		 'terms' => $first_param
	 ) ) : false;

	 $tax_query_taxonomy_slug_1 = ($second_param) ? array( array(
		 'taxonomy' => $taxonomy_slug_1,
		 'field' => 'term_id', //Category ID is the key
		 'terms' => $second_param
	 ) ) : false;

	 $tax_query_taxonomy_slug_2 = ($third_param) ? array( array(
		 'taxonomy' => $taxonomy_slug_2,
		 'field' => 'term_id', //Category ID is the key
		 'terms' => $third_param
	 ) ) : false;

if($category_id || $first_param || $second_param){
	 $args = array(
		 'post_type'   => 'product',
		 'post_status' => 'publish',
		 'fields'      => 'ids', // Only get post IDs
		 'showposts'   => -1,
		 'tax_query' => array(
						'relation' => 'AND',
						array( $tax_query_category, $tax_query_taxonomy_slug_0, $tax_query_taxonomy_slug_1, $tax_query_taxonomy_slug_2 )
						)
	 );
 }

 $product_list_ids = get_posts( $args );

 $tax_2_list = array();
 foreach ($product_list_ids as $id){
 $current_id   = wp_get_object_terms( $id, $taxonomy_slug_2, array('fields' => 'ids'));
 $current_name = wp_get_object_terms( $id, $taxonomy_slug_2, array('fields' => 'names'));

	if (!empty($current_id)) {
		 array_push($tax_2_list, ['term_id' => $current_id, 'name' => $current_name]);
	}
 }
 //echo '<pre>',print_r($ausfuhrung_list,1),'</pre>';

 $temp = array();
 foreach($tax_2_list as $item){
	array_push($temp, ['id' => $item['term_id'][0], 'name' => $item['name'][0] ]);
 }

 //https://vijayasankarn.wordpress.com/2017/02/20/array_unique-for-multidimensional-array/
 $terms = array_unique($temp, SORT_REGULAR);
 //echo '<pre>',print_r($temp,1),'</pre>';
 ?>

 <option value="" selected disabled><?php echo $taxonomy_label_2; ?></option>
 <?php foreach ($terms as $term) { ?>
	 <option value="<?php echo $term['id']; ?>"><?php echo $term['name']; ?></option>
 <?php }

}


function get_forth_param(){
 global $wpdb;
 $table_name = $wpdb->prefix.'oompa_filter';
 $result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

 $taxonomy_id_0    = $result[0]->id;
 $taxonomy_slug_0  = $result[0]->taxonomySlug;
 $taxonomy_label_0 = $result[0]->taxonomyLabel;

 $taxonomy_id_1    = $result[1]->id;
 $taxonomy_slug_1  = $result[1]->taxonomySlug;
 $taxonomy_label_1 = $result[1]->taxonomyLabel;

 $taxonomy_id_2    = $result[2]->id;
 $taxonomy_slug_2  = $result[2]->taxonomySlug;
 $taxonomy_label_2 = $result[2]->taxonomyLabel;

 $taxonomy_id_3    = $result[3]->id;
 $taxonomy_slug_3  = $result[3]->taxonomySlug;
 $taxonomy_label_2 = $result[3]->taxonomyLabel;

 $category_id  = isset($_GET['category_id']) ? $_GET['category_id'] : '';
 $first_param  = isset($_GET['first_param']) ? $_GET['first_param'] : '';
 $second_param = isset($_GET['second_param']) ? $_GET['second_param'] : '';
 $third_param  = isset($_GET['third_param']) ? $_GET['third_param'] : '';
 $forth_param  = isset($_GET['forth_param']) ? $_GET['forth_param'] : '';


 $tax_query_category = ($category_id) ? array( array(
	 'taxonomy' => 'product_cat',
	 'field' => 'term_id', //Category ID is the key
	 'terms' => $category_id
 ) ) : false;


 $tax_query_taxonomy_slug_0 = ($first_param) ? array( array(
	 'taxonomy' => $taxonomy_slug_0,
	 'field' => 'term_id', //Category ID is the key
	 'terms' => $first_param
 ) ) : false;

 $tax_query_taxonomy_slug_1 = ($second_param) ? array( array(
	 'taxonomy' => $taxonomy_slug_1,
	 'field' => 'term_id', //Category ID is the key
	 'terms' => $second_param
 ) ) : false;

 $tax_query_taxonomy_slug_2 = ($third_param) ? array( array(
	 'taxonomy' => $taxonomy_slug_2,
	 'field' => 'term_id', //Category ID is the key
	 'terms' => $third_param
 ) ) : false;

 $tax_query_taxonomy_slug_3 = ($forth_param) ? array( array(
	'taxonomy' => $taxonomy_slug_3,
	'field' => 'term_id', //Category ID is the key
	'terms' => $forth_param
 ) ) : false;

if($category_id || $first_param || $second_param || $third_param ){
 $args = array(
	 'post_type'   => 'product',
	 'post_status' => 'publish',
	 'fields'      => 'ids', // Only get post IDs
	 'showposts'   => -1,
	 'tax_query' => array(
					'relation' => 'AND',
					array($tax_query_category, $tax_query_taxonomy_slug_0, $tax_query_taxonomy_slug_1, $tax_query_taxonomy_slug_2, $tax_query_taxonomy_slug_3 )
					)
 );
}

$product_list_ids = get_posts( $args );

$tax_3_list = array();
foreach ($product_list_ids as $id){
$current_id   = wp_get_object_terms( $id, $taxonomy_slug_3, array('fields' => 'ids'));
$current_name = wp_get_object_terms( $id, $taxonomy_slug_3, array('fields' => 'names'));

if (!empty($current_id)) {
	 array_push($tax_3_list, ['term_id' => $current_id, 'name' => $current_name]);
}
}
//echo '<pre>',print_r($ausfuhrung_list,1),'</pre>';

$temp = array();
foreach($tax_3_list as $item){
array_push($temp, ['id' => $item['term_id'][0], 'name' => $item['name'][0] ]);
}

//https://vijayasankarn.wordpress.com/2017/02/20/array_unique-for-multidimensional-array/
$terms = array_unique($temp, SORT_REGULAR);
//echo '<pre>',print_r($temp,1),'</pre>';
?>

<option value="" selected disabled><?php echo $taxonomy_label_3; ?></option>
<?php foreach ($terms as $term) { ?>
 <option value="<?php echo $term['id']; ?>"><?php echo $term['name']; ?></option>
<?php }

}


function load_products(){
	//https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary

	global $wpdb;
  $table_name = $wpdb->prefix.'oompa_filter';
  $result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );

  $taxonomy_id_0    = $result[0]->id;
  $taxonomy_slug_0  = $result[0]->taxonomySlug;
  $taxonomy_label_0 = $result[0]->taxonomyLabel;

  $taxonomy_id_1    = $result[1]->id;
  $taxonomy_slug_1  = $result[1]->taxonomySlug;
  $taxonomy_label_1 = $result[1]->taxonomyLabel;

  $taxonomy_id_2    = $result[2]->id;
  $taxonomy_slug_2  = $result[2]->taxonomySlug;
  $taxonomy_label_2 = $result[2]->taxonomyLabel;

  $taxonomy_id_3    = $result[3]->id;
  $taxonomy_slug_3  = $result[3]->taxonomySlug;
  $taxonomy_label_2 = $result[3]->taxonomyLabel;

 $paged = (isset($_GET['paged']) ) ? intval($_GET['paged']) : 1;
 $category_id  = isset($_GET['category_id']) ? $_GET['category_id'] : '';
 $first_param  = isset($_GET['first_param']) ? $_GET['first_param'] : '';
 $second_param = isset($_GET['second_param']) ? $_GET['second_param'] : '';
 $third_param  = isset($_GET['third_param']) ? $_GET['third_param'] : '';
 $forth_param  = isset($_GET['forth_param']) ? $_GET['forth_param'] : '';


 //IF category exist in array get it, else return FALSE. Very important
 $tax_query_category = ($category_id) ? array( array(
	 'taxonomy' => 'product_cat',
	 'field' => 'id', //Category ID is the key
	 'terms' => $category_id
 ) ) : false;

 $tax_query_taxonomy_slug_0 = ($first_param) ? array( array(
	'taxonomy' => $taxonomy_slug_0,
	'field' => 'term_id', //Category ID is the key
	'terms' => $first_param
 ) ) : false;

 $tax_query_taxonomy_slug_1 = ($second_param) ? array( array(
	'taxonomy' => $taxonomy_slug_1,
	'field' => 'term_id', //Category ID is the key
	'terms' => $second_param
 ) ) : false;

 $tax_query_taxonomy_slug_2 = ($third_param) ? array( array(
	'taxonomy' => $taxonomy_slug_2,
	'field' => 'term_id', //Category ID is the key
	'terms' => $third_param
 ) ) : false;

 $tax_query_taxonomy_slug_3 = ($forth_param) ? array( array(
 'taxonomy' => $taxonomy_slug_3,
 'field' => 'term_id', //Category ID is the key
 'terms' => $forth_param
 ) ) : false;



 //CATEGORY page args
 if($category_id || $first_param || $second_param || $third_param || $forth_param ){
 $args = array(
	 'post_type' => 'product',
	 'post_status' => 'publish',
	 'posts_per_page' => 15,
	 'tax_query' => array(
					 'relation' => 'AND',
					 array($tax_query_category, $tax_query_taxonomy_slug_0, $tax_query_taxonomy_slug_1, $tax_query_taxonomy_slug_1, $tax_query_taxonomy_slug_3 )
					 ),
	 'paged' => $paged
 );
 }
 //SHOP page args
 else{
 $args = array(
	 'post_type' => 'product',
	 'post_status' => 'publish',
	 'posts_per_page' => 4,
	 'paged' => $paged
 );

 }

 $products_loop = new WP_Query($args);

 if( $products_loop->have_posts() ):
	 while( $products_loop->have_posts() ): $products_loop->the_post();

 $price = get_post_meta( get_the_ID(), '_price', true );
 $image = wp_get_attachment_image_src( get_post_thumbnail_id( $products_loop->post->ID ), 'single-post-thumbnail' );
	$url =  get_permalink( $loop->post->ID );
 $product = wc_get_product( get_the_ID() );
 $type = $product->get_type();
 ?>

	 <div class="product reveal-item">
		 <div class="product-inner">

			 <div class="onlineshp-products-list-title-container">
				 <div class="">
					 <h3><?php the_title(); ?></h3>
				 </div>

			 </div>
				<a href="<?php echo $url ?>"><img src="<?php echo $image[0]; ?>"></img></A>

			 <h3 class="price"><?php echo wc_price( $price ); ?></h3>
			 <div class="product-actions">
				 <a href="<?php echo $url ?>"><i class="fal fa-shopping-basket"></i></a>
			 </div>
			 <a></a>

		 </div>
	 </div>

 <?php endwhile;

	 echo '<div class="oompa-filter-pagination">';
	 $big = 999999999;
	 echo paginate_links( array(
		 'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		 'format' => '?paged=%#%',
		 'current' => max( 1, $paged ),
		 'total' => $products_loop->max_num_pages
	 ) );
	 echo '</div>';
 endif;
 wp_reset_postdata();
 die();
}

}//Class END
