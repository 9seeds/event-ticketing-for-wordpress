	<table class="form-table">
		<tbody>
<?php
/**
 * @todo Hawkins proke the calendar pop-up
 */
?>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[event_date]"><?php _e('Event Date', 'wpet'); ?></label></th>
				<td><input name="options[event_date]" id="options[event_date]" type="text" value="<?php esc_attr_e( $data['event_date'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[organizer_name]"><?php _e('Organizer Name', 'wpet'); ?></label></th>
				<td><input name="options[organizer_name]" id="options[organizer_name]" type="text" value="<?php esc_attr_e( $data['organizer_name'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[organizer_email]"><?php _e('Organizer Email', 'wpet'); ?></label></th>
				<td><input name="options[organizer_email]" id="options[organizer_email]" type="text" value="<?php esc_attr_e( $data['organizer_email'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[max_attendance]"><?php _e('Maximum Attendance', 'wpet'); ?></label></th>
				<td><input name="options[max_attendance]" id="options[max_attendance]" type="text" value="<?php esc_attr_e( $data['max_attendance'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[event_status]"><?php _e('Event Status', 'wpet'); ?></label></th>
				<td>
		<?php
		/**
		 * @TODO event-status
		 */
		?>
					<select name="options[event_status]"  id="options[event_status]">
						<option value="closed"><?php _e('Registration is closed', 'wpet'); ?></option>
						<option value="open"><?php _e('Registration is open', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[closed_message]"><?php _e('Closed Message text', 'wpet'); ?></label></th>
				<td><textarea name="options[closed_message]" id="options[closed_message]" rows="10"><?php esc_attr_e( $data['closed_message'] ) ?></textarea></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[thank_you]"><?php _e('Thank you page text', 'wpet'); ?></label></th>
				<td><textarea name="options[thank_you]" id="options[thank_you]" rows="10"><?php esc_attr_e( $data['thank_you'] ) ?></textarea><br />
					<em><?php _e( 'Note: To display the link to each ticket on the thank you page, include the [ticketlinks] shortcode.', 'wpet' ); ?></em>
				</td>
			</tr>
		</tbody>
	</table>
