<?php

require_once WPET_PLUGIN_DIR . 'lib/Gateway.class.php';

class WPET_Gateway_PayPalExpress extends WPET_Gateway {

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
		?><h3>PayPal Express Settings</h3><?php
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

