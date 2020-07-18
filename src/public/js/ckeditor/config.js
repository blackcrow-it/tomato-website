/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = [
		[ 'Source' ],
		[ 'Templates' ],
		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
		[ 'Undo', 'Redo' ],
		[ 'Find', 'Replace' ],
		[ 'SelectAll' ],
		[ 'Scayt' ],
		[ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ],
		'/',
		[ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ],
		[ 'CopyFormatting', 'RemoveFormat' ],
		[ 'NumberedList', 'BulletedList' ],
		[ 'Outdent', 'Indent' ],
		[ 'Blockquote', 'CreateDiv' ],
		[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
		[ 'BidiLtr', 'BidiRtl' ],
		[ 'Link', 'Unlink', 'Anchor' ],
		[ 'Image', 'Youtube', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'Iframe', 'EmojiPanel' ],
		'/',
		[ 'Styles', 'Format', 'Font', 'FontSize' ],
		[ 'TextColor', 'BGColor' ],
		[ 'ShowBlocks', 'Maximize' ]
	]
};
