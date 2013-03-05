<p><?php _e('Check the boxes for all of the settings you wish to reset. Be warned, checking the boxes and hitting the reset button WILL reset each checked box to the factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][ticket_options]"><?php _e('Ticket Options', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][ticket_options]" id="options[reset][ticket_options]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][ticket_types]"><?php _e('Ticket Types', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][ticket_types]" id="options[reset][ticket_types]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][packages]"><?php _e('Packages', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][packages]" id="options[reset][packages]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][coupons]"><?php _e('Coupons', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][coupons]" id="options[reset][coupons]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][attendees]"><?php _e('Attendees', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][attendees]" id="options[reset][attendees]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][event_settings]"><?php _e('Event Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][event_settings]" id="options[reset][event_settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][payment_settings]"><?php _e('Payment Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][payment_settings]" id="options[reset][payment_settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[reset][email_settings]"><?php _e('Email Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[reset][email_settings]" id="options[reset][email_settings]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
