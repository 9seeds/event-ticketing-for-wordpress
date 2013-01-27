<p><?php _e('Check the boxes for all of the settings you wish to reset. Be warned, checking the boxes and hitting the reset button WILL reset each checked box to the factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[ticket_options]"><?php _e('Ticket Options', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[ticket_options]" id="options[ticket_options]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[ticket_types]"><?php _e('Ticket Types', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[ticket_types]" id="options[ticket_types]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[packages]"><?php _e('Packages', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[packages]" id="options[packages]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[coupons]"><?php _e('Coupons', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[coupons]" id="options[coupons]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[attendees]"><?php _e('Attendees', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[attendees]" id="options[attendees]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[event_settings]"><?php _e('Event Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[event_settings]" id="options[event_settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[payment_settings]"><?php _e('Payment Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[payment_settings]" id="options[payment_settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[email_settings]"><?php _e('Email Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[email_settings]" id="options[email_settings]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
