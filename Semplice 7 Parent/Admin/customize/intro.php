<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Get;

// -----------------------------------------
// customize grid
// -----------------------------------------

class Intro extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// uri
		$uri = SEMPLICE_URI;
		// define output
		$output = '
			<div class="admin-column" data-xl-width="12">
				<div class="semplice-intro">
					<div class="inner">
						<div class="reset-success">
							<div class="reset-success-inner">
								<div class="icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14">
										<path id="Form_1" data-name="Form 1" d="M6.679,13.758L0.494,7.224,1.878,5.762l4.8,5.072L16.153,0.825l1.384,1.462Z"></path>
									</svg>
								</div>
								<div class="message">Successful reset</div>
							</div>
						</div>
						<img src="' . SEMPLICE_URI . '/assets/images/admin/customize/intro/head.png">
						<div class="description">A site intro is an animation that will be displayed<br />on the first visit of a user. After the animation is<br />done your regular content will fade in.</div>
						<div class="intro-options"></div>
						<div class="edit-button">
							<button class="edit-intro" data-button-color="yellow"><span>' . Get::svg('admin', 'edit') . '</span>Edit Intro</button>
							<button class="reset-intro">Reset Intro</button>
						</div>
					</div>
				</div>
			</div>
		';
		// return
		return $output;
	}
}

Customize::$setting['intro'] = new Intro;