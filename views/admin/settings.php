<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Event Settings', 'wpet'); ?></h2>

	<h3 class="nav-tab-wrapper">
		<?php

		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'event';
		foreach ( $data['tabs'] as $tab_id => $tab ) {
			$class = ( $tab_id == $current_tab ) ? ' nav-tab-active' : '';
			echo '<a href="' . $tab['url'] .'" class="nav-tab' . $class . '">' . esc_html( $tab['label'] ) . '</a>';
		}
		?>
	</h3>

	
	<?php
		WPET::getInstance()->display( "settings-{$current_tab}.php", $data );
	?>
	
</div>


