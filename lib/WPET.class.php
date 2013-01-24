<?php

/**
 * Uses Singleton design pattern
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
     * @since 2.0 
     */
    private function __construct() {

	/*
	 * Let add-ons know wpet has started. They could do things such as setup
	 * hooks to wpet_admin_menu at this point
	 */
	do_action('wpet_init');

	// Horrible name. This needs to be done better
	$this->initBuiltIn();


	/*
	 * Items that should only run in wp-admin
	 * 
	 * Reduces overhead on page load
	 */
	if (is_admin()) {
	    add_action('admin_menu', array(&$this, 'setupMenu'));
	    add_action('current_screen', array($this, 'onAdminScreen'));
	}
    }

    /**
     * Gives back an instance of the WPET class
     * 
     * @since 2.0
     * @return WPET 
     */
    public static function getInstance() {
	if (!(self::$mWpet instanceof self)) {
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
	require_once 'Attendees.class.php';
	$this->mModule['attendees'] = new Attendees();

	require_once 'Coupons.class.php';
	$this->mModule['coupons'] = new Coupons();

	require_once 'Packages.class.php';
	$this->mModule['packages'] = new packages();

	require_once 'TicketOptions.class.php';
	$this->mModule['ticket_options'] = new TicketOptions();

	require_once 'Tickets.class.php';
	$this->mModule['tickets'] = new Tickets();

	require_once 'Reports.class.php';
	$this->mModule['reports'] = new Reports();

	require_once 'Instructions.class.php';
	$this->mModule['instructions'] = new Instructions();

	require_once 'Settings.class.php';
	$this->mModule['settings'] = new Settings();
    }

    /**
     * Builds the Ticket menu in wp-admin
     * 
     * @since 2.0
     * @uses wpet_admin_menu_items 
     */
    public function setupMenu() {
	add_object_page('Tickets', 'Tickets', 'add_users', 'tickets', array(&$this, 'vtReporting'));
	$menu_items = array();

	$menu_items = apply_filters('wpet_admin_menu', $menu_items);

	foreach ($menu_items AS $i) {
	    add_submenu_page('tickets', $i[0], $i[1], $i[2], $i[3], $i[4]);
	}
    }

    /**
     * Handles the display of templates to the user. Pass it in associative
     * array of data that can be used by the template
     * 
     * @global WP_Post $post
     * @param String $template
     * @param Array $data - OPTIONAL - data to display in the template
     */
    public function display($template, $data = array()) {
	global $post;

	if (is_admin()) {
	    require_once( WPET_PLUGIN_DIR . '/views/admin/' . $template );
	    return;
	}

	if ($this->mLogPostType != $post->post_type)
	    return;

	if (is_singular($this->mLogPostType)) {
	    $template = "single-$this->mLogPostType.php";
	} else if (is_post_type_archive($this->mLogPostType)) {
	    $template = "archive-$this->mLogPostType.php";
	}


	if ('' == locate_template(array($template))) {
	    // Template could not be found in child or parent theme
	    $file = SHIPS_LOG_PLUGIN_DIR . "views/$template";
	    require_once( $file );
	}

	// Do not continue loading
	exit();
    }

    public function onAdminScreen($current_screen) {
	if (strpos($current_screen->base, 'tickets_page_') === 0) {
	    wp_register_style('wpet-admin-style', WPET_PLUGIN_URL . 'css/admin.css');
	    wp_enqueue_style('wpet-admin-style');
	}
    }

    /**
     * Method called on plugin activation
     * 
     * @since 2.0 
     */
    public static function activate() {
	$plugin_data = get_plugin_data(WPET_PLUGIN_DIR . '/ticketing.php');

	update_option('wpet_install_data', $plugin_data);
    }

    /**
     * Method called on plugin deactivation
     * 
     * @since 2.0 
     */
    public static function deactivate() {
	delete_option('wpet_install_data');
    }

    /**
     * Method called when plugin is uninstalled ( deleted )
     * 
     * @since 2.0
     */
    public static function uninstall() {
	delete_option('wpet_install_data');
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

}

// end class