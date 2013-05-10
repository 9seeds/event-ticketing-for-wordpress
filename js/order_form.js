
    var coupon_amount = 0;
    var coupon_type = 'flat-rate';
    
jQuery(document).ready(function($) {
    
    jQuery( '.quantity' ).change( function() { update_subtotal(); } );
    
    
    
    
    jQuery('#couponSubmitButton').click( function() {
	
	jQuery.post(
	    ajax_object.ajaxurl, 
	    {
		action: 'get_coupon',
		coupon_code: jQuery('#coupon_code').val()
	    },
	    function(response) {
		var obj = jQuery.parseJSON( response );
		console.log(jQuery('#coupon_code').val());
		console.log(obj);
		
		coupon_amount = obj.amount;
		coupon_type = obj.type;
		update_subtotal();
	    }
		
	    );
    });
    
    
   
});

 function update_subtotal() {
	rows = jQuery( '#order_form' ).find( '.package_row' );
	console.log(rows);
	total = 0;
	
	rows.each( function() {
	    price =  Number( jQuery(this).find( '.price' ).text().replace(/[^0-9\.]+/g, "") );
	    
	    quantity = Number( jQuery(this).find( '.quantity' ).val() );
	    
	    row_total = price * quantity;
	    
	    total += row_total;
	});
	
	switch( coupon_type ) {
	    case 'percentage':
		total = total - ( total * ( coupon_amount / 100 ) );
		break;
	    case 'flat-rate':
		total = total - coupon_amount;
	}
	
	if( 0 > total) {
	    total = 0;
	}
	
	jQuery( '#subTotal' ).text( total.toFixed(2) );
    }