<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Get;
use Semplice\Helper\Image;

// -----------------------------------------
// module
// -----------------------------------------

class ButtonModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// vars
		$text = (isset($options['button_text'])) ? $options['button_text'] : 'Button';
		$button = array(
			'text' => '<span class="text">' . $text . '</span>',
			'icon' => false
		);
		$direction = (isset($options['icon_direction'])) ? $options['icon_direction'] : 'right';
		$icon_effect = (isset($options['icon_effect'])) ? $options['icon_effect'] : 'yes';
		$target = (isset($options['link_target'])) ? $options['link_target'] : '_self';
		$font = (isset($options['font'])) ? $options['font'] : 'regular';
		if(isset($options['icon'])) {
			$img = Image::get($options['icon'], 'full');
			$button['icon'] = '<span class="icon"><img src="' . $img['src'] . '" alt="button-icon"></span>';
			$button = ($direction == 'left') ? array_reverse($button) : $button;
		}
		// content
		$content = '';
		foreach($button as $type => $html) {
			$content .= $html;
		}
		return '<button data-icon-direction="' . $direction . '" data-icon-effect="' . $icon_effect . '" class="smp-nav-button is-content" data-href="' . Get::url($options, 'home') . '" data-target="' . $target . '" data-font="' . $font . '">' . $content . '</button>';
	}
}

// instance
Navigations::$modules['button'] = new ButtonModule;
?>