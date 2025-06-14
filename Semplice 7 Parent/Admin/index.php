<?php
	// use
	use Semplice\Helper\Get;
	use Semplice\Helper\License;
?>

<!-- semplice -->
<div id="semplice" class="semplice-init" data-edition="<?php echo SEMPLICE_EDITION; ?>">
	<!-- admin wrapper -->
	<div id="semplice-wrapper">
		<header class="admin-menu">
			<a class="logo" href="#dashboard" id="nav-dashboard"><?php echo Get::svg('admin', 'logo/eagle'); ?></a>
			<nav class="primary">
				<ul>
					<li><a href="#content/pages/1" id="nav-pages">Pages</a></li>
					<li><a href="#content/projects/1" id="nav-projects">Projects</a></li>
					<li><a href="#customize/grid" id="nav-customize">Customize</a></li>
					<li><a href="#settings/general" id="nav-settings"><?php echo Get::svg('admin', 'nav/settings'); ?></a></li>
				</ul>
			</nav>
			<div class="actions">
				<div class="states" data-state="activate">
					<a class="update" href="#settings/license"><?php echo Get::svg('admin', 'nav/update'); ?> Update Available</a>
					<a class="activate" href="#settings/license">Activate License</a>
				</div>
				<ul>
					<?php //License::header_check(); ?>
					<li><a class="preview-site" href="<?php echo home_url(); ?>" target="_blank"><?php echo Get::svg('admin', 'nav/preview'); ?></a></li>
					<li class="spacer"></li>
					<li><a class="exit click-handler" data-handler="run" data-action-type="helper" data-setting-type="core" data-action="exit"><?php echo Get::svg('admin', 'nav/exit'); ?></a></li>
				</ul>
			</div>
			<nav class="customize"><?php echo Get::customize_nav('customize', 'admin', array('cursor')); ?></nav>
		</header>
		<section class="nav-spacer"><!-- spacer for the fixed nav --></section>
		<div id="semplice-content"><!-- dynamic content --></div>
	</div>
	<!-- admin templates -->
	<div class="admin-templates"><?php foreach (glob(SEMPLICE_DIR  . '/admin/templates/*.php') as $filename) { include $filename; } ?></div>
	<!-- admin dialogs -->
	<div class="admin-dialogs"><?php foreach (glob(SEMPLICE_DIR  . '/admin/dialogs/*.php') as $filename) { include $filename; } ?></div>
	<!-- semplice editor -->
	<?php require SEMPLICE_DIR . '/editor/index.php'; ?>
	<!-- admin intro -->
	<div class="admin-init-transition">
		<div class="bg-transition"></div>
		<div class="logo-transition">
			<div class="inner logo-transition-inner">
				<div class="admin-transition-logo">
					<?php echo Get::svg('admin', 'logo/admin_transition_logo'); ?>
				</div>
				<div class="admin-transition-word">		
					<?php echo Get::svg('admin', 'logo/admin_transition_word'); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- loader -->
	<div class="semplice-loader">
		<?php echo Get::svg('admin', 'loader'); ?>
	</div>
	<!-- edit popup -->
	<edit-popup>
		<ep-picker-holder></ep-picker-holder>
		<ep-help></ep-help>
		<ep-switch></ep-switch>
		<ep-dock></ep-dock>
		<ep-content></ep-content>
		<ep-size class="has-tooltip" data-tooltip="Resize" data-tooltip-settings="top,center,auto" data-tooltip-theme="inverted"><?php echo Get::svg('admin', 'edit-popup/resize'); ?></ep-size>
	</edit-popup>
	<!-- tooltip -->
	<div id="smp-tooltip">Tooltip</div>
	<!-- onboarding progress -->
	<div id="onboarding-progress">
		<div class="ob-progress-bg"></div>
		<div class="ob-progress"></div>
	</div>
</div>