<?php

// namespace
namespace Semplice\Admin;

// use
use Semplice\Helper\Basic;

// -----------------------------------------
// semplice post settings
// -----------------------------------------

class PostSettings {

	// -----------------------------------------
	// public vars
	// -----------------------------------------

	public static $db;

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {
		// database
		global $wpdb;
		self::$db = $wpdb;
	}

	// -----------------------------------------
	// save
	// -----------------------------------------

	public static function save($request, $id, $post_type) {
		// publish settings
		$args = array();
		// get settings json
		$settings_json = Basic::check_slashes($request['settings']);
		// is template?
		if(Basic::boolval($request['wp_template'])) {
			// save template settings
			update_option('semplice_template_' . $id . '_settings', $settings_json);
		} else {
			// save post settings in post meta
			update_post_meta($id, '_semplice_post_settings', wp_slash($settings_json), '');
			// vars
			$settings = json_decode($settings_json, true);
			$images = (isset($request['images']) && !empty($request['images'])) ? $request['images'] : false;
			// save admin images
			if($images) {
				update_option('semplice_admin_images', $request['images']);
			}
			// save seo
			if(isset($settings['seo']) && is_array($settings['seo'])) {
				foreach ($settings['seo'] as $key => $value) {
					update_post_meta($id, $key, wp_slash($value), '');
				}	
			}
			// get post
			$post = get_post($id);
			// apply post settings
			if(!empty($settings['meta'])) {
				$args['post_title'] = $settings['meta']['post_title'];
				// page slug
				if($post->post_name != $settings['meta']['permalink']) {
					$args['post_name'] = wp_unique_post_slug(sanitize_title($settings['meta']['permalink']), $id, 'publish', $post_type, 0);
				}
				// has categories and is not post type page
				if($post_type != 'page') {
					// set categories
					if(!empty($settings['meta']['categories'])) {
						wp_set_post_categories($id, $settings['meta']['categories'], false);
					} else {
						wp_set_post_categories($id, array(), false);
					}
				}
			}
		}
		// return args
		return $args;
	}
}
new PostSettings;
?>