<?php

// namespace
namespace Semplice\Admin\Customize;

// use
use Semplice\Admin\Customize;
use Semplice\Helper\Basic;
use Semplice\Helper\Get;
use Semplice\Helper\PostQueries;

// -----------------------------------------
// customize project nav
// -----------------------------------------

class Projectnav extends Customize {

	// -----------------------------------------
	// constructor
	// -----------------------------------------

	public function __construct() {}

	// -----------------------------------------
	// init
	// -----------------------------------------

	public function init() {
		// return
		return array(
			'css' => $this->css(false),
			'html' => '
				<div class="admin-column browser-pb" data-xl-width="12">
					<div class="smp-browser" data-view="navbar">
						<div class="inner">
							<div id="content-holder">
							' . $this->html('nextprev', false, false) . $this->html('projectpanel', false, false) . '
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

	public function html($mode, $is_frontend, $post_id) {
		// vars
		$output = '';
		// get project panel
		$projectnav = Get::customize('projectpanel');
		// attributes
		extract(shortcode_atts(
			array(
				'visibility'				=> 'visible',
				'images_per_row'			=> 2,
				'width'						=> 'container',
				'title_visibility'			=> 'visible',
				'meta_visibility'			=> 'both',
				'panel_title_font'			=> 'regular',
				'title_font'				=> 'regular',
				'category_font'				=> 'regular',
				'panel_label'				=> 'Selected Works',
				'gutter'					=> 'yes',
				'hide_active_project'		=> 'no',
				'np_visibility'				=> 'visible',
				'np_text_visibility'		=> 'visible',
				'np_image_visibility'		=> 'hidden',
				'np_font'					=> 'regular',
				'np_font_sub'				=> 'regular',
				'np_width'					=> 'container',
				'np_gutter'					=> 'yes',
				'np_justify'				=> 'edge',
				'np_alignment'				=> 'middle',
				'np_sep_visibility'			=> 'hidden',
				'np_label'					=> 'nextprev_above_title',
				'np_arrow'					=> 'default',
				'np_prefix'					=> 'visible',
				'np_text_position'			=> 'overlay',
				'np_image_scale'			=> 'cover',
				'np_next_only'				=> 'disabled',
				'np_mouseover_effect'		=> 'none',
				'np_mouseover_title_fade'	=> 'none',
			), $projectnav)
		);
		// get portfolio order
		$portfolio_order = json_decode(get_option('semplice_portfolio_order'));
		// get projects
		$projects = PostQueries::get_projects($portfolio_order, false, -1, false, true, 'publish');
		// columns per row
		$columns_per_row = array('lg' => 2, 'md' => 3, 'sm' => 4, 'xs' => 6);
		foreach ($columns_per_row as $bp => $value) {
			if(isset($projectnav['images_per_row_' . $bp])) {
				$columns_per_row[$bp] = $projectnav['images_per_row_' . $bp];
			}
		}
		// items
		$output = '';
		// are there any published projects
		if($mode == 'projectpanel') {
			if($is_frontend) {
				if(!empty($projects)) {
					foreach ($projects as $key => $project) {
						//show project
						$show_project = ($hide_active_project == 'yes' && $project['post_id'] == $post_id) ? false : true;
						// masonry items open
						if($show_project) {
							$output .= '
								<smp-column class="pp-thumb" data-xl-width="' . $images_per_row . '" data-lg-width="' . $columns_per_row['lg'] . '" data-md-width="' . $columns_per_row['md'] . '" data-sm-width="' . $columns_per_row['sm'] . '" data-xs-width="' . $columns_per_row['xs'] . '">
									<a href="' . $project['permalink'] . '" title="' . $project['post_title'] . '"><img src="' . $project['pp_thumbnail']['src'] . '" width="' . $project['pp_thumbnail']['width'] . '" height="' . $project['pp_thumbnail']['height'] . '"></a>
									<div class="pp-title"><a data-font="' . $title_font . '" href="' . $project['permalink'] . '" title="' . $project['post_title'] . '">' . $project['post_title'] . '</a><span data-font="' . $category_font . '">' . $project['project_type'] . '</span></div>
								</smp-column>
							';
						}
					}
				} else {
					$output = '<div class="empty-portfolio"><img src="' . SEMPLICE_URI . '/assets/images/admin/customize/projectnav/noposts.svg" alt="no-posts"><h3>Looks like you have an empty Portfolio. Please note that only<br />published projects are visible in the project panel.</h3></div>';
				}
			} else {
				for($i=1; $i<=6; $i++) {
					$output .= '
						<smp-column class="pp-thumb" data-xl-width="' . $images_per_row . '" data-lg-width="' . $columns_per_row['lg'] . '" data-md-width="' . $columns_per_row['md'] . '" data-sm-width="' . $columns_per_row['sm'] . '" data-xs-width="' . $columns_per_row['xs'] . '">
							<a><img src="' . SEMPLICE_URI . '/assets/images/admin/customize/projectnav/project-' . $i . '.svg"></a>
							<div class="pp-title"><a data-font="' . $title_font . '">Project No. ' . $i . '</a><span data-font="' . $category_font . '">Webdesign</span></div>
						</smp-column>
					';
				}
			}
			// is visible? if not return nothing
			if($visibility != 'hidden' && $is_frontend && get_post_type($post_id) == 'project' || !$is_frontend) {
				// html
				return '
					<smp-section class="project-panel" data-pp-gutter="' . $gutter . '" data-pn-layout="' . $width . '" data-pp-visibility="' . $visibility . '">
						<smp-container data-title-visibility="' . $title_visibility . '" data-meta-visibility="' . $meta_visibility . '">
							<smp-row>
								<smp-column data-xl-width="12">
									<p class="panel-label"><span data-font="' . $panel_title_font . '">' . $panel_label . '</span></p>
								</smp-column>
							</smp-row>
							<smp-row class="pp-thumbs">
								' . $output . '
							</smp-row>
						</smp-container>
					</smp-section>
				';
			} else {
				return '';
			}
		} else {
			// vars
			$np_classes = '';
			$pos = 0;
			$next = 0;
			$prev = 0;
			$prev_label = 'Prev';
			$next_label = 'Next';
			$prev_type = 'Project Type';
			$next_type = 'Project Type';
			$prev_image_src = SEMPLICE_URI . '/assets/images/admin/customize/projectnav/prev.svg';
			$next_image_src = SEMPLICE_URI . '/assets/images/admin/customize/projectnav/next.svg';
			// is not editor?
			if($is_frontend) {
				// check if there are projects
				if(!empty($projects) && count($projects) >= 3) {
					// is this project published yet?
					if(get_post_status($post_id) == 'publish') {
						// get published posts
						$published_posts = array();
						foreach ($projects as $key => $project) {
							$published_posts[] = $project['post_id'];
						}
						// if this post is not in published posts add it to published posts no matter its project nav visibility if no
						if(!in_array($post_id, $published_posts)) {
							$published_posts[] = $post_id;
						}
						// get pos
						$pos = array_search($post_id, $published_posts);
						// is first?
						if($pos == 0) {
							end($published_posts);
							$prev = $published_posts[key($published_posts)];
						} else {
							$prev = $published_posts[$pos - 1];
						}
						// is last?
						if(!isset($published_posts[$pos + 1])) {
							$next = $published_posts[0];
						} else {
							$next = $published_posts[$pos + 1];
						}
						// get labels
						if(strpos($np_label, 'title') !== false) {
							$prev_label = get_the_title($prev);
							$next_label = get_the_title($next);
						}
						// next / prev pos
						$prev_pos = array_search($prev, $published_posts);
						$next_pos = array_search($next, $published_posts);
						// get images
						if($np_image_visibility == 'visible') {
							// prev image
							if(is_array($projects[$prev_pos]['nextprev_thumbnail'])) {
								$prev_image_src = $projects[$prev_pos]['nextprev_thumbnail']['src'];
							}
							// next image
							if(is_array($projects[$next_pos]['nextprev_thumbnail'])) {
								$next_image_src = $projects[$next_pos]['nextprev_thumbnail']['src'];
							}
						}
						// get project type
						$prev_type = $projects[$prev_pos]['project_type'];
						$next_type = $projects[$next_pos]['project_type'];
					} else {
						$np_classes .= ' nextprev-error';
					}
				} else {
					$np_classes .= ' nextprev-error';
				}
			} else {
				// change labels for admin
				if(strpos($np_label, 'title') !== false) {
					$prev_label = 'My previous project';
					$next_label = 'My next project';
				}
			}
			// wrap images
			$background_prev = '';
			$background_next = '';
			if($np_image_visibility == 'visible' || false === $is_frontend) {
				$background_prev = ' style="background-image: url(' . $prev_image_src . ');"';
				$background_next = ' style="background-image: url(' . $next_image_src . ');"';
			}
			// output
			if(!$is_frontend || $is_frontend && $np_visibility == 'visible' && get_post_type($post_id) == 'project') {
				$output = '
					<smp-section class="semplice-next-prev"  data-np-visibility="' . $np_visibility . '" data-np-gutter="' . $np_gutter . '" data-pn-layout="' . $np_width . '" data-np-sep-visibility="' . $np_sep_visibility . '" data-np-prefix-visibility="' . $np_prefix . '" data-np-image-visibility="' . $np_image_visibility . '" data-np-text-visibility="' . $np_text_visibility . '" data-np-text-position="' . $np_text_position . '" data-np-mouseover="' . $np_mouseover_effect . '">
						<smp-container>
							<smp-row>
								<smp-column data-xl-width="12">
									<div class="np-inner' . $np_classes . '" data-np-justify="' . $np_justify . '" data-np-alignment="' . $np_alignment . '" data-np-image-scale="' . $np_image_scale . '" data-np-next-only="' . $np_next_only . '">
										<a class="semplice-prev np-link" href="' . get_permalink($prev) . '">
											<div class="np-bg"' . $background_prev . '></div>
											' . $this->label('prev', $np_arrow, $np_label, $prev_label, $prev_type, $np_mouseover_title_fade, $np_font, $np_font_sub) . '
										</a>
										<a class="semplice-next np-link" href="' . get_permalink($next) . '">
											<div class="np-bg"' . $background_next . '></div>
											' . $this->label('next', $np_arrow, $np_label, $next_label, $next_type, $np_mouseover_title_fade, $np_font, $np_font_sub) . '
										</a>
									</div>
								</smp-column>
							</smp-row>
						</smp-container>
						<div class="nextprev-seperator"></div>
					</smp-section>
				';
			}
			// return
			return $output;
		}
	}

	// -----------------------------------------
	// css
	// -----------------------------------------

	public function css($is_frontend) {
		// vars
		$projectnav = Get::customize('projectpanel');
		// attributes
		extract(shortcode_atts(
			array(
				'background'					=> '#f5f5f5',
				'panel_padding'					=> '2.5rem',
				'panel_title_color'				=> '#000000',
				'panel_title_fontsize'			=> '1.777777777777778rem',
				'panel_title_text_transform'	=> 'none',
				'panel_padding_left'			=> '0rem',
				'panel_padding_bottom'			=> '1.666666666666667rem',
				'panel_text_align'				=> 'left',
				'title_padding_bottom'			=> '1.666666666666667rem',
				'title_color'					=> '#000000',
				'title_fontsize'				=> '0.7222222222222222rem',
				'title_text_transform'			=> 'none',
				'title_padding_top'				=> '0.5555555555555556rem',
				'category_color'				=> '#999999',
				'category_fontsize'				=> '0.7222222222222222rem',
				'category_text_transform'		=> 'none',
				'radius'						=> '0rem',
				'np_background'					=> '#ffffff',
				'np_height'						=> '10rem',
				'np_padding_ver'				=> '0rem',
				'np_padding_hor'				=> '0rem',
				'np_color'						=> '#000000',
				'np_fontsize'					=> '1.555555555555556rem',
				'np_text_transform'				=> 'none',
				'np_letter_spacing'				=> '0rem',
				'np_color_sub'					=> '#aaaaaa',
				'np_fontsize_sub'				=> '0.7777777777777778rem',
				'np_text_transform_sub'			=> 'uppercase',
				'np_letter_spacing_sub'			=> '1px',
				'np_spacing_sub'				=> '2px',
				'np_sep_width'					=> 1,
				'np_sep_spacing'				=> '1.666666666666667rem',
				'np_sep_background'				=> '#000000',
				'np_padding_outer_top'			=> '0rem',
				'np_padding_outer_bottom'		=> '0rem',
				'np_mouseover_effect'			=> 'none',
				'np_mouseover_background'		=> '#ffffff',
				'np_mouseover_color'			=> '#000000',
				'np_mouseover_color_sub'		=> '#000000',
				'np_mouseover_so_opacity'		=> .4,
				'np_mouseover_so_scale'			=> 7,
				'np_mouseover_dimdown_opacity' 	=> .4,
			), $projectnav)
		);
		// if letter spacing add negative margin
		$negative_margin = '';
		if(strpos($np_letter_spacing, '-') === false) {
			$negative_margin .= 'margin-right: -' . $np_letter_spacing . ';'; 
		} else {
			$negative_margin .= 'margin-right: ' . str_replace('-', '', $np_letter_spacing) . ';'; 
		}
		// seperator positiong
		$sep_pos = ($np_sep_width > 1) ? round($np_sep_width / 2) : 0;
		// css
		$css = '
			.project-panel {
				background: ' . $background . ';
				padding: ' . $panel_padding . ' 0rem;
			}
			[data-pp-gutter="no"] .project-panel .pp-thumbs,
			.project-panel .pp-thumbs {
				margin-bottom: -' . $title_padding_bottom . ';
			}
			.project-panel .pp-thumb img {
				border-radius: ' . $radius . ';
			}
			#content-holder .panel-label, .panel-label {
				color: ' . $panel_title_color . ';
				font-size: ' . $panel_title_fontsize . ';
				text-transform: ' . $panel_title_text_transform . ';
				padding-left: ' . $panel_padding_left . ';
				padding-bottom: ' . $panel_padding_bottom . ';
				text-align: ' . $panel_text_align . ';
				line-height: 1;
			}
			.project-panel .pp-title {
				padding: ' . $title_padding_top . ' 0rem ' . $title_padding_bottom . ' 0rem;
			}
			.project-panel .pp-title a {
				color: ' . $title_color . '; 
				font-size: ' . $title_fontsize . '; 
				text-transform: ' . $title_text_transform . ';
			} 
			.project-panel .pp-title span {
				color: ' . $category_color . ';
				font-size: ' . $category_fontsize . ';
				text-transform: ' . $category_text_transform . ';
			}
			.semplice-next-prev {
				background: ' . $np_background . ';
				padding: ' . $np_padding_outer_top . ' 0rem ' . $np_padding_outer_bottom . ' 0rem;
			}
			.semplice-next-prev .np-inner {
				height: ' . $np_height . ';
			}
			.semplice-next-prev .np-inner .np-link .np-prefix,
			.semplice-next-prev .np-inner .np-link .np-label {
				color: ' . $np_color . ';
				font-size: ' . $np_fontsize . ';
				text-transform: ' . $np_text_transform . ';
				letter-spacing: ' . $np_letter_spacing . ';
			}
			.semplice-next-prev .np-inner .np-link .np-text-above {
				padding-bottom: ' . $np_spacing_sub . ';
			}
			.semplice-next-prev .np-inner .np-link .np-label-above {
				color: ' . $np_color_sub . ';
				font-size: ' . $np_fontsize_sub . ';
				text-transform: ' . $np_text_transform_sub . ';
				letter-spacing: ' . $np_letter_spacing_sub . ';
			}
			.semplice-next-prev .np-inner .np-link .np-text {
				padding: ' . $np_padding_ver . ' ' . $np_padding_hor . ';
			}
			.semplice-next .np-text {
				' . $negative_margin . '
			}
			.semplice-next-prev .nextprev-seperator {
				width: ' . $np_sep_width . 'px;
				margin: ' . $np_sep_spacing . ' -' . $sep_pos . 'px;
				background: ' . $np_sep_background . ';
			}
		';
		// mobile atts
		$mobile_atts = array(
			'panel_padding' => array('attribute' => 'padding', 'target' => '.project-panel'),
			'title_padding_bottom' => array('attribute' => 'margin-bottom', 'target' => '.pp-thumbs'),
			'panel_title_fontsize' => array('attribute' => 'font-size', 'target' => '#content-holder .panel-label, '),
			'panel_padding_left ' => array('attribute' => 'padding-left', 'target' => '#content-holder .panel-label'),
			'panel_padding_bottom ' => array('attribute' => 'padding-bottom', 'target' => '#content-holder .panel-label'),
		);	
		// get breakpoints
		$breakpoints = Get::breakpoints(false);
		// iterate breakpoints
		foreach ($breakpoints as $breakpoint => $width) {
			// breakpoint css
			$breakpoint_css = '';
			$sep_pos_changed = false;
			if(isset($projectnav['panel_padding_' . $breakpoint])) {
				$breakpoint_css .= '.project-panel { padding: ' . $projectnav['panel_padding_' . $breakpoint] . ' 0rem; }';
			}
			if(isset($projectnav['title_padding_bottom_' . $breakpoint])) {
				$breakpoint_css .= '.pp-thumbs { margin-bottom: -' . $projectnav['title_padding_bottom_' . $breakpoint] . '; }';
				$breakpoint_css .= '.project-panel .pp-title { padding-bottom: ' . $projectnav['title_padding_bottom_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['title_padding_top_' . $breakpoint])) {
				$breakpoint_css .= '.project-panel .pp-title { padding-top: ' . $projectnav['title_padding_top_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['panel_title_fontsize_' . $breakpoint])) {
				$breakpoint_css .= '#content-holder .panel-label { font-size: ' . $projectnav['panel_title_fontsize_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['panel_padding_left_' . $breakpoint])) {
				$breakpoint_css .= '#content-holder .panel-label { padding-left: ' . $projectnav['panel_padding_left_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['panel_padding_bottom_' . $breakpoint])) {
				$breakpoint_css .= '#content-holder .panel-label { padding-bottom: ' . $projectnav['panel_padding_bottom_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['title_fontsize_' . $breakpoint])) {
				$breakpoint_css .= '.project-panel .pp-title a { font-size: ' . $projectnav['title_fontsize_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['category_fontsize_' . $breakpoint])) {
				$breakpoint_css .= '.project-panel .pp-title span { font-size: ' . $projectnav['category_fontsize_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_padding_outer_top_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev { padding-top: ' . $projectnav['np_padding_outer_top_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_padding_outer_bottom_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev { padding-bottom: ' . $projectnav['np_padding_outer_bottom_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_height_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner { height: ' . $projectnav['np_height_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_fontsize_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-prefix, .semplice-next-prev .np-inner .np-link .np-label { font-size: ' . $projectnav['np_fontsize_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_letter_spacing_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-prefix, .semplice-next-prev .np-inner .np-link .np-label { letter-spacing: ' . $projectnav['np_letter_spacing_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_spacing_sub_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-text-above { padding-bottom: ' . $projectnav['np_spacing_sub_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_fontsize_sub_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-label-above { font-size: ' . $projectnav['np_fontsize_sub_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_letter_spacing_sub_' . $breakpoint])) {
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-label-above { letter-spacing: ' . $projectnav['np_letter_spacing_sub_' . $breakpoint] . '; }';
			}
			if(isset($projectnav['np_padding_ver_' . $breakpoint]) || isset($projectnav['np_padding_hor_' . $breakpoint])) {
				$nppv = isset($projectnav['np_padding_ver_' . $breakpoint]) ? $projectnav['np_padding_ver_' . $breakpoint] : $np_padding_ver;
				$npph = isset($projectnav['np_padding_hor_' . $breakpoint]) ? $projectnav['np_padding_hor_' . $breakpoint] : $np_padding_hor;
				$breakpoint_css .= '.semplice-next-prev .np-inner .np-link .np-text { padding: ' . $nppv . ' ' . $npph . '; }';
			}
			if(isset($projectnav['np_sep_width_' . $breakpoint])) {
				if($projectnav['np_sep_width_' . $breakpoint] > 1) {
					$sep_pos = round($projectnav['np_sep_width_' . $breakpoint] / 2);
					$sep_pos_changed = true;
				}
				$breakpoint_css .= '.semplice-next-prev .nextprev-seperator { width: ' . $projectnav['np_sep_width_' . $breakpoint] . 'px; }';
			}
			if(isset($projectnav['np_sep_spacing_' . $breakpoint]) || true === $sep_pos_changed) {
				$npsp = isset($projectnav['np_sep_spacing_' . $breakpoint]) ? $projectnav['np_sep_spacing_' . $breakpoint] : $np_sep_spacing;
				$breakpoint_css .= '.semplice-next-prev .nextprev-seperator { margin: ' . $npsp . ' -' . $sep_pos . 'px; }';
			}
			// only add breakpoint if css is not empty
			if(!empty($breakpoint_css)) {
				// breakpoint open
				$css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . $breakpoint_css . ' }';
			}
		}
		// mouseover
		if($is_frontend) {
			// basic mouseover
			$css .= '
				.np-link:hover {
					background: ' . $np_mouseover_background . ';
				}
				.np-link:hover .np-text .np-label,
				.np-link:hover .np-text .np-prefix {
					color: ' . $np_mouseover_color . ' !important;
				}
				.np-link:hover .np-label-above {
					color: ' . $np_mouseover_color_sub . ' !important;
				}
			';
			// scale or dimdown
			$mobile_detect = Basic::mobile_detect();
			if(!$mobile_detect->isMobile()) {
				switch($np_mouseover_effect) {
					case 'scale-opacity':
						$css .= '
							.np-inner:hover .np-link { opacity: ' . $np_mouseover_so_opacity . '; }
							.np-inner .np-link:hover .np-bg { transform: scale(' . (($np_mouseover_so_scale / 100) + 1) . '); }
						';
					break;
					case 'dim-down':
						$css .= '
							.np-link:hover .np-bg { opacity: ' . $np_mouseover_dimdown_opacity . '; }
						';
					break;
				}
			}
		}
		// return
		return $css;
	}

	// -----------------------------------------
	// label
	// -----------------------------------------

	public function label($state, $arrow, $label, $title, $type, $fade, $font, $font_sub) {
		// arrow
		$arrows = array('prev' => '&lsaquo;', 'next' => '&rsaquo;');
		if($arrow == 'advanced') {
			$arrows = array('prev' => '&larr;', 'next' => '&rarr;');
		}
		// default content
		$defaults = array(
			'prev' => array(
				'label' 	=> 'Previous',
				'prefix' 	=> '<span>' . $arrows['prev'] . '</span>',
			),
			'next' => array(
				'label' 	=> 'Next',
				'prefix' 	=> '<span>' . $arrows['next'] . '</span>',
			),
		);
		// labels
		switch ($label) {
			case 'arrows':
				$defaults[$state]['label'] = '';
			break;
			case 'nextprev':
				$defaults[$state]['prefix'] = '';
			break;
			case 'title':
				$defaults[$state]['prefix'] = '';
				$defaults[$state]['label'] = $title;
			break;
			case 'arrows_title':
				$defaults[$state]['label'] = $title;
			break;
			case 'type_above_title':
				$defaults[$state]['label'] = $type;
			break;
		}
		// return
		if(strpos($label, 'above') !== false) {
			return '
				<div class="np-text">
					<div class="np-text-inner np-above ' . $fade . '">
						<div class="np-text-above">
							<span class="np-label-above" data-font="' . $font_sub . '">' . $defaults[$state]['label'] . '</span>
						</div>
						<div class="np-text-main">
							<span class="np-label" data-font="' . $font . '">' . $title . '</span>
						</div>
					</div>
				</div>
			';
		} else {
			return '
				<div class="np-text">
					<div class="np-text-inner ' . $fade . '">
						<span class="np-prefix" data-font="' . $font . '">' . $defaults[$state]['prefix'] . '</span>
						<span class="np-label" data-font="' . $font . '">' . $defaults[$state]['label'] . '</span>
					</div>
				</div>
			';
		}
	}
}

Customize::$setting['projectnav'] = new Projectnav;