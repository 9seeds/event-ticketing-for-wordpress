jQuery(document).ready(function($) {
	$("#tabs").tabs({
		cookie: {
			name: 'wpet_settings_tab'
		}
	});

	$("#options\\[event-date\\]").datepicker();
});