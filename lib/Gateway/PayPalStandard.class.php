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
			'currency' => $this->settings->paypal_standard_currency,
			'currencies' => $this->getCurrencies(),
			'payment_gateway_status' => $this->settings->paypal_status,
			'sandbox_api_username' => $this->settings->paypal_sandbox_api_username,
			'sandbox_api_password' => $this->settings->paypal_sandbox_api_password,
			'sandbox_api_signature' => $this->settings->paypal_sandbox_api_signature,
			'live_api_username' => $this->settings->paypal_live_api_username,
			'live_api_password' => $this->settings->paypal_live_api_password,
			'live_api_signature' => $this->settings->paypal_live_api_signature,
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
}	
