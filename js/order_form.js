jQuery(document).ready(function($) {
    jQuery('.quantity').change( function() {
	rows = jQuery('#order_form').find('.package_row');
	console.log( rows );
	
	total = 0;
	
	rows.each( function() {
	    price =  Number(jQuery(this).find('.price').text().replace(/[^0-9\.]+/g,""));
	    
	    quantity = Number( jQuery(this).find('.quantity').val() );
	    
	    row_total = price * quantity;
	    console.log( row_total );
	    
	    total += row_total;
	});
	
	jQuery('#subTotal').text(total);
    });
});