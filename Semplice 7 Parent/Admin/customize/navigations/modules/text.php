<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;

// -----------------------------------------
// module
// -----------------------------------------

class TextModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// get image or return default
		if(isset($options['content']) && !empty($options['content'])) {
			$content = $options['content'];
		} else {
			$content = 'Sample text, double click to edit me!';
		}
		// return
		return '<div class="is-content">' . $content . '</div>';
	}
}

// instance
Navigations::$modules['text'] = new TextModule;
?>