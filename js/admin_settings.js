jQuery(document).ready(function($) {
	$("#tabs").tabs({
		cookie: {
			name: 'wpet_settings_tab'
		}
	});

	$("#event-date").datepicker();
});