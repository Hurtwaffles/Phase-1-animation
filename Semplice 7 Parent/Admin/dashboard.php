<?php

// namespace
namespace Semplice\Admin;

// use
use Semplice\Helper\Thumbnails;
use Semplice\Helper\PostQueries;
use Semplice\Helper\Get;
use Semplice\Helper\License;

// -----------------------------------------
// semplice customize
// -----------------------------------------

class Dashboard {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// output
	// -----------------------------------------

	public static function output() {
		return '
			<div class="smp-dashboard admin-container">
				' . self::notices() . '
				' . self::welcome() . '
				' . self::posts() . '
				' . self::showcase() . '
				' . self::tutorials() . '
				' . self::changelog() . '
				' . self::ressources() . '
			</div>
		';
	}

	// -----------------------------------------
	// notices
	// -----------------------------------------

	public static function notices() {
		$output = '';
		// mobile detect
		if (version_compare(PHP_VERSION, '8.0.0') < 0) {
			$output .= '<div class="dashboard-notice"><span>Mobile detection not working</span>To make mobile detection work please make sure that your PHP version is 8.0 or above.<br />You can normally change the PHP version within your host admin panel.</div>';
		}
		// return
		return $output;
	}

	// -----------------------------------------
	// welcome banner
	// -----------------------------------------

	public static function welcome() {
		// welcome notcie
		$notice = get_option('semplice_welcome');
		// not hidden or shown yet?
		if(!$notice) {
			// get license
			$license = License::get();
			$is_activated = ($license && $license['is_valid'] && isset($licenses[$license['product']])) ? true : false;
			// get activation card
			$activate = '';
			if(!$is_activated) {
				$activate = '
					<a href="#settings/license">
						<div class="welcome-card">
							<div class="card-arrow">' . Get::svg('admin', 'dashboard/arrow-card') . '</div>
							<div class="card-meta">
								<p class="head">License Activation</p>
								<p class="sub">Don\'t forget to activate your license.</p>
							</div>
							<div class="card-bg">' . Get::svg('admin', 'dashboard/gradient-card') . '</div>
						</div>
					</a>
				';
			}
			$output = '
				<div class="admin-row welcome-wrapper">
					<div class="smp-welcome">
						<div class="admin-column" data-xl-width="12">
							<div class="hide-welcome-banner"><p>Don\'t show this again.</p><div class="close-welcome">' . Get::svg('admin', 'dashboard/close-welcome') . '</div></div>
							<div class="welcome-content">
								<div class="welcome-head">
									<h2>Welcome to the<br />Semplice Family</h2>
									<p>Watch this short video to get familiar with the content editor and<br />learn how everything connects in Semplice.</p>
								</div>
								' . $activate . '
								<a href="https://www.semplice.com/videos#content-editor-overview" target="_blank">
									<div class="welcome-video">
										<div class="image"><img src="' . SEMPLICE_URI . '/assets/images/admin/dashboard/welcome.jpg"></div>
										<div class="play-button">' . Get::svg('admin', 'dashboard/play-welcome') . '</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			';
		}
		// return
		return (isset($output)) ? $output : '';
	}

	// -----------------------------------------
	// last modified posts
	// -----------------------------------------

	public static function posts() {
		// output
		$output = '';
		// get latest 3 projects
		$args = array(
			'posts_per_page' => 4,
			'post_type' 	 => array('page', 'project'),
			'orderby'		 => 'modified'
		);
		// get posts
		$posts = wp_get_recent_posts($args);
		// has posts?
		if(!empty($posts)) {
			foreach ($posts as $key => $post) {
				// is semplice?
				$is_semplice = get_post_meta($post['ID'], '_is_semplice', true);
				// get thumbnail
				$thumbnail = Thumbnails::get($post['ID'], false, 'medium_large');
				// format post
				$output .= PostQueries::post_row($post, 'project', false, $thumbnail, $is_semplice, 'thumb', true);
			}
		} else {
			$output .= '<div class="admin-column" data-xl-width="12"><div class="empty-posts">There are no pages or projects yet on your site.<br />Click on add new on the upper right to create a new page or project.</div></div>';
		}
		
		// get posts
		$output = '
			<div class="admin-row">
				<div class="admin-header admin-column">
					<div class="heading">
						<h4>Recently modified</h4>
					</div>
					<div class="actions">
						<button class="click-handler add-post-dashboard" data-button-icon="plus" data-handler="addPostDashboard"><span></span>Add new</a>
						<div class="add-post-dashboard-popup">
							<div class="add-page click-handler" data-handler="run" data-action-type="dialog" data-setting-type="post" data-action="add" data-post-type="page">
								' . Get::svg('admin', 'dashboard/add-page') . '
								<span>Page</span>
							</div>
							<div class="add-project click-handler" data-handler="run" data-action-type="dialog" data-setting-type="post" data-action="add" data-post-type="project">
								' . Get::svg('admin', 'dashboard/add-project') . '
								<span>Project</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="projects posts admin-row">
				<div class="projects-list">
					' . $output . '
				</div>
			</div>
		';
		// return
		return $output;
	}

