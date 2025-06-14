<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Admin\Customize\Navigations\ModuleAtts;
use Semplice\Admin\Customize\Navigations\ModuleCss;
use Semplice\Helper\Basic;
use Semplice\Helper\Image;
use Semplice\Helper\Color;
use Semplice\Helper\Get;
use Semplice\Helper\Menu;
use Semplice\Helper\Typography;
use Semplice\Editor\EditorStyles;

// -----------------------------------------
// customize navigations
// -----------------------------------------

class Navigations extends Customize {

	public static $is_frontend;
	public static $modules;

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {
		self::$is_frontend = false;
	}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public static function init() {
		return Menu::list();
	}

	// -----------------------------------------
	// get
	// -----------------------------------------

	public static function get($nav_id, $is_frontend, $no_reveal) {
		// set frontend
		self::$is_frontend = $is_frontend;
		// output
		$output = array(
			'html' => '',
			'css'  => ''
		);
		$widths = array(
			'navbar-top'	 => array(),
			'navbar-bottom'  => array(),
			'navbar-overlay' => array(),
			'modules' 		 => array()
		);
		// get ram
		$ram = self::get_ram($nav_id, $is_frontend);
		// has ram?
		if($ram) {
			// content position
			$content_pos = (isset($ram['settings']['content_position']) ? $ram['settings']['content_position'] : 'before');
			// nav id
			$nav_id = $ram['id'];
			// iterate order
			foreach($ram['order'] as $navbar_id => $navbar) {
				// vars
				$navbar_options = $ram[$navbar_id]['options'];
				$navbar_styles  = $ram[$navbar_id]['styles'];
				$navbar_inner = '';
				// before do anything, is navbar visible?
				if(!$is_frontend || !isset($navbar_options['visibility']) || isset($navbar_options['visibility']) && $navbar_options['visibility'] == 'visible') {
					// navbar styles
					$output['css'] .= EditorStyles::get('nav-section', $navbar_styles, $navbar_id, false);
					// getting navbar overlay padding and close button
					if($navbar_id == 'navbar-overlay') {
						// padding
						$output['css'] .= self::overlay_padding($navbar_styles);
						// close button
						$output['css'] .= self::overlay_close($navbar_options);
					}
					// iterate rows
					foreach($navbar as $row_id => $row) {
						// add row
						$navbar_inner .= '<smp-nav-row id="' . $row_id . '" class="' . ((empty($row)) ? ' navbar-dropzone' : '') . '">';
						// add to width array
						$widths[$navbar_id][$row_id] = array();
						// iterate columns
						if(!empty($row)) {
							foreach($row as $column_id => $column) {
								// add column
								$navbar_inner .= '<smp-nav-column id="' . $column_id . '"' . self::visibility($ram[$column_id]['options'], $is_frontend) . '>';
								// add width to row
								$widths[$navbar_id][$row_id][$column_id] = self::width($ram[$column_id], $is_frontend);
								// add modules to width
								$widths['modules'][$column_id] = array();
								// grid type
								$grid_type = $ram[$column_id]['layout']['display'];
								// layout
								$output['css'] .= self::layout($nav_id, $column_id, $ram[$column_id]['layout'], '[data-breakpoint="##breakpoint##"] .' . $nav_id . ' #' . $column_id, $grid_type);
								// column styles
								$output['css'] .= EditorStyles::get('nav-column', $ram[$column_id]['styles'], $column_id, false);
								// iterate content
								if(is_array($column) && !empty($column)) {
									foreach($column as $content_id) {
										$content = $ram[$content_id];
										// layout
										$output['css'] .= self::layout($nav_id, $content_id, $content['layout'], '[data-breakpoint="##breakpoint##"] .' . $nav_id . ' #' . $content_id, $grid_type);
										// width
										$widths['modules'][$column_id][$content_id] = self::width($content, $is_frontend);
										// add content
										$navbar_inner .= '<smp-nav-content id="' . $content_id . '" data-module="' . $content['module'] . '"' . ModuleAtts::get($content_id, $content['module'], $content['options']) . self::visibility($content['options'], $is_frontend) . '>' . self::$modules[$content['module']]->output($content['options']) . '</smp-nav-content>';
										// add css
										$output['css'] .= ModuleCss::get($navbar_id, $nav_id, $content_id, $content['module'], $content['options']);
										// content styles
										$output['css'] .= EditorStyles::get('nav-content', $ram[$content_id]['styles'], $content_id, false);
									}
								}
								// add width for modules if type is grid
								if($grid_type == 'grid') {
									$output['css'] .= self::width_css($nav_id, $navbar_id, $column_id, $widths['modules'][$column_id], $ram[$column_id]['options']);
								}
								// close column
								$navbar_inner .= '</smp-nav-column>';
							}
						}
						// close row
						$navbar_inner .= '</smp-nav-row>';
						// add width to css
						$output['css'] .= self::width_css($nav_id, $navbar_id, $row_id, $widths[$navbar_id][$row_id], false);
					}
					// add close button to overlay
					$close_button = ($navbar_id == 'navbar-overlay') ? '<div class="close-overlay">' . Get::svg('frontend', 'navigations/close') . '</div>' : '';
					// wrap with smp container
					$navbar_inner = $close_button . '<smp-container>' . $navbar_inner . '</smp-container>';
					// add navbar
					$output['html'] .= '<smp-nav-section id="' . $navbar_id . '"' . self::navbar_atts($nav_id, $navbar_id, $navbar_options, $navbar_styles, $no_reveal) . '>' . $navbar_inner . '</smp-nav-section>';
				}
			}
			// wrap output for frontend
			if($is_frontend) {
				$output['html'] = '<header class="semplice-header ' . $nav_id . '" data-content-position="' . $content_pos . '">' . $output['html'] . '<div class="overlay-dim"></div></header>';
			}
			// replace #content-holder
			$output['css'] = str_replace('#content-holder', '.' . $nav_id, $output['css']);
			// return
			return $output;
		} else {
			return self::empty();
		}
	}

