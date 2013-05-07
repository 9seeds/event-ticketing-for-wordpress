<?php

/**
 * @since 2.0
 */
class WPET_Instructions extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 100 );


		add_filter( 'wpet_instructions', array( $this, 'defaultInstructions' ) );
		add_filter( 'wpet_instructions_tabs', array( $this, 'defaultTabs' ), 1 );
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
			'content'	=> '<p>' . __( 'This is just an example of what you will see on the help tab on any of the other WP Event Ticketing pages.' ) . '</p>',
			)
		);
	}

	/**
	 * Add Instructions links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array(
			__( 'Instructions', 'wpet' ),
			__( 'Instructions', 'wpet' ),
			'add_users',
			'wpet_instructions',
			array( $this, 'renderAdminPage' ) );
		return $menu;
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
	 * Renders the page for the wp-admin area
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

//		$inst = apply_filters('wpet_instructions', $inst = array( 'instructions' => array() ) );
//
//		WPET::getInstance()->display( 'instructions.php', $inst );


		$tabs = apply_filters( 'wpet_instructions_tabs', array() );

		$settings = apply_filters( 'wpet_instructions', array() );

		$data = array(
		    'tabs' => $tabs,
		    'instructions' => $this->sortByTab( $settings ),
		    //'nonce' => wp_nonce_field( 'wpet_settings_update', 'wpet_settings_nonce', true, false ),
		);

		WPET::getInstance()->display( 'instructions.php', $data );
	}

	/**
	 * Setup default tabs
	 *
	 * @since 2.0
	 * @param array $tabs
	 * @return array
	 */
	public function defaultTabs( $tabs ) {

	    $tabs['getting_started'] = __( 'Getting Started', 'wpet' );
	    $tabs['payment_gateways'] = __( 'Payment Gateways', 'wpet' );
	    $tabs['design'] = __( 'Design', 'wpet' );
	    $tabs['extras'] = __( 'Extras', 'wpet' );

	    return $tabs;
	}

	/**
	 * Sorts the instructions into tabs
	 *
	 * @param array $instructions
	 * @return array
	 */
	private function sortByTab( $instructions ) {
	    $s = array();

	    foreach( $instructions as $set ) {
			//echo '<pre>';var_dump($settings); echo '</pre>';
			$s[$set['tab']][] = array(
				'title' => $set['title'],
				'text' => $set['text']
			);
	    }
	    return $s;
	}

	/**
	 * Adds a set of default instructions to the Instructions page
	 *
	 * @since 2.0
	 * @param array $inst
	 * @return string
	 */
	public function defaultInstructions( $instructions ) {

	    $instructions[] = array(
		'tab'	=> 'getting_started',
		'title' => 'Getting Started',
		'text'	=> WPET::getInstance()->getDisplay( 'instructions-getting-started.php', array(), true )
	    );
	    $instructions[] = array(
		'tab'	=> 'payment_gateways',
		'title' => 'Payment Gateways',
		'text'	=> WPET::getInstance()->getDisplay( 'instructions-payment-gateways.php', array(), true )
	    );
	    $instructions[] = array(
		'tab'	=> 'design',
		'title' => 'Design',
		'text'	=> WPET::getInstance()->getDisplay( 'instructions-design.php', array(), true )
	    );
	    $instructions[] = array(
		'tab'	=> 'extras',
		'title' => 'Extras',
		'text'	=> WPET::getInstance()->getDisplay( 'instructions-extras.php', array(), true )
	    );

		return $instructions;
	}

}// end class