	// -----------------------------------------
	// showcase
	// -----------------------------------------

	public static function showcase() {
		// output
		$output = '
			<div class="smp-showcase">
				<div class="admin-row">
					<div class="admin-header admin-column smp-tutorials-header">
						<div class="heading">
							<h4>Showcase</h4>
							<p class="sub-headline">Let the inspiration flow. The Semplice showcase is the best way to find<br />amazing looking portfolios and sites built with Semplice.</p>
						</div>
					</div>
				</div>
				<div class="showcase-images-wrapper">
		';
		// get showcase images
		$showcase = wp_remote_get('https://www.semplice.com/wp-json/semplice/v1/child/showcase-dashboard');
		// check for errors
		if(!is_wp_error($showcase) && empty($showcase->errors)) {
			$showcase = json_decode($showcase['body'], true);
			// has images?
			if(null !== $showcase && is_array($showcase)) {
				// iterate
				$output .= '<a class="showcase-images" href="https://www.semplice.com/showcase" target="_blank">';
				foreach($showcase['images'] as $index => $src) {
					$info_link = '';
					if($index == 1) {
						$info_link = '
							<div class="showcase-link">
								<div class="inner">Visit our Showcase<span>' . Get::svg('admin', 'dashboard/link') . '</span></div>
							</div>
							<div class="showcase-info">' . $showcase['info']['name'] . '<span class="divider">|</span><span class="role serif_regular_italic">' . $showcase['info']['role'] . '</span></div>
						';
					}
					$output .= '<div class="showcase-image si-' . $index . '"><img src="' . $src . '" alt="showcase-image">' . $info_link . '</div>';
				}
				$output .= '</a>';
			}
		}
		// showcase link
		$output .= '';
		// close images
		$output .= '</div></div>';
		// return
		return $output;
	}

	// -----------------------------------------
	// tutorials
	// -----------------------------------------

	public static function tutorials() {
		$play = '<div class="tutorial-play">' . Get::svg('admin', 'dashboard/play') . '</div>';
		return '
			<div class="admin-row">
				<div class="admin-header admin-column smp-tutorials-header">
					<div class="heading">
						<h4>Tutorials</h4>
						<p class="sub-headline">Our super easy to follow video tutorials give you the best overview on<br />what Semplice has to offer, from basic to advanced features.</p>
					</div>
					<div class="actions">
						<a class="dashboard-link" href="https://www.semplice.com/videos" target="_blank">All Video Tutorials<span>' . Get::svg('admin', 'dashboard/link') . '</span></a>
					</div>
				</div>
			</div>
			<div class="smp-tutorials admin-row">
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#content-editor-overview" target="_blank">
							<p class="title">Semplice<br/>Quickstart</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-quickstart') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#base-grid" target="_blank">
							<p class="title">Base Grid<br />Fundamentals</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-grid') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#portfolio-grid" target="_blank">
							<p class="title">Creating a<br />Portfolio Grid</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-portfoliogrid') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#apg" target="_blank">
							<p class="title">Advanced<br />Portfolio Grid</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-apg') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#custom-navigation" target="_blank">
							<p class="title">Custom<br />Navigations</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-navigations') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
				<div class="smp-tutorial admin-column" data-xl-width="2">
					<div class="inner">
						<a href="https://www.semplice.com/videos#webfont-self-hosted" target="_blank">
							<p class="title">Web Fonts<br />Fundamenals</p>
							<div class="tutorial-image">
								' . Get::svg('admin', 'dashboard/tutorial-webfonts') . '
								' . $play . '
							</div>
						</a>
					</div>
				</div>
			</div>
		';
	}

	// -----------------------------------------
	// changelog
	// -----------------------------------------

