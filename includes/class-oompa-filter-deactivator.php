<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://oompa.de
 * @since      1.0.0
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    oompa_filter
 * @subpackage oompa_filter/includes
 * @author     Andrija Nikolic <andrija.nikolic@oompa.de>
 */
class OOMPA_Filter_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

			 function x3m_oompa_remove_db(){
			    global $wpdb;
			    $table_name = $wpdb->prefix . 'oompa_filter';
			    $sql = "DROP TABLE IF EXISTS $table_name";
			    $wpdb->query($sql);
					delete_option("x3m_oompa_db_version");
      }
      x3m_oompa_remove_db();
	}

}
