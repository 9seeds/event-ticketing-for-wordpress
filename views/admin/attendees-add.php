<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Add Attendee', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Package', 'wpet'); ?></th>
				<td>
					<select name="options[package]" id="">
						<option value="">List of Ticket Packages</option>
<?php
// @TODO
// add dropdowns for all ticket packages
?>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Name', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Email', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
<?php
// @TODO
// add rows for all ticket options
?>

		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Attendee', 'wpet'); ?>"></p>
</form>

</div>