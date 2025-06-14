<?php

// namespace
namespace Semplice;

// use
use Semplice\Editor;
use Semplice\Editor\Blocks;
use Semplice\Editor\Components;
use Semplice\Helper\Basic;
use Semplice\Helper\Get;
use Semplice\Helper\Ram;
use Semplice\Helper\Covers;
use Semplice\Helper\PostQueries;
use Semplice\Admin\Customize;

// -----------------------------------------
// semplice content
// -----------------------------------------

class Content {

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
	// show content
	// -----------------------------------------

	public static function show($post_id, $what) {
		// vars
		$content = SEMPLICE_CONTENT;
		$main = '<main id="content-holder" data-active-post="' . $post_id . '">';
		$nav = $content['navbar']['html'];
		$intro = ($content['intro']) ? '<div id="semplice-intro">' . $content['intro']['html'] . '</div>' : '';
		// if password required show form instead of content (only on pages and projects)
		if($what != 'posts' && $what != 'taxonomy' && post_password_required()) {
			$content['html'] = get_the_password_form();
		}
		// echo content
		echo '
			' . (Basic::is_exclusive_nav() ? $nav . $main : $main) . '	
				<div id="content-' . $post_id . '" class="content-container active-content">
					' . (!Basic::is_exclusive_nav() ? $nav : '') . '
					' . $intro . '
					<div class="transition-wrap">
						<div class="sections">
							' . $content['html'] . '
						</div>
					</div>
				</div>
			</main>
		';
	}

	// -----------------------------------------
	// get
	// -----------------------------------------

	public static function get($post_id, $is_preview, $paged, $filter, $url, $is_spa) {
		// get is_semplice status
		$is_semplice = get_post_meta($post_id, '_is_semplice', true);
		// if taxonomy is set, set post id to posts
		if($filter && $filter['type'] != 'singlepost') {
			$post_id = 'posts';
		}
		// get post
		if($post_id == 'notfound') {
			$output = self::template('not-found', false);
		} else if($post_id == 'posts') {
			$output = self::posts($filter, $url, $paged, $is_spa);
		} else if($is_semplice) {
			$output = self::semplice($post_id, $url, $is_preview, $paged);
		} else {
			$output = self::post($filter, $post_id, $is_preview, $is_spa);
		}
		// set semplice status
		if(is_array($output)) {
			if($is_semplice) {
				$output['is_semplice'] = true;
			} else {
				$output['is_semplice'] = false;
			}
		}
		// add empty ram if not yet added
		$output['ram'] = (isset($output['ram']) && is_array($output['ram'])) ? $output['ram'] : '{}';
		// return output
		return $output;
	}

	// -----------------------------------------
	// posts
	// -----------------------------------------

	public static function posts($filter, $url, $paged, $is_spa) {
		// is taxonomy or search and has a custom template?
		$output = self::blog_template(false, $filter, $url, $paged, $is_spa);
		// add footer
		$output = self::footer($output, false);
		// return output
		return $output;
	}

	// -----------------------------------------
	// post
	// -----------------------------------------

	public static function post($filter, $post_id, $is_preview, $is_spa) {
		// is taxonomy or search and has a custom template?
		$output = self::blog_template($post_id, $filter, false, 1, $is_spa);
		// add footer
		$output = self::footer($output, false);
		// return
		return $output;
	}

	// -----------------------------------------
	// semplice
	// -----------------------------------------

	public static function semplice($post_id, $url, $is_preview, $page_num) {
		// get ram
		$ram = Ram::get($post_id, $is_preview);
		// is coverslider
		$is_coverslider = Basic::boolval(get_post_meta($post_id, '_is_coverslider', true));
		// check if ram is not empty
		if($ram !== null) {
			// get content from ram
			if($is_coverslider) {
				// get coverslider
				$output = Covers::coverslider($post_id, (isset($ram['coverslider'])) ? $ram['coverslider'] : array(), 'frontend');
				// empty ram
				$output['ram'] = $ram;
				// merge slider modules
				if(!empty($output['slider_ram'])) {
					$output['ram'] = array_merge($output['ram'], $output['slider_ram']);
				}
			} else if(isset($ram['order']) && !empty($ram['order']) && self::has_content($ram['order'], $ram)) {
				// add post id to ram
				$ram['post_id'] = $post_id;
				// add paged
				$ram['posts_filter'] = array(
					'type' 			=> 'page',
					'template_type'	=> false,
					'page_num' 		=> $page_num,
					'meta'			=> false,
					'url'			=> $url
				);
				// get editor output
				$output = Editor::output($ram, false, false, false);
				// add motion css
				if(!empty($output['motion_css'])) {
					$output['css'] .= $output['motion_css'];
				}
				// add ram
				$output['ram'] = $ram;
				// add footer
				$output = self::footer($output, $post_id);
			} else {
				$output = self::template('empty-semplice', $post_id);
			}
			// add branding
			if(isset($ram['branding'])) {
				$output['branding'] = $ram['branding'];
			}
		} else if($is_preview) {
			$output = self::template('empty-preview', $post_id);
		} else {
			$output = self::template('empty-semplice', $post_id);
		}
		// output
		return $output;
	}

	// -----------------------------------------
	// footer
	// -----------------------------------------

