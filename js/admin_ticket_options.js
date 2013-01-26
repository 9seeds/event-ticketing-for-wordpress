jQuery(document).ready(function($) {
	$('#options\\[option-type\\]').change(wpet_ticket_option_change);
	$('#options\\[option-type\\]').keyup(wpet_ticket_option_change);

	function wpet_ticket_option_change() {
		if ( $(this).val() == 'text' ) {
			$('#option-values').hide();
		} else {
			$('#option-values').show();
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
			.removeAttr('id')
			.attr('id', id)
			.removeAttr('disabled');

		copy.find('label').attr('for', id);

	});

});