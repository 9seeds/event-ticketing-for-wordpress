<h2><?php _e('Tickets', 'wpet'); ?> <a href="<?php echo $data['new_url'] ?>" class="add-new-h2"><?php _e( 'Add New', 'wpet' ); ?></a></h2>
<?php

require_once WPET_PLUGIN_DIR . 'lib/Table/Tickets.class.php';

$args = array(
	'base_url' => $data['base_url'],
	'edit_url' => $data['edit_url'],
	'trash_url' => $data['trash_url'],
);
$wp_list_table = new WPET_Table_Tickets( $args );
$wp_list_table->prepare_items();
$wp_list_table->views();
$wp_list_table->display();