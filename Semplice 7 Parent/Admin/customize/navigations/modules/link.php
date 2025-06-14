<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Get;

// -----------------------------------------
// module
// -----------------------------------------

class LinkModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// vars
		$text = (isset($options['link_text'])) ? $options['link_text'] : 'Link';
		$target = (isset($options['link_target'])) ? $options['link_target'] : '_self';
		// return
		return '<a href="' . Get::url($options, 'home') . '" target="' . $target . '" class="is-content">' . $text . '</a>';
	}
}

// instance
Navigations::$modules['link'] = new LinkModule;
?>