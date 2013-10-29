jQuery(document).ready(function($) {
	$("#start_date").datepicker();
	$("#end_date").datepicker();

	$('#wpet_admin_package_add').submit(function() {
		//do validation
		if ( jQuery.trim( $( '#package_name' ).val() ) == '' ) {
				alert( wpet_package_add.name_required );
				return false;
		}

		if ( jQuery.trim( $( '#description' ).val() ) == '' ) {
				alert( wpet_package_add.description_required );
				return false;
		}

		if ( jQuery.trim( $( '#ticket_id' ).val() ) == '' ) {
				alert( wpet_package_add.ticket_required );
				return false;
		}

		if ( jQuery.trim( $( '#ticket_quantity' ).val() ) == '' ) {
				alert( wpet_package_add.ticket_quantity_required );
				return false;
		}

		if ( ! jQuery.isNumeric( $( '#ticket_quantity' ).val() ) ) {
				alert( wpet_package_add.ticket_quantity_not_numeric );
				return false;
		}

		if ( jQuery.trim( $( '#start_date' ).val() ) == '' ) {
				alert( wpet_package_add.start_required );
				return false;
		}

		if ( jQuery.trim( $( '#end_date' ).val() ) == '' ) {
				alert( wpet_package_add.end_required );
				return false;
		}

		var start_date = new Date( $( '#start_date' ).val() );
		var end_date = new Date( $( '#end_date' ).val() );
		if ( end_date.getTime() < start_date.getTime() ) {
				alert( wpet_package_add.end_after_start );
				return false;
		}

		if ( jQuery.trim( $( '#package_cost' ).val() ) == '' ) {
				alert( wpet_package_add.cost_required );
				return false;
		}

		if ( ! jQuery.isNumeric( $( '#package_cost' ).val() ) ) {
				alert( wpet_package_add.cost_not_numeric );
				return false;
		}

		if ( jQuery.trim( $( '#quantity' ).val() ) == '' ) {
				alert( wpet_package_add.quantity_required );
				return false;
		}

		if ( ! jQuery.isNumeric( $( '#quantity' ).val() ) ) {
				alert( wpet_package_add.ticket_package_quantity_not_numeric );
				return false;
		}

		return true;
	});

});