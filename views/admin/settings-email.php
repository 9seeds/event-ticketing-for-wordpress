<form method="post" action="">
	<h2><?php _e('Registration Email', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('From Name', 'wpet'); ?></th>
				<td><input name="options[from-name]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('From Email', 'wpet'); ?></th>
				<td><input name="options[from-email]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Subject', 'wpet'); ?></th>
				<td><input name="options[subject]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Email Body', 'wpet'); ?></th>
				<td><textarea name="options[email-body]" type="text" id="" value=""></textarea><br />
					<em><?php _e('Note: Include the shortcode [ticketlinks] in the email body to send the buyer the link to edit their ticket information.', 'wpet'); ?></em>



				</textarea></td>
			</tr>
		</tbody>
	</table>
</form>
