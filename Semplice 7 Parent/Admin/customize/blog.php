<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Get;

// -----------------------------------------
// customize advanced
// -----------------------------------------

class Blog extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// output
		$output = '';
		// templates
		$templates = array(
			'overview' => array(
				'name' => 'Overview',
				'desc' => 'This blog overview gets displayed<br />if there is not homepage set in WordPress.'
			),
			'singlepost' => array(
				'name' => 'Single post',
				'desc' => 'This template will get used for the<br />single post view once you click on a blog post.'
			),
			'archive' => array(
				'name' => 'Archives',
				'desc' => 'Archive will get used if you click<br />on a taxonomy like a category, tag or author.'
			),
			'searchresults' => array(
				'name' => 'Search results',
				'desc' => 'This template gets displayed once a<br />user enters a keyword in your blog search.'
			),
		);
		// reset success
		$reset = '
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
		';
		// iterate templates
		foreach($templates as $id => $template) {
			$output .= '
				<div id="template-' . $id . '" class="blog-template" data-template="' . $id . '">
					<img class="blog-icon" src="' . SEMPLICE_URI . '/assets/images/admin/customize/blog/icon_' . $id . '.svg" alt="icon">
					<p class="heading">' . $template['name'] . '</p>
					<p class="description">' . $template['desc'] . '</p>
					<div class="buttons">
						<a class="edit-template">Edit Template<span>' . Get::svg('admin', 'dashboard/link') . '</span></a>
						<a class="reset-template" data-template="' . $id . '">Reset Template</a>
					</div>
					<img class="blog-bg" src="' . SEMPLICE_URI . '/assets/images/admin/customize/blog/bg_' . $id . '.svg" alt="icon">
					' . $reset . '
				</div>
			';
		}
		// return
		return '<div class="blog-templates admin-column" data-xl-width="12">' . $output . '</div>';
	}
}

Customize::$setting['blog'] = new Blog;