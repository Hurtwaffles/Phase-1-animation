<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Basic;
use Semplice\Helper\Get;
use Semplice\Helper\Image;
use Semplice\Helper\Color;
use Semplice\Helper\Background;

// -----------------------------------------
// customize grid
// -----------------------------------------

class Thumbhover extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// options
		$options = Get::customize('thumbhover');
		// return
		return array(
			'css' => $this->css(false, $options, true, '', false),
			'html' => '
				<div class="admin-column browser-pb" data-xl-width="12">
					<div class="smp-browser" data-view="navbar">
						<div class="inner">
							<div id="content-holder">
								<smp-section>
									<smp-container>
										<smp-row>
											<smp-column data-xl-width="4">
												<smp-content class="empty-thumbnail"></smp-content>
											</smp-column>
											<smp-column data-xl-width="8">
												<smp-content>
													<div id="project-1337" class="masonry-item thumb">
														<div class="thumb-inner">
															<img src="' . SEMPLICE_URI . '/assets/images/admin/customize/thumbhover/thumbhover_1.jpg" alt="thumbnail">
															' . $this->html($options, 'noproject', true) . '
														</div>
													</div>
												</smp-content>
											</smp-column>
										</smp-row>
										<smp-row>
											<smp-column data-xl-width="7">
												<smp-content>
													<div id="project-1337" class="masonry-item thumb">
														<div class="thumb-inner">
															<img src="' . SEMPLICE_URI . '/assets/images/admin/customize/thumbhover/thumbhover_2.jpg" alt="thumbnail">
															' . $this->html($options, 'noproject', true) . '
														</div>
													</div>
												</smp-content>
											</smp-column>
											<smp-column data-xl-width="5">
												<smp-content class="empty-thumbnail"></smp-content>
											</smp-column>
										</smp-row>
									</smp-container
								</smp-section>
							</div>
						</div>
					</div>
				</div>
			'
		);
	}

	// -----------------------------------------
	// html
	// -----------------------------------------

	public function html($hover, $project, $is_frontend) {
		// mobile detect
		$mobile_detect = Basic::mobile_detect();
		// is frontend?
		if($is_frontend && !$mobile_detect->isMobile()) {
			// extract options
			extract(shortcode_atts(
				array(
					'hover_title_visibility'	=> 'hide-both',
					'hover_title_alignment' 	=> 'top-left',
					'hover_title_transition'	=> 'fade',
					'hover_title_font'	  		=> 'regular',
					'hover_category_font'	  	=> 'regular',
					'hover_bg_type'				=> 'img',
					'hover_title_color'			=> '#ffffff',
					'hover_category_color'		=> '#999999',
				), $hover)
			);
			// get global video hover
			$video_hover = '';
			if($hover_bg_type == 'vid' && $project != 'noproject') {
				$video_hover = Background::video($hover, 'frontend', false);
			}
			// is project?
			if(isset($project) && is_array($project)) {
				if(isset($project['thumb_hover'])) {	
					// title font family
					if(isset($project['thumb_hover']['hover_title_font'])) {
						$hover_title_font = $project['thumb_hover']['hover_title_font'];
					}
					// category font family
					if(isset($project['thumb_hover']['hover_category_font'])) {
						$hover_category_font = $project['thumb_hover']['hover_category_font'];
					}
					// title alignment
					if(isset($project['thumb_hover']['hover_title_alignment'])) {
						$hover_title_alignment = $project['thumb_hover']['hover_title_alignment'];
					}
					// visibility
					if(isset($project['thumb_hover']['hover_title_visibility'])) {
						$hover_title_visibility = $project['thumb_hover']['hover_title_visibility'];
					}
					// title transition
					if(isset($project['thumb_hover']['hover_title_transition'])) {
						$hover_title_transition = $project['thumb_hover']['hover_title_transition'];
					}
					// title color
					if(isset($project['thumb_hover']['hover_title_color'])) {
						$hover_title_color = $project['thumb_hover']['hover_title_color'];
					}
					// category color
					if(isset($project['thumb_hover']['hover_category_color'])) {
						$hover_category_color = $project['thumb_hover']['hover_category_color'];
					}
					// is video hover?
					if(isset($project['thumb_hover']['hover_bg_type']) && $project['thumb_hover']['hover_bg_type'] == 'vid') {
						$video_hover = '<smp-bg-video>' . Background::video($project['thumb_hover'], false, false) . '</smp-bg-video>';
					} else {
						$video_hover = '';
					}
				}
			} else {
				// set defaults
				$project = array(
					'post_title'   => 'Sample project title',
					'project_type' => 'Project Type'
				);
			}
			// return thumb hover html
			return '
				<div class="thumb-hover">
					' . $video_hover . '
					<div class="thumb-hover-meta ' . $hover_title_alignment . ' ' . $hover_title_visibility . ' ' . $hover_title_transition . '">
						<p>
							<span class="title" data-font="' . $hover_title_font . '"' . Color::has_gradient($hover_title_color, '') . '>' . $project['post_title'] . '</span><br />
							<span class="category" data-font="' . $hover_category_font. '"' . Color::has_gradient($hover_category_color, '') . '>' . $project['project_type'] . '</span>
						</p>			
					</div>
				</div>
			';
		}
	}

	// -----------------------------------------
	// css
	// -----------------------------------------

	public function css($id, $options, $is_global, $prefix_id, $is_editor) {
		// if not options take global
		$options = (!$options) ? Get::customize('thumbhover') : $options;
		// mobile detect
		$mobile_detect = Basic::mobile_detect();
		// make sure we are not in the editor and not on mobile
		if(!$is_editor && !$mobile_detect->isMobile()) {
			// id
			$id = ($is_global) ? '.thumb' : '#' . $id;
			// extract options
			extract( shortcode_atts(
				array(
					'hover_scale'					=> 'noscale',
					'hover_scale_amount'			=> 15,
					'hover_scale_duration'			=> .3,
					'hover_bg_type'					=> 'img',
					'hover_bg_color'				=> '#00000080',
					'hover_bg_color_opacity'		=> .5,
					'hover_bg_image'				=> false,
					'hover_bg_size'					=> 'auto',
					'hover_bg_position'				=> '0% 0%',
					'hover_bg_repeat'				=> 'no-repeat',
					'bg_video_opacity'				=> '1',
					'hover_title_color'				=> '#ffffff',
					'hover_title_font'				=> 'regular',
					'hover_title_fontsize'			=> '1.33rem',
					'hover_title_text_transform'	=> 'none',
					'hover_title_padding'			=> '2.22rem',
					'hover_category_fontsize'		=> '1rem',
					'hover_category_color'			=> '#999999',
					'hover_category_font'			=> 'regular',
					'hover_category_text_transform'	=> 'none',
					'hover_box_shadow'				=> 'none',
					'hover_box_shadow_duration'		=> .3,
				), $options )
			);
			// thumb hover start
			$css = $prefix_id . ' ' . $id . ' .thumb-inner .thumb-hover {';
			// hover background
			if($hover_bg_type != 'vid') {
				$image = Image::get($hover_bg_image, 'full');
				// has image?
				if($image) {
					// has gradient?
					$bg = (false !== strpos($hover_bg_color, 'gradient')) ? $hover_bg_color . ', ' : '';
					// add image
					$bg .= 'url(' . $image['src'] . ')';
					// css
					$css .= 'background-image: ' . $bg . ';';
				} else {
					if(false !== strpos($hover_bg_color, 'gradient')) {
						$css .= 'background-image: ' . $hover_bg_color . ';';
					} else {
						$css .= 'background-color: ' . $hover_bg_color . '; background-image: none;';
					}
				}
			}
			// hover bg size
			$css .= '
				background-size: ' . $hover_bg_size . ';
				background-position: ' . $hover_bg_position . ';
				background-repeat: ' . $hover_bg_repeat . ';
			';
			// thumb hover close
			$css .= '}';
			// box shadow
			if($hover_box_shadow != 'none') {

				$css .= $prefix_id . ' ' . $id . ' .thumb-inner { transition: box-shadow ' . $hover_box_shadow_duration . 's ease; }';
				$css .= $prefix_id . ' ' . $id . ' .thumb-inner:hover, .is-frontend ' . $prefix_id . ' ' . $id . ' .wrap-focus { box-shadow: ' . $hover_box_shadow . '; }'; 
			}
			// scale thumb on hover
			if($hover_scale == 'scale') {
				$css .= $prefix_id . ' ' . $id . ' .thumb-inner img { transition: all ' . $hover_scale_duration . 's ease; }';
				if($hover_scale_amount < 10) {
					$hover_scale_amount = '0' . $hover_scale_amount;
				}
				$css .= $prefix_id . ' ' . $id . ' .thumb-inner:hover img, .is-frontend ' . $prefix_id . ' ' . $id . ' .wrap-focus img { transform: scale(1.' . $hover_scale_amount . '); }';
			}
			// meta padding
			$css .= $prefix_id . ' ' . $id . ' .thumb-hover-meta { padding: ' . $hover_title_padding . '; }';
			// title options
			$css .= $prefix_id . ' ' . $id . ' .thumb-hover-meta .title { ' . Color::css($hover_title_color, true) . ' font-size: ' . $hover_title_fontsize . '; text-transform: ' . $hover_title_text_transform . '; }';
			// category options
			$css .= $prefix_id . ' ' . $id . ' .thumb-hover-meta .category { ' . Color::css($hover_category_color, true) . ' font-size: ' . $hover_category_fontsize . '; text-transform: ' . $hover_category_text_transform . '; }';
			// video opacity
			$css .= $prefix_id . ' ' . $id . ' video { opacity: ' . $bg_video_opacity . '; }';
			// return
			return $css;
		}
	}
}

Customize::$setting['thumbhover'] = new Thumbhover;