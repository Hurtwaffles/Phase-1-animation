<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Basic;
use Semplice\Helper\Get;
use Semplice\Helper\Color;
use Semplice\Helper\Typography;

// -----------------------------------------
// module atts
// -----------------------------------------

class ModuleCss {

	// -----------------------------------------
	// get
	// -----------------------------------------

	public static function get($navbar, $nav_id, $id, $module, $options) {
		$output_css = '';
		$selector = '[data-customize-bp="##breakpoint##"] .' . $nav_id . ' #' . $id;
		// default hover for menu and link
		if($module == 'menu' || $module == 'link') {
			$options['hover-color'] = (isset($options['hover-color'])) ? $options['hover-color'] : '#000000';
			$options['hover-text-decoration-color'] = (isset($options['hover-text-decoration-color'])) ? $options['hover-text-decoration-color'] : '#000000';
		}
		// get module atts
		$modules = self::modules();
		// module is set?
		if(isset($modules[$module])) {
			// module atts
			$module_atts = $modules[$module];
			// get breakpoints
			$breakpoints = Get::breakpoints(true);
			// iterate breakpoints
			foreach ($breakpoints as $bp => $width) {
				// reset css
				$css = '';
				// iterate modules
				foreach($module_atts as $name => $atts) {
					if(isset($atts['attr']) && is_array($atts['attr'])) {
						$multi_css = '';
						// text decoration thickness shows .5px when set to 0
						if(in_array('text-decoration-thickness', $atts['attr']) && isset($options['text-decoration-thickness']) && $options['text-decoration-thickness'] != '0rem' && !is_array($atts['target']) && false === strpos($atts['target'], ':hover') && $bp == 'xl') {
							$multi_css .= 'text-decoration: underline;';
						}
						foreach($atts['attr'] as $css_attribute) {
							$val = Navigations::get_val($options, $css_attribute, $bp);
							//echo $css_attribute;
							if($val) {
								$multi_css .=  str_replace(array('hover-', 'active-'), '', $css_attribute) . ': ' . $val . ';';
							}
						}
						if(!empty($multi_css)) {
							$css .= Navigations::selector($selector, $atts['target']) . ' {' . $multi_css . '}';
						}
					} else if(false !== strpos($name, 'custom-')) {
						$css .= self::{$atts['handler']}($navbar, $nav_id, $id, $module, $options, $bp);
					} else {
						$val = Navigations::get_val($options, $name, $bp);
						if($val) {

							$css .= Navigations::selector($selector, $atts['target']) . ' { ' . str_replace(array('hover-', 'active-'), '', $atts['attr']) . ': ' . $val . '; }';
						}
					}
				}
				// add to css output
				if(!empty($css) && $bp != 'xl') {
					if(!Navigations::$is_frontend) {
						$output_css .= str_replace('##breakpoint##', $bp, $css);
					} else {
						$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-customize-bp="##breakpoint##"] ', '', $css) . '}';
					}
				} else {
					$output_css .= str_replace('[data-customize-bp="##breakpoint##"]', '', $css);
				}
			}
		}
		// return css
		return $output_css;
	}

	// -----------------------------------------
	// modules
	// -----------------------------------------

	public static function modules() {
		return array(
			'menu' => array(
				'menu' => array(
					'target' => 'ul',
					'attr' => array(
						'text-align',
						'gap'
					)
				),
				'regular' => array(
					'target' => 'li a span',
					'attr' => array(
						'color',
						'font-size',
						'text-transform',
						'letter-spacing',
						'text-decoration-thickness',
						'text-decoration-color',
						'text-decoration-style',
						'text-underline-offset'
					)
				),
				'custom-item_background' => array(
					'handler' => 'item_background'
				),
				'mouseover' => array(
					'target' => 'li a:hover span',
					'attr' => array(
						'hover-color',
						'hover-text-decoration-color',
					)
				),
				'mouseoverItemBackground' => array(
					'target' => 'li a:hover',
					'attr' => array(
						'hover-background'
					)
				),
				'active' => array(
					'target' => array('li.current-menu-item a span', 'li.current_page_item a span', 'li.wrap-focus a span'),
					'attr' => array(
						'active-color',
						'active-text-decoration-color',
					)
				),
				'activeItemBackground' => array(
					'target' => array('li.current-menu-item a', 'li.current_page_item a', 'li.wrap-focus a'),
					'attr' => array(
						'active-background'
					)
				),
				'custom-seperator' => array(
					'handler' => 'seperator'
				),
				'custom-dropdown' => array(
					'handler' => 'dropdown'
				),
				'custom-active_font' => array(
					'handler' => 'active_font'
				),
				'custom-hamburger' => array(
					'handler' => 'hamburger'
				),
				'custom-underline_hover' => array(
					'handler' => 'underline_hover'
				),
			),
			'link' => array(
				'regular' => array(
					'target' => 'a',
					'attr' => array(
						'color',
						'font-size',
						'line-height',
						'text-transform',
						'letter-spacing',
						'padding-bottom',
						'text-decoration-thickness',
						'text-decoration-color',
						'text-decoration-style',
						'text-underline-offset'
					)
				),
				'mouseover' => array(
					'target' => 'a:hover',
					'attr' => array(
						'hover-color',
						'hover-text-decoration-color',
					)
				),
			),
			'text' => array(
				'regular' => array(
					'target' => '.is-content',
					'attr' => array(
						'color',
						'font-size',
						'line-height',
						'text-transform',
						'letter-spacing'
					)
				)
			),
			'image' => array(
				'custom_width' =>array(
					'target' => 'img',
					'attr' => 'width'
				)
			),
			'spacer' => array(
				'regular' => array(
					'target' => '.spacer',
					'attr' => array(
						'background-color',
						'height',
					)
				)
			),
			'button' => array(
				'regular' => array(
					'target' => 'button',
					'attr' => array(
						'color',
						'font-size',
						'line-height',
						'text-transform',
						'letter-spacing',
						'background',
						'border-color',
						'border-width',
						'border-radius',
						'padding-top',
						'padding-right',
						'padding-bottom',
						'padding-left',
						'justify-content'
					)
				),
				'mouseover' => array(
					'mode' => 'multi',
					'target' => 'button:hover',
					'attr' => array(
						'hover-color',
						'hover-letter-spacing',
						'hover-background',
						'hover-border-color',
					)
				),
				'icon_height' => array(
					'target' => 'button span.icon img',
					'attr' => 'height'
				),
				'custom-button_hover' => array(
					'handler' => 'button_hover'
				),
				'custom-button_icon' => array(
					'handler' => 'button_icon'
				)
			),
			'lottie' => array(
				'custom_width' =>array(
					'target' => '.smp-nav-lottie',
					'attr' => 'width'
				)
			)
		);
	}

	// -----------------------------------------
	// button hover
	// -----------------------------------------

	public static function button_hover($navbar, $nav_id, $id, $module, $options, $bp) {
		// only add for xl
		$css = '';
		if($bp == 'xl') {
			$colors = array(
				'normal' => isset($options['background']) ? $options['background'] : '#ffd300',
				'hover'	 => isset($options['hover-background']) ? $options['hover-background'] : '#ffe152'
			);
			// background color
			$css .= '.' . $nav_id . ' #' . $id . ' button { transition: all .2s ease-out, ' . Color::gradient_vars('.7', '--ease-out-expo') . '; }';
			$css .= Color::gradient_mouseover('.smp-navbar #' . $id . ' button', $colors['normal'], $colors['hover'], false);
		}
		return $css;
	}

	// -----------------------------------------
	// button icon
	// -----------------------------------------

	public static function button_icon($navbar, $nav_id, $id, $module, $options, $bp) {
		// vars
		$direction = (isset($options['icon_direction'])) ? $options['icon_direction'] : 'right';
		$spacing = Navigations::get_val($options, 'icon_spacing', $bp);
		$spacing_dir = ($direction == 'left') ? 'right' : 'left';
		// is spacing?
		if($spacing) {
			return '.' . $nav_id . ' #' . $id . ' button span.icon { margin-' . $spacing_dir . ': ' . $spacing . '; }';
		}
	}

	// -----------------------------------------
	// hamburger
	// -----------------------------------------

	public static function hamburger($navbar, $nav_id, $id, $module, $options, $bp) {
		// vars
		$css = '';
		$mode = (isset($options['hamburger_mode'])) ? $options['hamburger_mode'] : 'default';
		// switch
		switch($mode) {
			case 'default':
				// defaults
				$values = array(
					'color' => '#000000',
					'thickness' => 2,
					'padding' => 6,
					'width' => 24
				);
				$bp = ($bp != 'xl') ? '_' . $bp : '';
				// get values
				$atts = array('width', 'thickness', 'padding', 'color');
				foreach($atts as $name) {
					if(isset($options['hamburger_' . $name . $bp])) {
						$values[$name] = $options['hamburger_' . $name . $bp];
					} else if(isset($options['hamburger_' . $name])) {
						$values[$name] = $options['hamburger_' . $name];
					}
					if(false !== strpos($name, 'thickness') || false !== strpos($name, 'padding')) {
						$values[$name] = intval($values[$name]);
					}
				}
				// sel
				$sel = '.' . $nav_id . ' #' . $id . ' .hamburger ';
				// calc height
				$height = $values['thickness'] + ($values['padding'] * 2);
				// hamburger width
				$css .= $sel . '.menu-icon { width: ' . $values['width'] . ' !important; height: ' . $height . 'px !important;  }';
				// hamburger padding
				$css .= $sel . '.open-menu span::before { transform: translateY(-' . $values['padding'] . 'px) !important; }';
				$css .= $sel . '.open-menu span::after { transform: translateY(' . $values['padding'] . 'px) !important; }';
				// hover
				$css .= $sel . '.open-menu:hover span::before { transform: translateY(-' . ($values['padding'] + 2) . 'px) !important; }';
				$css .= $sel . '.open-menu:hover span::after { transform: translateY(' . ($values['padding'] + 2) . 'px) !important; }';
				// thickness and color
				$css .= $sel . '.menu-icon span { height: ' . $values['thickness'] . 'px !important; background: ' . $values['color'] . ' !important; }';
				// margin top
				$css .= $sel . '.menu-icon span { margin-top: ' . ($height / 2) . 'px !important; }';
			break;
			case 'custom':
				// mouseover
				$mouseover = (isset($options['hamburger_custom_mouseover'])) ? $options['hamburger_custom_mouseover'] : 'scale';
				// width
				$width = Navigations::get_val($options, 'hamburger_custom_width', $bp);
				if($width) {
					$css .= '.' . $nav_id . ' #' . $id . ' .hamburger img { width: ' . $width . '; }';
				}
			break;
			case 'text':
				// prefix
				$prefix = 'hamburger_text_';
				// values
				$values = array(
					'color' => 'color',
					'mouseover_color' => 'color',
					'fontsize' => 'font-size',
					'text_transform' => 'text-transform',
					'letter_spacing' => 'letter-spacing',
					'border_color' => 'border-bottom-color',
					'mouseover_border_color' => 'border-bottom-color',
					'border' => 'border-bottom-width',
					'border_padding' => 'padding-bottom'
				);
				// iterate
				foreach($values as $name => $css_attr) {
					$val = Navigations::get_val($options, $prefix . $name, $bp);
					if($val) {
						if(false !== strpos($name, 'mouseover')) {
							$css .= '.' . $nav_id . ' #' . $id . ' .hamburger-text:hover { ' . $css_attr . ': ' . $val . '; }';
						} else {
							$css .= '.' . $nav_id . ' #' . $id . ' .hamburger-text { ' . $css_attr . ': ' . $val . '; }';
						}
					}
				}
			break;
		}
		// return
		return $css;
	}

	// -----------------------------------------
	// item background
	// -----------------------------------------

	public static function item_background($navbar, $nav_id, $id, $module, $options, $bp) {
		// vars
		$defaults = array(
			'bg_color' => '#00000000',
			'bg_spacing_ver' => 0,
			'bg_spacing_hor' => 0,
			'bg_radius' => 0
		);
		// iterate defaults
		foreach($defaults as $name => $val) {
			if(isset($options[$name . '_' . $bp])) {
				$defaults[$name] = $options[$name . '_' . $bp];
			} else if(isset($options[$name])) {
				$defaults[$name] = $options[$name];
			}
		}
		// bg color
		$bg_color = ($bp == 'xl') ? 'background: ' . $defaults['bg_color'] . ';' : '';
		// css
		return '
			.' . $nav_id . ' #' . $id . ' nav > ul > li > a {
				' . $bg_color . '
				border-radius: ' . ($defaults['bg_radius']) . 'px;
			}
			.' . $nav_id . ' #' . $id . ' nav > ul > li > a > span {
				padding: ' . $defaults['bg_spacing_ver'] . 'px ' . $defaults['bg_spacing_hor'] . 'px;
			}
		';
	}

	// -----------------------------------------
	// active font
	// -----------------------------------------

	public static function active_font($navbar, $nav_id, $id, $module, $options, $bp) {
		// is something other than default font?
		if(isset($options['active-font-family']) && $options['active-font-family'] != 'default-font') {
			$font = Navigations::get_val($options, 'active-font-family', 'xl');
			// has font family set
			if($font) {
				$sel = '.' . $nav_id . ' #' . $id . ' nav ul';
				// get font family
				$font = Typography::get_font_family($font);
				// add to css
				return '
					' . $sel . ' li.current-menu-item a span,
					' . $sel . ' li.current_page_item a span,
					' . $sel . ' li.wrap-focus a span {
						' . $font . '
					}
				';
			}
		}
	}

	// -----------------------------------------
	// seperator
	// -----------------------------------------

	public static function seperator($navbar, $nav_id, $id, $module, $options, $bp) {
		// vars
		$css = '';
		$prefix = 'seperator_';
		$sel = '#' . $id . ' nav ul li';
		$menu_dir = isset($options['menu_direction']) ? $options['menu_direction'] : 'horizontal';
		// defaults
		$defaults = [
			'visibility' => 'hidden',
			'mode'		 => 'hidden',
			'color'		 => '#B6B6B6',
			'height'	 => '0.055556rem'
		];
		// iterate defaults
		foreach($defaults as $name => $val) {
			if(isset($options[$prefix . $name . '_' . $bp])) {
				$defaults[$name] = $options[$prefix . $name . '_' . $bp];
			} else if(isset($options[$prefix . $name])) {
				$defaults[$name] = $options[$prefix . $name];
			}
		}
		// gap and height
		$gap = isset($options['gap']) ? Basic::rem_to_px($options['gap']) : 24;
		$defaults['height'] = Basic::rem_to_px($defaults['height']);
		// visibility
		if($defaults['visibility'] == 'visible' && $menu_dir == 'vertical') {
			$css .= "
				{$sel}::after,
				{$sel}::before {
					content: \"\";
					position: absolute;
					left: 0;
					right: 0;
					height: {$defaults['height']}px;
					background: {$defaults['color']};
					display: none;
				}
				{$sel}::after {
					bottom: -" . (($gap + $defaults['height']) / 2) . "px;
					display: block;
				}
				{$sel}::before { top: -" . (($gap + $defaults['height']) / 2) . "px; }
				{$sel}:last-child::after { display: none }
			";
			// mode
			if ($defaults['mode'] == 'top') {
				$css .= "{$sel}:first-child::before { display: block; }";
			} elseif ($defaults['mode'] == 'bottom') {
				$css .= "{$sel}:last-child::after { display: block; }";
			} elseif ($defaults['mode'] == 'both') {
				$css .= "{$sel}:first-child::before { display: block; }";
				$css .= "{$sel}:last-child::after { display: block; }";
			}
		}
		// return
		return $css;
	}

	// -----------------------------------------
	// dropdown
	// -----------------------------------------

	public static function dropdown($navbar, $nav_id, $id, $module, $options, $bp) {
		// Initialize CSS string
		$css = '';
		// Set up default values
		$defaults = [
			'dropdown_bg_color'			=> '#ffffff',
			'dropdown_bg_blur'			=> 0,
			'dropdown_radius'			=> 8,
			'dropdown_spacing'			=> '0.88889rem',
			'dropdown_top'				=> '0.44445rem',
			'dropdown_gap'				=> '0.27778rem',
			'dropdown_fontsize'			=> '1rem',
			'dropdown_color'			=> '#777777',
			'dropdown_hover_color'		=> '#000000',
			'dropdown_font_family'		=> 'regular',
			'dropdown_letter_spacing'	=> '0rem',
			'dropdown_text_transform'	=> 'none',
			'dropdown_text_align'		=> 'left',
			'dropdown_border_color'		=> '#000000',
			'dropdown_border_width'		=> '0rem',
			'dropdown_shadow_color'		=> '#00000033',
			'dropdown_shadow_size'		=> 10,
			'dropdown_shadow_opacity'	=> '.2',
			'dropdown_arrow'			=> false,
			'dropdown_arrow_color'		=> '#777777',
			'dropdown_arrow_width'		=> '0.55556rem',
			'dropdown_arrow_spacing'	=> '0.27778rem',
			'nested_bg_color'			=> '#00000000',
			'nested_radius'				=> 0,
			'nested_gap'				=> '0.33334rem',
			'nested_top'				=> '0.44445rem',
			'nested_bottom'				=> '0.44445rem',
			'nested_hor_spacing'		=> '0.88889rem',
			'nested_ver_spacing'		=> '0rem',
			'nested_fontsize'			=> '1.33334rem',
			'nested_color'				=> '#999999',
			'nested_hover_color'		=> '#000000',
			'nested_font_family'		=> 'regular',
			'nested_letter_spacing'		=> '0rem',
			'nested_text_transform'		=> 'none',
			'nested_text_align'			=> 'center',
			'nested_arrow'				=> false,
			'nested_arrow_color'		=> '#444444',
			'nested_arrow_width'		=> '0.72223rem',
			'nested_arrow_spacing'		=> '0.44445rem',
			'nested_arrow_align'		=> 'inline'
		];
		// menu dir
		$menu_dir = isset($options['menu_direction']) ? $options['menu_direction'] : 'horizontal';
		// iterate defaults
		foreach($defaults as $name => $val) {
			if(isset($options[$name . '_' . $bp])) {
				$defaults[$name] = $options[$name . '_' . $bp];
			} else if(isset($options[$name])) {
				$defaults[$name] = $options[$name];
			}
		}
		// bg blur
		$bg_blur = ($defaults['dropdown_bg_blur'] > 0) ? 'backdrop-filter: blur(' . $defaults['dropdown_bg_blur'] . 'px);' : '';
		// margin dir
		$margin_dir = ($navbar == 'navbar-bottom') ? 'bottom' : 'top';
		// shadow color
		$shadow_color = Color::hex_to_rgb($defaults['dropdown_shadow_color']);
		// css
		if($menu_dir == 'horizontal') {
			$css .= "
				#{$id} nav ul li ul.sub-menu {
					padding: {$defaults['dropdown_spacing']};
					background: {$defaults['dropdown_bg_color']};
					{$bg_blur}
					border-radius: {$defaults['dropdown_radius']}px;
					margin-{$margin_dir}: {$defaults['dropdown_top']};
					gap: {$defaults['dropdown_gap']};
					text-align: {$defaults['dropdown_text_align']};
					border-color: {$defaults['dropdown_border_color']};
					border-width: {$defaults['dropdown_border_width']};
					box-shadow: 0px 0px {$defaults['dropdown_shadow_size']}px {$defaults['dropdown_shadow_color']};
				}
				#{$id} nav ul li ul.sub-menu li a span {
					color: {$defaults['dropdown_color']};
					font-size: {$defaults['dropdown_fontsize']};
					letter-spacing: {$defaults['dropdown_letter_spacing']};
					text-transform: {$defaults['dropdown_text_transform']};
					" . Typography::get_font_family($defaults['dropdown_font_family']) . "
				}
				#{$id} nav ul li ul.sub-menu li a:hover span {
					color: {$defaults['dropdown_hover_color']};
				}
				";
		} else {
			$css .= "
				#{$id} nav ul li ul.sub-menu {
					background: {$defaults['nested_bg_color']};
					border-radius: {$defaults['nested_radius']}px;
					gap: {$defaults['nested_gap']};
					text-align: {$defaults['nested_text_align']};
				}
				#{$id} nav ul li ul.sub-menu li a span {
					color: {$defaults['nested_color']};
					font-size: {$defaults['nested_fontsize']};
					letter-spacing: {$defaults['nested_letter_spacing']};
					text-transform: {$defaults['nested_text_transform']};
					" . Typography::get_font_family($defaults['nested_font_family']) . "
				}
				#{$id} nav ul li ul.sub-menu li a:hover span {
					color: {$defaults['nested_hover_color']};
				}
			";
			// add padding and margin to nested
			if(isset($options['nested_mode']) && $options['nested_mode'] != 'toggle') {
				$css .= "
					#{$id} nav ul li ul.sub-menu {
						padding: {$defaults['nested_ver_spacing']} {$defaults['nested_hor_spacing']};
						margin: {$defaults['nested_top']} 0px {$defaults['nested_bottom']} 0px;
					}
				";
			} else {
				$css .= "
					#{$id} nav ul li ul.sub-menu {
						padding-left: {$defaults['nested_hor_spacing']};
						padding-right: {$defaults['nested_hor_spacing']};
					}
				";
			}
		}
		// sub menu arrow
		$arrow_type = ($menu_dir == 'vertical') ? 'nested' : 'dropdown';
		$css .= "
			#{$id} nav ul li a submenu-arrow {
				width: {$defaults[$arrow_type . '_arrow_width']};
				margin-left: {$defaults[$arrow_type . '_arrow_spacing']};
			}
			#{$id} nav ul li a submenu-arrow svg path {
				stroke: {$defaults[$arrow_type . '_arrow_color']};
			}
		";
		// vertical fixed
		if($menu_dir == 'vertical') {
			if($defaults['nested_arrow_align'] != 'inline') {
				$css .= "
					#{$id} nav ul li a submenu-arrow {
						position: absolute;
						{$defaults['nested_arrow_align']}: 0;
						margin: 0px !important;
					}
				";
			}
		}
		// return
		return $css;
	}

	// -----------------------------------------
	// underline hover
	// -----------------------------------------

	public static function underline_hover($navbar, $nav_id, $id, $module, $options, $bp) {
		$css = '';
		// is underline effect set?
		if(isset($options['hover-effect']) && $options['hover-effect'] == 'underline') {
			// vars
			$prefix = 'hover-underline-';
			$sel = '#' . $id . ' nav > ul > li >';
			// defaults
			$defaults = [
				'color'  => '#000000',
				'size'   => 2,
				'offset' => 0

			];
			// compensate for item background paddings
			$padding_ver = intval(isset($options['bg_spacing_ver']) ? $options['bg_spacing_ver'] : 0);
			$padding_hor = intval(isset($options['bg_spacing_hor']) ? $options['bg_spacing_hor'] : 0);
			// iterate defaults
			foreach($defaults as $name => $val) {
				if(isset($options[$prefix . $name . '_' . $bp])) {
					$defaults[$name] = $options[$prefix . $name . '_' . $bp];
				} else if(isset($options[$prefix . $name])) {
					$defaults[$name] = $options[$prefix . $name];
				}
			}
			// bottom
			$bottom = $padding_ver - intval($defaults['offset']);
			// add to css
			$css .= "
				{$sel} a span::after {
					height: {$defaults['size']}px;
					background: {$defaults['color']};
					bottom: {$bottom}px;
					left: {$padding_hor}px;
					right: {$padding_hor}px;
				}
			";
		}
		// return
		return $css;
	}
}

// instance
new ModuleCss;
?>