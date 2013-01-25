<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Add Ticket Options', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Display Name', 'wpet'); ?></th>
				<td><input name="options[display-name]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Option Type', 'wpet'); ?></th>
				<td>
					<select name="options[option-type]" id="option-type">
						<option value="text">Text Input</option>
						<option value="dropdown">Dropdown</option>
						<option value="multiselect">Multi Select</option>
					</select>
				</td>
			</tr>
		</tbody>
		<tbody style="display: none;" id="option-values">
			<tr class="form-field">
				<th scope="row"><?php _e('Option Value', 'wpet'); ?></th>
				<td>
					<input type="text" name="options[option-value-0]" class="option-value" />
				</td>
			</tr>
			<tr class="form-field" id="add-another">
	 			<th></th>
				<td>
					<a id="add-value"><?php _e( 'Add Another Value', 'wpet' ) ?></a>
				</td>
			</tr>
			<tr class="form-field" style="display: none;" id="new-value">
				<th scope="row"><?php _e('Option Value', 'wpet'); ?></th>
				<td>
					<input type="text" value="" class="option-value-new" /><span class="wpet-delete">&nbsp;<a class="option-delete">X</a>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket Option', 'wpet'); ?>"></p>
</form>
</div>