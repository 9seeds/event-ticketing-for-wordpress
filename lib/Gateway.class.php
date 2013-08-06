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
    public function setPayment($payment) {
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

    // if not overloaded will simply go to the next step
    public function processPayment() {
		$payment = WPET::getInstance()->payment->loadPayment();
		wp_update_post(array('ID' => $payment->ID, 'post_status' => 'processing'));
		wp_redirect(get_permalink($payment->ID));
    }

    // if not overloaded will simply go to the next step
    public function processPaymentReturn() {
		$payment = WPET::getInstance()->payment->loadPayment();
		wp_update_post(array('ID' => $payment->ID, 'post_status' => 'publish'));
		wp_redirect(get_permalink($payment->ID));
    }

}