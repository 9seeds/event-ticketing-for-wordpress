<div class="wrap">
	<?php
	echo $admin_page_icon;
	$heading = empty( $_REQUEST['post'] ) ? __('Add Ticket', 'wpet') : __('Edit Ticket', 'wpet');
	?>
	<h2><?php echo $heading; ?> <?php if( isset($_GET['action'] ) && $_GET['action'] == 'edit' ) { echo '<a href="'. $data['edit_url'] .'" class="add-new-h2">'. __( 'Add New', 'wpet' ) .'</a>'; } ?></h2>
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
			$selected = empty( $data['ticket']->wpet_options_selected ) ? array() : $data['ticket']->wpet_options_selected;
			$checkboxes = WPET::getInstance()->ticket_options->getAdminOptionsCheckboxes( $selected );
			foreach ( $checkboxes as $cb_info ):
			?>
			<tr class="form-field">
				<th scope="row"><?php echo $cb_info['label']; ?></th>
				<td><?php echo $cb_info['checkbox']; ?></td>
			</tr>
			<?php
			endforeach;
			?>

		</tbody>
	</table>
	<?php
	echo $data['nonce'];
	$button_label = empty( $_REQUEST['post'] ) ? __('Add Ticket', 'wpet') : __('Save Ticket', 'wpet');
	?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $button_label; ?>"></p>
</form>

</div>