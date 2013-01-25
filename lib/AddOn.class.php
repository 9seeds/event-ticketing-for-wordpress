<?php

/**
 * @since 2.0
 */
abstract class WPET_AddOn {
	/**
	 * @since 2.0
	 */
	abstract public function adminMenu( $menu );

	/**
	 * @since 2.0
	 */
	abstract public function renderAdminPage();

	/**
	 * override if you'd like
	 * 
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
	}
}