	// -----------------------------------------
	// get ram
	// -----------------------------------------

	public static function get_ram($nav_id, $is_frontend) {
		// vars
		global $post;
		$nav = 'fallback';
		$navs = Get::customize('navigations');
		$advanced = Get::customize('advanced');
		$settings = (is_object($post) && !is_404()) ? json_decode(get_post_meta($post->ID, '_semplice_post_settings', true), true) : '';
		$hide_menu = false;
		// check if there are already navigations
		if(is_array($navs) && $is_frontend) {
			// default
			$default_id = isset($navs['default']) ? $navs['default'] : false;
			$default_nav = isset($navs[$default_id]) ? $navs[$default_id] : self::fallback('logo_left_menu_right');
			// has nav id?
			if($nav_id) {
				if($nav_id == 'default' || !isset($navs[$nav_id])) {
					$nav = $default_nav;
				} else if(isset($navs[$nav_id])) {
					$nav = $navs[$nav_id];
				}
			} else {
				if (is_array($settings) && isset($settings['meta']['navbar']) && isset($navs[$settings['meta']['navbar']]) && $settings['meta']['navbar'] != 'default') {
					$nav = $navs[$settings['meta']['navbar']];
				} else {
					$nav = $default_nav;
				}
			}
		} else if(empty($navs) && $is_frontend) {
			$nav = self::fallback('logo_left_menu_right');
		} else {
			$nav = $nav_id; // nav id is the ram from the nav editor in this case
		}
		// hide menu?
		if(isset($settings) && is_array($settings)) {
			if(isset($settings['meta']['navbar_visibility']) && $settings['meta']['navbar_visibility'] == 'false') {
				$hide_menu = true;
			}
		}
		// return ram
		if(!$hide_menu) {
			// is <s7 preset?
			if($nav && !isset($nav['version']) && isset($nav['preset'])) {
				return self::fallback($nav['preset']);
			} else {
				return $nav;
			}
		} else {
			return false;
		}
	}

	// -----------------------------------------
	// fallback
	// -----------------------------------------

	public static function fallback($preset) {
		// presets
		$presets_ids = Get::nav_preset_ids();
		return json_decode(file_get_contents(SEMPLICE_DIR . '/assets/json/nav-presets/' . $presets_ids[$preset] . '.json'), true);
	}

