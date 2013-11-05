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
		return array('AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'SEK', 'SGD', 'THB', 'TWD', 'USD');
   }

    public function getDefaultCurrency() {
		return 'USD';
    }

    public function getCurrencyCode() {
		//set a default currency for first time use
		return $this->mSettings->manual_currency ? $this->mSettings->manual_currency : $this->getDefaultCurrency();
    }

    public function settingsForm() {
		$payment_data = array(
		    'currency' => $this->getCurrencyCode(),
	    	'currencies' => $this->getCurrencies(),
		);

		return WPET::getInstance()->display('gateway-manual.php', $payment_data, true);
    }

    public function settingsSave() {
	
    }

    public function getPaymentForm() {

   		wp_register_script( 'wpet-gateway-manual', WPET_PLUGIN_URL . 'js/gateway_manual.js', array( 'jquery' ) );
		wp_enqueue_script( 'wpet-gateway-manual');
		wp_localize_script( 'wpet-gateway-manual', 'wpet_manual_gateway', array(
			'name_required' => __( 'Name is required', 'wpet' ),
			'email_required' => __( 'Email is required', 'wpet' ),
		) );

		if ( isset( $_POST['submit'] ) && isset( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
			if ( ! is_email( $_POST['email'] ) || empty( $_POST['payee_name'] ) ) {
				//wp_die('errors!');
			} else {
				// Yay! It worked
				$payment = WPET::getInstance()->payment->loadPayment();
		
				$meta = array(
					'name' => $_POST['payee_name'],
					'email' => $_POST['email']
				);
		
				WPET::getInstance()->payment->update( $payment->ID, array( 'meta' => $meta ) );
				wp_update_post( array( 'ID' => $payment->ID, 'post_status' => 'pending' ) );
				wp_redirect( get_permalink( $payment->ID ) );
			}
		} else {
			$render_data = array(
				'cart' => WPET::getInstance()->payment->getCart(),
			);
	    
			if( isset( $_POST['email'] ) && ! is_email( $_POST['email'] ) ) {
				$render_data['invalid_email'] = 'Please enter a valid email address';
			}
			return WPET::getInstance()->getDisplay( 'gateway-manual.php', $render_data );
		}
    }

    

}

