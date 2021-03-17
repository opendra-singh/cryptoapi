<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cryptapi.io/
 * @since             1.0.0
 * @package           Cryptapi
 *
 * @wordpress-plugin
 * Plugin Name:       cryptapi
 * Plugin URI:        https://cryptapi.io/
 * Description:       Accept cryptocurrency payments on your WooCommerce website.
 * Version:           1.0.0
 * Author:            cryptapi
 * Author URI:        https://cryptapi.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cryptapi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CRYPTAPI_VERSION', '1.0.0' );

if( ! defined('CRYPTAPI_ADMIN_PARTIAL')){
	define('CRYPTAPI_ADMIN_PARTIAL', plugin_dir_path( __FILE__ ).'admin/partials/' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cryptapi-activator.php
 */
function activate_cryptapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cryptapi-activator.php';
	Cryptapi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cryptapi-deactivator.php
 */
function deactivate_cryptapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cryptapi-deactivator.php';
	Cryptapi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cryptapi' );
register_deactivation_hook( __FILE__, 'deactivate_cryptapi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cryptapi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cryptapi() {

	$plugin = new Cryptapi();
	$plugin->run();

}
run_cryptapi();
