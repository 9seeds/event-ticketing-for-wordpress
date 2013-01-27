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

		wp_enqueue_style( 'editor' );
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

	/**
	 * Displays the admin page in wp-admin
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {
		if ( ! empty($_POST['wpet_settings_nonce'] ) && wp_verify_nonce( $_POST['wpet_settings_nonce'], 'wpet_settings_update' ) ) {
			$this->submit( $_POST );
		}

		$tabs = apply_filters( 'wpet_settings_tabs', array() );

		$settings = apply_filters( 'wpet_settings', array() );

		$data = array(
		    'tabs' => $tabs,
		    'settings' => $this->sortByTab( $settings ),
		    'nonce' => wp_nonce_field( 'wpet_settings_update', 'wpet_settings_nonce', true, false ),
		);

		WPET::getInstance()->display( 'settings.php', $data );
	}

	/**
	 * Sets up the default settings tabs
	 *
	 * @since 2.0
	 * @param array $tabs
	 * @return array
	 */
	public function defaultTabs( $tabs ) {

	    $tabs['event'] = 'Events';
	    $tabs['payment'] = 'Payments';
	    $tabs['email'] = 'Email';
	    $tabs['form_display'] = 'Form Display';

	    return $tabs;
	}

	/**
	 * Adds the reset tab. In it's own function to move it to the far right
	 *
	 * @since 2.0
	 * @param array $tabs
	 * @return array
	 */
	public function resetTab( $tabs ) {
	    $tabs['reset'] = 'Reset';

	    return $tabs;
	}

	/**
	 * Sets up the default settings available
	 *
	 * @ince 2.0
	 * @param array $settings
	 * @return array
	 */
	public function defaultSettings( $settings ) {

		$event = WPET::getInstance()->events->getWorkingEvent();

		$event_data = array(
			'event_date' => $event->wpet_event_date,
			'organizer_name' => get_option( 'wpet_organizer_name', '' ),
			'organizer_email' => get_option( 'wpet_organizer_email', '' ),
			'max_attendance' => $event->wpet_max_attendance,
			'event_status' => $event->wpet_event_status,
			'closed_message' => get_option( 'wpet_closed_message', '' ),
			'thank_you' => get_option( 'wpet_thank_you', '' ),
		);

		$settings[] = array(
		    'tab' => 'event',
			'title' => 'Settings Title',
			'text' => WPET::getInstance()->getDisplay( 'settings-event.php', $event_data )
		);

		$email_data = array(
			'from_name' => get_option( 'wpet_from_name', '' ),
			'from_email' => get_option( 'wpet_from_email', '' ),
			'subject' => get_option( 'wpet_subject', '' ),
			'email_body' => get_option( 'wpet_email_body', '' ),
		);

		$settings[] = array(
		    'tab' => 'email',
			'title' => 'Disable upgrade nag?',
			'text' => WPET::getInstance()->getDisplay( 'settings-email.php', $email_data )
		);

		//@TODO real data
		$payment_data = array(
			'payment_gateway' => '',
			'currency' => get_option( 'wpet-currency'),
			'payment_gateway_status' => '',
			'sandbox_api_username' => get_option( 'wpet_sandbox_api_username', '' ),
			'sandbox_api_password' => get_option( 'wpet_sandbox_api_password', '' ),
			'sandbox_api_signature' => get_option( 'wpet_sandbox_api_signature', '' ),
			'live_api_username' => get_option( 'wpet_live_api_username', '' ),
			'live_api_password' => get_option( 'wpet_live_api_password', '' ),
			'live_api_signature' => get_option( 'wpet_live_api_signature', '' ),
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

	/**
	 * Sorts the settings into tabs
	 *
	 * @param array $settings
	 * @return array
	 */
	private function sortByTab( $settings ) {
	    $s = array();

	    foreach( $settings as $set ) {
			//echo '<pre>';var_dump($settings); echo '</pre>';
			$s[$set['tab']][] = array(
				'title' => $set['title'],
				'text' => $set['text']
			);
	    }
	    return $s;
	}

	/**
	 * Magic method. Will return the specified setting from the wp_options table
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
	    $name = 'wpet_' . str_replace( '_', '-', $name );
	    return get_option( $name, '' );
	}

	/**
	 * @since 2.0
	 * @uses wpet_settings_submit
	 */
	public function submit( $post ) {
		$options = $post['options'];

		//these go with the active event
		$event = (array)WPET::getInstance()->events->getWorkingEvent();

		$event['meta']['event_date'] = $options['event-date'];
		$event['meta']['max_attendance'] = $options['max-attendance'];
		$event['meta']['event_status'] = $options['event-status'];

		WPET::getInstance()->events->add( $event );

		//don't update these in options
		unset( $options['event_date'] );
		unset( $options['max_attendance'] );
		unset( $options['event_status'] );

		foreach ( $options as $key => $value ) {
			update_option( "wpet_{$key}", stripslashes( $value ) );
		}

		do_action( 'wpet_settings_submit', $post );
	}

}// end class