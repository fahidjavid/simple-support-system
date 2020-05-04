<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fahidjavid.com
 * @since             1.0.0
 * @package           Simple_Support_System
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Support System
 * Plugin URI:        https://www.fahidjavid.com
 * Description:       Offers simple support system to provide Envato items support to the verified buyers of your items.
 * Version:           1.1.0
 * Author:            Fahid Javid
 * Author URI:        https://www.fahidjavid.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-support-system
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-support-system-activator.php
 */
function activate_simple_support_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-support-system-activator.php';
	Simple_Support_System_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-support-system-deactivator.php
 */
function deactivate_simple_support_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-support-system-deactivator.php';
	Simple_Support_System_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_support_system' );
register_deactivation_hook( __FILE__, 'deactivate_simple_support_system' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-support-system.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_support_system() {

	$plugin = new Simple_Support_System();
	$plugin->run();

}
run_simple_support_system();
