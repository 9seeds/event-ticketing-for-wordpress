<?php

require_once WPET_PLUGIN_DIR . 'lib/Gateway.class.php';

class WPET_Gateway_PayPalStandard extends WPET_Gateway {

	public function getName() {
		return 'PayPal Standard';
	}
	
	public function getImage() {
		return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';
	}

	public function getCurrencies() {
		return array('AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'SEK', 'SGD', 'THB', 'TWD', 'USD');
	}

	public function getCurrencyCode() {
		return $this->settings->paypal_standard_currency;
	}
	
	public function settingsForm() {
		$payment_data = array(
			'paypal_standard_currency' => $this->settings->paypal_standard_currency,
			'paypal_standard_currencies' => $this->getCurrencies(),
			'paypal_standard_status' => $this->settings->paypal_standard_status,
			'paypal_standard_status_menu' => $this->statusMenu( 'options[paypal_standard_status]', 'paypal_standard_status', $this->settings->paypal_standard_status ),
			'paypal_sandbox_api_username' => $this->settings->paypal_sandbox_api_username,
			'paypal_sandbox_api_password' => $this->settings->paypal_sandbox_api_password,
			'paypal_sandbox_api_signature' => $this->settings->paypal_sandbox_api_signature,
			'paypal_live_api_username' => $this->settings->paypal_live_api_username,
			'paypal_live_api_password' => $this->settings->paypal_live_api_password,
			'paypal_live_api_signature' => $this->settings->paypal_live_api_signature,
		);
			
		return WPET::getInstance()->display( 'gateway-paypal-standard.php', $payment_data, true );
	}

	public function settingsSave() {
		
	}
	
	public function getPaymentForm() {

	}

	public function processPayment() {

	}

	public function processPaymentReturn() {

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
	
}	
