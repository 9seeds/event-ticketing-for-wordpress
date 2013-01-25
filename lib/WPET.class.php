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

	/**
	 * Private object constructor. This class is a singleton. 
	 * Use WPET::getInstance()
	 * 
	 * @since 2.0
	 * @uses wpet_init
	 */
	private function __construct() {
		require_once( WPET_PLUGIN_DIR . '/lib/WPETDebugBar.class.php' );
		
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
		}
		
		add_action( 'init', array( $this, 'registerShortcodes' ) );
	}
	
	/**
	 * Registers the shortcodes required by the WPET base plugin
	 * 
	 * @since 2.0
	 */
	public function registerShortcodes() {
	    add_shortcode( 'wpet',  array( $this, 'renderOrderFormShortcode' ) );
	}
	
	/**
	 * Displays the [wpet] shortcode to visitors
	 * 
	 * Valid attributes:
	 * - event_id
	 * 
	 * @since 2.0
	 * @param array $atts 
	 */
	public function renderOrderFormShortcode( $atts ) {
	    
	    /*
	     * Find the event to display here
	     */
	    $this->display( 'order_form.php' );
	    
	    echo "<p>Is pro installed? ";
	    if( self::$mProInstalled )
		echo " It sure is you lucky dog!!";
	    else 
		echo "Noppers :'(";
	    
	    echo "</p>";
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
		return self::$mWpet;
	}

	/**
	 * Really crappy way to load in some stuff. Need to determine a better
	 * method
	 * 
	 * @since 2.0 
	 */
	private function initBuiltIn() {
		//reports must come first to override the default option
		require_once 'Reports.class.php';
		$this->mModule['reports'] = new Reports();

		require_once 'TicketOptions.class.php';
		$this->mModule['ticket_options'] = new TicketOptions();

		require_once 'Tickets.class.php';
		$this->mModule['tickets'] = new Tickets();

		require_once 'Packages.class.php';
		$this->mModule['packages'] = new packages();

		require_once 'Coupons.class.php';
		$this->mModule['coupons'] = new Coupons();

		require_once 'Attendees.class.php';
		$this->mModule['attendees'] = new Attendees();

		require_once 'Instructions.class.php';
		$this->mModule['instructions'] = new Instructions();

		require_once 'Settings.class.php';
		$this->mModule['settings'] = new Settings();
	}

	/**
	 * Builds the Ticket menu in wp-admin
	 * 
	 * @since 2.0
	 * @uses wpet_admin_menu
	 */
	public function setupMenu() {
		add_object_page( 'Tickets', 'Tickets', 'add_users', 'wpet_reports', array( $this->mModule['reports'], 'renderAdminPage' ), WPET_PLUGIN_URL . '/images/menu_icon.png' );
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
	 */
	public function display( $template, $data = array() ) {
		global $post;

		if ( is_admin() ) {
			require_once( WPET_PLUGIN_DIR . '/views/admin/' . $template );
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
			$file = WPET_PLUGIN_DIR . "views/$template";
			require_once( $file );
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
	public function getDisplay( $template, $data = array() ) {
	    ob_start();
	    $this->display( $template, $data );
	    return ob_get_clean();
	}

	/**
	 * Method called on current admin screen
	 * 
	 * @since 2.0
	 */
	public function onAdminScreen( $current_screen ) {
		if ( $current_screen->base == 'toplevel_page_wpet_reports' ||
			 strpos( $current_screen->base, 'tickets_page_' ) === 0 ) {
			wp_register_style( 'wpet-admin-style', WPET_PLUGIN_URL . 'css/admin.css' );
			wp_enqueue_style( 'wpet-admin-style' );

			wp_register_style( 'wpet-jquery-ui-theme', WPET_PLUGIN_URL . '3rd-party/jquery-ui-' . WPET_JQUERY_VERSION . '/themes/base/jquery-ui.css' );
			wp_enqueue_style( 'wpet-jquery-ui-theme' );

			wp_register_script( 'wpet-jquery-cookie', WPET_PLUGIN_URL . '3rd-party/jquery-ui-' . WPET_JQUERY_VERSION . '/external/cookie.js' );

			wp_register_script( 'wpet-admin-tabs', WPET_PLUGIN_URL . 'js/admin_tabs.js', array( 'jquery-ui-tabs', 'wpet-jquery-cookie' ) );
			wp_enqueue_script( 'wpet-admin-tabs' );
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
		do_action( 'wpet_debug', $title, $data, $format );
	}

	/**
	 * Method called on plugin activation
	 * 
	 * @since 2.0 
	 */
	public static function activate() {
		$plugin_data = get_plugin_data( WPET_PLUGIN_DIR . '/ticketing.php' );

		update_option( 'wpet_install_data', $plugin_data );
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

}// end class
