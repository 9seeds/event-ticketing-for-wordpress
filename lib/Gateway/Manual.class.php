<?php

require_once WPET_PLUGIN_DIR . 'lib/Gateway.class.php';

class WPET_Gateway_Manual extends WPET_Gateway {

	public function getName() {
		return 'Manual';
	}
	
	public function getImage() {
		return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';
	}

	public function getCurrencies() {
		return array( 'USD' );
	}

	public function getCurrencyCode() {
		return $this->settings->manual_currency;
	}
	
	public function settingsForm() {
		$payment_data = array(
			'currency' => $this->settings->paypal_standard_currency,
			'currencies' => $this->getCurrencies(),
		);
		
		return WPET::getInstance()->display( 'gateway-manual.php', $payment_data, true );
	}

	public function settingsSave() {
		
	}

	public function getPaymentForm() {
		return WPET::getInstance()->display( 'gateway-manual.php' );
	}

	public function processPayment() {
		
	}

	public function processPaymentReturn() {

	}
}	
