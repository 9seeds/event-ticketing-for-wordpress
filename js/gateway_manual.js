jQuery(document).ready(function($) {
	$('#order_form').submit(function() {
		//do validation
		if ( jQuery.trim( $( '.quantity' ).val() ) < 1 ) {
				alert( wpet_manual_gateway.quantity_required );
				return false;
		}

		return true;
	});

});

jQuery(document).ready(function($) {
	$('#manual_payment_details').submit(function() {
		//do validation
		if ( jQuery.trim( $( '#name' ).val() ) == '' ) {
				alert( wpet_manual_gateway.name_required );
				return false;
		}

		if ( jQuery.trim( $( '#email' ).val() ) == '' ) {
				alert( wpet_manual_gateway.email_required );
				return false;
		}

		return true;
	});

});

