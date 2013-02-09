jQuery(document).ready(function(){
   
	function wpet_show_options() {
		//console.log( $('#package').val() );
		$.post(
	    	ajaxurl, 
	    	{
				action: "get_ticket_options_for_package", 
				package_id : $('#package').val(),
				attendee_id : $('#attendee_id').val()
	    		//nonce: nonce
	    	},
	    	function(response) {
				console.log(response);
				$('#ticket_options').html(response);
		    }	         
	    );
    }

    $("#package").bind('change keyup', wpet_show_options );

	//pull in the options on page load
	wpet_show_options();
});