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
        'erc20' => 'ERC-20',
        'ltc' => 'Litecoin',
        'eth' => 'Ethereum',
        'xmr' => 'Monero',
        'tron' => 'TRON',
        'iota' => 'IOTA',
    );

    public $erc20_token_options = array(
        'usdt' => 'USDT',
        'usdc' => 'USDC',
        'busd' => 'BUSD',
        'pax' => 'PAX',
        'tusd' => 'TUSD',
        'bnb' => 'BNB',
        'link' => 'ChainLink',
        'cro' => 'Crypto.com Coin',
        'mkr' => 'Maker',
        'nexo' => 'NEXO',
        'bcz' => 'BECAZ',
    );

    public static $COIN_MULTIPLIERS = [
        'btc' => 100000000,
        'bch' => 100000000,
        'ltc' => 100000000,
        'eth' => 1000000000000000000,
        'tron' => 1000000000000000000,
        'usdt' => 1000000000000000000,
        'usdc' => 1000000000000000000,
        'busd' => 1000000000000000000,
        'pax' => 1000000000000000000,
        'tusd' => 1000000000000000000,
        'bnb' => 1000000000000000000,
        'link' => 1000000000000000000,
        'cro' => 1000000000000000000,
        'mkr' => 1000000000000000000,
        'nexo' => 1000000000000000000,
        'bcz' => 1000000000000000000,
        'iota' => 1000000,
        'xmr' => 1000000000000,
    ];

    function __construct()
    {
        $this->id = 'cryptapi';
        $this->has_fields = true;
        $this->method_title = 'CryptAPI';
        $this->method_description = 'CryptAPI allows customers to pay in cryptocurrency';
        $this->initiliaze_url = '';
        $this->init_form_fields();
        $this->init_settings();
        $this->cryptapi_settings();

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

            'erc20_tokens' => array(
                'title' => esc_html__('Accepted Tokens for ERC-20', 'cryptapi'),
                'type' => 'multiselect',
                'default' => '',
                'css' => 'height: 10rem;',
                'options' => $this->erc20_token_options,
                'description' => esc_html__("Select which tokens for ERC-20 do you wish to accept. CTRL + click to select multiple", 'cryptapi'),
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

            'erc20_address' => array(
                'title' => esc_html__('ERC-20 Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your ERC-20 address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
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

            'tron_address' => array(
                'title' => esc_html__('TRON Address', 'cryptapi'),
                'type' => 'text',
                'description' => esc_html__("Insert your TRON address here. Leave blank if you want to skip this cryptocurrency", 'cryptapi'),
                'desc_tip' => true,
            ),
        );
    }

    function cryptapi_settings() {
        $this->enabled = $this->get_option('enabled');
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->cryptapi_coins = $this->get_option('coins');
        $this->erc_20_tokens = $this->get_option('erc20_tokens');

        foreach ( array_keys(  $this->cryptapi_coin_options ) as $coin ) {
            $this->{ $coin . '_address'} = $this->get_option( $coin . '_address' );
        }
    }

    function needs_setup()
    {
        if ( empty( $this->cryptapi_coins) || !is_array( $this->cryptapi_coins ) ) return true;

        foreach ( $this->cryptapi_coins as $cryptapi_val ) {
            if ( !empty( $this->{ $cryptapi_val . '_address'} ) ) return false;
        }

        return true;
    }

    function payment_fields()
    { ?>
        <div class="form-row form-row-wide">
            <p><?php echo isset( $this->description ) ? $this->description : ""; ?></p>
            <ul style="list-style: none outside;">
                <?php
                if ( !empty( $this->cryptapi_coins ) && is_array( $this->cryptapi_coins ) ) {
                    foreach ( $this->cryptapi_coins as $cryptapi_val ) {
                        $cryptapi_addr = $this->{ $cryptapi_val . '_address' };
                        if ( !empty( $cryptapi_addr ) ) { ?>
                            <li>
                                <input id="payment_method_<?php echo isset( $cryptapi_val ) ? $cryptapi_val : ""; ?>" type="radio" class="payment_selected_coin input-radio" name="cryptapi_used_coin" value="<?php echo isset( $cryptapi_val ) ? $cryptapi_val : ""; ?>"/>
                                <label for="payment_method_<?php echo isset( $cryptapi_val ) ? $cryptapi_val : ""; ?>" style="display: inline-block;"><?php echo __('Pay with', 'cryptapi') . ' ' . isset( $this->cryptapi_coin_options[$cryptapi_val] ) ? $this->cryptapi_coin_options[$cryptapi_val] : ""; ?></label>
                            </li>
                            <?php
                        }
                    }
                    if ( in_array( "erc20", $this->cryptapi_coins ) ) { ?>
                            <input type='hidden' id='cryptapi_erc_active' value='yes' />
                            <select name='erc20_used_token' id='cryptapi_select_box' class='cryptapi_select_box_hide' style="margin:17px 0 0 -30px">
                            <option value=''><?php esc_html_e( 'Select ERC-20 Token', 'cryptapi' ); ?></option>
                        <?php
                        if( isset( $this->erc_20_tokens ) && !empty( $this->erc_20_tokens ) && is_array( $this->erc_20_tokens ) ) {
                            foreach ( $this->erc_20_tokens as $token ) { ?>
                                <option value='<?php echo isset( $token ) ? $token : ""; ?>'><?php echo isset( $this->erc20_token_options[$token] ) ? $this->erc20_token_options[$token] : ""; ?></option>
                                <?php
                            }
                        } ?>
                            </select>
                        <?php
                    }
                } ?>
            </ul>
        </div>
        <?php
    }

    function validate_fields()
    {
        return array_key_exists(sanitize_text_field($_POST['cryptapi_used_coin']), $this->cryptapi_coin_options);
    }

    function process_payment($order_id)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        global $woocommerce;

        if( isset( $_POST['cryptapi_used_coin'] ) && !empty( $_POST['cryptapi_used_coin'] ) ) {

            $selected_coin = sanitize_text_field($_POST['cryptapi_used_coin']);
            
            $sel_coin_addr = $this->{$selected_coin . '_address'};

            if ( isset($sel_coin_addr) && !empty( $sel_coin_addr ) && isset($selected_coin) && !empty( $selected_coin ) ) {
    
                $nonce = wp_create_nonce();
    
                $base_url = 'https://api.cryptapi.io/';

                $selected_coin1 = $selected_coin;
                if( $selected_coin == 'erc20' ) {
                    if( isset( $_POST['erc20_used_token'] ) && !empty( $_POST['erc20_used_token'] ) ) {
                        $selected_coin1 = $_POST['erc20_used_token'];
                    }
                    else {
                        wc_add_notice(__('Payment error:', 'woocommerce') . __('Choose Atleast one token for', 'cryptapi') . ' ' . $min_tx . ' ' . strtoupper($selected_coin1), 'error');
                        return null;
                    }
                }
    
                $prefix_url = "{$base_url}{$selected_coin1}/create/";

                if( $selected_coin == 'erc20' ) {
                    if( isset( $_POST['erc20_used_token'] ) && !empty( $_POST['erc20_used_token'] ) ) {
                        $prefix_url = "{$base_url}{$selected_coin}/{$selected_coin1}/create/";
                    }
                }
                
                $order = new WC_Order( isset( $order_id ) ? $order_id : "" );
    
                $order_received_url = $order->get_checkout_order_received_url();
                
                if( isset( $prefix_url ) && !empty( $prefix_url ) ) {

                    $send_first_url = add_query_arg(array(
                        'address' => isset( $sel_coin_addr ) ? $sel_coin_addr : "" ,
                        'callback' => isset( $order_received_url ) ? $order_received_url : "" ,
                        'invoice' => isset( $order_id ) ? $order_id : "" ,
                        'nonce' => isset( $nonce ) ? $nonce : "" ,
                    ), $prefix_url);

                    $this->initiliaze_url = isset( $send_first_url ) ? $send_first_url : "";

                    $total = $order->get_total('edit');

                    $this->info_url = 'https://api.cryptapi.io/'.$selected_coin1.'/info/';

                    if( $selected_coin == 'erc20' ) {
                        if( isset( $_POST['erc20_used_token'] ) && !empty( $_POST['erc20_used_token'] ) ) {
                            $this->info_url = 'https://api.cryptapi.io/'.$selected_coin.'/'.$selected_coin1.'/info/';
                        }
                    }
                    
                    $info = $this->cryptapi_use_curl('info');

                    $currency = get_woocommerce_currency();

                    $price = floatval($info->prices->USD);

                    if ( isset( $info->prices->{$currency} ) ) {
                        $price = floatval( $info->prices->{$currency} );
                    }

                    $crypto_total = $this->round_sig( $total / $price, 5 );

                    $min_tx = $this->convert_div( $info->minimum_transaction, $selected_coin1 );

                    if ($crypto_total < $min_tx) {
                        wc_add_notice(__('Payment error:', 'woocommerce') . __('Value too low, minimum is', 'cryptapi') . ' ' . $min_tx . ' ' . strtoupper($selected_coin1), 'error');
                        return null;
                    }
                        
                    $curl_req_for_add = $this->cryptapi_use_curl( 'address' );

                    if( isset( $curl_req_for_add ) && !empty( $curl_req_for_add ) && ( $curl_req_for_add == 'Success' ) ) {
                        
                        $order->add_meta_data( 'cryptapi_nonce', $nonce );
                        $order->add_meta_data( 'cryptapi_address', $this->address_in );
                        $order->add_meta_data( 'cryptapi_total', $crypto_total );
                        $order->add_meta_data( 'cryptapi_currency', $selected_coin );
                        $order->add_meta_data( 'cryptapi_currency_full_name', $this->cryptapi_coin_options[$selected_coin] );
                        if( $selected_coin == 'erc20' ) {
                            $order->add_meta_data( 'cryptapi_currency_token', $selected_coin1 );
                        }
                        $order->save_meta_data();
    
                        $order->update_status( 'on-hold', __( 'Awaiting payment', 'woothemes' ) . ': ' . $this->cryptapi_coin_options[$selected_coin] );
                        $woocommerce->cart->empty_cart();
    
                        return array(
                            'result' => 'success',
                            'redirect' => $this->get_return_url( $order )
                        );
                    }
                    else if( isset( $curl_req_for_add ) && !empty( $curl_req_for_add ) && ( $curl_req_for_add == 'error' ) ) {
                        wc_add_notice(__( 'Payment error: ', 'woocommerce' ) . __( $this->error_msg, 'woocommerce' ), 'error' );
                        return null;
                    }
                    else {
                        wc_add_notice(__( 'Payment error: ', 'woocommerce' ) . __( 'Payment Could not be proccessed, try Again', 'woocommerce' ), 'error' );
                        return null;
                    }
                }
            }
        }
        else {
            wc_add_notice(__( 'Payment error: ', 'woocommerce' ) . __( 'Choose Atleast one method to pay', 'woocommerce' ), 'error' );
            return null;
        }


            // try {
            //     $order = new WC_Order($order_id);
            //     $total = $order->get_total('edit');
            //     $currency = get_woocommerce_currency();

            //     $info = CryptAPI\Helper::get_info($selected);
                
            //     $min_tx = CryptAPI\Helper::convert_div($info->minimum_transaction, $selected);

            //     $price = floatval($info->prices->USD);
            //     if (isset($info->prices->{$currency})) {
            //         $price = floatval($info->prices->{$currency});
            //     }

            //     $crypto_total = $this->round_sig($total / $price, 5);

            //     if ($crypto_total < $min_tx) {
            //         wc_add_notice(__('Payment error:', 'woocommerce') . __('Value too low, minimum is', 'cryptapi') . ' ' . $min_tx . ' ' . strtoupper($selected), 'error');
            //         return null;
            //     }

            //     $ca = new CryptAPI\Helper($selected, $addr, $callback_url, [], true);
            //     $addr_in = $ca->get_address();

            //     $order->add_meta_data('cryptapi_nonce', $nonce);
            //     $order->add_meta_data('cryptapi_address', $addr_in);
            //     $order->add_meta_data('cryptapi_total', $crypto_total);
            //     $order->add_meta_data('cryptapi_currency', $selected);
            //     $order->save_meta_data();

            //     $order->update_status('on-hold', __('Awaiting payment', 'woothemes') . ': ' . $this->coin_options[$selected]);
            //     $woocommerce->cart->empty_cart();

            //     return array(
            //         'result' => 'success',
            //         'redirect' => $this->get_return_url($order)
            //     );

            // } catch (Exception $e) {
            //     wc_add_notice(__('Payment error:', 'woothemes') . 'Unknown coin', 'error');
            //     return;
            // }

        // wc_add_notice(__('Payment error:', 'woocommerce') . __('Payment could not be processed, please try again', 'woocommerce'), 'error');
        // return null;
    }

    private function round_sig($number, $sigdigs = 5)
    {
        $multiplier = 1;
        while ($number < 0.1) {
            $number *= 10;
            $multiplier /= 10;
        }
        while ($number >= 1) {
            $number /= 10;
            $multiplier *= 10;
        }
        return round($number, $sigdigs) * $multiplier;
    }

    private function cryptapi_use_curl( $ca_for )
    {
        $ca_curl = curl_init();
        curl_setopt( $ca_curl, CURLOPT_HEADER, false );
        curl_setopt( $ca_curl, CURLOPT_HTTPHEADER, array( 'Accept: application/json', 'Accept-Language: en_US' ) );
        curl_setopt( $ca_curl, CURLOPT_VERBOSE, 1 );
        curl_setopt( $ca_curl, CURLOPT_TIMEOUT, 30 );
        if( $ca_for == 'address' ) {
            curl_setopt( $ca_curl, CURLOPT_URL, $this->initiliaze_url );
        }
        else if( $ca_for == 'info' ) {
            curl_setopt( $ca_curl, CURLOPT_URL, $this->info_url );
        }
        curl_setopt( $ca_curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ca_curl, CURLOPT_SSLVERSION, 6 );
        $ca_curl_response = curl_exec( $ca_curl );
        curl_close( $ca_curl );
        if( $ca_for == 'address' ) {
            $ca_curl_response_array = json_decode( $ca_curl_response, true );
            if( isset( $ca_curl_response_array['status'] ) && !empty( $ca_curl_response_array['status'] ) ) {
                if( $ca_curl_response_array['status'] == 'success' ) {
                    $this->address_in = isset( $ca_curl_response_array['address_in'] ) ? $ca_curl_response_array['address_in'] : "";
                    $this->address_out = isset( $ca_curl_response_array['address_out'] ) ? $ca_curl_response_array['address_out'] : "";
                    $this->callback_url = isset( $ca_curl_response_array['callback_url'] ) ? $ca_curl_response_array['callback_url'] : "";
                    $this->priority = isset( $ca_curl_response_array['priority'] ) ? $ca_curl_response_array['priority'] : "";
                    return 'Success';
                }
                else if( $ca_curl_response_array['status'] == 'error' ) {
                    $this->error_msg = isset( $ca_curl_response_array['error'] ) ? $ca_curl_response_array['error'] : "" ;
                    return 'error';
                }
                else {
                    return 'False';
                }
            }
        }
        else if( $ca_for == 'info' ) {
            return json_decode( $ca_curl_response );
        }
    }

    private function convert_div( $val, $coin ) {
        return $val / WC_CryptAPI_Gateway::$COIN_MULTIPLIERS[$coin];
    }
}