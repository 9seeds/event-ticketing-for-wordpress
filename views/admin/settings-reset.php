<p><?php _e('Archive your current event by giving it a useful name and confirm by checking the box. Be warned, WP Event Ticketing only handles one event at a time, checking this box will archive your event and create a new event with factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="archive_name"><?php _e('Archived Event Name', 'wpet'); ?></label></th>
			<td><input name="options[archive_name]" id="archive_name" type="text" value="<?php esc_attr_e($data['archive_name']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row">
	 			<label for="archive_name"><?php _e('DO NOT Archive', 'wpet'); ?></label>
			</th>
			<td>
				<fieldset>
					<label for="options[archive_keep_tickets]"><input type="checkbox" name="options[archive_keep]" id="options[archive_keep_tickets]" value="tickets" /> <?php _e('Tickets', 'wpet'); ?></label>
					<label for="options[archive_keep_ticketoptions]"><input type="checkbox" name="options[archive_keep]" id="options[archive_keep_ticketoptions]" value="ticketoptions" /> <?php _e('Ticket Options', 'wpet'); ?></label>
					<label for="options[archive_keep_packages]"><input type="checkbox" name="options[archive_keep]" id="options[archive_keep_packages]" value="packages" /> <?php _e('Packages', 'wpet'); ?></label>
				</fieldset>
	 		</td>
		</tr>
		
	 
		<tr class="form-field form-required">
			<th scope="row"><label for="options[archive_confirm]"><?php _e('Check to Confirm', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[archive_confirm]" id="options[archive_confirm]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
