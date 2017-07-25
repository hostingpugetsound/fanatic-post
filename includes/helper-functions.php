<?php
function crb_mobile_login() {
	ob_start();
	?>
	<ul class="menu">
		<li>
			<a href="<?php echo home_url('/'); ?>" style="margin-right:8px;">Home</a>
		</li>
		<li>
			<a href="<?php echo home_url('/fanaticsu/profile/'); ?>" style="margin-right:8px;">My Account</a>
		</li>
		<li>
			<a href="<?php echo home_url('/search/'); ?>" style="margin-right:8px;">Search</a>
		</li>
	</ul><!-- /.menu -->
	<?php
	return ob_get_clean();
}


function crb_menus() {
	$menus = carbon_get_theme_option('crb_header_menus');
	?>

	<?php if (!empty($menus)) { ?>
		<div class="desktop-menus clearfix">
			<?php
			foreach ($menus as $m) {
				if (is_nav_menu($m)) {
					wp_nav_menu(array(
						'menu'           => $m,
						'container'      => false,
						'fallback_cb'    => false,
						'menu_class'     => 'header-menu',
						'walker'         => new Crb_Header_Menu
					));
				}
			}
			?>
		</div><!-- /.desktop-menus -->
	
		<div class="mobile-menus cleafix">
			<div class="mobile-menus-wrapper">
				<?php 
				echo crb_mobile_login();
				foreach ($menus as $m) {
					if (is_nav_menu($m)) {
						wp_nav_menu(array(
							'menu' => $m,
							'container' => 'header-nav mobile',
							'container_class' => 'nav',
							'fallback_cb' => false,
							'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'walker' => new Crb_Header_Mobile_Menu
						));
					}
				}
				?>
			</div><!-- /.mobile-menus-wrapper -->
		</div><!-- /.mobile-menus hidden -->
		<?php
		
	} else {
		echo '<ul class="x-nav"><li><a href="' . home_url( '/' ) . 'wp-admin/admin.php?page=crbn-theme-options.php">Assign Menus</a></li></ul>';	
	}
}