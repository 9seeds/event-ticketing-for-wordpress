<h2><?php _e('Attendees', 'wpet'); if( $data['show_add'] ) {
	?><a href="<?php echo $data['new_url'] ?>" class="add-new-h2"><?php _e( 'Add New', 'wpet' ); ?></a><?php
} ?></h2>
<?php

require_once WPET_PLUGIN_DIR . 'lib/Table/Attendees.class.php';

$args = array(
	'base_url' => $data['base_url'],
	'edit_url' => $data['edit_url'],
	'trash_url' => $data['trash_url'],
);
$wp_list_table = new WPET_Table_Attendees( $args );
$wp_list_table->prepare_items();
$wp_list_table->views();
$wp_list_table->display();

?>
<form action="" method="post">
<p>
	<input class="button" type="submit" name="download" value="<?php _e( 'Download CSV', 'wpet' ) ?>" />
</p>
</form>