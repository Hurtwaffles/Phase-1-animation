<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Basic;
use Semplice\Helper\Get;
use Semplice\Helper\Typography;

// -----------------------------------------
// customize advanced
// -----------------------------------------

class Cursor extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// return
		return '';
	}

	// -----------------------------------------
	// get
	// -----------------------------------------

	public function get($mode) {
		// vars
		$settings = Get::customize('cursor');
		$cursor = array('status' => false, 'classes' => 'semplice-cursor');
		$mobile_detect = Basic::mobile_detect();
		// check if mode option in the admin is already set
		if(!$mobile_detect->isMobile() && $settings && isset($settings['custom_cursor']) && $settings['custom_cursor'] == 'enabled') {
			if($mode == 'output') {
				echo '
					<div id="semplice-cursor">
						<div class="semplice-cursor-inner">
							<span class="cursor-text cursor-view">View</span>
							<span class="cursor-icon cursor-top-arrow">' . Get::svg('frontend', 'cursor/arrow_top') . '</span>
							<span class="cursor-icon cursor-bottom-arrow">' . Get::svg('frontend', 'cursor/arrow_bottom') . '</span>
							<span class="cursor-icon cursor-right-arrow">' . Get::svg('frontend', 'cursor/arrow_right') . '</span>
							<span class="cursor-icon cursor-left-arrow">' . Get::svg('frontend', 'cursor/arrow_left') . '</span>
							<span class="cursor-icon cursor-zoom-in">' . Get::svg('frontend', 'cursor/zoom_in') . '</span>
							<span class="cursor-icon cursor-ba">' . Get::svg('frontend', 'cursor/ba') . '</span>
							<span class="cursor-icon cursor-drag">' . Get::svg('frontend', 'cursor/drag') . '</span>
							<span class="cursor-icon cursor-play">' . Get::svg('frontend', 'cursor/play') . '</span>
							<span class="cursor-icon cursor-pause">' . Get::svg('frontend', 'cursor/pause') . '</span>
						</div>
					</div>
				';
			} else {
				// set status
				$cursor['status'] = true;
				// gallery next prev
				$gallery_next_prev = true;
				if(isset($settings['gallery_next_cursor_type']) && $settings['gallery_next_cursor_type'] == 'none' || isset($settings['gallery_prev_cursor_type']) && $settings['gallery_prev_cursor_type'] == 'none') {
					$gallery_next_prev = false;
				}
				if($gallery_next_prev) {
					$cursor['classes'] .= ' gallery-cursor';
				}
				// return
				return $cursor;
			}
		} else {
			return $cursor;
		}
	}

	// -----------------------------------------
	// css
	// -----------------------------------------

	public function css($is_frontend) {
		// output
		$css = '';
		// get cursor options
		$cursor = Get::customize('cursor');
		// selector
		$sel = '.semplice-cursor #semplice-cursor';
		// is array?
		if(is_array($cursor)) {
			// color
			if(isset($cursor['color'])) {
				$css .= $sel . ' .semplice-cursor-inner { background: ' . $cursor['color'] . '; }';
			}
			// size
			if(isset($cursor['size'])) {
				$css .= $sel . ' { width: ' . $cursor['size'] . 'px; height: ' . $cursor['size'] . 'px; }';
			}
			// blend mode
			if(isset($cursor['blur'])) {
				$css .= $sel . ' .semplice-cursor-inner { -webkit-backdrop-filter: blur(' . $cursor['blur'] . 'px); backdrop-filter: blur(' . $cursor['blur'] . 'px); }';
			}
			// bacgorund blur
			if(isset($cursor['blend_mode'])) {
				$css .= $sel . ' { mix-blend-mode: ' . $cursor['blend_mode'] . '; }';
			}
			// mouseover blend mode
			if(isset($cursor['mouseover_blend_mode'])) {
				$css .= $sel . '.mouseover-cursor { mix-blend-mode: ' . $cursor['mouseover_blend_mode'] . '; }';
			}
			// font family
			if(isset($cursor['font_family'])) {
				$css .= $sel . ' .semplice-cursor-inner .cursor-text { ' . Typography::get_font_family($cursor['font_family']) . ' }';
			}
			// font size
			if(isset($cursor['font_size'])) {
				$css .= $sel . ' .semplice-cursor-inner .cursor-text { font-size: ' . $cursor['font_size'] . '; }';
			}
			// letter spacing
			if(isset($cursor['letter_spacing'])) {
				$css .= $sel . ' .semplice-cursor-inner .cursor-text { letter-spacing: ' . $cursor['letter_spacing'] . '; margin-right: -' . $cursor['letter_spacing'] . '; }';
			}
			// text transform
			if(isset($cursor['text_transform'])) {
				$css .= $sel . ' .semplice-cursor-inner .cursor-text { text-transform: ' . $cursor['text_transform'] . '; }';
			}
			// text color
			if(isset($cursor['inner_color'])) {
				$css .= $sel . ' .semplice-cursor-inner .cursor-text { color: ' . $cursor['inner_color'] . '; }';
				$css .= $sel . ' .semplice-cursor-inner .cursor-icon svg { fill: ' . $cursor['inner_color'] . '; }';
			}
		}
		// output
		return $css;
	}
}

Customize::$setting['cursor'] = new Cursor;