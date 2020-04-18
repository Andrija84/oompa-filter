<?php

/**
 * Fired during plugin activation
 *
 * @link       https://oompa.de
 * @since      1.0.0
 *
 * @package    oompa_filter
 * @subpackage oompa_filter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    oompa_filter
 * @subpackage oompa_filter/includes
 * @author     Andrija Nikolic <andrija.nikolic@oompa.de>
 */
class OOMPA_Filter_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    //Add database at plugin activation
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-oompa-filter-taxonomy.php';
		OOMPA_Taxonomy::createDB();
  }

}
