<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;

// -----------------------------------------
// module atts
// -----------------------------------------

class ModuleAtts {

	// -----------------------------------------
	// init
	// -----------------------------------------

	public static function get($id, $module, $options) {
		// return atts
		return method_exists(__CLASS__, $module) ? self::{$module}($id, $module, $options) : '';
	}

	// -----------------------------------------
	// image
	// -----------------------------------------

	public static function image($id, $module, $options) {
		return 'data-width="' . ((isset($options['width'])) ? $options['width'] : 'custom') . '"';
	}

	// -----------------------------------------
	// lottie
	// -----------------------------------------

	public static function lottie($id, $module, $options) {
		return 'data-width="' . ((isset($options['width'])) ? $options['width'] : 'custom') . '"';
	}

	// -----------------------------------------
	// button
	// -----------------------------------------

	public static function button($id, $module, $options) {
		$atts = 'data-width="' . ((isset($options['width'])) ? $options['width'] : 'auto') . '"';
		if(Navigations::$is_frontend) {
			$atts .= ' data-font="' . ((isset($options['font'])) ? $options['font'] : 'regular') . '"';
		}
		// return
		return $atts;
	}

	// -----------------------------------------
	// menu
	// -----------------------------------------

	public static function menu($id, $module, $options) {
		// vars
		$breakpoints = array('xl', 'lg', 'md', 'sm', 'xs');
		$atts = '';
		if(Navigations::$is_frontend) {
			// menu type and nested spacing
			$type_default = ((isset($options['menu_type'])) ? $options['menu_type'] : 'text');
			$atts = 'data-menu-type="' . $type_default . '"';
			// menu type for breakpoints
			$type_bp = array('xl' => $type_default);
			$nested_spacing = array(
				'xl' => array(
					'top' 		  => ((isset($options['nested_top'])) ? $options['nested_top'] : '0.44445rem'),
					'bottom' 	  => ((isset($options['nested_bottom'])) ? $options['nested_bottom'] : '0.44445rem'),
					'ver_spacing' => ((isset($options['nested_ver_spacing'])) ? $options['nested_ver_spacing'] : '0rem'),
					'hor_spacing' => ((isset($options['nested_hor_spacing'])) ? $options['nested_hor_spacing'] : '0.88889rem')
				)
			);
			foreach($breakpoints as $bp) {
				if(isset($options['menu_type_' . $bp])) {
					$type_bp[$bp] = $options['menu_type_' . $bp];
				} else {
					$type_bp[$bp] = $type_default;
				}
				$nested_spacing[$bp] = (!isset($nested_spacing[$bp])) ? array() : $nested_spacing[$bp];
				foreach($nested_spacing['xl'] as $attr => $val) {
					if($bp != 'xl' && isset($options['nested_' . $attr . '_' . $bp])) {
						$nested_spacing[$bp][$attr] = $options['nested_' . $attr . '_' . $bp];
					}
				}
			}
			// add to atts
			$atts .= ' data-menu-type-bp=\'' . json_encode($type_bp) . '\'';
			// menu direction
			$atts .= ' data-menu-direction="' . ((isset($options['menu_direction'])) ? $options['menu_direction'] : 'horizontal') . '"';
			// text align
			$atts .= ' data-text-align="' . ((isset($options['text-align'])) ? $options['text-align'] : 'left') . '"';
			// dropdown mode
			$atts .= ' data-dropdown-mode="' . ((isset($options['dropdown_mode'])) ? $options['dropdown_mode'] : 'mouseover') . '"';
			// nested mode
			$atts .= ' data-nested-mode="' . ((isset($options['nested_mode'])) ? $options['nested_mode'] : 'toggle') . '"';
			// dropdown align
			$atts .= ' data-dropdown-align="' . ((isset($options['dropdown_align'])) ? $options['dropdown_align'] : 'center') . '"';
			// menu width
			$atts .= ' data-menu-width="' . ((isset($options['menu_width'])) ? $options['menu_width'] : 'fit-content') . '"';
			// menu distributed
			$atts .= ' data-distributed="' . ((isset($options['distributed'])) ? $options['distributed'] : 'no') . '"';
			// menu font
			$atts .= ' data-font="' . ((isset($options['font'])) ? $options['font'] : 'regular') . '"';
			// hover effect
			$atts .= ' data-hover-effect="' . ((isset($options['hover-effect'])) ? $options['hover-effect'] : 'none') . '"';
			// nested spacing
			if(!isset($options['nested_mode']) || $options['nested_mode'] == 'toggle') {
				$atts .= ' data-nested-spacing=\'' . json_encode($nested_spacing) . '\'';
			}
		}
		// return
		return $atts;
	}

	// -----------------------------------------
	// link
	// -----------------------------------------

	public static function link($id, $module, $options) {
		if(Navigations::$is_frontend) {
			// menu font
			return ' data-font="' . ((isset($options['font'])) ? $options['font'] : 'regular') . '"';
		}
	}

	// -----------------------------------------
	// text
	// -----------------------------------------

	public static function text($id, $module, $options) {
		if(Navigations::$is_frontend) {
			// menu font
			return ' data-font="' . ((isset($options['font'])) ? $options['font'] : 'regular') . '"';
		}
	}
}

// instance
new ModuleAtts;
?>