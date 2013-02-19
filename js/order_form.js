
    var coupon_amount = 0;
    var coupon_type = 'flat-rate';
    
jQuery(document).ready(function($) {
    
    jQuery( '.quantity' ).change( function() { update_subtotal(); } );
    
    
    
    
    jQuery('#couponSubmitButton').click( function() {
	
	jQuery.post(
	    ajax_object.ajaxurl, 
	    {
		action: 'get_coupon',
		coupon_code: jQuery('#couponCode').val()
	    },
	    function(response) {
		var obj = jQuery.parseJSON( response );
		coupon_amount = obj.amount;
		coupon_type = obj.type;
		update_subtotal();
	    }
		
	    );
    });
    
    
   
});

 function update_subtotal() {
	rows = jQuery( '#order_form' ).find( '.package_row' );
	
	total = 0;
	
	rows.each( function() {
	    price =  Number( jQuery(this).find( '.price' ).text().replace(/[^0-9\.]+/g, "") );
	    
	    quantity = Number( jQuery(this).find( '.quantity' ).val() );
	    
	    row_total = price * quantity;
	    
	    total += row_total;
	});
	
	switch( coupon_type ) {
	    case 'percent':
		total = total - ( total * ( coupon_amount / 100 ) );
	    case 'flat-rate':
		total = total - coupon_amount;
	}
	
	
	jQuery( '#subTotal' ).text( total );
    }