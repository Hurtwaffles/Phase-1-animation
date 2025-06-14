// semplice global elements
var $semplice = jQuery('#semplice');
var $adminWrapper = jQuery('#semplice-wrapper');
var $adminContent = jQuery('#semplice-content');
var $editor = jQuery('#semplice-editor');
var $editorContent = jQuery('#editor-content');
var $sempliceGrid = jQuery('#semplice-grid');
var $holder = jQuery('#content-holder');
var $ep = jQuery('edit-popup');
var $expandOptions = jQuery('ep-expand-options');
var $tooltip = jQuery('#smp-tooltip');
var $layers = jQuery('#semplice-layers');
var $wysiwygToolbar = jQuery('#wysiwyg-toolbar');
var $codeMirror = jQuery('#codemirror');
var $contextMenu = jQuery('#context-menu');

// semplice activate button
jQuery(document).on('click', '.activate-semplice-button', function() {
	// get element
	var $el = jQuery('.is-semplice');
	// get status
	var status = $el.val();
	// set new status
	if(status == 1) {
		$el.attr('value', 0);
		jQuery('.activate-semplice').attr('data-semplice-status', 'inactive');
		jQuery('.activate-semplice-button').text('Activate Semplice');
	} else {
		$el.attr('value', 1);
		jQuery('.activate-semplice').attr('data-semplice-status', 'active');
		jQuery('.activate-semplice-button').text('Deactivate Semplice');
	}
});