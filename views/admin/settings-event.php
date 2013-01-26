<?php
// @TODO
// Add date picker for event date
?>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Event Date', 'wpet'); ?></th>
				<td><input name="options[event-date]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Organizer Name', 'wpet'); ?></th>
				<td><input name="options[organizer-name]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Organizer Email', 'wpet'); ?></th>
				<td><input name="options[organizer-email]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Maximum Attendance', 'wpet'); ?></th>
				<td><input name="options[max-attendance]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Event Status', 'wpet'); ?></th>
				<td>
					<select name="options[event-status]" id="">
						<option value="closed"><?php _e('Registration is closed', 'wpet'); ?></option>
						<option value="open"><?php _e('Registration is open', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Coming soon text', 'wpet'); ?></th>
				<td><textarea name="options[coming-soon]" id="" value=""></textarea></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Thank you page text', 'wpet'); ?></th>
				<td><textarea name="options[thank-you]" id="" value=""></textarea></td>
			</tr>
		</tbody>
	</table>
