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

class OOMPA_Taxonomy {

  private $oompa_filter_tax_label;
  private $oompa_filter_tax_name;

  function __construct(){
    $this->oompa_filter_tax_label = $oompa_filter_tax_label;
    $this->oompa_filter_tax_name = $oompa_filter_tax_name;

    $this->createDB();

    //add_action( 'init', array($this, 'register_taxonomy'), 0 );


  }

  public function createDB(){

    global $x3m_oompa_db_version;
    $x3m_oompa_db_version = '1.0';

          global $wpdb;
          global $x3m_oompa_db_version;

          $table_name = $wpdb->prefix . 'oompa_filter';

          $charset_collate = $wpdb->get_charset_collate();

          $sql = "CREATE TABLE $table_name (
            id int(9) NOT NULL AUTO_INCREMENT,
            taxonomySlug text NOT NULL,
            taxonomyLabel text NOT NULL,
            taxonomyOrder int(2) NOT NULL,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

          require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
          dbDelta( $sql );

          add_option( 'x3m_oompa_db_version', $x3m_oompa_db_version );

  }


  //https://www.billerickson.net/code/register-multiple-taxonomies/
  private function register_taxonomy($oompa_filter_tax_label, $oompa_filter_tax_name){

    $args = array(
        'label'        => __( 'Ausfuhrung', 'textdomain' ),
        'public'       => true,
        'rewrite'      => false,
        'hierarchical' => true
    );

    register_taxonomy( 'ausfuhrung', 'product', $args );

  }

}
