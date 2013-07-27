jQuery(document).ready(function($) {

	$('#wpet_admin_notification_add').submit(function() {
		//do validation
		if ( ! ( $( '#all-attendees' ).is(':checked' ) || 
			    $( '#attendees-have-info' ).is(':checked' ) ||
			    $( '#attendees-no-info' ).is(':checked' ) ) ) {
            alert( wpet_notifications_add.send_to_required );
            return false;
        }

		if ( $.trim( $( '#options\\[subject\\]' ).val() ) == '' ) {
			alert( wpet_notifications_add.subject_required );
			return false;
		}

        if ( tinyMCE.activeEditor == null && $.trim( $( '#options\\[email_body\\]' ).val() ) == '' ||
                tinyMCE.activeEditor != null && tinyMCE.activeEditor.getContent() == '' ) {
            alert( wpet_notifications_add.body_required );
            return false;
        }

        return confirm( wpet_notifications_add.confirmation );
	});
});