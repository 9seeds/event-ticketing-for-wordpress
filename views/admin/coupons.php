<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Coupons', 'wpet'); ?> <a href="<?php echo $data['new_url'] ?>" class="add-new-h2">Add New</a></h2>
<?php

require_once WPET_PLUGIN_DIR . 'lib/Table/Coupons.class.php';

$args = array(
	'edit_url' => $data['edit_url'],
);
$wp_list_table = new WPET_Table_Coupons( $args );
$wp_list_table->prepare_items();
$wp_list_table->display();

?>
</div><!-- .wrap -->