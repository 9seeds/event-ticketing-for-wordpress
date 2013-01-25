<?php

/**
 * @since 2.0
 */
class Settings {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 2 );
		add_filter( 'wpet_settings', array( $this, 'defaultSettings' ) );
	}

	/**
	 * Add Settings links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu($menu) {
		$menu[] = array( 'Settings', 'Settings', 'add_users', 'wpet_settings', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	public function renderAdminPage() {
		$settings_url = admin_url( 'admin.php?page=wpet_settings' );
		$tabs = array(
			'event'   => array( 'label' => __( 'Event', 'wpet' ),   'url' => add_query_arg( array( 'tab' => 'event' ),   $settings_url ) ),
			'payment' => array( 'label' => __( 'Payment', 'wpet' ), 'url' => add_query_arg( array( 'tab' => 'payment' ), $settings_url ) ),
			'email'   => array( 'label' => __( 'Email', 'wpet' ),   'url' => add_query_arg( array( 'tab' => 'email' ),   $settings_url ) ),
			'reset'   => array( 'label' => __( 'Reset', 'wpet' ),   'url' => add_query_arg( array( 'tab' => 'reset' ),   $settings_url ) ),
		);

		$tabs = apply_filters( 'wpet_settings_tabs', $tabs, $settings_url );
		
		$settings = apply_filters(
			'wpet_settings',
			$settings = array(
				'settings' => array(),
				'tabs' => $tabs,
		) );
		WPET::getInstance()->display( 'settings.php', $settings );
	}

	public function defaultSettings( $settings ) {
		$settings['settings'][] = array(
			'title' => 'Settings Title',
			'text' => "Life is pain, Highness. Anyone who says differently is selling something. Westley didn't reach his destination. His ship was attacked by the Dread Pirate Roberts, who never left captives alive. When Buttercup got the news that Westley was murdered... Murdered by pirates is good... Get used to disappointment. Are you the Miracle Max who worked for the king all those years? So it's to be torture? If you're in such a hurry, you could lower a rope or a tree branch or find something useful to do. Yes, yes, some of the time. "
		);
		
		$settings['settings'][] = array(
			'title' => 'Disable upgrade nag?',
			'text' => 'Should we have an option to disable the upgrade to pro nag?'
		);

		return $settings;
	}

}// end class