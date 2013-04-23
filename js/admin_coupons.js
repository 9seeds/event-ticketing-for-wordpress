jQuery(document).ready(function($) {

	$('#wpet_admin_coupons_add').submit(function() {
		//do validation
		if ( $('#type').val() == 'percentage' ) {
			if ( $( '#amount' ).val() > 100 ) {
				alert( wpet_coupons_add.percent_too_high );
				return false;
			}
				
		} 

		return true;
	});

});