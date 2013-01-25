<p><?php _e('Check the boxes for all of the settings you wish to reset. Be warned, checking the boxes and hitting the reset button WILL reset each checked box to the factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Ticket Options', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[ticket-options]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Ticket Types', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[ticket-types]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Packages', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[packages]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Coupons', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[coupons]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Attendees', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[attendees]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Event Settings', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[event-settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Payment Settings', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[payment-settings]" value="1"></label>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Email Settings', 'wpet'); ?></th>
			<td>
				<label><input type="checkbox" name="options[email-settings]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
