<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Get;
use Semplice\Helper\Typography;

// -----------------------------------------
// customize advanced
// -----------------------------------------

class PasswordProtected extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// return
		return '';
	}

	// -----------------------------------------
	// generate css
	// -----------------------------------------

	public function css($is_frontend) {
		// output
		$css = '';
		// get ppc options
		$ppc = Get::customize('ppc');
		// selector
		$selector = '#content-holder .post-password-form .inner';
		// is array?
		if(is_array($ppc)) {
			// background color
			if(isset($ppc['background_color'])) {
				$css .= '#content-holder .post-password-form { background: ' . $ppc['background_color'] . '; }';
			}
			// title color
			if(isset($ppc['title_color'])) {
				$css .= $selector . ' p span.title { color: ' . $ppc['title_color'] . '; }';
			}
			// sub title color
			if(isset($ppc['sub_color'])) {
				$css .= $selector . ' p span.subtitle { color: ' . $ppc['sub_color'] . '; }';
			}
			// placeholder color
			if(isset($ppc['placeholder_color'])) {
				$css .= $selector . ' input::placeholder { color: ' . $ppc['placeholder_color'] . ' !important; }';
			}
			// input bg color
			if(isset($ppc['input_bg_color'])) {
				$css .= $selector . ' .input-fields input[type="password"] { background-color: ' . $ppc['input_bg_color'] . '; }';
			}
			// submit button
			if(isset($ppc['submit_icon'])) {
				// get image url
				$icon = wp_get_attachment_image_src($ppc['submit_icon'], 'full', false);
				if($icon) {
					$css .= $selector . ' input[type="submit"], ' . $selector . ' .post-password-submit { background-image: url(' . $icon[0] . ') !important; opacity: 1 !important; }';
				}
			}
			// lock color
			if(isset($ppc['lock_color'])) {
				$css .= $selector . ' .password-lock { fill: ' . $ppc['lock_color'] . '; }';
			}
			// submit color
			if(isset($ppc['submit_color'])) {
				$css .= $selector . ' .input-fields input[type="submit"], ' . $selector . ' .input-fields .post-password-submit { }';
			}
			// formatting
			$formatting = array(
				'title'		=> $selector . ' p.title',
				'subtitle'	=> $selector . ' p.subtitle',
				'input'		=> $selector . ' .input-fields input[type="password"]'
			);
			foreach($formatting as $format => $target) {
				// font family
				if(isset($ppc[$format . '_font_family'])) {
					$css .= $target . ' { ' . Typography::get_font_family($ppc[$format . '_font_family']) . ' }';
				}
				// font color
				if(isset($ppc[$format . '_color'])) {
					$css .= $target . ' { color: ' . $ppc[$format . '_color'] . '; }';
				}
				// letter spacing
				if(isset($ppc[$format . '_letter_spacing'])) {
					$css .= $target . ' { letter-spacing: ' . $ppc[$format . '_letter_spacing'] . '; }';
				}
			}
		}
		// output
		return $css;
	}
}

Customize::$setting['ppc'] = new PasswordProtected;