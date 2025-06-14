<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Get;
use Semplice\Helper\Image;
use Semplice\Helper\Menu;

// -----------------------------------------
// module
// -----------------------------------------

class MenuModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// vars
		$menu_dir = isset($options['menu_direction']) ? $options['menu_direction'] : 'horizontal';
		$type = ($menu_dir == 'vertical') ? 'nested' : 'dropdown';
		$effect = isset($options['hover-effect']) ? $options['hover-effect'] : false;
		// submenu arrow
		if(isset($options[$type . '_arrow'])) {
			$arrow_img = Image::get($options[$type . '_arrow'], 'full');
			$arrow = $arrow_img ? '<img src="' . $arrow_img['src'] . '">' : Get::svg('frontend', 'arrows/sub_menu');
		} else {
			$arrow = Get::svg('frontend', 'arrows/sub_menu');
		}
		// walker args
		$walker_args = array(
			'effect' => $effect,
			'arrow' => $arrow
		);
		// get menu
		$menu = (isset($options['menu']) && !empty($options['menu'])) ? Menu::html($options['menu'], $walker_args) : Menu::html('Semplice Menu', $walker_args);
		// return
		return '
			<div class="text">
				' . $menu . '
			</div>
			' . $this->hamburger($options) . '
		';
	}

	// -----------------------------------------
	// hamburger
	// -----------------------------------------

	public function hamburger($options) {
		// mode
		$mode = (isset($options['hamburger_mode'])) ? $options['hamburger_mode'] : 'default';
		// switch mode
		switch($mode) {
			case 'default':
				return '<div class="hamburger"><a class="open-menu menu-icon"><span></span></a></div>';
			break;
			case 'custom':
				// hamburger options
				$burgers = array(
					'hamburger_custom' 		 => '',
					'hamburger_custom_close' => ''
				);
				// iterate options
				foreach ($burgers as $burger => $val) {
					// display option
					$display_option = str_replace('_', '-', $burger);
					// not found
					$not_found = '<img class="' . $display_option . '" src="' . SEMPLICE_URI . '/assets/images/admin/customize/navigations/hamburger.png" alt="' . $display_option . '">';
					// has image?
					if(isset($options[$burger])) {
						// show hamburger or not found if not in the library anymore
						$image = wp_get_attachment_image_src($options[$burger], 'full', false);
						// assign image
						$burgers[$burger] = ($image) ? '<img class="' . $display_option . '" src="' . $image[0] . '" alt="' . $display_option . '">' : $not_found;
					} else {
						$burgers[$burger] = $not_found;
					}
				}
				// mouseover
				$mouseover = (isset($options['hamburger_custom_mouseover'])) ? $options['hamburger_custom_mouseover'] : '';
				// return
				return '<div class="hamburger custom-hamburger" data-hamburger-mouseover="' . $mouseover . '"><a class="open-menu menu-icon">' . $burgers['hamburger_custom'] . $burgers['hamburger_custom_close'] . '</a></div>';
			break;
			case 'text':
				// define defaults
				$defaults = array(
					'font_family' => 'regular',
					'label'  	  => 'Menu',
					'close_label' => 'Close'
				);
				// iterate defaults
				foreach($defaults as $option => $val) {
					if(isset($options['hamburger_text_' . $option])) {
						$defaults[$option] = $options['hamburger_text_' . $option];
					}
				}
				// return
				return '<div class="hamburger"><span class="hamburger-text open-menu" data-font="' . $defaults['font_family'] . '" data-label-open="' . $defaults['label'] . '" data-label-close="' . $defaults['close_label'] . '">' . $defaults['label'] . '</div>';
			break;
		}
	}
}

// instance
Navigations::$modules['menu'] = new MenuModule;
?>