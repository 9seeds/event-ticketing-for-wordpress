<h2><?php _e('Ticket Options', 'wpet'); ?> <a href="<?php echo $data['new_url'] ?>" class="add-new-h2"><?php _e( 'Add New', 'wpet' ); ?></a></h2>
<?php

require_once WPET_PLUGIN_DIR . 'lib/Table/TicketOptions.class.php';

$table_args = array(
	'edit_url' => $data['edit_url'],
);
$wp_list_table = new WPET_Table_TicketOptions( $table_args );
$wp_list_table->prepare_items();
$wp_list_table->display();