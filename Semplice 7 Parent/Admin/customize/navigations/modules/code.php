<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Get;

// -----------------------------------------
// module
// -----------------------------------------

class CodeModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// return
		if(self::$is_frontend) {
			$code = (isset($options['is_shortcode']) && $options['is_shortcode'] == 'yes') ? do_shortcode($options['content']) : $options['content'];
			return '<div class="smp-nav-code is-content">' . $code . '</div>';
		} else {
			return '<div class="smp-nav-code is-content">' . Get::svg('admin', 'customize/navigations/modules/code') . '<span>Code Module</span></div>';
		}
	}
}

// instance
Navigations::$modules['code'] = new CodeModule;
?>