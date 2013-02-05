jQuery(document).ready(function($) {
	$("#tabs").tabs({
		cookie: {
			name: 'wpet_settings_tab'
		}
	});

	$("#event_date").datepicker();

	$('#payment_gateway').bind('change keyup', wpetGatewaySelected);
	
	function wpetGatewaySelected() {
		$('#gateway_container > div').each(function() {
			$(this).hide();
		});
		$('#' + $(this).val()).show();
	}

});