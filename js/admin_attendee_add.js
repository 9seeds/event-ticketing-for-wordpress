jQuery(document).ready(function(){
   
    jQuery("#package").change(function( package_id ) {
	//console.log( jQuery(this).val() );
	jQuery.post(
	    ajaxurl, 
	    {
		action: "get_ticket_options_for_package", 
		package_id : jQuery(this).val() 
	    //nonce: nonce
	    },
	    function(response) {
		console.log(response);
		jQuery('#ticket_options').html(response);
		
	    }
	         
	    );
   
    });
});