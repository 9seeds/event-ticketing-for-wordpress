<?php
$heading = empty($_REQUEST['post']) ? __('Add Notification', 'wpet') : __('View Notification', 'wpet');
?>
<h2><?php echo $heading; ?> <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
} ?></h2>
<form method="post" action="">
	<h2><?php _e( 'To:', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[all-attendees]"><?php _e('All attendees', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[all-attendees]" id="options[all-attendees]" value="1">
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[have-info]"><?php _e('Have filled out info', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[have-info]" id="options[have-info]" value="1">
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[no-info]"><?php _e('Have not filled out info', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[no-info]" id="options[no-info]" value="1">
				</td>
			</tr>
<?php
// @TODO
// Add a row for each package type
?>
		</tbody>
	</table>
	<h2><?php _e( 'Message', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[subject]"><?php _e('Subject', 'wpet'); ?></label></th>
				<td><input name="options[subject]" type="text" id="options[subject]" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[email_body]"><?php _e('Email Body', 'wpet'); ?></label></th>
				<td><?php wp_editor( esc_attr( $data['email_body'] ), 'options[email_body]', array( 'textarea_rows' => 20 ) ); ?>
				</td>
			</tr>

		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Send Notification', 'wpet'); ?>"></p>
</form>
