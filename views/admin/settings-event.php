<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="event_date"><?php _e('Event Date', 'wpet'); ?></label></th>
			<td><input name="options[event_date]" id="event_date" type="text" value="<?php esc_attr_e($data['event_date']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="organizer_name"><?php _e('Organizer Name', 'wpet'); ?></label></th>
			<td><input name="options[organizer_name]" id="organizer_name" type="text" value="<?php esc_attr_e($data['organizer_name']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="organizer_email"><?php _e('Organizer Email', 'wpet'); ?></label></th>
			<td><input name="options[organizer_email]" id="organizer_email" type="text" value="<?php esc_attr_e($data['organizer_email']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="max_attendance"><?php _e('Maximum Attendance', 'wpet'); ?></label></th>
			<td><input name="options[max_attendance]" id="max_attendance" type="text" value="<?php esc_attr_e($data['max_attendance']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="event_status"><?php _e('Event Status', 'wpet'); ?></label></th>
			<td>
				<select name="options[event_status]"  id="event_status">
					<option value="closed"><?php _e('Registration is closed', 'wpet'); ?></option>
					<option value="open"><?php _e('Registration is open', 'wpet'); ?></option>
				</select>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="closed_message"><?php _e('Closed Message text', 'wpet'); ?></label></th>
			<td><textarea name="options[closed_message]" id="closed_message" rows="10"><?php esc_attr_e($data['closed_message']) ?></textarea></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="thank_you"><?php _e('Thank you page text', 'wpet'); ?></label></th>
			<td><textarea name="options[thank_you]" id="thank_you" rows="10"><?php esc_attr_e($data['thank_you']) ?></textarea><br />
				<em><?php _e('Note: To display the link to each ticket on the thank you page, include the [ticketlinks] shortcode.', 'wpet'); ?></em>
			</td>
		</tr>
	</tbody>
</table>
