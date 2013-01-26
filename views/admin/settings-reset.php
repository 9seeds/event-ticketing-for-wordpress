<p><?php _e('Check the boxes for all of the settings you wish to reset. Be warned, checking the boxes and hitting the reset button WILL reset each checked box to the factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[ticket-options]"><?php _e('Ticket Options', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[ticket-options]" id="options[ticket-options]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[ticket-types]"><?php _e('Ticket Types', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[ticket-types]" id="options[ticket-types]" value="1"></label>
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
			<th scope="row"><label for="options[event-settings]"><?php _e('Event Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[event-settings]" id="options[event-settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[payment-settings]"><?php _e('Payment Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[payment-settings]" id="options[payment-settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[email-settings]"><?php _e('Email Settings', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[email-settings]" id="options[email-settings]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
