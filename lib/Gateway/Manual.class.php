<?php

require_once WPET_PLUGIN_DIR . 'lib/Gateway.class.php';

class WPET_Gateway_Manual extends WPET_Gateway {

	public function getName() {
		return 'PayPal Standard';
	}
	
	public function getImage() {
		return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';
	}

	public function getCurrencies() {
		return array( 'USD' );
	}

	public function settingsForm() {
		?><h3>Manual Payment Settings</h3><?php
	}

	public function settingsSave() {
		
	}

	public function getPaymentForm() {
		return 'Give me all your cash!</h1>';
	}

	public function processPayment() {
		
	}

	public function processPaymentReturn() {

	}
}	
