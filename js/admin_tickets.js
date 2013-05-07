jQuery(document).ready(function($) {
	$('#wpet_admin_ticket_add').submit(function() {
		//do validation
		if ( jQuery.trim( $( '#options[ticket-name]' ).val() ) == '' ) {
				alert( wpet_tickets_add.name_required );
				return false;
		}

		return true;
	});

});