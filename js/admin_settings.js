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

	$('#settings_form').submit(function() {
		//do validation

		if ( jQuery.trim( $( '#event_date' ).val() ) == '' ) {
				alert( settings_check.event_date_required );
				return false;
		}

		if ( jQuery.trim( $( '#organizer_name' ).val() ) == '' ) {
				alert( settings_check.organizer_name_required );
				return false;
		}

		if ( jQuery.trim( $( '#organizer_email' ).val() ) == '' ) {
				alert( settings_check.organizer_email_required );
				return false;
		}

		if ( ! jQuery.isNumeric( $( '#max_attendance' ).val() ) ) {
				alert( settings_check.max_attendees_not_numeric );
				return false;
		}

		if ( jQuery.trim( $( '#options\\[subject\\]' ).val() ) == '' ) {
				alert( settings_check.options_subject_required );
				return false;
		}

		if ( jQuery.trim( $( '#options\\[email_body\\]' ).val() ) == '' ) {
				alert( settings_check.options_email_body_required );
				return false;
		}

		return true;
	});



});