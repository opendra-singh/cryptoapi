<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cryptapi.io/
 * @since      1.0.0
 *
 * @package    Cryptapi
 * @subpackage Cryptapi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cryptapi
 * @subpackage Cryptapi/public
 * @author     cryptapi <developer@cryptapi.io>
 */
class Cryptapi_Public {

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

	public static $COIN_MULTIPLIERS = [
        'btc' => 100000000,
        'bch' => 100000000,
        'ltc' => 100000000,
        'eth' => 1000000000000000000,
        'iota' => 1000000,
        'xmr' => 1000000000000,
    ];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Cryptapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cryptapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cryptapi-public.css', array(), $this->version, 'all' );

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
		 * defined in Cryptapi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cryptapi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cryptapi-public.js', array( 'jquery' ), $this->version, false );

	}

	function cryptapi_thankyou_page( $order_id ) {

		$order = new WC_Order( $order_id );
        $total = $order->get_total();
        $currency_symbol = get_woocommerce_currency_symbol();
        $address_in = $order->get_meta('cryptapi_address');
        $crypto_value = $order->get_meta('cryptapi_total');
        $crypto_coin = $order->get_meta('cryptapi_currency');
        $crypto_coin_full_name = $order->get_meta('cryptapi_currency_full_name');
        $crypto_token = $order->get_meta('cryptapi_currency_token');

		$show_crypto_coin = $crypto_coin;
        if ($show_crypto_coin == 'iota') $show_crypto_coin = 'miota';

		$qr_value = $crypto_value;
        if (in_array($crypto_coin, array('eth', 'iota'))) $qr_value = $this->convert_mul( $crypto_value, $crypto_coin );

		wp_enqueue_style( 'thank-you-page', plugin_dir_url( __FILE__ ) . 'css/cryptapi-thankyou.css', array(), $this->version, 'all' );
		wp_enqueue_script( 'qr-code-min', plugin_dir_url( __FILE__ ) . 'js/kjua-0.9.0.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'qr-code-script', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery' ), $this->version, false );
		?>
		<div class='loader-center'><div class="loader"></div></div>
		<h4 class='text-center'><?php esc_html_e( 'Waiting for payment' ); ?></h4>
		<div id="container" class='ca_slite_container'>-
			<div class='ca_right_hide'>
				<textarea class='ca_right_hide' id="text"></textarea>
				<input type='hidden' class='ca_right_hide' id="address" value='<?php echo $address_in; ?>' />
				<input class='ca_right_hide' type='hidden' id="currency" value='<?php echo $crypto_coin_full_name; ?>' />
				<input class='ca_right_hide' type='hidden' id="amount" value='<?php echo $qr_value; ?>' />
			</div>
			<div class="ca_right_hide">
				<input class="ca_right_hide" id="image" type="file" />
			</div>
		</div>
		<div style="width: 100%; margin: 2rem auto; text-align: center;">
			<?php echo __('In order to confirm your order, please send', 'cryptapi') ?>
			<span style="font-weight: 500"><?php echo $crypto_value ?></span>
			<span style="font-weight: 500"><?php echo strtoupper($show_crypto_coin) ?></span>
			<?php if( $show_crypto_coin == 'erc20' ) { ?>
				<span style="font-weight: 500"><?php echo ' - ' .strtoupper( $crypto_token ); ?></span>
				<?php
			} ?>
			(<?php echo $currency_symbol . ' ' . $total; ?>)
			<?php echo __('to', 'cryptapi') ?>
			<span style="font-weight: 500"><?php echo $address_in ?></span>
		</div>
		<?php
	}

	private function convert_mul( $val, $coin ) {
        return $val / Cryptapi_Public::$COIN_MULTIPLIERS[$coin];
    }

	function validate_payment($a) {
		print_r($a);
		die;
	}

}
