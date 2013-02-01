jQuery(document).ready(function($) {
	$("#tabs").tabs({
		cookie: {
			name: 'wpet_settings_tab'
		}
	});

	$("#event_date").datepicker();

	//$('#payment_gateway').change();
	//$('#payment_gateway').keyup();

});