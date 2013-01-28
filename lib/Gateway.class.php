<?php

abstract class WPET_Gateway {

	//name
	//abstract public function getName();
	//instead set `public static $NAME`
	
	//image
	abstract public function getImage();

	//supported currencies
	abstract public function getCurrencies();
	
	//settings_display
	abstract public function settingsForm();
	
	//settings_submit
	abstract public function settingsSave();

	//processPayment / submit
	abstract public function processPayment();
	
	//processPaymentReturn / process_gateway_notification
	abstract public function processPaymentReturn();
	
}