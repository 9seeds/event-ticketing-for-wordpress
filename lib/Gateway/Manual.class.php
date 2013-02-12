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
		return $this->mSettings->manual_currency;
	}
	
	public function settingsForm() {
		$payment_data = array(
			//set a default currency for first time use
			'currency' => empty( $this->mSettings->manual_currency ) ? 'USD' : $this->mSettings->manual_currency,
			'currencies' => $this->getCurrencies(),
		);
		
		return WPET::getInstance()->display( 'gateway-manual.php', $payment_data, true );
	}

	public function settingsSave() {
		
	}

	public function getPaymentForm() {		
		$render_data = array(
			'cart' => WPET::getInstance()->payment->getCart(),
		);
		return WPET::getInstance()->display( 'gateway-manual.php', $render_data );
	}

	public function processPayment() {
		if ( isset( $_POST['submit'] ) ) {
			if ( ! is_email( $_POST['email'] ) || empty( $_POST['name'] ) ) {
				wp_die('errors!');
			} else {
				//die(print_r($_POST, true));
				WPET::getInstance()->payment->draftPayment();
			}
		}
	}

	public function processPaymentReturn() {
		WPET::getInstance()->payment->savePayment();
	}
}	
