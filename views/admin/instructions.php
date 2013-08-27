
<?php settings_errors(); ?>

<?php
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'getting_started';
?>

<h2 class="nav-tab-wrapper">
	<?php
	foreach ($data['tabs'] as $tab_id => $tab) {
		?>
		<a href="?page=wpet_instructions&tab=<?php echo $tab_id; ?>" class="nav-tab <?php echo $active_tab == $tab_id ? 'nav-tab-active' : ''; ?>"><?php echo $tab; ?></a>
		<?php
	}
	?>
</h2>

<?php
foreach ($data['instructions'] as $tab_id => $instructions) {

	if (!in_array($tab_id, array_keys($data['tabs'])))
		continue;

	if( $tab_id == $active_tab ) {
		echo "<div id='tab-{$tab_id}'>";

		foreach ($instructions AS $set) {
			echo $set['text'];
		}
		echo '</div>';
	}
}
?>