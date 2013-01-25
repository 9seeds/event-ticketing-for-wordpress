jQuery(document).ready(function($) {
	$('#option-type').change(wpet_ticket_option_change);
	$('#option-type').keyup(wpet_ticket_option_change);

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

	$('#add-value').click(function() {
		$('#new-value')
			.clone()
			.removeAttr('id')
			.insertBefore('#add-another')
			.show();
	});
	
});