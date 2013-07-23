<p><?php _e('These settings affect how the form is displayed to visitors.', 'wpet'); ?></p>
<table class="form-table">
	<tbody>
	   
		<tr>
			<th scope="row"><label for="options[show_package_count]"><?php _e('Show # of remaining packages', 'wpet'); ?></label></th>
			<td>
			    <label><input type="checkbox" name="options[show_package_count]" id="options[show_package_count]" value="1" <?php checked( $data['show_package_count'], '1'); ?>></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="options[hide_coupons]"><?php _e('Hide coupons', 'wpet'); ?></label></th>
			<td>
				<input type="checkbox" name="options[hide_coupons]" id="options[hide_coupons]" value="1"<?php checked( $data['hide_coupons'], '1'); ?>>
			</td>
		</tr>
	</tbody>
</table>

