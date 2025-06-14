<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Get;
use Semplice\Helper\PostQueries;

// -----------------------------------------
// customize advanced
// -----------------------------------------

class Footer extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// output
		$output = array();
		// get footers
		$request = array(
			'post_type'		  => 'footer',
			'page'			  => 'show_all',
			'hide_row_header' => true,
			'only_posts'	  => true
		);
		// get posts
		return PostQueries::get_posts($request);
	}
}

Customize::$setting['footer'] = new Footer;