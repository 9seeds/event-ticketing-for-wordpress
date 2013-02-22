<h2><?php _e('Registration Email', 'wpet'); ?></h2>
<table class="form-table">
	<tbody>
		<!--<tr class="form-field form-required">
			<th scope="row"><label for="options[from_name]"><?php _e('From Name', 'wpet'); ?></label></th>
			<td><input name="options[from_name]" type="text" id="options[from_name]" value="<?php esc_attr_e($data['from_name']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[from_email]"><?php _e('From Email', 'wpet'); ?></label></th>
			<td><input name="options[from_email]" type="text" id="options[from_email]" value="<?php esc_attr_e($data['from_email']) ?>"></td>
		</tr>-->
		<tr class="form-field form-required">
			<th scope="row"><label for="options[subject]"><?php _e('Subject', 'wpet'); ?></label></th>
			<td><input name="options[subject]" type="text" id="options[subject]" value="<?php esc_attr_e($data['subject']) ?>"></td>
		</tr>
		<tr class="form-required">
			<th scope="row"><label for="options[email_body]"><?php _e('Email Body', 'wpet'); ?></label></th>
			<td>
				<div class="postarea">
					<?php wp_editor(esc_attr($data['email_body']), 'options[email_body]', array('textarea_rows' => 20)); ?>
				</div>
				<em><?php _e('Note: Include the [ticketlinks] shortcode in the email body to send the buyer the link to edit their ticket information.', 'wpet'); ?></em>
			</td>
		</tr>
	</tbody>
</table>
