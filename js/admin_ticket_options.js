jQuery(document).ready(function($) {
	$('#option_type').change(wpet_ticket_option_change);
	$('#option_type').keyup(wpet_ticket_option_change);

	function wpet_ticket_option_change() {
		if ( $(this).val() == 'text' ) {
			$('#option_values').hide();
		} else {
			$('#option_values').show();
		}
	}

	$('.option-delete').live('click', function() {
		$(this).parent().parent().parent().remove();
	});

	$('#add-ticket-option').click(function() {
		var options = $('.option-value');

		var copy = $('#new-value')
			.clone()
			.removeAttr('id')
			.insertBefore('#add-another')
			.show();

		var id = 'options[option-value]['+options.length+']';

		copy.find('.option-value-new')
			.removeClass('option-value-new')
			.addClass('option-value')
			.attr('id', id)
			.removeAttr('disabled');

		copy.find('label').attr('for', id);

	});

	$('#wpet_admin_ticket_option_add').submit(function() {
		//do validation
	/*	if ( jQuery.trim( $( '#option_values' ).val() ) == '' ) {
				alert( wpet_ticket_options_add.name_required );
				return false;
		}
		*/
		
		// Dynamic ticket options
		rows = jQuery( '#option_values' ).find( '.option-value' );
		console.log( rows );
		var good_options = true;
		rows.each( function() {
		    /*
		     * Do the validation in here.
		     * If something is invalide set good_options to false;
		     */
		});
		
		if( !good_options ) {
		    return false;
		}

		return true;
	});

});