<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cryptapi.io/
 * @since      1.0.0
 *
 */
?>

<?php

class WC_CryptAPI_Gateway extends WC_Payment_Gateway
{
    public $cryptapi_coin_options = array(
        'btc' => 'Bitcoin',
        'bch' => 'Bitcoin Cash',
        'ltc' => 'Litecoin',
        'eth' => 'Ethereum',
        'xmr' => 'Monero',
        'tron' => 'TRON',
        'iota' => 'IOTA',
    );

    function __construct()
    {
        $this->id = 'cryptapi';
        $this->has_fields = true;
        $this->method_title = 'CryptAPI';
        $this->method_description = 'CryptAPI allows customers to pay in cryptocurrency';

        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => esc_html__('Enabled', 'cryptapi'),
                'type' => 'checkbox',
                'label' => esc_html__('Enable CryptAPI Payments', 'cryptapi'),
                'default' => 'yes'
            ),

            'title' => array(
                'title' => esc_html__('Title', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__('This controls the title which the user sees during checkout.', 'cryptapi'),
                'default' => esc_html__('Cryptocurrency', 'cryptapi'),
                'desc_tip' => true,
            ),
            
            'description' 	=> array(
                'title'       	=> esc_html__('Description', 'cryptapi'),
                'type'        	=> 'textarea',
                'default'     	=> esc_html__('Pay with cryptocurrency (BTC, BCH, LTC, ETH, XMR, TRON and IOTA)', 'cryptapi'),
                'description' 	=> esc_html__('Payment method description that the customer will see on your checkout', 'cryptapi' )
            ),

            'coins' => array(
                'title' => esc_html__('Accepted cryptocurrencies', 'cryptapi'),
                'type' => 'multiselect',
                'default' => '',
                'css' => 'height: 10rem;',
                'options' => $this->cryptapi_coin_options,
                'description' => esc_html__("Select which coins do you wish to accept. CTRL + click to select multiple", 'cryptapi'),
            ),

            'btc_address' => array(
                'title' => esc_html__('Bitcoin Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your Bitcoin address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),

            'bch_address' => array(
                'title' => esc_html__('Bitcoin Cash Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your Bitcoin Cash address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),

            'ltc_address' => array(
                'title' => esc_html__('Litecoin Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your Litecoin address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),

            'eth_address' => array(
                'title' => esc_html__('Ethereum Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your Ethereum address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),

            'xmr_address' => array(
                'title' => esc_html__('Monero Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your Monero address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),

            'iota_address' => array(
                'title' => esc_html__('IOTA Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your IOTA address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),
        );
    }
}