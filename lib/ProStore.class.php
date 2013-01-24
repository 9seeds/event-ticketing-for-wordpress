<?php

/**
 * 
 * @todo WE CANNOT RELEASE THE PLUGIN ON THE .ORG REPO WITH THIS CODE IN IT!!! 
 * @since 2.0 
 */
class ProStore {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 1000 );
	}

	/**
	 * Add Settings links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu($menu) {
		$menu[] = array( 'Store', 'Store', 'add_users', 'wpet_store', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	public function renderAdminPage() {
		echo "This will eventually show add-ons available through WPET Pro";
	}

}// end class