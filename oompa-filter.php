<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://oompa.de
 * @since             1.0.0
 * @package           oompa_filter
 *
 * @wordpress-plugin
 * Plugin Name:       OOMPA Product Ajax Filter
 * Plugin URI:        https://oompa.de/oompa-filter-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        https://oompa.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oompa-filter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//https://developer.wordpress.org/plugins/

/**
Create Plugin Options Page
https://blog.wplauncher.com/create-wordpress-plugin-settings-page/
**/

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OOMPA_FILTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oompa-filter-activator.php
 */
function activate_oompa_filter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oompa-filter-activator.php';
	OOMPA_Filter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oompa-filter-deactivator.php
 */
function deactivate_oompa_filter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oompa-filter-deactivator.php';
	OOMPA_Filter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oompa_filter' );
register_deactivation_hook( __FILE__, 'deactivate_oompa_filter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oompa-filter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oompa_filter() {

	$plugin = new OOMPA_Filter();
	$plugin->run();

}
run_oompa_filter();
