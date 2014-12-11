/**
 * Basic sample plugin inserting current date and time into CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/#!/guide/plugin_sdk_intro
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'flowplayer', {

	// Register the icons. They must match command names.
	icons: 'flowplayer',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that inserts a flowplayer.
		editor.addCommand( 'insertFlowplayer', {

			// Define the function that will be fired when the command is executed.
			exec: function( editor ) {

				// Insert the flowplayer into the document.
				editor.insertHtml('{flowplayer}{/flowplayer}');
			}
		});

		// Create the toolbar button that executes the above command.
		editor.ui.addButton( 'Flowplayer', {
			label: 'Insert Flowplayer',
			command: 'insertFlowplayer',
			toolbar: 'insert'
		});
	}
});