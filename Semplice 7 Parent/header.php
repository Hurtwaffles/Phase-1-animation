<?php
	use Semplice\Helper\Get;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-semplice="<?php echo SEMPLICE_VER ?>">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
		<style>html{margin-top:0px!important;}#wpadminbar{top:auto!important;bottom:0;}</style>
		<?php Get::header(); ?>
	</head>
	<body <?php body_class(); Get::body_bg(); ?> data-post-type="<?php if(is_object($post) && !is_404()) { echo get_post_type($post->ID); } ?>" data-post-id="<?php the_ID(); ?>">