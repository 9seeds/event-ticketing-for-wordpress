<?php

abstract class WPET_Gateway {

	protected $settings;
	
	public function __construct() {
		$this->settings = WPET::getInstance()->settings;
	}

	public function filterCurrencies( $default_currencies ) {
		$my_currencies = $this->getCurrencies();
		return $default_currencies;
	}
	
	//name
	abstract public function getName();
	
	//image
	abstract public function getImage();

	//supported currencies
	abstract public function getCurrencies();

	//currently selected currency
	abstract public function getCurrencyCode();
	
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