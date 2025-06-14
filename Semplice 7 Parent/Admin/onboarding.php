<?php

// namespace
namespace Semplice\Admin;

// Use
use Semplice\RestApi\Editor\RestAPISave;
use Semplice\Helper\Basic;
use Semplice\Helper\PostQueries;

// -----------------------------------------
// semplice onboarding
// -----------------------------------------

class Onboarding {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// setup
	// -----------------------------------------

	public static function setup() {
		// get onboarding status
		$completed = get_option('semplice_completed_onboarding');
		// return status
		if($completed) {
			return false;
		} else {
			return true;
		}
		return true;
	}

	// -----------------------------------------
	// save
	// -----------------------------------------

	public static function save($request) {
		// instance
		$rest_api_save = new RestAPISave();
		// defaults
		$defaults = array(
			'site_title' 	=> 'Sergio Rambotta',
			'site_tagline' 	=> 'Graphic Designer'
		);
		// get content and check slashes
		$content = json_decode(Basic::check_slashes($request['content']), true);
		// loop throught data and fill with default if no data there
		foreach ($defaults as $attribute => $default) {
			// is empty?
			if(!isset($content[$attribute]) || empty($content[$attribute])) {
				$content[$attribute] = $default;
			}	
		}
		// save pages
		$pages = array('work', 'about');
		$savePages = array();
		// create new pages
		foreach ($pages as $page) {
			// first page
			$savePages[$page] = array(
				'post_title'   => ucfirst($page),
				'post_status'  => 'publish',
				'post_type'	   => 'page',
				'post_name'	   => wp_unique_post_slug(sanitize_title(ucfirst($page)), '', 'publish', 'page', 0),
			);
			// add first page
			$savePages[$page]['id'] = wp_insert_post($savePages[$page]);
			// get first page data
			$savePages[$page]['data'] = self::first_page($savePages[$page]['id'], $page, array('active' => 'latest_version', 'published' => 'latest_version'));
			// add content to first project
			$rest_api_save->post($savePages[$page]['data']);
			// created with s4 admin so per default set is semplice to true
			update_post_meta($savePages[$page]['id'], '_is_semplice', true, '');
			// is homepage
			if($page == 'work') {
				// set show on front to page
				update_option('show_on_front', 'page');
				// make homepage
				update_option('page_on_front', $savePages[$page]['id']);
			}
		}
		// set blog name
		update_option('blogname', $content['site_title']);
		// set blog description
		update_option('blogdescription', $content['site_tagline']);
		// first project
		$first_project = array(
		  'post_title'    => 'My First Project',
		  'post_status'   => 'draft',
		  'post_type'	  => 'project',
		  'post_name'	  => wp_unique_post_slug(sanitize_title('My First Project'), '', 'publish', 'project', 0),
		);
		// add first projects
		$first_project_id = wp_insert_post($first_project);
		// get first project data
		$first_project_data = self::first_project($first_project_id, array('active' => 'latest_version', 'published' => 'latest_version'));
		// portfolio order
		PostQueries::save_portfolio_order($first_project_id);
		// add content to first project
		$rest_api_save->post($first_project_data);
		// add a new menu called semplice menu
		$menu_name = 'Semplice Menu';
		$menu_object = wp_get_nav_menu_object($menu_name);
		// craate new menu if it doesnt already exist
		if(!$menu_object) {
			$menu_id = wp_create_nav_menu($menu_name);
			// get menu localtions
			$locations = get_theme_mod('nav_menu_locations');
			// assign new menu
			$locations['semplice-main-menu'] = $menu_id;
			// set new menu
			set_theme_mod('nav_menu_locations', $locations);
		} else {
			// menu id
			$menu_id = $menu_object->term_id;
		}
		// add our new created homepage as first page
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-object' 		=> 'page',
			'menu-item-title'  	 	=> 'Work',
			'menu-item-object-id' 	=> $savePages['work']['id'],
			'menu-item-status' 	 	=> 'publish',
			'menu-item-type'	 	=> 'post_type',
			'menu-item-url'			=> '',
		));
		// add about page
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-object' 		=> 'page',
			'menu-item-title'  	 	=> 'About',
			'menu-item-object-id' 	=> $savePages['about']['id'],
			'menu-item-status' 	 	=> 'publish',
			'menu-item-type'	 	=> 'post_type',
			'menu-item-url'			=> '',
		));
		// save nav
		if(!empty($request['nav'])) {
			update_option('semplice_customize_navigations', $request['nav']);
		}
		// set completed onboarding to true
		update_option('semplice_completed_onboarding', true);
	}

	// -----------------------------------------
	// onboarding content
	// -----------------------------------------

	public static function content() {
		// output
		$output = array(
			'start' => array(
				'title' => 'Welcome to Semplice.',
				'sub' => 'Great choice! You are officially part of our little<br />family. Let’s start building with pride.',
				'input' => ''
			),
			'one' => array(
				'title' => 'What’s your name?',
				'sub'	=> 'This will show up when people search fro your site. Use your name,<br />studio’s name, or even your artistic pseudonym.',
				'input' => '<input type="text" class="ob-stagger" data-type="onboarding" name="site_title" placeholder="Sergio Rambotta">',
			),
			'two' => array(
				'title' => 'You are a...',
				'sub'	=> 'This is your one liner. The title that professionally defines you and your<br />skillset. You can always change this later.',
				'input' => '<input type="text" class="ob-stagger" data-type="onboarding" name="site_tagline" placeholder="Graphic Designer">',
			),
			'three' => array(
				'title' => 'Pick your navigation style.',
				'sub'	=> 'Go with your gut. You can completely customize this later<br />on with our new navigation editor. Don’t stress it.',
				'input' => '',
			),

			'four' => array(
				'title' => 'We’re getting you ready.',
				'sub'	=> 'You just relax for a moment, we’re setting up a few pages and projects<br />to get you started. You can always edit these later.',
				'input' => '',
			),
			'five' => array(
				'title' => 'Welcome to the club.',
				'sub'	=> 'You are ready to create your portfolio. There is no right or wrong<br />way from here. It’s entirely up to you.',
				'input' => '',
			),
		);
		// output
		return $output;
	}

	// -----------------------------------------
	// onboarding first page
	// -----------------------------------------

	public static function first_page($post_id, $title, $post_revision) {
		// content
		if($title == 'work') {
			$content = '{"order":{"section_kb3god8ou":{"columns":{"column_z2clynthm":["content_g6nt8g63h"]},"row_id":"row_spc6vkgc0"}},"images":{},"branding":{},"content_g6nt8g63h":{"content":{"xl":""},"module":"portfoliogrid","options":{},"styles":{"xl":{}},"motions":{"active":[],"start":{},"end":{}}},"section_kb3god8ou":{"options":{},"layout":{"data-column-mode-sm":"single","data-column-mode-xs":"single"},"customHeight":{"xl":{"height":"15rem"}},"styles":{"xl":{}},"motions":{"active":[],"start":{},"end":{}}},"column_z2clynthm":{"width":{"xl":12},"options":{},"layout":{},"styles":{"xl":{}},"motions":{"active":[],"start":{},"end":{}}},"first_save":"yes","unpublished_changes":false}';
		} else {
			$content = '{"order":{},"images":{"":""},"branding":{},"first_save":"yes","unpublished_changes":false}';
		}
		// create array
		$page = array(
			'post' => array(
				'id' 		=> $post_id,
				'type'		=> 'page',
				'password' => '',
				'revision' => $post_revision,
			),
			'settings' => array(
				'meta' => array(
					'post_title' => ucfirst($title),
					'permalink' => sanitize_title($title),
				),
			),
			'first_save'	=> 'yes',
			'content'		=> $content,
			'change_status'	=> 'yes',
			'save_mode'		=> 'publish',
			'wp_template'	=> false,
		);
		// encode
		$page['post'] = json_encode($page['post']);
		$page['settings'] = json_encode($page['settings']);
		return $page;
	}

	// -----------------------------------------
	// first project
	// -----------------------------------------

	public static function first_project($post_id, $post_revision) {
		// create array
		$project = array(
			'post' => array(
				'id' 		=> $post_id,
				'type'		=> 'project',
				'password' => '',
				'revision' => $post_revision,
			),
			'settings' => array(
				'thumbnail' => array(
					'image' => '',
					'width'	=> '4',
					'hover_visibility' => 'disabled',
				),
				'meta' => array(
					'post_title' => 'My first project',
					'permalink' => sanitize_title('My first project'),
				),
			),
			'change_status'	=> 'yes',
			'save_mode'		=> 'publish',
			'first_save'	=> 'yes',
			'content'		=> '{"order":{},"images":{"":""},"branding":{},"first_save":"yes","unpublished_changes":false}',
			'wp_template'	=> false,
		);
		// encode
		$project['post'] = json_encode($project['post']);
		$project['settings'] = json_encode($project['settings']);
		return $project;
	}
}

new Onboarding;