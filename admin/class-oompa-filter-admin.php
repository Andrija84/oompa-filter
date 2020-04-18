<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/admin
 * @author     Andrija Nikolic <info@oompa.de>
 */
class OOMPA_Filter_Admin {

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
	 * @param      string    $oompa_filter       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $oompa_filter, $version ) {

		$this->oompa_filter = $oompa_filter;
		$this->version = $version;
		//$this->load_dependencies();

	  add_action( 'admin_menu', array ($this,'oompa_filter_add_admin_menu'), 9);
		add_action( 'admin_init', array ($this,'oompa_filter_define_section'), 10);
		//add_action( 'admin_enqueue_scripts', array ($this,'enqueue_scripts'), 8 );
		add_action( 'wp_ajax_insert_taxonomy', array ($this,'insert_taxonomy'), 11);
		add_action( 'wp_ajax_nopriv_insert_taxonomy', array ($this,'insert_taxonomy'), 12);

		add_action( 'wp_ajax_delete_taxonomy', array ($this,'delete_taxonomy'), 11);
		add_action( 'wp_ajax_nopriv_delete_taxonomy', array ($this,'delete_taxonomy'), 12);

		add_action( 'wp_ajax_save_sort', array ($this,'oompa_filter_save_reorder'), 13);
		add_action( 'wp_ajax_nopriv_save_sort', array ($this,'oompa_filter_save_reorder'), 14);




	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->oompa_filter, plugin_dir_url( __FILE__ ) . 'css/oompa-filter-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', false, '1.0.0' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
    wp_enqueue_script( 'jquery-ui-js', '//code.jquery.com/ui/1.12.1/jquery-ui.js', false, '1.0.0' );

		wp_enqueue_script( $this->oompa_filter, plugin_dir_url( __FILE__ ) . 'js/oompa-filter-admin.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( 'oompa-filter-taxonomy-ajax', plugin_dir_url( __FILE__ ) . 'js/oompa-filter-taxonomy.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'oompa-filter-taxonomy-ajax', 'oompa_filter_taxonomy_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );


		wp_enqueue_script( 'oompa-filter-taxonomy-reorder', plugin_dir_url( __FILE__ ) . 'js/oompa-filter-taxonomy-reorder.js', array( 'jquery', 'jquery-ui-sortable' ), '20150626', true );
		wp_localize_script( 'oompa-filter-taxonomy-reorder', 'oompa_filter_taxonomy_reorder', array(
		'security' => wp_create_nonce( 'oompa_filter_taxonomy_reorder_nonce' ),
		'success' => __( 'Taxonomy sort order has been saved.' ),
		'failure' => __( 'There was an error saving the sort order or you do not have proper permissions.' ) ) );



	}

  //Load all other decendent files
  private function load_dependencies() {

	}

   function slugify($text){
		  // replace non letter or digits by -
		  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
		  // transliterate
		  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		  // remove unwanted characters
		  $text = preg_replace('~[^-\w]+~', '', $text);
		  // trim
		  $text = trim($text, '-');
		  // remove duplicate -
		  $text = preg_replace('~-+~', '-', $text);
		  // lowercase
		  $text = strtolower($text);
		  if (empty($text)) {
		    return 'n-a';
		  }
		  return $text;
  }


	function insert_taxonomy(){

		global $wpdb;
		$table_name = $wpdb->prefix.'oompa_filter';

		$taxonomy_names = $_POST["taxonomy_name"];
		$taxonomy_labels = $_POST["taxonomy_label"];

    //https://www.php.net/manual/en/function.array-map.php
		$query_string_array = array_map(null, $taxonomy_names, $taxonomy_labels );

		for($i = 0; $i < count($query_string_array); ++$i) {
		    //echo $query_string_array[$i][0] .':'. $query_string_array[$i][1].'<br>';

				$taxonomy_name = $this->slugify( $query_string_array[$i][0]); //Function slugify is used to clean strings to feet url standard
				$taxonomy_label = $query_string_array[$i][1];

				$query = $wpdb->insert(
												$table_name,
												array(
																'taxonomySlug'  => $taxonomy_name,
																'taxonomyLabel' => $taxonomy_label,
															  'taxonomyOrder' => $i),
												array( '%s','%s', '%d' )
										 );
		}
	}


	function delete_taxonomy(){
    $id = $_POST['id'];
		global $wpdb;
		$table_name = $wpdb->prefix.'oompa_filter';
		//$result = $wpdb->deleted ( "DELETE FROM $table_name WHERE id = $id" );
		$wpdb->delete( $table_name, array( 'id' => $id ) );

	}

	function oompa_filter_add_admin_menu(  ) {
		 //add_menu_page(  $page_title, $menu_title,  $capability,  $menu_slug, callable $function = '',  $icon_url = '',  $position  )
	    add_menu_page( 'OOMPA Filter Settings', 'OOMPA Filter', 'manage_options', 'oompa-filter-page', array($this, 'oompa_filter_page_callback'), 'dashicons-excerpt-view' );
	}

	function oompa_filter_define_section(  ) {

      //register_setting( string $option_group, string $option_name, array $args = array() )
	    //register_setting( 'oompa_filter_group_name', 'oompa_filter_option_name' );
			register_setting( 'oompa_filter_group_name', '' );

      //add_settings_section( string $id, string $title, callable $callback, string $page )
	    add_settings_section(
	        'oompa_filter_section',
	        '<h3>Add new taxonomies here</h3>',
					array($this, 'oompa_filter_section_callback'),
	        'oompa_filter_group_name'
	    );
	}

  //Main filter admin page
	function oompa_filter_section_callback(  ) {
	    echo '<p><strong>You can insert up to 4 relatioal taxonomies</strong> + 1 that is default product category.</p><p>Default product category is first parameter always</p>';
      echo '<p>Copy shortcode anywhere on the page and it will display filter</p><p class="notice notice-info">[oompa-filters]</p>'
			?>

			<div class="new-taxonomy-btn-container">
					<button type="button" class="addRow button-success">Add new taxonomy</button>
			</div>
			<p class="notice notice-info">You can insert multiple taxonomies at once by adding new row</p>

			<div class="new-taxonomy-row-container">
					<input type="text" name="taxonomy_name[]" class="oompa-filter-taxonomy-name-input" placeholder="Taxonomy name" autocomplete="off">
					<input type="text" name="taxonomy_label[]" class="oompa-filter-taxonomy-label-input" placeholder="Taxonomy label" autocomplete="off">
					<button type="button" class="removeRow button-error">Remove</button>
			</div>

			<div class="newRow"></div>

			<?php
	}



//Display taxonomy table when plugin page is loaded
function oompa_filter_display_initial_taxonomy_table(){
	global $wpdb;
	$table_name = $wpdb->prefix.'oompa_filter';
	$result = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY taxonomyOrder ASC" );
		?>
		<p><strong>You can reorder taxonomies</strong>, drag and drop table rows as you wish. Reorder affects the actual relations between filter parameters.</p>
		<table id="oompa-filter-taxonomy-table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Label</th>
					<th>Order</th>
					<th>#</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $result as $print )   {
					?>
					<tr id="<?php echo $print->id; ?>" data-order="<?php echo $print->taxonomyOrder; ?>">
						<td><?php echo $print->taxonomySlug; ?></td>
						<td><?php echo $print->taxonomyLabel; ?></td>
						<td><?php echo $print->taxonomyOrder; ?></td>
						<td><button id="<?php echo $print->id; ?>" class="button-error delete-taxonomy">-</button></td>
					</tr>

			<?php  } ?>
	    </tbody>
		</table>
	<?php
}

	function oompa_filter_page_callback(  ) {
   //define('NONCE', 'CHECK');

		?>
		<h1>OOMPA Filter v1</h1>

  <!-- <form id="oompa-filter-main-form" action='<?php echo plugin_dir_url(__FILE__) ?>db.php' method='POST'> -->
		<form id="oompa-filter-main-form" action='' method=''>

        <?php
        //settings_fields( 'oompa_filter_group_name' );
        do_settings_sections( 'oompa_filter_group_name' );
        submit_button();
        ?>
    </form>
    <?php
		//https://www.php.net/manual/en/function.call-user-func.php
		call_user_func(array($this, 'oompa_filter_display_initial_taxonomy_table'));

	}


	//Save taxonomy reorder
	function oompa_filter_save_reorder() {
		if ( ! check_ajax_referer( 'oompa_filter_taxonomy_reorder_nonce', 'security' ) ) {
			return wp_send_json_error( 'Invalid Nonce' );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return wp_send_json_error( 'You are not allow to do this.' );
		}
		//Get reordered values
		$order = $_GET['reorder'];


		$counter = '';
		foreach( $order as $id ) {
			global $wpdb;
			$table_name = $wpdb->prefix.'oompa_filter';

			$wpdb->update( $table_name, array( 'taxonomyOrder' => $counter), array( 'id' => $id ), array( '%s' ) );

			$counter++;
		}
		wp_send_json_success( 'Taxonomy order Saved.' );
	}






}//Class END
