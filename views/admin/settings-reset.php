<p><?php _e('Archive your current event by giving it a useful name and confirm by checking the box. Be warned, WP Event Ticketing only handles one event at a time, checking this box will archive your event and create a new event with factory defaults.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><label for="archive_name"><?php _e('Archived Event Name', 'wpet'); ?></label></th>
			<td><input name="options[archive_name]" id="archive_name" type="text" value="<?php esc_attr_e($data['archive_name']) ?>"></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="options[archive_confirm]"><?php _e('Check to Confirm', 'wpet'); ?></label></th>
			<td>
				<label><input type="checkbox" name="options[archive_confirm]" id="options[archive_confirm]" value="1"></label>
			</td>
		</tr>
	</tbody>
</table>
