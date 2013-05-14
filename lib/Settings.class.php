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

		add_action( 'wpet_settings_submit', array( $this, 'submit_form_display' ) );
	}

	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
	    $screen->add_help_tab(
		    array(
			'id'	=> 'overview',
			'title'	=> __( 'Overview' ),
			'content'	=> '<p>' . __( 'This screen provides access to all the settings for WP Event Ticketing.', 'wpet' ) . '</p>'
		    )
	    );

	    $screen->add_help_tab(
		    array(
			'id'	=> 'default-values',
			'title'	=> __( 'Default Values' ),
			'content'	=> '<p>' . __( 'When you install WP Event Ticketing, the following settings are set by default:', 'wpet' ) . '</p>' .
				'<p><strong>' . __( 'Events Tab:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( 'Event Status: Registration is closed', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Payments Tab:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( 'Payment Gateway: Manual', 'wpet' ) .'</li>' .
				'<li>' . __( 'Currency: United States Dollar ($)', 'wpet' ) .'</li>' .
				'<p><strong>' . __( 'Form Display Tab:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( 'Show # of remaining packages: Checked', 'wpet' ) .'</li>' ,
		    )
	    );

	    $screen->add_help_tab(
		    array(
			'id'	=> 'tabs',
			'title'	=> __( 'Tabs' ),
			'content'	=> '<p>' . __( 'Here is a discription of each tab\'s functionality:', 'wpet' ) . '</p>' .
				'<p><strong>' . __( 'Events:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( '<strong>Event Date:</strong> The date the event will be held on. If you are running a multi-day event, set this to the first day of the event.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Organizer Name:</strong> The name of the organizer. This will be used for outgoing emails from the system.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Organizer Email:</strong> The email address for the organizer. This will be used for outgoing emails from the system.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Maximum Attendance:</strong> This is the total amount of tickets that can be sold for this event.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Event Status:</strong> Allows you to turn ticket sales on/off for the event. When registration is closed, no tickets are able to be purchased.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Closed Message Text:</strong> This is the text that will be shown in place of the order from when registration is closed.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Thank You Page Text:</strong> THis is the text that will be displayed after a ticket is purchased.', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Payments:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( '<strong>Payment Gateway:</strong> Select which method you will use to accept payments.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Currency:</strong> Select which currency you want funds paid in.', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Email:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( '<strong>Subject:</strong> This is the subject of the email that will get sent to the purchaser upon completion of an order.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Email Body:</strong> This will be the content of the email that gets sent to the purchaser upon completion of an order. Be sure to include the [ticketlinks] shortcode in the email body to send the buyer the link to edit their ticket information.', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Form Display:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( '<strong>Show # of Remaining Packages: </strong> Unchecking this setting will remove the column from the order form that displays how many of each package are still available for purchase.', 'wpet' ) . '</li>' .
				'<li>' . __( '<strong>Hide Coupons:</strong> If you do not intend to offer coupons, check this box and the coupon field will be removed from the order form.', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Reset:', 'wpet' ) .'</strong></p>' .
				'<li>' . __( '<strong>ALL CHECKBOXES</strong> Checking any of these boxes and clicking the Save All Settings button will delete any changes you added to the system. This can not be undone.', 'wpet' ) . '</li>' ,
		    )
	    );

	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-settings', WPET_PLUGIN_URL . 'js/admin_settings.js', array( 'jquery-ui-tabs', 'jquery-ui-datepicker', 'wpet-jquery-cookie' ) );
		wp_enqueue_script( 'wpet-admin-settings' );
		wp_localize_script( 'wpet-admin-settings', 'resetL10n',
							array( 'message' => __( "Are you sure you want to reset these?:\n{reset_list}", 'wpet' ) )
		);

		wp_localize_script( 'wpet-admin-settings', 'settings_check',
							array( 'max_attendees_not_numeric' => __( 'Max attendance must be numeric', 'wpet' ),
							'event_date_required' => __( 'Event Date is required', 'wpet' ),
							'organizer_name_required' => __( 'Organizer Name is required', 'wpet' ),
							'organizer_email_required' => __( 'Organizer Email is required', 'wpet' ),
							'options_subject_required' => __( 'Email Subject is required', 'wpet' ),
							'options_email_body_required' => __( 'Email Body is required', 'wpet' ),

		) );

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
		$menu[] = array( __( 'Settings', 'wpet' ),
						 __( 'Settings', 'wpet' ),
						 'add_users',
						 'wpet_settings',
						 array( $this, 'renderAdminPage' ) );
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

	    $tabs['event'] = __( 'Events', 'wpet' );
	    $tabs['payment'] = __( 'Payments', 'wpet' );
	    $tabs['email'] = __( 'Email', 'wpet' );
	    $tabs['form_display'] = __( 'Form Display', 'wpet' );

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
	    $tabs['reset'] = __( 'Reset', 'wpet' );

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
			'organizer_name' => $this->organizer_name,
			'organizer_email' => $this->organizer_email,
			'max_attendance' => $event->wpet_max_attendance,
			'event_status' => $event->wpet_event_status,
			'closed_message' => $this->closed_message,
			'thank_you' => $this->thank_you,
		);

		$settings[] = array(
		    'tab' => 'event',
			'title' => 'Settings Title',
			'text' => WPET::getInstance()->getDisplay( 'settings-event.php', $event_data, true )
		);

		$email_data = array(
			//'from_name' => $this->from_name,
			//'from_email' => $this->from_email,
			'subject' => $this->subject,
			'email_body' => $this->email_body,
		);

		$settings[] = array(
		    'tab' => 'email',
			'title' => 'Disable upgrade nag?',
			'text' => WPET::getInstance()->getDisplay( 'settings-email.php', $email_data, true )
		);

		$payment_data = array(
			'payment_gateway' => $this->payment_gateway,
		);

		$settings[] = array(
		    'tab' => 'payment',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-payment.php', $payment_data, true )
		);

		$form_display = array(
		  'show_package_count' => $this->show_package_count,
		    'hide_coupons' => $this->hide_coupons
		);

		//@TODO real data
		$settings[] = array(
		    'tab' => 'form_display',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-form-display.php', $form_display, true )
		);
		$settings[] = array(
		    'tab' => 'reset',
			'title' => 'Second email',
			'text' => WPET::getInstance()->getDisplay( 'settings-reset.php', array(), true )
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
	    return get_option( "wpet_{$name}", '' );
	}

	public function __set( $key, $value ) {
	    return update_option( "wpet_{$key}", $value );
	}

	/**
	 * @since 2.0
	 * @uses wpet_settings_submit
	 */
	public function submit( $post ) {
		$options = $post['options'];

		//these go with the active event
		$event = (array)WPET::getInstance()->events->getWorkingEvent();

		$event['meta']['event_date'] = $options['event_date'];
		$event['meta']['max_attendance'] = $options['max_attendance'];
		$event['meta']['event_status'] = $options['event_status'];

		WPET::getInstance()->events->add( $event );

		//don't update these in options
		unset( $options['event_date'] );
		unset( $options['max_attendance'] );
		unset( $options['event_status'] );

		//@TODO do the resets here
		unset( $options['reset'] );

		foreach ( $options as $key => $value ) {
			$this->{$key} = stripslashes( $value );
		}

		do_action( 'wpet_settings_submit', $post );
	}

	/**
	 * Handles the Form Display checkboxes on Settings save.
	 *
	 * Caution: Uses reverse logic
	 *
	 * @since 2.0
	 * @param array $post
	 */
	public function submit_form_display( $post ) {

	    if( ! isset( $post['options']['show_package_count'] ) )
			update_option ( 'wpet_show_package_count', '0' );

	    if( ! isset( $post['options']['hide_coupons'] ) )
			update_option( 'wpet_hide_coupons', '0' );

	}

	/**
	 * Builds a select menu of payment gateways
	 *
	 * @since 2.0
	 * @param string $name
	 * @param string $id
	 * @param string $selected_value
	 * @return string
	 */
	public function gatewaySelectMenu( $name, $id, $selected_value = NULL ) {
	    $s = "<select name='{$name}' id='{$id}'>";

	    foreach ( WPET::getInstance()->getGateways() as $id => $gateway ) {
			$s .= "<option value='{$id}' ";
			$s .= selected( $selected_value, $id, false ) ;
			$s .= ">{$gateway->getName()}</option>\n";
	    }

	    $s .= '</select>';
	    return $s;
	}


}// end class