	public static function footer($output, $post_id) {
		// get post
		global $post;
		// vars
		$footer_id = false;
		$show_footer = true;
		$parent_motions = false;
		$blog_footer = false;
		$blog = Get::customize('blog');
		// add next prev and project panel before footer
		$output['html'] .= Customize::$setting['projectnav']->html('nextprev', true, $post_id);
		$output['html'] .= Customize::$setting['projectnav']->html('projectpanel', true, $post_id);
		// if semplice look in the post settings
		if($post_id || isset($output['template_type'])) {
			if(isset($output['template_type'])) {
				$post_settings = json_decode(get_option('semplice_template_' . $output['template_type'] . '_settings'), true);
			} else {
				$post_settings = json_decode(get_post_meta($post_id, '_semplice_post_settings', true), true);
			}
			if(is_array($post_settings)) {
				if(isset($post_settings['meta']['footer_visibility']) && !Basic::boolval($post_settings['meta']['footer_visibility'])) {
					$show_footer = false;
				} else if(isset($post_settings['meta']['footer']) && $post_settings['meta']['footer'] != 0 && !PostQueries::is_removed($post_settings['meta']['footer'])) {
					$footer_id = $post_settings['meta']['footer'];
				}
			}
		}
		// get global footer if not footer defined
		if(!$footer_id) {
			// get global footer
			$advanced = Get::customize('advanced');
			if(is_array($advanced)) {
				if(isset($advanced['global_footer']) && $advanced['global_footer'] != 0 && !PostQueries::is_removed($advanced['global_footer'])) {
					$footer_id = $advanced['global_footer'];
				}
			}
		}
		// get and add footer if visible
		if($footer_id && $show_footer) {
			// get ram
			$ram = Ram::get($footer_id, false);
			// is ram?
			if(null !== $ram) {
				// remove cover if there
				if(isset($ram['order']['cover'])) {
					unset($ram['order']['cover']);
				}
				// assign content
				$ram = Ram::change_ids($ram, false, false, 'section');
				// get content
				$content = Editor::output($ram, false, false, false);
				// add motion css if there
				if(!empty($content['motion_css'])) {
					$output['css'] .= $content['motion_css'];
				}
				// add to output
				foreach ($content as $type => $value) {
					if(isset($output[$type])) {
						if($type != 'images' && $type != 'module_css' && $type != 'slider_ram') {
							$output[$type] .= $content[$type];
						}
					}
				}
			}
		}
		// return
		return $output;
	}

	// -----------------------------------------
	// has content
	// -----------------------------------------

	public static function has_content($sections, $content) {
		// default set to false
		$has_content = false;
		$sections_count = count($sections);
		// more than 1 unit + cover?
		if($sections_count > 1) {
			$has_content = true;
		}
		// is only 1 section but has a visible cover?
		if($sections_count == 1 && isset($content['cover']) && isset($content['cover_visibility']) && $content['cover_visibility'] == 'visible') {
			$has_content = true;
		}
		// only 1 section but not a cover
		if($sections_count == 1 && !isset($sections['cover'])) {
			$has_content = true;
		}
		// return
		return $has_content;
	}

	// -----------------------------------------
	// system templates
	// -----------------------------------------

	public static function template($type, $post_id) {
		// get template
		$output = array(
			'ram'  => '{}', 
			'css'  => 'body { background: #eeeeee; }',
			'html' => Get::template($type, false),
		);
		// add sections wrap
		$output['html'] = '<smp-section class="smp-template">' . $output['html'] . '</smp-section>';
		// add footer
		$output = self::footer($output, $post_id);
		// return output
		return $output;	
	}

	// -----------------------------------------
	// blog template
	// -----------------------------------------

	public static function blog_template($post_id, $filter, $url, $page_num, $is_spa) {
		// vars
		$output = false;
		$template = false;
		$template_type = 'archive';
		$meta = false;
		// no filter means a single page that is not semplice
		$filter = (!$filter) ? array('type' => 'singlepost', 'meta' => $post_id) : $filter;
		// is taxonomy or search?
		if($filter) {
			// change template type
			if($filter['type'] == 'overview' || $filter['type'] == 'searchresults' || $filter['type'] == 'singlepost') {
				$template_type = $filter['type'];
			}
			// check if there is a custom template for $type
			if(get_option('semplice_template_' . $template_type)) {
				$template = get_option('semplice_template_' . $template_type);
				// init masterblocks if not empty
				$template = Components::get($template);
				// load content from post meta if not a preview
				$template = json_decode($template, true);
			}
		}
		// has template?
		if($template) {
			// add post id to ram
			$template['post_id'] = $post_id;
			// add paged
			$template['posts_filter'] = array(
				'type'			=> $filter['type'],
				'template_type' => $template_type,
				'page_num' 		=> $page_num,
				'meta' 			=> $filter['meta'],
				'url'			=> $url
			);
			// get content
			$output = Editor::output($template, false, false, false);
			// add ram
			$output['ram'] = $template;
			// add branding
			$output['branding'] = $template['branding'];
			// get settings
			$settings = json_decode(get_option('semplice_template_' . $template_type . '_settings'), true);
			// nav no reveal
			$no_reveal = ($is_spa) ? true : false;
			// get default navbar
			$output['navbar'] = Customize::$setting['navigations']->get(false, true, $no_reveal);
			// has navbar
			if($settings && is_array($settings)) {
				$meta = $settings['meta'];
				if(!isset($meta['navbar_visibility']) || Basic::boolval($meta['navbar_visibility'])) {
					if(isset($meta['navbar'])) {
						$output['navbar'] = Customize::$setting['navigations']->get($meta['navbar'], true, $no_reveal);
					}
				} else if(isset($meta['navbar_visibility']) && $meta['navbar_visibility'] == 'false') {
					$output['navbar'] = Customize::$setting['navigations']->empty();
				}
			}
			// add motion css
			if(!empty($output['motion_css'])) {
				$output['css'] .= $output['motion_css'];
			}
			// add type to output
			$output['template_type'] = $template_type;
		}
		// return output
		return $output;
	}
}
new Content;
?>