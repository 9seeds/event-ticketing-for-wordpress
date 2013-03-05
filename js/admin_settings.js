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
			
			var reset_list = '';
			reset_settings.each(function() {
				reset_list += $('label[for="'+ $(this).attr('id') +'"]').html() + "\n";
			});

			var message = resetL10n.message.replace('{reset_list}', reset_list);

			if ( confirm( message ) ) {
				return true;
			}
			return false;
		}

		return true;
	});

});