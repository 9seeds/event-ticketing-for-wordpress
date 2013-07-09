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

        console.debug( tinyMCE.activeEditor.getContent() );
		if ( tinyMCE.activeEditor.getContent() == '' ) {
			alert( wpet_notifications_add.body_required );
			return false;
		}

		return false;
	});
});