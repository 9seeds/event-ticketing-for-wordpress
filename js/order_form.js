
var coupon_amount = 0;
    
jQuery(document).ready(function($) {
    
    $( '.quantity' ).change( function() { update_subtotal(); } );
        
    $('#couponSubmitButton').click( function() {
	
		jQuery.post(
			ajax_object.ajaxurl, 
			{
				action: 'get_coupon',
				coupon_code: $('#coupon_code').val(),
				packages: get_package_qty()
			},
			function(response) {
				var obj = jQuery.parseJSON( response );
				
				console.log($('#coupon_code').val());
				console.log(obj);
				if(obj.error) {
					$('#invalid_coupon').show();
				} else {
					$('#invalid_coupon').hide();
				}
				coupon_amount = obj.amount;
				update_subtotal();
			}			
	    );
    });
    
    
   
});

function get_package_qty() {
	var package_qty = {};

	jQuery('select[name^="package_purchase"]').each(function() {
		var package_info = jQuery(this).attr('name').match( /package_purchase\[(\d+)\]/ );
		package_qty[package_info[1]] = jQuery(this).val();
	});
	return package_qty;
}

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
	
	total = total - coupon_amount;
	
	if( 0 > total) {
		total = 0;
	}
	
	jQuery( '#subTotal' ).text( total.toFixed(2) );
}