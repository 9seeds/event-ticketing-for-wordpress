<?php

require_once WPET_PLUGIN_DIR . 'lib/Gateway.class.php';

class WPET_Gateway_PayPalExpress extends WPET_Gateway {

	const SANDBOX_NVP_API = 'https://api-3t.sandbox.paypal.com/nvp';
	const LIVE_NVP_API = 'https://api-3t.paypal.com/nvp';
	const SANDBOX_PPX_URL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout';
	const LIVE_PPX_URL = 'https://www.paypal.com/webscr?cmd=_express-checkout';
	const NVP_VERSION = '63.0';
	
	public function getName() {
		return 'PayPal Express';
	}
	
	public function getImage() {
		return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';		
	}

	public function getCurrencies() {
		return array('AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'SEK', 'SGD', 'THB', 'TWD', 'USD');
	}

	public function getDefaultCurrency() {
		return 'USD';
	}

	public function getCurrencyCode() {
		return empty( $this->mSettings->paypal_express_currency ) ? $this->getDefaultCurrency() : $this->mSettings->paypal_express_currency;
	}

	public function settingsForm() {
		$payment_data = array(
			'paypal_express_currency' => $this->getCurrencyCode(),
			'paypal_express_currencies' => $this->getCurrencies(),
			'paypal_express_status' => $this->mSettings->paypal_express_status,
			'paypal_express_status_menu' => $this->statusMenu( 'options[paypal_express_status]', 'paypal_express_status', $this->mSettings->paypal_express_status ),
			'paypal_sandbox_api_username' => $this->mSettings->paypal_sandbox_api_username,
			'paypal_sandbox_api_password' => $this->mSettings->paypal_sandbox_api_password,
			'paypal_sandbox_api_signature' => $this->mSettings->paypal_sandbox_api_signature,
			'paypal_live_api_username' => $this->mSettings->paypal_live_api_username,
			'paypal_live_api_password' => $this->mSettings->paypal_live_api_password,
			'paypal_live_api_signature' => $this->mSettings->paypal_live_api_signature,
		);
			
		return WPET::getInstance()->display( 'gateway-paypal-express.php', $payment_data, true );
	}

	/**
	 * Builds a select menu of packages
	 *
	 * @since 2.0
	 * @param string $name
	 * @param string $id
	 * @param string $selected_value
	 * @return string
	 */
	public function statusMenu( $name, $id, $selected_value ) {
	    $s = "<select name='{$name}' id='{$id}'>";

		$options = array(
			'sandbox' => __('Sandbox', 'wpet'),
			'live' => __('Live', 'wpet'),
		);
		
	    foreach ( $options as $value => $name ) {
			$s .= "<option value='{$value}' ";
			$s .= selected( $selected_value, $value, false ) ;
			$s .= ">{$name}</option>\n";
	    }

	    $s .= '</select>';
	    return $s;
	}

	public function settingsSave() {

	}

	public function getPaymentForm() {
		$render_data = array(
			'cart' => WPET::getInstance()->payment->getCart(),
		);
		return WPET::getInstance()->getDisplay( 'gateway-paypal-express.php', $render_data );
	}

	public function processPayment( $return_url ) {
		//@TODO maybe just pass the total in?
		$cart = WPET::getInstance()->payment->getCart();
		
		$nvp = array(
			'METHOD' => 'SetExpressCheckout',
			'VERSION' => self::NVP_VERSION,
			'PWD' => $this->mSettings->paypal_sandbox_api_password,
			'USER' => $this->mSettings->paypal_sandbox_api_username,
			'SIGNATURE' => $this->mSettings->paypal_sandbox_api_signature,
			'AMT' => $cart['total'],
			'PAYMENTACTION' => 'Sale',
			'CURRENCYCODE' => $this->getCurrencyCode(),
			'RETURNURL' => $return_url,
			'CANCELURL' => add_query_arg( array( 'cancel' => '1' ), $return_url ),
		);

		$nvpurl = $this->mSettings->paypal_express_status == 'live' ? self::LIVE_NVP_API : self::SANDBOX_NVP_API;

		$nvpurl = add_query_arg( $nvp, $nvpurl );
				
		$other_args = array(
			'sslverify' => false,
		);
				
		$resp = wp_remote_post( $nvpurl, $other_args );
		die(print_r($resp));
		$resp = PPHttpPost($method, $nvpStr, $cred, $env);


				
		if( isset( $resp['ACK'] )  && 'Success' == $resp['ACK'] ) {
			$paypalurl = $this->mSettings->paypal_express_status == 'live' ? self::LIVE_PPX_URL : self::SANDBOX_PPX_URL;
			$paypalurl = add_query_arg( array( 'token' => $resp['TOKEN'] ), $paypalurl );
			wp_redirect( $paypalurl );
			exit();
		} else {
			echo '<div class="ticketingerror">There was an error from PayPal<br />Error: <strong>' . urldecode($resp["L_LONGMESSAGE0"]) . '</strong></div>';
		}
		// echo '<pre>'; var_dump( $resp ); echo '</pre>';

	}

	public function processPaymentReturn() {
		/*
		// Make sure the proper items are set
		if(isset($_GET["token"]) && isset($_GET["PayerID"]) ) {
			//we will be using these two variables to execute the "DoExpressCheckoutPayment"
			//Note: we haven't received any payment yet.

			$token = $_GET["token"];
			$payer_id = $_GET["PayerID"];
                    
			$purchase = get_page_by_title( $ticket_id, OBJECT, 'wpevt_purchase' );
                    
			// Update the ticket with the PayPal details for posterity
			$post_content = unserialize( $purchase->post_content );
                    
			$post_content['token'] = $_GET['token'];
			$post_content['PayerID'] = $_GET['PayerID'];
                    
			$purchase->post_content = serialize( $post_content );
                    
			wp_update_post( $purchase );
                    
			$p = $o["paypalInfo"];
			$p = WPEVT::instance()->gateway()->getSettings();
			$cred = array("apiuser" => $p["paypalAPIUser"], "apipwd" => $p["paypalAPIPwd"], "apisig" => $p["paypalAPISig"]);
			$method = "DoExpressCheckoutPayment";
			$env = $p["paypalEnv"];
			$nvp = array(
				'TOKEN' => $_GET['token'],
				'PAYERID' => $_GET['PayerID'],
				'AMT' => number_format( $post_content['total'], 2 ),
				"PAYMENTACTION" => 'Sale',
				"CURRENCYCODE" => $p["paypalCurrency"],
				'RETURNURL' => $post_content['ticket_url'] . '&paymentSuccessful=' . $post_content['purchase_id'],
				'CANCELURL' => $post_content['ticket_url']
						 );
			$nvpStr = nvp($nvp);
			$resp = PPHttpPost($method, $nvpStr, $cred, $env);
                    
			echo '<pre>'; var_dump( $resp ); echo '</pre>';
			if( "SUCCESS" == strtoupper($resp["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($resp["ACK"]) ) {
				// Update post with payment info
				$post_content['TIMESTAMP'] = $resp['TIMESTAMP'];
				$post_content['CORRELATIONID'] = $resp['CORRELATIONID'];
				$post_content['PAYMENTTYPE'] = $resp['PAYMENTTYPE'];
				$post_content['ACK'] = $resp['ACK'];
				$post_content['ORDERTIME'] = $resp['ORDERTIME'];
				$post_content['AMT'] = $resp['AMT'];
				$post_content['FEEAMT'] = $resp['FEEAMT'];
				$post_content['TAXAMT'] = $resp['TAXAMT'];
				$post_content['PAYMENTSTATUS'] = $resp['PAYMENTSTATUS'];
                        
				$purchase->post_content = serialize( $post_content );

				wp_update_post( $purchase );
				header('Location: '. $post_content['ticket_url'] . '&paymentSuccessful=1');
			}
    
		}
		*/
	}
}

