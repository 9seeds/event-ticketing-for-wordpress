<?php

abstract class WPET_Gateway {

	protected $settings;
	
	public function __construct() {
		$this->settings = WPET::getInstance()->settings;
		add_filter( 'wpet_currencies', array( $this, 'filterCurrencies' ) );
	}

	public function filterCurrencies( $default_currencies ) {
		$my_currencies = $this->getCurrencies();
		foreach ( $default_currencies as $index => $currency_info ) {
			if ( ! in_array( $currency_info['code'], $my_currencies ) )
				unset( $default_currencies[$index] );
		}
		return $default_currencies;
	}
	
	//name
	abstract public function getName();
	
	//image
	abstract public function getImage();

	//supported currencies
	abstract public function getCurrencies();
	
	//settings_display
	abstract public function settingsForm();
	
	//settings_submit
	abstract public function settingsSave();

	//payment form to show
	abstract public function getPaymentForm();

	//processPayment / submit
	abstract public function processPayment();
	
	//processPaymentReturn / process_gateway_notification
	abstract public function processPaymentReturn();
	
}