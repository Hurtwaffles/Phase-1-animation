<?php

// namespace
namespace Semplice\Admin\Customize\Navigations;

// use
use Semplice\Admin\Customize\Navigations;
use Semplice\Helper\Image;

// -----------------------------------------
// module
// -----------------------------------------

class ImageModule extends Navigations {

	// -----------------------------------------
	// output
	// -----------------------------------------

	public function output($options) {
		// id
		$img_id = (isset($options['content'])) ? $options['content'] : false;
		// get image or return default
		if($img_id) {
			$img = Image::get($img_id, 'full');
			$output = '<img src="' . $img['src'] . '" alt="' . $img['alt'] . '">';
		} else {
			$output = '<img src="' . SEMPLICE_URI . '/assets/images/admin/customize/navigations/logo.svg' . '">';
		}
		// link
		extract(shortcode_atts(
			array(
				'link' 		=> '',
				'link_type'	=> 'url',
				'link_page'	=> '',
				'link_project'=> '',
				'link_post'	=> '',
				'link_target' => '_blank',
			), $options )
		);
		if(self::$is_frontend) {
			if($link_type == 'url' && !empty($link)) {
				$output = '<a class="image-link" href="' . $link . '" target="' . $link_target . '">' . $output . '</a>';
			} else if($link_type == 'home') {
				$output = '<a class="image-link" href="' . home_url() . '" target="' . $link_target . '">' . $output . '</a>';
			} else if($link_type != 'url') {
				if(!empty($options['link_' . $link_type])) {
					$output = '<a class="image-link" href="' . get_permalink($options['link_' . $link_type]) . '" target="' . $link_target . '">' . $output . '</a>';
				}
			}
		}
		// return
		return $output;
	}
}

// instance
Navigations::$modules['image'] = new ImageModule;
?>