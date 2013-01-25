<?php

abstract class WPET_AddOn {
	abstract public function adminMenu( $menu );

	abstract public function renderAdminPage();

	//override if you'd like
	public function enqueueAdminScripts() {
	}
}