<?php

// namespace
namespace Semplice\Admin;

// use
use Semplice\Helper\Get;

// -----------------------------------------
// semplice customize
// -----------------------------------------

class Customize {

	// -----------------------------------------
	// public vars
	// -----------------------------------------

	public static $setting;

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// actions
	// -----------------------------------------

	public static function actions($setting) {
		// breakpoint support
		$bp_support = array('navigations-edit', 'typography', 'projectnav');
		// breakpoint selection
		$bp_select = (in_array($setting, $bp_support)) ? Get::bp_select('customize') : '';
		// custom actions
		$custom_actions = '';
		switch($setting) {
			case 'webfonts':
				$custom_actions .= '<button class="click-handler" data-button-color="dark-gray" data-button-icon="plus" data-button-icon-color="white" data-handler="run" data-action-type="dialog" data-setting-type="webfonts" data-action="editWebfont" data-mode="new"><span></span>Add New</button>';
			break;
			case 'footer':
				$custom_actions .= '<button class="click-handler" data-button-color="dark-gray" data-button-icon="plus" data-button-icon-color="white" data-handler="run" data-action-type="dialog" data-setting-type="post" data-action="add" data-post-type="footer"><span></span>Add New</button>';
			break;
			case 'navigations':
				$custom_actions .= '<button class="click-handler add-navigation" data-button-color="dark-gray" data-button-icon-color="white" data-button-icon="plus" data-handler="run" data-action-type="customize" data-setting-type="navigations" data-action="addNavDialog" data-state="view"><span></span>Add New</button>';
			break;
		}
		// return
		return '
			<div class="actions ' . $setting . '-actions">
				' . $bp_select . '
				' . $custom_actions . '
				<button class="click-handler save-customize ajax-save" data-handler="saveCustomize" data-setting="' . $setting . '"><span>Save</span></button>
			</div>
		';
	}
}

new Customize;