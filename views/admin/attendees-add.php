<?php
$heading = empty($_REQUEST['post']) ? __('Add Attendee', 'wpet') : __('Edit Attendee', 'wpet');
$message = empty( $_REQUEST['message'] ) ? false : $data['message'][$_REQUEST['message']];
$attendee = isset( $data['attendee'] ) ? $data['attendee'] : NULL;
?>
<h2><?php echo $heading; ?> <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
} ?></h2>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<form method="post" action="" id="wpet_admin_attendee_add">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package]"><?php _e('Package', 'wpet'); ?></label></th>
				<td>
				<?php echo WPET::getInstance()->packages->selectMenu( 'package', 'package', @$attendee->wpet_package ); ?>
				</td>
			</tr>
			<!--<tr class="form-field form-required">
				<th scope="row"><label for="first_name"><?php _e('First Name', 'wpet'); ?></label></th>
				<td><input name="first_name" type="text" id="first_name" value="<?php echo @$attendee->wpet_first_name; ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="last_name"><?php _e('Last Name', 'wpet'); ?></label></th>
				<td><input name="last_name" type="text" id="last_name" value="<?php echo @$attendee->wpet_last_name; ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[email]"><?php _e('Email', 'wpet'); ?></label></th>
				<td><input name="email" type="text" id="email" value="<?php echo @$attendee->wpet_email; ?>"></td>
			</tr>-->
		</tbody>
	</table>
    <table class="form-table"><tbody id="ticket_options" ></tbody></table>
	<?php
	echo $data['nonce'];
	$button_label = empty( $_REQUEST['post'] ) ? __( 'Add Attendee', 'wpet' ) : __( 'Save Attendee', 'wpet' );	
	?>
	<input type="hidden" name="attendee_id" id="attendee_id" value="<?php echo empty( $_REQUEST['post'] ) ? '' : $_REQUEST['post']; ?>" />
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $button_label; ?>"></p>
</form>

</div>