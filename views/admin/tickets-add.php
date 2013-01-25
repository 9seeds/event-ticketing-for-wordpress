<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Add Ticket', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Ticket Name', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Ticket Options', 'wpet'); ?></th>
				<td>
					<input type="checkbox" name="wpet-options[name]" value="1" checked="checked" disabled="disabled"> Name<br />
					<input type="checkbox" name="wpet-options[email]" value="1" checked="checked" disabled="disabled"> Email<br />
<?php
// @TODO
// auto-fill checkboxes with all available ticket options
?>

				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket', 'wpet'); ?>"></p>
</form>

</div>