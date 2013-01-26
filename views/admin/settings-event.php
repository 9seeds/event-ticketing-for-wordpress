	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Event Date', 'wpet'); ?></th>
				<td><input name="options[event-date]" id="event-date" type="text" value="<?php esc_attr_e( $data['event-date'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Organizer Name', 'wpet'); ?></th>
				<td><input name="options[organizer-name]" type="text" value="<?php esc_attr_e( $data['organizer-name'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Organizer Email', 'wpet'); ?></th>
				<td><input name="options[organizer-email]" type="text" value="<?php esc_attr_e( $data['organizer-email'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Maximum Attendance', 'wpet'); ?></th>
				<td><input name="options[max-attendance]" type="text" value="<?php esc_attr_e( $data['max-attendance'] ) ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Event Status', 'wpet'); ?></th>
				<td>
<?php //@TODO event-status ?>
					<select name="options[event-status]">
						<option value="closed"><?php _e('Registration is closed', 'wpet'); ?></option>
						<option value="open"><?php _e('Registration is open', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Coming soon text', 'wpet'); ?></th>
				<td><textarea name="options[coming-soon]"><?php echo $data['coming-soon'] ?></textarea></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Thank you page text', 'wpet'); ?></th>
				<td><textarea name="options[thank-you]"><?php echo $data['thank-you'] ?></textarea></td>
			</tr>
		</tbody>
	</table>
