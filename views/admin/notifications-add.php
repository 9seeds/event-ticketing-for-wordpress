<?php
$heading = empty($_REQUEST['post']) ? __('Add Notification', 'wpet') : __('View Notification', 'wpet');

$submit_url =(add_query_arg(array('notify' => 'doit')));

?>
<h2><?php echo $heading; ?> <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
} ?></h2>
<?php if ( !empty($data['mail']) && $data['mail'] ) : ?>
<div id="message" class="updated"><p>Notification sent</p></div>
<?php endif; ?>
<form method="post" action="<?php echo $submit_url; ?>" id="wpet_admin_notification_add">
	<h2><?php _e( 'To:', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="all-attendees"><?php _e('All attendees', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[to][all-attendees]" id="all-attendees" class="options-to" value="1" <?php if( isset( $_POST['options']['to']['all-attendees'] ) ) { echo 'checked'; } ?>  />
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="attendees-have-info"><?php _e('Have filled out info', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[to][have-info]" id="attendees-have-info" class="options-to" value="1" <?php if( isset( $_POST['options']['to']['have-info'] ) ) { echo 'checked'; } ?> />
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="attendees-no-info"><?php _e('Have not filled out info', 'wpet'); ?></label></th>
				<td>
					<input type="checkbox" name="options[to][no-info]" id="attendees-no-info" class="options-to" value="1" <?php if( isset( $_POST['options']['to']['no-info'] ) ) { echo 'checked'; } ?> />
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
				<td><input name="options[subject]" type="text" id="options[subject]" value="<?php if( isset( $_POST['options']['subject'] ) ) { echo $_POST['options']['subject']; } ?>"></td>
			</tr>
			<tr class="form-required">
				<th scope="row"><label for="options[email_body]"><?php _e('Email Body', 'wpet'); ?></label></th>
				<td>
				<div class="postarea">
				<?php
					$body = empty( $_POST['options']['email_body'] ) ? '' : $_POST['options']['email_body'];
					wp_editor( $body, 'options[email_body]', array( 'textarea_rows' => 20 ) );
				?>
				</div>
				</td>
			</tr>

		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Send Notification', 'wpet') ?>"></p>
</form>
