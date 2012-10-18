<?php

require_once( 'WPEVT_PaymentGateway.class.php' );

/**
 * The basic WP Events Ticketing object. Only one instantiation of this object
 * ever exists at one time as this is a singleton.
 * 
 * To get access to this object
 * 
 * @example $wpevt = WPEVT::instance();
 * 
 * @since 1.4
 * @author Ben Lobaugh 
 */
class WPEVT {
    
    /**
     * Holds an instance of this object - singleton
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @var WPEVT 
     */
    private static $mInstance;
    
    /**
     * Holds a list of the payment gateway objects. Each object extends the
     * abstract 'interface' WPEVT_PaymentGateway
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @var Array
     */
    private $mPaymentGateways;
    
    
    private function __construct() {
        /*
         * When the action plugins_loaded happens fire off the method that will load
         * all of the payment gateways
         */
        add_action('init', array( $this, 'registerPaymentGateways'));

        /*
         * Load in the built-in payment gateways
         */
        add_action('plugins_loaded', array( $this, 'loadBuiltinPaymentGateways'));
    }
    
    /**
     * Get access to the WPEVT singleton object
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @return WPEVT 
     */
    public static function instance() {
        if (!isset(self::$mInstance)) {
            $className = __CLASS__;
            self::$mInstance = new $className;
        }
        return self::$mInstance;
    }	
    
    /**
     * Registers payment gateways with WPEVT through usage of a filter, thus
     * allowing other plugins to tap in and extend the possible payment gateways
     * 
     * Creates filters:
     * -  wpevt_register_payment_gateway
     * 
     * @since 1.4
     * @author Ben Lobaugh
     */
    public function registerPaymentGateways() {
        $gateways = apply_filters('wpevt_register_payment_gateway', array());
        $this->mPaymentGateways = $gateways;
    }

    /**
     * Load the files containing the built-in WPEVT payment gateways
     * 
     * Hooks are so cool event WPEVT uses them to register it's own payment gateways!!!
     * 
     * @see WPEVT_PaymentGateway
     * @see DemoGateway 
     * @since 1.4
     * @author Ben Lobaugh
     */
    public function loadBuiltinPaymentGateways() {
        require_once( WPEVT_DIR . '/payment_gateways/PayPalExpress.class.php' );
        require_once( WPEVT_DIR . '/payment_gateways/DemoGateway.class.php' );
    }
    
    /**
     * Return a listing of all the currently registered payment gateways
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @return Array 
     */
    public function getPaymentGateways() {
        return $this->mPaymentGateways;
    }
    
    /**
     * Returns an object representing the currently saved payment gateway from
     * the settings page
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @returns WPEVT_PaymentGateway 
     */
    public function gateway() {
        $o = get_option("eventTicketingSystem");
        $pg = $o['paymentGateway'];
        
//        echo '<pre>';
//        var_dump( $this->getPaymentGateways() );
//        echo '</pre>';
        
        // Find the gateway object
        foreach( $this->getPaymentGateways() AS $g ) {
            if( $g->slug() == $pg )
                return $g;
        }
        
        // If we reach here no gateway was found
        return null;
    }

} // end class
