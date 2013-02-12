jQuery(document).ready(function($) {
    coupon_amount = 0;
    coupon_type = 'flat-rate';
    
    jQuery( '.quantity' ).change( function() {
	rows = jQuery( '#order_form' ).find( '.package_row' );
	
	total = 0;
	
	rows.each( function() {
	    price =  Number( jQuery(this).find( '.price' ).text().replace(/[^0-9\.]+/g, "") );
	    
	    quantity = Number( jQuery(this).find( '.quantity' ).val() );
	    
	    row_total = price * quantity;
	    
	    total += row_total;
	});
	
	jQuery( '#subTotal' ).text( total );
    });
    
    
    
    
    jQuery('#couponSubmitButton').click( function() {
	console.log(ajax_object.ajaxurl);
	jQuery.post(
	    ajax_object.ajaxurl, 
	    {
		action: 'get_coupon',
		coupon_code: jQuery('#couponCode').val()
	    },
	    function(response) {
		console.log( response );
	    }
		
	    );
    });
});