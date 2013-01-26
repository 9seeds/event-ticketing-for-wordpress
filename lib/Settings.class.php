<?php

/**
 * @since 2.0
 */
class WPET_Settings extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 30 );
		add_filter( 'wpet_settings', array( $this, 'defaultSettings' ) );
		add_filter( 'wpet_settings_tabs', array( $this, 'defaultTabs' ), 1 );
		
		add_filter( 'wpet_settings_tabs', array( $this, 'resetTab' ), 100 );
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-settings', WPET_PLUGIN_URL . 'js/admin_settings.js', array( 'jquery-ui-tabs', 'jquery-ui-datepicker', 'wpet-jquery-cookie' ) );
		wp_enqueue_script( 'wpet-admin-settings' );
	}
	
	/**
	 * Add Settings links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 * @uses wpet_settings_tabs, wpet_settings
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Settings', 'Settings', 'add_users', 'wpet_settings', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	public function renderAdminPage() {
		if ( ! empty($_POST['wpet_settings_nonce'] ) && wp_verify_nonce( $_POST['wpet_settings_nonce'], 'wpet_settings_update' ) ) {
			$this->update( $_POST );
		}
		
		/*$tabs = array(
			'event'   => array( 'label' => __( 'Event', 'wpet' ),   'tab_content' => WPET::getInstance()->getDisplay( 'settings-event.php' ) ),
			'payment' => array( 'label' => __( 'Payment', 'wpet' ), 'tab_content' => WPET::getInstance()->getDisplay( 'settings-payment.php' ) ),
			'email'   => array( 'label' => __( 'Email', 'wpet' ),   'tab_content' => WPET::getInstance()->getDisplay( 'settings-email.php' ) ),
			'reset'   => array( 'label' => __( 'Reset', 'wpet' ),   'tab_content' => WPET::getInstance()->getDisplay( 'settings-reset.php' ) ),
		);

		$tabs = apply_filters( 'wpet_settings_tabs', $tabs );
		
		$settings = apply_filters(
			'wpet_settings',
			$settings = array(
				'settings' => array(),
				'tabs' => $tabs,
				'nonce' => wp_nonce_field( 'wpet_settings_update', 'wpet_settings_nonce', true, false ),
		) );*/
		
		
		$tabs = apply_filters( 'wpet_settings_tabs', array() );
		
		$settings = apply_filters( 'wpet_settings', array() );
		
		$data = array(
		    'tabs' => $tabs,
		    'settings' => $this->sortByTab( $settings ),
		    'nonce' => wp_nonce_field( 'wpet_settings_update', 'wpet_settings_nonce', true, false ),
		);
		WPET::getInstance()->display( 'settings.php', $data );
	}
	
	public function defaultTabs( $tabs ) {
	    
	    $tabs['event'] = 'Events';
	    $tabs['payment'] = 'Payments';
	    $tabs['email'] = 'Email';
	    $tabs['form_display'] = 'Form Display';
	    
	    return $tabs;
	}
	
	public function resetTab( $tabs ) {
	    $tabs['reset'] = 'Reset';
	    
	    return $tabs;
	}

	public function defaultSettings( $settings ) {

		$event_data = array(
			//@TODO real data
			'event-date' => '04/27/2012',
			'organizer-name' => get_option( 'wpet-organizer-name', '' ),
			'organizer-email' => get_option( 'wpet-organizer-email', '' ),
			'max-attendance' => '',
			'event-status' => '',
			'coming-soon' => get_option( 'wpet-coming-soon', '' ),
			'thank-you' => get_option( 'wpet-thank-you', '' ),
		);
		
		$settings[] = array(
		    'tab' => 'event',
			'title' => 'Settings Title',
			'text' => WPET::getInstance()->getDisplay( 'settings-event.php', $event_data ) 
			);

		$email_data = array(
			'from-name' => get_option( 'wpet-from-name', '' ),
			'from-email' => get_option( 'wpet-from-email', '' ),
			'subject' => get_option( 'wpet-subject', '' ),
			'email-body' => get_option( 'wpet-email-body', '' ),
		);
		
		$settings[] = array(
		    'tab' => 'email',
			'title' => 'Disable upgrade nag?',
			'text' => WPET::getInstance()->getDisplay( 'settings-email.php', $email_data )
		);

		$payment_data = array(
			'payment-gateway' => '',
			'currency' => '',
			'payment-gateway-status' => '',
			'sandbox-api-username' => get_option( 'wpet-sandbox-api-username', '' ),
			'sandbox-api-password' => get_option( 'wpet-sandbox-api-password', '' ),
			'sandbox-api-signature' => get_option( 'wpet-sandbox-api-signature', '' ),
			'live-api-username' => get_option( 'wpet-live-api-username', '' ),
			'live-api-password' => get_option( 'wpet-live-api-password', '' ),
			'live-api-signature' => get_option( 'wpet-live-api-signature', '' ),
		);

		$settings[] = array(
		    'tab' => 'payment',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-payment.php', $payment_data )
		);
		$settings[] = array(
		    'tab' => 'form_display',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-form-display.php' )
		);
		$settings[] = array(
		    'tab' => 'reset',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-reset.php' )
		);

		return $settings;
	}
	
	private function sortByTab( $settings ) {
	    $s = array();
	    
	    
	    foreach( $settings AS $set ) {
		//echo '<pre>';var_dump($settings); echo '</pre>';
		$s[$set['tab']][] = array(
		    'title' => $set['title'],
		    'text' => $set['text']
		);
	    }
	    return $s;
	}

	/**
	 * @since 2.0
	 * @uses wpet_settings_save
	 */
	public function update( $post ) {
		$options = $post['options'];
		
		//these go with the active event
		//$this->updateEvent();		
		unset( $options['event-date'] );
		unset( $options['max-attendance'] );
		unset( $options['event-status'] );

		foreach ( $options as $key => $value ) {
			update_option( "wpet-{$key}", stripslashes( $value ) );
		}

		do_action( 'wpet_settings_save', $post );
	}

}// end class