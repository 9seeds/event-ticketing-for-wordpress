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

	public function settingsForm() {
		return WPET::getInstance()->display( 'gateway-paypal-standard.php' );
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
