	<table class="form-table">
		<tbody>
<?php
/**
 * @todo Hawkins proke the calendar pop-up
 */
?>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[event-date]"><?php _e('Event Date', 'wpet'); ?></label></th>
				<td><input name="options[event-date]" id="options[event-date]" type="text" value="<?php esc_attr_e( $data['event-date'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[organizer-name]"><?php _e('Organizer Name', 'wpet'); ?></label></th>
				<td><input name="options[organizer-name]" id="options[organizer-name]" type="text" value="<?php esc_attr_e( $data['organizer-name'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[organizer-email]"><?php _e('Organizer Email', 'wpet'); ?></label></th>
				<td><input name="options[organizer-email]" id="options[organizer-email]" type="text" value="<?php esc_attr_e( $data['organizer-email'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[max-attendance]"><?php _e('Maximum Attendance', 'wpet'); ?></label></th>
				<td><input name="options[max-attendance]" id="options[max-attendance]" type="text" value="<?php esc_attr_e( $data['max-attendance'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[event-status]"><?php _e('Event Status', 'wpet'); ?></label></th>
				<td>
		<?php
		/**
		 * @TODO event-status
		 */
		?>
					<select name="options[event-status]"  id="options[event-status]">
						<option value="closed"><?php _e('Registration is closed', 'wpet'); ?></option>
						<option value="open"><?php _e('Registration is open', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[coming-soon]"><?php _e('Coming soon text', 'wpet'); ?></label></th>
				<td><textarea name="options[coming-soon]" id="options[coming-soon]"><?php esc_attr_e( $data['coming-soon'] ) ?></textarea></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[thank-you]"><?php _e('Thank you page text', 'wpet'); ?></label></th>
				<td><textarea name="options[thank-you]" id="options[thank-you]"><?php esc_attr_e( $data['thank-you'] ) ?></textarea></td>
			</tr>
		</tbody>
	</table>
