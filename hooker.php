<?php

/* test actions & filters here!!! */

function my_add_tab( $tabs, $base_url ) {
	$tabs['justin'] =
		array( 'label' => __( 'Justin', 'wpet' ),
			   'tab_content' => "<h2>I'm a hooker</h2>"
		);
	unset($tabs['reset']);
	return $tabs;
}
add_filter( 'wpet_settings_tabs', 'my_add_tab', 10, 2 );