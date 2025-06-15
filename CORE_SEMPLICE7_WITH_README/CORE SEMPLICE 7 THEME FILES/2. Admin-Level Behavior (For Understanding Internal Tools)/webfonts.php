<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Get;
use Semplice\Helper\Typography;

// -----------------------------------------
// customize webfonts
// -----------------------------------------

class Webfonts extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// define output
		$output = '';
		// return
		return $output;
	}

	// -----------------------------------------
	// get fonts
	// -----------------------------------------

	public function get() {
		// output
		$output = '';
		// get fonts
		$webfonts = Get::customize('webfonts');
		// webfonts installed?
		if(is_array($webfonts) && !empty($webfonts['ressources'])) {
			// define self hosted css
			$self_hosted_css = '';
			// get ressources
			foreach ($webfonts['ressources'] as $id => $ressource) {
				// service
				if($ressource['ressource-type'] == 'service') {
					// only embed if valid css, js file
					if(strpos($ressource['ressource-src'], '<link') !== false || strpos($ressource['ressource-src'], '<script') !== false) {
						$output .= $ressource['ressource-src'];
					}
				} else if($ressource['ressource-type'] == 'self-hosted-upload' && !empty($ressource['uploadedFonts'])) {
					$self_hosted_css .= $this->uploaded_fonts_css($ressource['uploadedFonts']);
				} else {
					$self_hosted_css .= $ressource['ressource-src'];
				}
			}
			// self hosted tag
			if(!empty($self_hosted_css)) {
				$output .= '<style type="text/css" id="smp-css-webfonts-selfhosted">' . $self_hosted_css . '</style>';
			}
		}
		// if webfonts not installed or default fonts are activated
		if(is_array($webfonts) && isset($webfonts['defaultFonts']) && $webfonts['defaultFonts'] == 'on' || !is_array($webfonts) || empty($webfonts['ressources']) && empty($webfonts['fonts']) || empty($webfonts['fonts'])) {
			// load default fonts
			$output .= '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i|Lora:400,400i,700,700i" rel="stylesheet">';
		}
		// generate fonts css
		$fonts_css = $this->generate_css($webfonts);
		// add to output
		$output .= '<style type="text/css" id="smp-css-webfonts">' . $fonts_css . '</style>';
		// return
		return $output;
	}

	// -----------------------------------------
	// get uploaded fonts css
	// -----------------------------------------

	public function uploaded_fonts_css($fonts) {
		// formats
		$formats = array(
			'otf'   => 'opentype',
			'woff2' => 'woff2',
			'woff'  => 'woff',
			'ttf'   => 'truetype',
			'svg'   => 'svg'
		);
		// css
		$css = '';
		// iterate fonts
		foreach($fonts as $name => $font) {
			// css
			$css .= '@font-face { font-family: "' . $name . '"; src: ';
			// iterate font urls
			foreach($font['urls'] as $ext => $url) {
				if(isset($formats[$ext])) {
					$css .= 'url("' . $url . '") format("' . $formats[$ext] . '"),';
				}
			}
			// close src
			$css = substr_replace($css,';',-1);
			// close css
			$css .= '}';
		}
		// ret
		return $css;
	}

	// -----------------------------------------
	// css
	// -----------------------------------------

	public function generate_css($webfonts) {
		// get webfonts
		$webfonts = (!$webfonts) ? Get::customize('webfonts') : $webfonts;
		// define css
		$css = '';
		// get typography settings
		$typography = Get::customize('typography');
		// typography attributes
		$attributes = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p');
		// webfonts?
		if(is_array($webfonts) && !empty($webfonts['fonts'])) {
			foreach ($webfonts['fonts'] as $id => $font) {
				// font array
				$font_atts = array(
					'family'   => '',
					'weight'   => '',
					'style'	   => '',
					'variable' => ''
				);
				// font family
				if(strpos($font['system-name'],',') !== false) {
					$font_name = explode(',', $font['system-name']);
					$font_atts['family'] = 'font-family: "' . $font_name['0'] . '", "' . $font_name['1'] . '", ' . $font['category'] . ';';
				} else {
					$font_atts['family'] = 'font-family: "' . $font['system-name'] . '", ' . $font['category'] . ';';
				}
				// font type
				if(isset($font['font_type']) && $font['font_type'] == 'variable') {
					// variable styles
					if(isset($font['styles']) && is_array($font['styles']) && !empty($font['styles'])) {
						foreach ($font['styles'] as $style_id => $style) {
							// get selector
							$typo_selector = $this->selector($typography, $style_id, $attributes, false);
							// css
							$style_css = '';
							$exclude = array('name', 'font-size', 'line-height', 'letter-spacing');
							foreach ($style as $axis => $val) {
								if(!in_array($axis, $exclude)) {
									$style_css .= "'" . $axis . "' " . $val . ", ";
								}
							}
							// cut off last 2 chars
							$style_css = substr($style_css, 0, -2);
							// add to variable
							$font_atts['variable'] = 'font-variation-settings: ' . $style_css . '; font-weight: normal; font-style: normal;';
							// get font css
							$css .= $this->font_css($font_atts, $style_id, $typo_selector);
						}
					}
				} else {
					// get selector
					$typo_selector = $this->selector($typography, $id, $attributes, false);
					// font weight
					if($font['font-weight-usage'] == 'css') {
						$font_atts['weight'] = 'font-weight: ' . $font['font-weight'] . ';';
					} else {
						$font_atts['weight'] = 'font-weight: normal;';
					}
					// when a variable style is set as a font family for h1, p etc. in typography, these font variation settings will overwrite our font weight and needs to be resettet
					$font_atts['weight'] .= 'font-variation-settings: normal;';
					// style
					$font_atts['style'] = 'font-style: ' . $font['font-style'] . ';';
					// get font css
					$css .= $this->font_css($font_atts, $id, $typo_selector);
				}
			}
			// if default fonts are installed also get the css for them
			if(isset($webfonts['defaultFonts']) && $webfonts['defaultFonts'] == 'on') {
				$css = $this->default_fonts_css($css, $typography, $attributes);
			}
		} else {
			$css = $this->default_fonts_css($css, $typography, $attributes);
		}
		return $css;
	}

	// -----------------------------------------
	// default fonts css
	// -----------------------------------------

	public function default_fonts_css($css, $typography, $attributes) {
		// get default fonts
		$default_fonts = Typography::get_default_fonts('work', false);
		// iterate default fonts
		foreach ($default_fonts as $id => $values) {
			// get typo selector
			$typo_selector = $this->selector($typography, $id, $attributes, true);
			// add to css
			if(!empty($typo_selector)) {
				// css open
				$css .= $typo_selector . ', .blog-settings [data-font="' . $id . '"] {';
				// font name
				$css .= 'font-family: "' . $values['system-name'] . '", ' . $values['category'] . ';';
				// fontweight
				$css .= 'font-weight: ' . $values['font-weight'] . ';';
				// font style
				$css .= 'font-style: ' . $values['font-style'] . ';';
				// close
				$css .= '}';
			}
		}
		// return css
		return $css;
	}

	// -----------------------------------------
	// fonts css
	// -----------------------------------------
	public function font_css($font_atts, $id, $typo_selector) {
		// selector
		$selector = '.' . $id . ', [data-font="' . $id . '"], [data-font="' . $id . '"] li a' . $typo_selector . ' {';
		// css
		return $selector . $font_atts['family'] . $font_atts['weight'] . $font_atts['style'] . $font_atts['variable'] . '}';
	}

	// -----------------------------------------
	// selector 
	// -----------------------------------------

	public function selector($typography, $id, $attributes, $defaultFonts) {
		// define
		$typo_selector = '';
		if(is_array($typography) && in_array($id, $typography)) {
			// count
			$count = 0;
			foreach ($attributes as $attribute) {
				if(isset($typography[$attribute . '_font_family']) && $id == $typography[$attribute . '_font_family']) {
					if(isset($typography[$attribute . '_customize']) && $typography[$attribute . '_customize'] == 'on' || $attribute == 'p') {
						if($attribute == 'p') {
							$attribute = 'p, #content-holder li';
						}
						if(!$defaultFonts) {
							$typo_selector .= ', #content-holder ' . $attribute;
						} else {
							if($count == 0) {
								$typo_selector .= '#content-holder ' . $attribute;
							} else {
								$typo_selector .= ', #content-holder ' . $attribute;
							}
						}
						// inc count
						$count++;
					}
				}
			}
		}
		return $typo_selector;
	}
}

Customize::$setting['webfonts'] = new Webfonts;