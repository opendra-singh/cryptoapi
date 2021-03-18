<?php

/**
 * Fired during plugin activation
 *
 * @link       https://cryptapi.io/
 * @since      1.0.0
 *
 * @package    Cryptapi
 * @subpackage Cryptapi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cryptapi
 * @subpackage Cryptapi/includes
 * @author     cryptapi <developer@cryptapi.io>
 */
class Cryptapi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			exit( sprintf( esc_html__( 'CryptAPI requires WooCommerce to be installed and active. You can download %s here.', 'cryptapi' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) );
		}

	}

}
