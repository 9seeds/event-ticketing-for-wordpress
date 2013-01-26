<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Notify Attendees', 'wpet'); ?></h2>
<form method="post" action="">
	<h2><?php _e( 'To:', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('All attendees', 'wpet'); ?></th>
				<td>
					<label><input type="checkbox" name="options[all-attendees]" value="1"></label>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Have filled out info', 'wpet'); ?></th>
				<td>
					<label><input type="checkbox" name="options[have-info]" value="1"></label>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Have not filled out info', 'wpet'); ?></th>
				<td>
					<label><input type="checkbox" name="options[no-info]" value="1"></label>
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
				<th scope="row"><?php _e('Subject', 'wpet'); ?></th>
				<td><input name="options[subject]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Email Body', 'wpet'); ?></th>
				<td><?php wp_editor( '', 'options[email-body]' ); ?>
				</td>
			</tr>

		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Send Notification', 'wpet'); ?>"></p>
</form>
