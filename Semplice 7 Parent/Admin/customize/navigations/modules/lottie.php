<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;

// -----------------------------------------
// module
// -----------------------------------------

class LottieModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// vars
		$lottie = (isset($options['content'])) ? $options['content'] : false;
		$loop = (isset($options['loop'])) ? $options['loop'] : false;
		$speed = (isset($options['speed'])) ? $options['speed'] : 1;
		// return
		if(self::$is_frontend) {
			$lottie = ($lottie) ? $lottie : array('url' => SEMPLICE_URI . '/assets/json/lottie/navigations.json');
			return '<div class="smp-nav-lottie" data-lottie=\'{"url": "' . $lottie['url'] . '", "speed": "' . $speed . '", "loop": "' . $loop . '"}\'></div>';
		} else {
			return '<div class="smp-nav-lottie"></div>';
		}
	}
}

// instance
Navigations::$modules['lottie'] = new LottieModule;
?>