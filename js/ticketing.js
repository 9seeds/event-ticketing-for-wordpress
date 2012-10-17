jQuery(document).ready(function() {
	jQuery('#btnAdd').click(function() {
		var num		= jQuery('.clonedInput').length;	// how many "duplicatable" input fields we currently have
		var newNum	= new Number(num + 1);		// the numeric ID of the new input field being added
		var newElem = jQuery('#input' + num).clone().attr('id', 'input' + newNum);
		newElem.children(':first').attr('id', 'ticketOptionDrop' + newNum).attr('name', 'ticketOptionDrop[' + newNum + ']');
		jQuery('#input' + num).after(newElem);
		jQuery('#btnDel').attr('disabled','');
	});

	jQuery('#btnDel').click(function() {
		var num	= jQuery('.clonedInput').length;	// how many "duplicatable" input fields we currently have
		jQuery('#input' + num).remove();		// remove the last element
		jQuery('#btnAdd').attr('disabled','');
		if (num-1 == 1)
			jQuery('#btnDel').attr('disabled','disabled');
	});
	//jQuery('#btnDel').attr('disabled','disabled');
});

jQuery(document).ready(function() {
	jQuery("#ticketoptionselect").change(function() {
		var value = jQuery(this).val();
		if(value == 'dropdown')
			jQuery('#optionvalsdiv').show();
		if(value == 'multidropdown')
			jQuery('#optionvalsdiv').show();
		if(value == 'text')
			jQuery('#optionvalsdiv').hide();
	});
	
	jQuery(function() {
		var value = jQuery("#ticketoptionselect").val();
		if(value == 'dropdown')
			jQuery('#optionvalsdiv').show();
		if(value == 'multidropdown')
			jQuery('#optionvalsdiv').show();
		if(value == 'text')
			jQuery('#optionvalsdiv').hide();
	});
		

});

jQuery(function($) {
	$("#expireStart").datepicker();
        $("#expireEnd").datepicker();
        
        /*
         * On page load hide all of the payment gateway forms except the selected
         * form.
         * 
         * @since 1.4
         */
        changed();
});


/*
 * Toggles which div is shown for the payment gateway information
 * 
 * @since 1.4
 * @author Ben Lobaugh
 */
function changed() { 
    var newGateway = jQuery( '#paymentGateway').val(); // The form to show
    var divs = jQuery( '#paymentGateway').children(); // A list of all the forms
  
    // Loop through and hide each form we do not want displayed
    divs.each( function( k, div ) {
        jQuery( '#payment-gateway-settings-' + div.value ).css( 'display', 'none');
    });
    
    // Show the one form the user wants to use
   jQuery( '#payment-gateway-settings-' + newGateway ).css( 'display', 'block');
}
    
    
