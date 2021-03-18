<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cryptapi.io/
 * @since      1.0.0
 *
 * @package    Cryptapi
 * @subpackage Cryptapi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */
class Cryptapi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Cryptapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cryptapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cryptapi-admin.css', array(), $this->version, 'all' );

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
		 * defined in Cryptapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cryptapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cryptapi-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Function is used to inherit the payment method class and include it's file.
	 *
	 * @since    1.0.0
	 * 
	 */
	function cryptapi_loader() {
		
		include_once( CRYPTAPI_ADMIN_PARTIAL . 'cryptapi-admin-display.php');

		$cryptapi_gateway = new WC_CryptAPI_Gateway();
	}

	/**
	 * Function is used to include gateway class in the existing woocommerce gateways.
	 *
	 * @since    1.0.0
	 * 
	 */
	function cryptapi_payment_gateway( $methods ) {

		$methods[] = 'WC_CryptAPI_Gateway';
		return $methods;

	}

}
