/**
 * Convert php var_dump to javascript console.log
 */
jQuery(function($) {

	var selectVarDump = $('[data-console-log]');

	if ( selectVarDump.length > 0 ) {

		selectVarDump.each(function() {

			// Grab the data
			var varDump = $(this).attr('data-console-log');

			// Parse the data to json
			var varDumpJson = JSON.parse(varDump);

			// Log to developer tools console
			console.log(varDumpJson);

			// Delete div
			$(this).remove();
		});
	}
});