<?php

/**
 * @since 2.0
 */
class WPET_Settings extends WPET_Module {

	private $message = NULL;
	
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
			'title'	=> __( 'Overview', 'wpet' ),
			'content'	=> '<p>' . __( 'This screen provides access to all the settings for WP Event Ticketing.', 'wpet' ) . '</p>'
		    )
	    );

	    $screen->add_help_tab(
		    array(
			'id'	=> 'default-values',
			'title'	=> __( 'Default Values', 'wpet' ),
			'content'	=> '<p>' . __( 'When you install WP Event Ticketing, the following settings are set by default:', 'wpet' ) . '</p>' .
				'<p><strong>' . __( 'Event Tab', 'wpet' ) .':</strong></p>' .
				'<li>' . __( 'Event Status: Registration is closed', 'wpet' ) . '</li>' .
				'<p><strong>' . __( 'Payments Tab', 'wpet' ) .':</strong></p>' .
				'<li>' . __( 'Payment Gateway: Manual', 'wpet' ) .'</li>' .
				'<li>' . __( 'Currency: United States Dollar ($)', 'wpet' ) .'</li>' .
				'<p><strong>' . __( 'Form Display Tab', 'wpet' ) .':</strong></p>' .
				'<li>' . __( 'Show # of remaining packages: Checked', 'wpet' ) .'</li>' ,
		    )
	    );

	    $screen->add_help_tab(
		    array(
			'id'	=> 'tabs',
			'title'	=> __( 'Tabs', 'wpet' ),
			'content'	=> '<p>' . __( 'Here is a discription of each tab\'s functionality:', 'wpet' ) . '</p>' .
				'<p><strong>' . __( 'Event', 'wpet' ) .':</strong></p>' .
				'<li>'. sprintf( __( '%sEvent Date%s is the date the event will be held on. If you are running a multi-day event, set this to the first day of the event.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sOrganizer Name', 'wpet' ). '</strong> '. __( ' is the name of the organizer. This will be used for outgoing emails from the system.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sOrganizer Email%s is the email address for the organizer. This will be used for outgoing emails from the system.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sMaximum Attendance%s is the total amount of tickets that can be sold for this event.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sEvent Status%s allows you to turn ticket sales on/off for the event. When registration is closed, no tickets are able to be purchased.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sClosed Message Text%s is the text that will be shown in place of the order from when registration is closed.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sThank You Page Text%s is the text that will be displayed after a package is purchased.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<p><strong>' . __( 'Payments', 'wpet' ) .':</strong></p>' .
				'<li>'. sprintf( __( '%sPayment Gateway%s lets you select which method you will use to accept payments.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sCurrency%s lets you select which currency you want funds paid in.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<p><strong>' . __( 'Email', 'wpet' ) .':</strong></p>' .
				'<li>'. sprintf( __( '%sSubject%s is the subject of the email that will get sent to the purchaser upon completion of an order.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sEmail Body%s is the content of the email that gets sent to the purchaser upon completion of an order. Be sure to include the %s shortcode in the email body to send the buyer the link to edit their ticket information.', 'wpet' ), '<strong>', '</strong>', '[ticketlinks]' ) . '</li>' .
				'<p><strong>' . __( 'Form Display', 'wpet' ) .':</strong></p>' .
				'<li>'. sprintf( __( '%sShow # of Remaining Packages%s, Unchecking this setting will remove the column from the order form that displays how many of each package are still available for purchase.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<li>'. sprintf( __( '%sHide Coupons%s, If you do not intend to offer coupons, check this box and the coupon field will be removed from the order form.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' .
				'<p><strong>' . __( 'Reset', 'wpet' ) .':</strong></p>' .
				'<li>'. sprintf( __( '%sALL CHECKBOXES%s, Checking any of these boxes and clicking the Save All Settings button will delete any changes you added to the system. This can not be undone.', 'wpet' ), '<strong>', '</strong>' ) . '</li>' ,
		    )
	    );
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-settings', WPET_PLUGIN_URL . 'js/admin_settings.js', array( 'jquery-ui-tabs', 'jquery-ui-datepicker' ) );
		wp_enqueue_script( 'wpet-admin-settings' );
		wp_localize_script( 'wpet-admin-settings', 'resetL10n',
							array( 'message' => __( "Are you sure you want to archive your current event?", 'wpet' ) )
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
		if ( !empty($_POST) /* ! empty($_POST['wpet_settings_nonce'] ) && wp_verify_nonce( $_POST['wpet_settings_nonce'], 'wpet_settings_update' ) */) {
			$this->submit( $_POST );
		}

		$tabs = apply_filters( 'wpet_settings_tabs', array() );

		$settings = apply_filters( 'wpet_settings', array() );

		$data = array(
		    'tabs' => $tabs,
		    'settings' => $this->sortByTab( $settings ),
		    'nonce' => wp_nonce_field( 'wpet_settings_update', 'wpet_settings_nonce', true, false ),
			'message' => $this->message,
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

	    $tabs['event'] = __( 'Event', 'wpet' );
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
			'title' => '',
			'text' => WPET::getInstance()->getDisplay( 'settings-event.php', $event_data, true )
		);

		$email_data = array(
			'from_name' => $this->from_name,
			'from_email' => $this->from_email,
			'subject' => $this->subject,
			'email_body' => $this->email_body,
		);

		$settings[] = array(
		    'tab' => 'email',
			'title' => '',
			'text' => WPET::getInstance()->getDisplay( 'settings-email.php', $email_data, true )
		);

		$payment_data = array(
			'payment_gateway' => $this->payment_gateway,
		);

		$settings[] = array(
		    'tab' => 'payment',
			'title' => '',
			'text' => WPET::getInstance()->getDisplay( 'settings-payment.php', $payment_data, true )
		);

		$form_display = array(
		  'show_package_count' => $this->show_package_count,
		    'hide_coupons' => $this->hide_coupons
		);

		//@TODO real data
		$settings_data = array(
			'archive_name' => $event->post_title . ' ' . $event->wpet_event_date,
		);
		$settings[] = array(
		    'tab' => 'form_display',
			'title' => '',
			'text' => WPET::getInstance()->getDisplay( 'settings-form-display.php', $form_display, true )
		);
		$settings[] = array(
		    'tab' => 'reset',
			'title' => '',
			'text' => WPET::getInstance()->getDisplay( 'settings-reset.php', $settings_data, true )
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

		if ( ! isset( $_GET['tab'] ) || $_GET['tab'] == 'event' ) {
			$event['meta']['event_date'] = $options['event_date'];
			$event['meta']['max_attendance'] = $options['max_attendance'];
			$event['meta']['event_status'] = $options['event_status'];
		}

		WPET::getInstance()->events->add( $event );

		//@TODO do the resets here
		//give user choice to keep ticket options
		if ( ! empty( $options['archive_confirm'] ) ) {
			if ( ! empty( $event->ID ) )
				WPET::getInstance()->events->archive( $event->ID );
			WPET::getInstance()->events->add();
		}

		if( $options ) {
			foreach ( $options as $key => $value ) {
				$this->{$key} = stripslashes( $value );
			}
		}

		do_action( 'wpet_settings_submit', $post );

		//maybe change this based on tab?
		$this->message = __( 'Settings saved.', 'wpet' );
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

		$_GET['save_message'] = TRUE;
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