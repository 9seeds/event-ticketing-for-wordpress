<div class="wrap">
	<?php
	echo $admin_page_icon;
	$heading = empty( $_REQUEST['post'] ) ? __('Add Ticket', 'wpet') : __('Edit Ticket', 'wpet');
	?>
	<h2><?php echo $heading; ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[ticket-name]"><?php _e('Ticket Name', 'wpet'); ?></label></th>
				<td><input name="options[ticket-name]" id="options[ticket-name]" type="text" id="options[ticket-name]" value="<?php echo empty( $data['ticket'] ) ? '' : $data['ticket']->post_title ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Ticket Options', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Name', 'wpet'); ?></th>
				<td>
					<input type="checkbox" name="options[name]" value="1" checked="checked" disabled="disabled">
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Email', 'wpet'); ?></th>
				<td>
				    <input type="checkbox" name="options[email]" value="1" checked="checked" disabled="disabled">
				</td>
			</tr>
<?php
/**
 * @todo auto-fill checkboxes with all available ticket options
 */
// @TODO
//
?>

			<?php echo WPET::getInstance()->ticket_options->buildAdminOptionsCheckboxForm(); ?>

		</tbody>
	</table>
	<?php
	echo $data['nonce'];
	$button_label = empty( $_REQUEST['post'] ) ? __('Add Ticket', 'wpet') : __('Edit Ticket', 'wpet');
	?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $button_label; ?>"></p>
</form>

</div>