	// -----------------------------------------
	// return empty nav
	// -----------------------------------------

	public static function empty() {
		return array(
			'html' 			=> '',
			'css'  			=> '',
			'mobile_css'	=> array(
				'lg' => '',
				'md' => '',
				'sm' => '',
				'xs' => '',
			),
		);
	}

	// -----------------------------------------
	// overlay padding
	// -----------------------------------------

	public static function overlay_padding($styles) {
		$directions = array('top', 'right', 'bottom', 'left');
		$padding = array(
			'xl' => array(),
			'lg' => array(),
			'md' => array(),
			'sm' => array(),
			'xs' => array()
		);
		foreach($padding as $bp => $val) {
			foreach($directions as $dir) {
				if(isset($styles[$bp]['padding-' . $dir])) {
					$padding[$bp]['padding-' . $dir] = $styles[$bp]['padding-' . $dir];
				}
			}
		}
		return EditorStyles::get('container', $padding, 'navbar-overlay', false);
	}

	// -----------------------------------------
	// overlay close button
	// -----------------------------------------

	public static function overlay_close($options) {
		// vars
		$output_css = '';
		$sel = '#navbar-overlay .close-overlay';
		$atts = array(
			'padding' => $sel,
			'width' => $sel . ' svg',
			'stroke' => $sel . ' svg path',
			'stroke-width' => $sel . ' svg path'
		);
		// position
		if(isset($options['close_position']) && $options['close_position'] == 'left') {
			$output_css .= $sel . ' { right: initial; left: 0px; }';
		}
		// get breakpoints
		$breakpoints = Get::breakpoints(true);
		// iterate breakpoints
		foreach ($breakpoints as $bp => $width) {
			// reset css
			$css = '';
			// iterate modules
			foreach($atts as $attr => $sel) {
				$val = self::get_val($options, 'close_' . $attr, $bp);
				if($val) {
					$css .= $sel . ' { ' . $attr . ': ' . $val . '; }';
				}
			}
			// add to css output
			if(!empty($css) && $bp != 'xl') {
				if(!self::$is_frontend) {
					$output_css .= str_replace('##breakpoint##', $bp, $css);
				} else {
					$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-customize-bp="##breakpoint##"] ', '', $css) . '}';
				}
			} else {
				$output_css .= str_replace('[data-customize-bp="##breakpoint##"]', '', $css);
			}
		}
		// return css
		return $output_css;
	}

	// -----------------------------------------
	// visibility
	// -----------------------------------------

	public static function visibility($options, $is_frontend) {
		// breakpoints
		$breakpoints = Get::breakpoints(true);
		$output = '';
		// iterate breaktpoins
		foreach($breakpoints as $bp => $value) {
			$bp_var = ($bp == 'xl') ? '' : '_' . $bp;
			// visibility
			if($is_frontend && isset($options['visibility' . $bp_var]) && $options['visibility' . $bp_var] == 'hidden') {
				$output .= ' data-nav-display-' . $bp . '="none"';
			}
		}
		return ($output != '') ? $output . ' ' : $output;
	}

	// -----------------------------------------
	// width
	// -----------------------------------------

	public static function width($ram, $is_frontend) {
		// breakpoints
		$widths = array(
			'xl' => '',
			'lg' => '',
			'md' => '',
			'sm' => '',
			'xs' => ''
		);
		// iterate if layout is set
		if(isset($ram['layout'])) {
			foreach($widths as $bp => $value) {
				$visible = true;
				// visibility
				if($is_frontend && isset($ram['options']['visibility_' . $bp]) && $ram['options']['visibility_' . $bp] == 'hidden') {
					$visible = false;
				}
				// only add if visible
				if($visible) {
					$widths[$bp] = isset($ram['layout']['width']) ? $ram['layout']['width'] : '1fr';
					// add to array
					if(isset($ram['layout']['width_' . $bp])) {
						$widths[$bp] = $ram['layout']['width_' . $bp];
					}
				}
			}
		}
		return $widths;
	}

	// -----------------------------------------
	// width css
	// -----------------------------------------

	public static function width_css($nav_id, $navbar_id, $id, $widths, $options) {
		// vars
		$output = '';
		$breakpoints = Get::breakpoints(true);
		$css = '';
		$gcw_xl = '';
		$stack_xl = 'hor';
		// iterate breakpoints
		foreach ($breakpoints as $bp => $width) {
			// get grid stack
			$stack = false;
			if(is_array($options)) {
				if($bp == 'xl' && isset($options['stack'])) {
					$stack_xl = $options['stack'];
					$stack = $stack_xl;
				} else if(isset($options['stack_' . $bp])) {
					$stack = $options['stack_' . $bp];
				} else {
					$stack = $stack_xl;
				}
			}
			// grid column width
			$gcw = '';
			// iterate columns
			foreach($widths as $el_id => $values) {
				// has width?
				if($values[$bp]) {
					$gcw .= ' ' . $values[$bp];
					if($bp == 'xl') {
						$gcw_xl .= ' ' . $values[$bp];
					}
				}
			}
			// css
			$css = '';
			if($bp != 'xl' && $gcw != $gcw_xl && $gcw != '' || $bp != 'xl' && $stack_xl == 'ver') {
				$css = '[data-customize-bp="##breakpoint##"] .' . $nav_id . ' #' . $navbar_id . ' #' . $id . ' { grid-template-columns: ' . substr($gcw, 1) . '; }';
			} else if($bp == 'xl' && $gcw_xl != '') {
				$css = '.' . $nav_id . ' #' . $navbar_id . ' #' . $id . ' { grid-template-columns: ' . substr($gcw_xl, 1) . '; }';
			}
			// stack
			if($bp == 'xl' && $stack == 'ver' || $bp != 'xl' && $stack && $stack == 'ver') {
				$css = (($bp != 'xl') ? '[data-customize-bp="##breakpoint##"] ' : '') . '.' . $nav_id . ' #' . $navbar_id . ' #' . $id . ' { grid-auto-flow: row; grid-template-columns: auto; }';
			}
			// add to output
			if($bp != 'xl') {
				if(!self::$is_frontend) {
					$output .= str_replace('##breakpoint##', $bp, $css);
				} else {
					$output .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-customize-bp="##breakpoint##"] ', '', $css) . '}';
				}
			} else {
				$output = $css;
			}
		}
		return $output;
	}

	// ----------------------------------------
	// layout
	// ----------------------------------------

	public static function layout($nav_id, $id, $layout, $selector, $grid_type) {
		// grid/flex
		$convert = array(
		'flex' => array(
			'top' => 'flex-start',
			'center' => 'center',
			'bottom' => 'flex-end'
		),
		'grid' => array(
			'top' => 'start',
			'center' => 'center',
			'bottom' => 'end'
			)
		);
		$output_css = '';
		// fist add display for xl and remove it so it doesnt get added every time
		if(isset($layout['display'])) {
			$output_css = '.' . $nav_id . ' #' . $id . ' { display: ' . $layout['display'] . ' }'; 
			unset($layout['display']);
		}
		// get breakpoints
		$breakpoints = Get::breakpoints(true);
		// iterate breakpoints
		foreach ($breakpoints as $bp => $width) {
			$css = '';
			foreach($layout as $attr => $val) {
				$attr = ($bp != 'xl') ? $attr . '_' . $bp : $attr;
				if(false === strpos($attr, 'width')) {
					// remove any bp stuff
					$attr = str_replace('_' . $bp, '', $attr);
					// convert align-self and justify-self
					if($attr == 'align-self') {
						$val = $convert[$grid_type][$val];
					}
					$css .= $attr . ': ' . $val . ';';
				}
			}
			// add to css output
			if(!empty($css)) {
				// add selector
				$css = $selector . '{ ' . $css . ' }'; 
				if(!self::$is_frontend) {
					$output_css .= str_replace('##breakpoint##', $bp, $css);
				} else {
					$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $css) . '}';
				}
			}
		}
		// return
		return $output_css;
	}

	// -----------------------------------------
	// navbar atts
	// -----------------------------------------

	public static function navbar_atts($nav_id, $navbar_id, $options, $styles, $no_reveal) {
		// vars
		$navbar = str_replace('navbar-', '', $navbar_id);
		$classes = ($navbar_id == 'navbar-overlay') ? 'smp-overlay' : 'smp-navbar';
		// add section class on admin
		if(!self::$is_frontend) {
			$classes .= ' nav-section';
			
		} else if($navbar == 'top') {
			$classes .= (isset($options['navbar_cover']) && $options['navbar_cover'] == 'enabled') ? ' cover-transparent' : '';
		}
		// atts
		$data_atts = ' ';
		$atts = array(
			'navbar_width' => array('data-navbar-width', 'fluid', 'all'),
			'navbar_mode' => array('data-navbar-mode', 'sticky', 'all'),
			'overlay_height' => array('data-overlay-height', 'full', 'overlay'),
			'justify-content' => array('data-navbar-halign', 'left', 'overlay'),
			'align-content' => array('data-navbar-valign', 'flex-start', 'overlay'),
			'navbar_headroom' => array('data-use-headroom', 'disabled', 'top'),
			'navbar_cover' => array('data-cover-transparent', 'disabled', 'top'),
		);
		foreach($atts as $attr => $values) {
			if($navbar == $values[2] || $values[2] == 'all') {
				$data_atts .= $values[0] . '="' . (isset($options[$attr]) ? $options[$attr] : $values[1]) . '" ';
			}
		}
		// reveal transition
		$reveal = (self::$is_frontend && !$no_reveal || self::$is_frontend && $navbar_id == 'navbar-overlay') ? self::reveal($navbar_id, $options, $styles) : '';		
		// return
		return ' class="' . $classes . '" data-navbar="' . $navbar . '" data-nav-id="' . $nav_id . '"' . $data_atts . $reveal;
	}

	// -----------------------------------------
	// reveal animation
	// -----------------------------------------

	public static function reveal($navbar_id, $options, $styles) {
		if(!isset($options['reveal_transition']) || isset($options['reveal_transition']) && $options['reveal_transition'] != 'none') {
			// offset
			$offset = array('xl' => 0);
			$offset_attr = str_replace('navbar-', '', $navbar_id);
			foreach($styles as $bp => $values) {
				$offset[$bp] = (isset($values[$offset_attr])) ? $values[$offset_attr] : 0;
			}
			$reveal = array(
				'transition' => 'slide',
				'easing'	 => 'Expo.easeOut',
				'duration'	 => .4,
				'delay'		 => 0,
				'offset'	 => $offset
			);
			// adding custom options for overlay
			if($navbar_id == 'navbar-overlay') {
				$reveal['dimdown'] = '#000000';
				$reveal['dimdown_opacity'] = '.85';
				$reveal['start_pos'] = 0;
				$reveal['start_opacity'] = 0;
				$reveal['grow_attr'] = 'height';
				$reveal['push_dir'] = 'right';
			}
			// iterate
			foreach($reveal as $attr => $val) {
				if(isset($options['reveal_' . $attr])) {
					$reveal[$attr] = $options['reveal_' . $attr];
				}
			}
			// reveal
			return ' data-reveal-type="' . $reveal['transition'] . '" data-reveal-transition=\'' . json_encode($reveal) . '\'';
		} else {
			return '';
		}
	}

	// -----------------------------------------
	// get selector
	// -----------------------------------------

	public static function selector($selector, $targets) {
		if(is_array($targets)) {
			$output = '';
			foreach($targets as $target) {
				$output .= $selector . ' ' . $target . ',';
			}
			return substr($output, 0, -1);
		} else {
			return $selector . ' ' . $targets;
		}
	}

	// -----------------------------------------
	// get value
	// -----------------------------------------

	public static function get_val($options, $name, $bp) {
		if(isset($options[$name . '_' . $bp])) {
			return $options[$name . '_' . $bp];
		} else if($bp == 'xl' && isset($options[$name])) {
			return $options[$name];
		} else {
			return false;
		}
	}
}

Customize::$setting['navigations'] = new Navigations;