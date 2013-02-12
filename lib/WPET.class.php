<?php

/**
 * Base WP Event Ticketing object. Sets up all the modules and inits the plugin.
 *
 * WPET is a singleton. Get a reference with WPET::getInstance()
 *
 * @todo Add button to wp_editor() for WPET shortcodes. Should be able to select from list of events
 * @todo Remove pro store. Plugins that purchase things directly are not allowed in the .org repo
 * @todo Add code to allow alternate payment gateways. Do now show this ability to change in free edition?
 * @todo How do we reliably check to see if Pro is installed?
 * @todo How can we reliably allow plugins to tap into our hooks? If the free base is activated first the hooks will all execute before the addons/Pro are loaded. Look at The Events Calendar and Easy Digital Downloads for ideas
 * @todo Send HTML emails
 *
 * @since 2.0
 */
class WPET {

	/**
	 * Holds links the various initialized WPET modules. Uses magic functions
	 *
	 * @since 2.0
	 * @var Array
	 */
	private $mModules = array();


	/**
	 * Holds payment gateways
	 * class_name => class_instance
	 *
	 * @since 2.0
	 * @var Array
	 */
	private $mGateways = array();
 
	/**
	 * Singleton link
	 *
	 * @since 2.0
	 * @var WPET
	 */
	static $mWpet = false;

	/**
	 * Flag to determine if WPET Pro is installed
	 *
	 * @var bool
	 */
	static $mProInstalled;
	
	
	private $mLog = array();

