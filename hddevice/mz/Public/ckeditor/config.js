/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

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
	
	config.toolbar = 'MyToolbar';
 
        config.toolbar_MyToolbar =
        [
                { name: 'document', items : [ 'Source','NewPage','Preview' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','-','RemoveFormat','JustifyLeft','JustifyCenter','JustifyRight' ] },
     

                
            { name: 'insert', items :[ 'Image','Flash','Table','HorizontalRule','Smiley' ] },
                { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
				{ name: 'colors', items : [ 'TextColor','BGColor' ] }

        ];


	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.skin='moono';
	config.height=350;
	config.toolbar = 'full';
	
	config.filebrowserBrowseUrl = '/Public/Admin/ckfinder/ckfinder.html';  
	config.filebrowserImageBrowseUrl = '/Public/Admin/ckfinder/ckfinder.html?type=Images';  
	config.filebrowserFlashBrowseUrl = '/Public/Admin/ckfinder/ckfinder.html?type=Flash';  
	config.filebrowserUploadUrl = '/Public/Admin/ckfinder/core/connector/java/connector.php?command=QuickUpload&type=Files';  
	config.filebrowserImageUploadUrl = '/Public/Admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';  
	config.filebrowserFlashUploadUrl = '/Public/Admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';  
	// config.filebrowserWindowWidth = '1000';   
	// config.filebrowserWindowHeight = '700';  
};
