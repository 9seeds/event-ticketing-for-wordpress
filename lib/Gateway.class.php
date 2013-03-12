<?php

abstract class WPET_Gateway {

	protected $mSettings;
	protected $mPayment;
	
	public function __construct() {
		$this->mSettings = WPET::getInstance()->settings;
	}
	
	/**
	 * @since 2.0
	 * @param $payment WP_Post payment cpt object
	 */
	public function setPayment( $payment ) {
		$this->mPayment = $payment;

	}
	
	//name
	abstract public function getName();
	
	//image
	abstract public function getImage();

	//supported currencies
	abstract public function getCurrencies();

	//currently selected currency
	abstract public function getCurrencyCode();

	//currency to use if no settings set
	abstract public function getDefaultCurrency();
	
	//settings_display
	abstract public function settingsForm();
	
	//settings_submit - might not be needed if options[] is used
	abstract public function settingsSave();

	//payment form to show
	abstract public function getPaymentForm();
	
	//processPayment / submit
	abstract public function processPayment( $return_url );
	
	//processPaymentReturn / process_gateway_notification
	abstract public function processPaymentReturn();	
}