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
	}

	/**
	 * Add Instructions links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Instructions', 'Instructions', 'add_users', 'wpet_instructions', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Renders the page for the wp-admin area
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {	
			
		$inst = apply_filters('wpet_instructions', $inst = array( 'instructions' => array() ) );
		
		WPET::getInstance()->display( 'instructions.php', $inst );
	}

	/**
	 * Adds a set of default instructions to the Instructions page
	 * 
	 * @since 2.0
	 * @param array $inst
	 * @return string 
	 */
	public function defaultInstructions( $inst ) {
		$inst['instructions'][] = array(
			'title' => 'Instructions Title',
			'text' => "Life is pain, Highness. Anyone who says differently is selling something. Westley didn't reach his destination. His ship was attacked by the Dread Pirate Roberts, who never left captives alive. When Buttercup got the news that Westley was murdered... Murdered by pirates is good... Get used to disappointment. Are you the Miracle Max who worked for the king all those years? So it's to be torture? If you're in such a hurry, you could lower a rope or a tree branch or find something useful to do. Yes, yes, some of the time. "
		);

		return $inst;
	}

}// end class