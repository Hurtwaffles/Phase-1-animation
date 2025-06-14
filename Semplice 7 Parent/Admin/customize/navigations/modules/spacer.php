<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;

// -----------------------------------------
// module
// -----------------------------------------

class SpacerModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		return '
			<div class="spacer-container">
				<div class="is-content">
					<div class="spacer"></div>
				</div>
			</div>
		';
	}
}

// instance
Navigations::$modules['spacer'] = new SpacerModule;
?>