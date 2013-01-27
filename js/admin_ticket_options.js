jQuery(document).ready(function($) {
	$('#option_type').change(wpet_ticket_option_change);
	$('#option_type').keyup(wpet_ticket_option_change);

	function wpet_ticket_option_change() {
		if ( $(this).val() == 'text' ) {
			$('#option_values').hide();
		} else {
			$('#option_values').show();
		}
	}

	$('.option-delete').live('click', function() {
		$(this).parent().parent().parent().remove();
	});

	$('#add-ticket-option').click(function() {
		var options = $('.option-value');

		var copy = $('#new-value')
			.clone()
			.removeAttr('id')
			.insertBefore('#add-another')
			.show();

		var id = 'options[option-value]['+options.length+']';

		copy.find('.option-value-new')
			.removeClass('option-value-new')
			.addClass('option-value')
			.attr('id', id)
			.removeAttr('disabled');

		copy.find('label').attr('for', id);

	});

});