	/**
	 * Private object constructor. This class is a singleton.
	 * Use WPET::getInstance()
	 *
	 * @since 2.0
	 * @uses wpet_init
	 */
	private function __construct() {
		require_once WPET_PLUGIN_DIR . '/lib/WPETDebugBar.class.php';

		/*
		 * Determine if WPET Pro is installed. Of itself this flag does
		 * nothing. No special features are "unlocked". It does however
		 * help add-ons ensure the special features provided by Pro
		 * are available for use
		 */
		self::$mProInstalled = apply_filters( 'wpet_pro_installed', false );

		/*
		 * Let add-ons know wpet has started. They could do things such as setup
		 * hooks to wpet_admin_menu at this point
		 */
		do_action( 'wpet_init' );

		// Horrible name. This needs to be done better
		$this->initBuiltIn();


		/*
		 * Items that should only run in wp-admin
		 *
		 * Reduces overhead on page load
		 */
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'setupMenu' ) );
			add_action( 'current_screen', array( $this, 'onAdminScreen' ) );
		} else {
			add_action( 'wp_head', array( $this, 'onSalesPage' ) );
			add_action( 'init', array( $this, 'maybeSalesSubmit' ) );
		}

		add_action( 'init', array( $this, 'registerShortcodes' ) );
	}

	/**
	 * Registers the shortcodes required by the WPET base plugin
	 *
	 * @since 2.0
	 */
	public function registerShortcodes() {
	    add_shortcode( 'wpeventticketing',  array( $this, 'renderwpeventticketingShortcode' ) );
	}

	/**
	 * Displays the [wpeventticketing] shortcode to visitors
	 *
	 * Valid attributes:
	 * - event_id
	 *
	 * @since 2.0
	 * @param array $atts
	 */
	public function renderwpeventticketingShortcode( $atts ) {
	    wp_enqueue_script( 'wpet-order-form', WPET_PLUGIN_URL . 'js/order_form.js' );
	    $data = array();
	    
	    //var_dump( $this->settings->hide_coupons ); die();

	    $defaults = array(
		'event' => $this->events->getWorkingEvent()
	    );

	    $atts = wp_parse_args( $atts, $defaults );
	    /*
	     * Find the event to display here
	     */
	    $columns = array(
		'post_content' => __( 'Description', 'wpet' ),
		'wpet_cost' => __( 'Price', 'wpet' )
	    );


	    // show_package_count
	    if( $this->settings->show_package_count ) {
		$columns['wpet_quantity_remaining'] = __( 'Remaining', 'wpet' );
	    }

	    $columns['wpet_quantity'] = __( 'Quantity', 'wpet' );

	    $rows = $this->packages->findAllByEvent( $atts['event'] );

	    $data['columns'] = apply_filters( 'wpet_wpeventticketing_shortcode_columns', $columns );
	    $data['rows'] = apply_filters( 'wpet_wpeventticketing_shortcode_rows', $rows );
	    $data['hide_coupons'] = $this->settings->hide_coupons;
	    $this->display( 'order_form.php', $data );
	}

	/**
	 * Gives back an instance of the WPET class
	 *
	 * @since 2.0
	 * @return WPET
	 */
	public static function getInstance() {
		if ( ! ( self::$mWpet instanceof self ) ) {
			self::$mWpet = new self();
		}
		self::debug( 'Calling another WPET instance', 'This log entry has not real purpose other than its extremly cool factor' );
		return self::$mWpet;
	}

	/**
	 * Really crappy way to load in some stuff. Need to determine a better
	 * method
	 *
	 * @since 2.0
	 */
	private function initBuiltIn() {
		require_once 'Module.class.php';

		$modules = array();

		//reports must come first to override the default option
		require_once 'Reports.class.php';
		$modules['reports'] = new WPET_Reports();

		require_once 'TicketOptions.class.php';
		$modules['ticket_options'] = new WPET_TicketOptions();

		require_once 'Tickets.class.php';
		$modules['tickets'] = new WPET_Tickets();

		require_once 'Packages.class.php';
		$modules['packages'] = new WPET_Packages();

		require_once 'Coupons.class.php';
		$modules['coupons'] = new WPET_Coupons();

		require_once 'Attendees.class.php';
		$modules['attendees'] = new WPET_Attendees();
		
		require_once 'Notifications.class.php';
		$modules['notifications'] = new WPET_Notifications();

		require_once 'Instructions.class.php';
		$modules['instructions'] = new WPET_Instructions();

		require_once 'Settings.class.php';
		$modules['settings'] = new WPET_Settings();

		require_once 'Events.class.php';
		$modules['events'] = new WPET_Events();

		require_once 'Currency.class.php';
		$modules['currency'] = new WPET_Currency();
		
		require_once 'Payments.class.php';
		$modules['payment'] = new WPET_Payments();

		$this->mModules = apply_filters( 'wpet_modules', $modules );
	}

	/**
	 * Builds the Ticket menu in wp-admin
	 *
	 * @since 2.0
	 * @uses wpet_admin_menu
	 */
	public function setupMenu() {
		add_object_page( 'Tickets', 'Tickets', 'add_users', 'wpet_reports', array( $this->mModules['reports'], 'renderAdminPage' ), WPET_PLUGIN_URL . '/images/menu_icon.png' );
		$menu_items = array();

		$menu_items = apply_filters( 'wpet_admin_menu', $menu_items );

		foreach ( $menu_items as $i ) {
			add_submenu_page( 'wpet_reports', $i[0], $i[1], $i[2], $i[3], $i[4] );
		}
		//die();

		$this->debug( 'Some title', 'This is a normal log message' );
		$this->debug( 'Some title', 'This is a warning message', 'warning' );
		$this->debug( 'Some title', 'This is an error message', 'error' );
		$this->debug( 'Variable dump', $menu_items, 'dump' );
	}

	/**
	 * Handles the display of templates to the user. Pass it in associative
	 * array of data that can be used by the template
	 *
	 * @todo Does not allow site owners to override templates in their theme yet. Add that
	 * @since 2.0
	 * @global WP_Post $post
	 * @param String $template
	 * @param Array $data - OPTIONAL - data to display in the template
	 * @param Boolean $is_sub - OPTIONAL ( Default: false ) - If is subpage head will not show
	 */
	public function display( $template, $data = array(), $is_sub = false ) {
		global $post;
		
		$this->debug( 'Loading template', $template );

		$admin_page_icon = apply_filters( 'wpet_admin_page_icon', '<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>' );

		if ( is_admin() ) {
			if( !$is_sub ) {
			    echo '<div class="wrap">';
			    echo $admin_page_icon;
			}

			include WPET_PLUGIN_DIR . "views/admin/{$template}";
			if( !$is_sub ) echo '</div><!-- end .wrap -->';
			return;
		}

/*		if ( $this->mLogPostType != $post->post_type )
			return;

		if ( is_singular( $this->mLogPostType ) ) {
			$template = "single-$this->mLogPostType.php";
		} else if ( is_post_type_archive( $this->mLogPostType ) ) {
			$template = "archive-$this->mLogPostType.php";
		}
*/

		if ( '' == locate_template( array( $template ) ) ) {
			// Template could not be found in child or parent theme
			include WPET_PLUGIN_DIR . "views/{$template}";
		}

		// Do not continue loading
		//exit();
	}

	/**
	 * Returns the string containing the contents of a template rather than
	 * echoing it
	 *
	 * @param string $template
	 * @param array $data
	 * @return string
	 */
	public function getDisplay( $template, $data = array(), $is_sub = false ) {
	    ob_start();
	    $this->display( $template, $data, $is_sub );
	    return ob_get_clean();
	}

	/**
	 * Gets all of the payment gateway class names and an instance of each
	 * @uses wpet_payment_gateway_list
	 * @return array of WPET_Gateway
	 */
	public function getGateways() {
		if ( ! empty( $this->mGateways ) )
			return $this->mGateways;
		
		require_once WPET_PLUGIN_DIR . 'lib/Gateway/Manual.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Gateway/PayPalExpress.class.php';
		$payment_gateways = array(
			'WPET_Gateway_Manual' => new WPET_Gateway_Manual(),
			'WPET_Gateway_PayPalExpress' => new WPET_Gateway_PayPalExpress(),
		);
		$this->mGateways = apply_filters( 'wpet_payment_gateway_list', $payment_gateways );		
		return $this->mGateways;	
	}

	/**
	 * 
	 */
	public function getGateway() {
		$gateways = $this->getGateways();
		return $gateways[$this->settings->payment_gateway];
	}
	
	/**
	 * enqueue stylesheet on front end
	 *
	 * @since 2.0
	 */
	public function onSalesPage() {
		wp_register_style( 'wpet-style', WPET_PLUGIN_URL . 'css/ticketing.css' );
		wp_enqueue_style( 'wpet-style' );
	}

	/**
	 * (possibly) process sales page form front end
	 *
	 * @since 2.0
	 */
	public function maybeSalesSubmit() {
		if ( ! empty( $_POST['wpet_purchase_nonce'] ) && wp_verify_nonce( $_POST['wpet_purchase_nonce'], 'wpet_purchase_tickets' ) ) {
			if ( ! empty( $_POST['couponSubmitButton'] ) ) {
				//@TODO DO COUPON STUFF!!
			} else if ( ! empty( $_POST['submit'] ) ) {
				//@TODO maybe double-check coupon stuff here too?
				//@TODO some form validation as well before sending to payment CPT (gateway step)
				//@TODO add attendees (based on package->ticket_quantity) here if attendee info is at beginning
				$data = array(
					'post_title' => uniqid(),
					'post_status' => 'publish',
					'meta' => array(
						'package_data' => $_POST
					)  
				);
				$payment_id = $this->payment->add( $data );

				wp_redirect( get_permalink( $payment_id ) );
				exit();
			}
		}
	}

	/**
	 * Method called on current admin screen
	 *
	 * @since 2.0
	 */
	public function onAdminScreen( $current_screen ) {
		if ( ( $pos = strpos( $current_screen->base, 'tickets_page_wpet_' ) ) === 0 ||
			$current_screen->base == 'toplevel_page_wpet_reports' ) {

			wp_register_style( 'wpet-admin-style', WPET_PLUGIN_URL . 'css/admin.css' );
			wp_enqueue_style( 'wpet-admin-style' );

			wp_register_style( 'wpet-jquery-ui-theme', WPET_PLUGIN_URL . '3rd-party/jquery-ui-' . WPET_JQUERY_VERSION . '/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'wpet-jquery-ui-theme' );

			wp_register_script( 'wpet-jquery-cookie', WPET_PLUGIN_URL . '3rd-party/jquery-ui-' . WPET_JQUERY_VERSION . '/external/cookie.js' );

			//allow individual pages to do pre-header actions
			if ( $pos === 0 ) {
				$page = substr( $current_screen->base, 18 ); //'tickets_page_wpet_'
				if ( ! empty( $this->mModules[$page] ) ) {
					$this->mModules[$page]->maybeSubmit();
					$this->mModules[$page]->enqueueAdminScripts();
					$this->mModules[$page]->contextHelp( $current_screen );
				}
			}
		}
	}



	/**
	* Sends debugging data to a custom debug bar extension
	*
	* @since 2.0
	* @param String $title
	* @param Mixed $data
	* @param String $format Optional - (Default:log) log | warning | error | notice | dump
	*/
	function debug( $title, $data, $format='log' ) {
	    global $wpet_debug;
	    
	    $wpet_debug[] = array( 'title' => $title, 'data' => $data, 'format' => $format );
	}
	
	

	/**
	 * Method called on plugin activation
	 *
	 * @since 2.0
	 */
	public function activate() {
		$plugin_data = get_plugin_data( WPET_PLUGIN_DIR . '/ticketing.php' );
		update_option( 'wpet_install_data', $plugin_data );
		require_once 'Module.class.php';
		require_once 'Events.class.php';

		//@TODO default TicketOption "Twitter"

		$event = new WPET_Events();
		$event->registerPostType();
		//install an event if there are none
		$my_event = $event->getWorkingEvent();
		if ( ! $my_event )
			$event->add();

		if( !get_option( 'wpet_activated_once' ) ) {
		    update_option( 'wpet_activate_once', true );

			// events tab
			$this->settings->event_status = 'closed';
			$this->settings->closed_message = 'Tickets for this event will go on sale shortly.';
			$this->settings->thank_you = 'Thanks for purchasing a ticket to our event!' . "\n".
				'Your ticket link(s) are below' . "\n".
				'[ticketlinks]' . "\n\n".
				'If you have any questions please let us know!';

			// payments tab
			$this->settings->currency = 'USD';
			$this->settings->payment_gateway = 'WPET_Gateway_Manual';
			$this->settings->payment_gateway_status = 'sandbox';

			// email tab
			$this->settings->email_body = 'Thanks for purchasing a ticket to our event!' . "\n".
				'Your ticket link(s) are below' . "\n".
				'[ticketlinks]' . "\n\n".
				'If you have any questions please let us know!';

			// form display tab
			$this->settings->show_package_count = 1;
		}
	}

	/**
	 * Method called on plugin deactivation
	 *
	 * @since 2.0
	 */
	public static function deactivate() {
		delete_option( 'wpet_install_data' );
	}

	/**
	 * Method called when plugin is uninstalled ( deleted )
	 *
	 * @since 2.0
	 */
	public static function uninstall() {
		delete_option( 'wpet_install_data' );
	}

	/**
	 * Magic method to convert object into a string
	 *
	 * @since 2.0
	 * @return string
	 */
	public function __toString() {
		return 'WPET::__toString';
	}

	/**
	 * Magic method to access WPET modules
	 *
	 * @since 2.0
	 * @return WPET_Module
	 */
	public function __get( $name ) {
		if ( ! empty( $this->mModules[$name] ) )
			return $this->mModules[$name];

		return NULL;
	}


}// end class
