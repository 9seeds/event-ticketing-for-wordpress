<h2><?php _e('Event Settings', 'wpet'); ?></h2>
<?php settings_errors(); ?>

<?php
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'event';
?>

<h2 class="nav-tab-wrapper">
	<?php
	foreach ($data['tabs'] as $tab_id => $tab) {
		?>
		<a href="?page=wpet_settings&tab=<?php echo $tab_id; ?>" class="nav-tab <?php echo $active_tab == $tab_id ? 'nav-tab-active' : ''; ?>"><?php echo $tab; ?></a>
		<?php
	}
	?>
</h2>

<?php
foreach ( $data['settings'] as $tab_id => $settings ) {

	if ( !in_array( $tab_id, array_keys($data['tabs'] ) ) )
		continue;
	echo '<div>';
	if( $tab_id == $active_tab ) {
		echo "<div id='tab-{$tab_id}'>";

		foreach ($settings AS $set) {
			echo $set['text'];
		}
	}
	echo '</div>';
}
?>


<form action="" method="post" id="settings_form">

	<?php settings_fields( 'sandbox_theme_display_options' ); ?>
	<?php do_settings_sections( 'sandbox_theme_display_options' ); ?>	
	
	<?php settings_fields( 'sandbox_theme_social_options' ); ?>
	<?php do_settings_sections( 'sandbox_theme_social_options' ); ?>	

	<?php submit_button(); ?>
	
</form>
