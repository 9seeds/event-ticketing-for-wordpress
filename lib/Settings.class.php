<?php

/**
 * @since 2.0
 */
class WPET_Settings extends WPET_AddOn {

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
		wp_register_script( 'wpet-admin-settings', WPET_PLUGIN_URL . 'js/admin_settings.js', array( 'jquery-ui-tabs', 'wpet-jquery-cookie' ) );
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
			$this->update();
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
		    
		$settings[] = array(
		    'tab' => 'event',
			'title' => 'Settings Title',
			'text' => "Life is pain, Highness. Anyone who says differently is selling something. Westley didn't reach his destination. His ship was attacked by the Dread Pirate Roberts, who never left captives alive. When Buttercup got the news that Westley was murdered... Murdered by pirates is good... Get used to disappointment. Are you the Miracle Max who worked for the king all those years? So it's to be torture? If you're in such a hurry, you could lower a rope or a tree branch or find something useful to do. Yes, yes, some of the time. "
		);
		
		$settings[] = array(
		    'tab' => 'email',
			'title' => 'Disable upgrade nag?',
			'text' => 'Should we have an option to disable the upgrade to pro nag?'
		);
		$settings[] = array(
		    'tab' => 'email',
			'title' => 'Second email',
			'text' => 'Bippety boppety boo'
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
	 */
	public function update() {
		die(print_r($_POST, true));
	}

}// end class