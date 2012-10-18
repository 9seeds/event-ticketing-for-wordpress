<?php


add_filter( 'wpevt_register_payment_gateway', 'wpevt_pg_demo' );

function wpevt_pg_demo( $gateways ) { 
    if( !class_exists( 'EVT_Payment_Gateway_Demo' ) ) {
        class EVT_Payment_Gateway_Demo extends WPEVT_PaymentGateway {

           public function settingsForm( $data ) {
              echo 'There are some possible settings here';
           }
           
           public function button() {
               return '<input type="submit" name="paymentButton" />';
           }
           
           public function processPayment( $args ) {
               echo 'processing demo payment';
           }
           public function saveSettings() {
               $data = array();
               parent::saveSettings($data);
           }
        } // end class
        $gateways[] = new EVT_Payment_Gateway_Demo( 'Demo Gateway', 'DemoGateway');
    } // end if( !class_exists( 'PayPalExpress' ) ) 
    return $gateways;
} // end function