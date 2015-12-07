/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.plugins.add( 'mycombo' ) ;

CKEDITOR.editorConfig = function( config ) {
	config.filebrowserBrowseUrl = 'http://117.3.36.11:8069/ckfinder/ckfinder.html';

config.filebrowserImageBrowseUrl = 'http://117.3.36.11:8069/ckfinder/ckfinder.html?type=Images';

config.filebrowserFlashBrowseUrl = 'http://117.3.36.11:8069/ckfinder/ckfinder.html?type=Flash';

config.filebrowserUploadUrl = 'http://117.3.36.11:8069/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

config.filebrowserImageUploadUrl = 'http://117.3.36.11:8069/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

config.filebrowserFlashUploadUrl = 'http://117.3.36.11:8069/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';


	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.extraPlugins = 'section,dragresize';
	 config.strinsert_strings = [
            {'name': 'Name', 'value': 'div'},
            {'name': 'Group 1'},
            {'name': 'Another name', 'value': 'h1', 'label': 'Good looking'},
        ];
    config.format_section = 'profile';
    config.allowedContent = true;
};
