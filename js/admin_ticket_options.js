jQuery(document).ready(function($) {
	$('#options[option-type]').change(wpet_ticket_option_change);
	$('#options[option-type]').keyup(wpet_ticket_option_change);

	function wpet_ticket_option_change() {
		if ( $(this).val() == 'text' ) {
			$('#options[option-values]').hide();
		} else {
			$('#options[option-values]').show();
		}
	}

	$('.option-delete').live('click', function() {
		$(this).parent().parent().parent().remove();
	});

	$('#add-value').click(function() {
		var copy = $('#new-value')
			.clone()
			.removeAttr('id')
			.insertBefore('#add-another')
			.show();

		copy.find('.option-value-new')
			.removeClass('option-value-new')
			.removeAttr('disabled');
	});

});