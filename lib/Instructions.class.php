<?php

/**
 * @since 2.0 
 */
class Instructions {

    /**
     * @since 2.0 
     */
    public function __construct() {
	add_filter('wpet_admin_menu', array(&$this, 'adminMenu'));

	add_filter('wpet_instructions', array(&$this, 'defaultInstructions'));
    }

    /**
     * Add Instructions links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu($menu) {
	global $wpet;
	$menu[] = array('Instructions', 'Instructions', 'add_users', 'instructions', array(&$this, 'renderAdminPage'));
	return $menu;
    }

    public function renderAdminPage() {
	
	WPET::getInstance()->debug( 'Loading Instructions', 'Rendering...' );
	
	$inst = apply_filters('wpet_instructions', $inst = array('instructions' => array()));
	

	WPET::getInstance()->debug( 'Instruction Data', $inst, 'dump' );
	
	WPET::getInstance()->display('instructions.php', $inst);
    }

    public function defaultInstructions($inst) {

	$inst['instructions'][] = array(
	    'title' => 'Instructions Title',
	    'text' => "Life is pain, Highness. Anyone who says differently is selling something. Westley didn't reach his destination. His ship was attacked by the Dread Pirate Roberts, who never left captives alive. When Buttercup got the news that Westley was murdered... Murdered by pirates is good... Get used to disappointment. Are you the Miracle Max who worked for the king all those years? So it's to be torture? If you're in such a hurry, you could lower a rope or a tree branch or find something useful to do. Yes, yes, some of the time. "
	);

	return $inst;
    }

}// end class