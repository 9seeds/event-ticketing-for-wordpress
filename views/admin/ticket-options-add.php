<div class="wrap">
	<?php
	echo $admin_page_icon;
	$heading = empty( $_REQUEST['post'] ) ? __('Add Ticket Options', 'wpet') : __('Edit Ticket Options', 'wpet');
	?>
	<h2><?php echo $heading; ?></h2>
	<form method="post" action="<?php echo $data['edit_url'] ?>">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[display-name]"><?php _e('Display Name', 'wpet'); ?></label></th>
				<td><input name="options[display-name]" type="text" id="options[display-name]" value="<?php echo isset( $data['option'] ) ? $data['option']->post_title : '' ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[option-type]"><?php _e('Option Type', 'wpet'); ?></label></th>
				<td>
					<select name="options[option-type]" id="options[option-type]">
					<?php
					$value = empty( $data['option'] ) ? '' : $data['option']->wpet_type;
					$options = array(
						'text'        => __('Text Input', 'wpet'),
						'dropdown'    => __('Dropdown', 'wpet'),
						'multiselect' => __('Multi Select', 'wpet'),
					);
					foreach ( $options as $index => $label ) {
						$selected = $index == $value ? ' selected="selected"' : '';
						echo "<option value='{$index}'{$selected}>{$label}</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
		</tbody>
		<?php
		$extra_style = 'style="display: none;" ';
		if ( ! empty( $data['option'] ) && in_array( $data['option']->wpet_type, array( 'dropdown', 'multiselect' ) ) ) {
			$extra_style = '';	
		}
		?>
		<tbody <?php echo $extra_style ?>id="option-values">
			<?php
			$count = 0;
			$values = empty( $data['option'] ) ? array() : $data['option']->wpet_values;
			if ( empty( $values ) )
				$values[] = '';
			foreach ( $values  as $value ):
				$delete = $count ? '<span class="wpet-delete">&nbsp;<a class="option-delete">X</a>' : '';
			?>
			<tr class="form-field">
				<th scope="row"><label for="options[option-value][<?php echo $count; ?>]"><?php _e('Option Value', 'wpet'); ?></label></th>
				<td>
					<input type="text" name="options[option-value][]" id="options[option-value][<?php echo $count; ?>]" class="option-value" value="<?php echo $value ?>" /><?php echo $delete ?>
				</td>
			</tr>
			<?php $count++; endforeach; ?>
			<tr class="form-field" id="add-another">
	 			<th></th>
				<td>
					<a id="add-ticket-option"><?php _e( 'Add Another Value', 'wpet' ) ?></a>
				</td>
			</tr>
			<tr class="form-field" style="display: none;" id="new-value">
				<th scope="row"><label><?php _e('Option Value', 'wpet'); ?></label></th>
				<td>
					<input type="text" name="options[option-value][]" id="options[option-value]" class="option-value-new" value="" disabled="disabled" /><span class="wpet-delete">&nbsp;<a class="option-delete">X</a>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
	echo $data['nonce'];
	$button_label = empty( $_REQUEST['post'] ) ? __( 'Add Ticket Option', 'wpet' ) : __( 'Edit Ticket Option', 'wpet' );
	?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $button_label; ?>"></p>
	</form>
</div>