	public static function changelog() {
		$output = '';
		// get content from semplicelabs
		$content = wp_remote_get('https://news.semplice.com/wp-json/news/v1/news_seven');
		// is error?
		if(!is_wp_error($content) && empty($content->errors)) {
			$content = json_decode($content['body'], true);
			// is content?
			if(null !== $content) {
				// changelog + misc headings
				$output .= '
					<div class="dashboard-changelog-wrapper">
						<div class="admin-row">
							<div class="admin-column smp-license">
								<div class="admin-header">
									<div class="heading">
										<h4>License</h4>
										<p class="sub-headline">Here you’ll find useful information<br />in regards to your license.</p>
									</div>
								</div>
								<div class="about-semplice" data-update-status="uptodate">
									' . License::about() . '
								</div>
							</div>
							<div class="admin-column smp-changelog">
								<div class="admin-header">
									<div class="heading">
										<h4>Changelog</h4>
										<p class="sub-headline">We’re always working hard to improve your<br />experience. Here are the latest changes:</p>
									</div>
									<div class="actions">
										<a class="dashboard-link" href="https://www.semplice.com/changelog" target="_blank">Complete Changelog<span>' . Get::svg('admin', 'dashboard/link') . '</span></a>
									</div>
								</div>
								<div class="dashboard-changelog" data-changelog-version="' . SEMPLICE_EDITION . '">
									' . $content . '
									<div class="changelog-expand">' . Get::svg('admin', 'dashboard/arrow_down') . '</div>
								</div>
							</div>
						</div>
					</div>
				';
			}
		} else {
			// column open
			$error = '<div class="admin-column dashboard-error" data-xl-width="12"><p>An error occured while trying to fetch content from https://news.semplice.com.</p>';
			if(is_wp_error($content)) {
				$error .= '<p><span>Error Message: ' . $content->get_error_message() . '</span></p>';
			} else {
				$error .= '<p><span>Error Message: Unfortunately there were no detailed error message provided.</span></p>';
			}
			// column close
			$error .= '</div>';
			// output
			$output .= '<div class="admin-container"><h4>Problems connecting to semplice.com</h4><div class="admin-row"><div class="admin-column" data-xl-width="12">' . $error . '</div></div></div>';
		}
		// return
		return $output;
	}

	// -----------------------------------------
	// ressources
	// -----------------------------------------

	public static function ressources() {
		$dir = SEMPLICE_URI;
		return '
			<div class="admin-row">
				<div class="admin-header admin-column smp-ressources-header">
					<div class="heading">
						<h4>Resources</h4>
						<p class="sub-headline">Premium resources & guides, as well as mockups and assets from the<br />makers of Semplice. Carefully crafted and designed to impress. </p>
					</div>
				</div>
			</div>
			<div class="smp-ressources admin-row">
				<div class="smp-ressource-links admin-column" data-xl-width="8">
					<a class="ressource-link" href="https://www.semplice.com/handheld-device-mockups" target="_blank"><img src="' . $dir . '/assets/images/admin/dashboard/ressources_1.jpg" alt="ressources"><button>Handheld Mockups<span>' . Get::svg('admin', 'dashboard/link') . '</span></button></a>
					<a class="ressource-link" href="https://www.semplice.com/smart-watch-mockup" target="_blank"><img src="' . $dir . '/assets/images/admin/dashboard/ressources_2.jpg" alt="ressources"><button>Smart Watch Pack<span>' . Get::svg('admin', 'dashboard/link') . '</span></button></a>
					<a class="ressource-link" href="https://www.semplice.com/exterior-sign-mockup" target="_blank"><img src="' . $dir . '/assets/images/admin/dashboard/ressources_3.jpg" alt="ressources"><button>Exterior Signs<span>' . Get::svg('admin', 'dashboard/link') . '</span></button></a>
				</div>
				<div class="smp-guides admin-column" data-xl-width="4">
					<div class="inner">
						<p class="title">Guides</p>
						<ul>
							<li><span class="icon">' . Get::svg('admin', 'dashboard/guide-about-page') . '</span><a href="https://www.semplice.com/how-to-create-your-about-page" target="_blank">How to create an about page</a></li>
							<li><span class="icon">' . Get::svg('admin', 'dashboard/guide-portfolio') . '</span><a href="https://www.semplice.com/beginner-guide" target="_blank">Creating your first portfolio</a></li>
							<li><span class="icon">' . Get::svg('admin', 'dashboard/guide-type-foundries') . '</span><a href="https://www.semplice.com/favorite-type-foundries" target="_blank">Our favorite type foundries</a></li>
							<li><span class="icon">' . Get::svg('admin', 'dashboard/guide-case-studies') . '</span><a href="https://www.semplice.com/how-to-write-case-studies-for-your-portfolio" target="_blank">How to write case studies</a></li>
							<li><span class="icon">' . Get::svg('admin', 'dashboard/guide-portfolio-launch') . '</span><a href="https://www.semplice.com/portfolio-building-checklist" target="_blank">How to launch your portfolio</a></li>
						</ul>
					</div>
				</div>
			</div>
		';
	}
}

new Dashboard;