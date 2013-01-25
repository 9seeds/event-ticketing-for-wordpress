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
					<select name="options[option-type]" id="">
						<option value="text">Text Input</option>
						<option value="dropdown">Dropdown</option>
						<option value="multiselect">Multi Select</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket Option', 'wpet'); ?>"></p>
</form>
</div>