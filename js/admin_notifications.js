jQuery(document).ready(function($) {

	//disable return key binding (subject field)
	$('#wpet_admin_notification_add').bind('keypress', function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
			return false;
		}
	});

	$('#wpet_admin_notification_add').submit(function() {
		//disable send button
		$('#submit').attr('disabled', 'disabled');

		//do validation
		if ( ! ( $( '#all-attendees' ).is(':checked' ) || 
			    $( '#attendees-have-info' ).is(':checked' ) ||
			    $( '#attendees-no-info' ).is(':checked' ) ) ) {
            alert( wpet_notifications_add.send_to_required );
			$('#submit').removeAttr('disabled');
            return false;
        }

		if ( $.trim( $( '#options\\[subject\\]' ).val() ) == '' ) {
			alert( wpet_notifications_add.subject_required );
			$('#submit').removeAttr('disabled');
			return false;
		}

        if ( tinyMCE.activeEditor == null && $.trim( $( '#options\\[email_body\\]' ).val() ) == '' ||
                tinyMCE.activeEditor != null && tinyMCE.activeEditor.getContent() == '' ) {
            alert( wpet_notifications_add.body_required );
			$('#submit').removeAttr('disabled');
            return false;
        }

		var ok = confirm( wpet_notifications_add.confirmation );
		if ( ! ok )
			$('#submit').removeAttr('disabled');
        return ok;
	});
});