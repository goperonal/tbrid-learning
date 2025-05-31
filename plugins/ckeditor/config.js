/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	config.extraPlugins = 'video,html5audio,SimpleLink,filebrowser,image,table,maximize,sourcearea,justify';


	config.filebrowserBrowseUrl 		= 'https://t-bridlearning.com/plugins/kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl 	= base_url + 'plugins/kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl 	= base_url + 'plugins/kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl 		= base_url + 'plugins/kcfinder/browse.php?type=files';
	config.filebrowserImageUploadUrl 	= base_url + 'plugins/kcfinder/browse.php?type=images';
	config.filebrowserFlashUploadUrl 	= base_url + 'plugins/kcfinder/browse.php?type=flash';

	// config.toolbar = [
	        
    //     { name: 'insert', items: [ 'Html5audio', 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] }
       
    // ];

	// The toolbar groups arrangement, optimized for a single toolbar row.
	config.toolbarGroups = [
		// { name: 'insert', items: [ 'Html5audio', 'Image', 'Flash' ] },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'forms' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'others' },
		{ name: 'fullscreen' }
	];

	toolbar: [
		{ name: 'insert', items: ['AudioRecorder'] }
	];

	// The default plugins included in the basic setup define some buttons that
	// are not needed in a basic editor. They are removed here.
	config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Strike,Subscript,Superscript';

	// Dialog windows are also simplified.
	config.removeDialogTabs = 'link:advanced;link:upload;link:Browse;image:advanced;image:Upload';
};
