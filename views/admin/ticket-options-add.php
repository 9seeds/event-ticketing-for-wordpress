<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Add Ticket Options', 'wpet'); ?></h2>
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
					$value = isset( $data['option'] ) ? $data['option']->wpet_type : '';
					$options = array( 'text' => __('Text Input', 'wpet'),
					   'dropdown' => __('Dropdown', 'wpet'),
					   'multiselect' => _e('Multi Select', 'wpet')
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
<? //@TODO real values ?>
		<?php
		$extra_style = 'style="display: none;" ';
		if ( isset( $data['option'] ) && in_array( $data['option']->wpet_type, array( 'dropdown', 'select' ) ) ) {
			$extra_style = '';	
		}
		?>
		<tbody <?php echo $extra_style ?>id="option-values">
			<tr class="form-field">
				<th scope="row"><label for="options[option-value][0]"><?php _e('Option Value', 'wpet'); ?></label></th>
				<td>
					<input type="text" name="options[option-value][]" id="options[option-value][0]" class="option-value" />
				</td>
			</tr>
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
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket Option', 'wpet'); ?>"></p>
	</form>
</div>