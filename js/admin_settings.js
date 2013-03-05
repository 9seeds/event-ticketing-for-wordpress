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

	$('#settings_form').submit(function() {
		var reset_settings = $('[id^="options\[reset\]"]:checked');

		if ( reset_settings.length ) {
			
			//@TODO i18n/l10n these
			var message = "Are you sure you want to reset these?:\n";	
			reset_settings.each(function() {
				message += $('label[for="'+ $(this).attr('id') +'"]').html() + "\n";
			});

			if ( confirm(message) ) {
				return true;
			}
			return false;
		}

		return true;
	});

});