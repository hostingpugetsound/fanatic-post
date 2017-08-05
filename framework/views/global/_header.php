<?php

// =============================================================================
// VIEWS/GLOBAL/_HEADER.PHP
// -----------------------------------------------------------------------------
// Declares the DOCTYPE for the site and include the <head>.
// =============================================================================

?>

	<!DOCTYPE html>
	<!--[if IE 9]><html class="no-js ie9" <?php language_attributes(); ?>><![endif]-->
	<!--[if gt IE 9]><!-->
	<html class="no-js" <?php language_attributes(); ?>>
	<!--<![endif]-->

	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0 minimal-ui" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<title>
			<?php wp_title(''); ?>
		</title>
		<?php do_action('fsu_insert_fb_in_head');?>
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

			<?php wp_head(); ?>
            <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/style-andy.css" />
            <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/css/tooltipster.css" />

            <script src="https://use.typekit.net/cqk3esy.js"></script>
            <script>try{Typekit.load({ async: true });}catch(e){}</script>
            <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.tooltipster.min.js"></script>
            <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/date.js"></script>

        <?php if( is_singular('team') ) { # for favorites ?>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/favorites.js"></script>
        <?php } ?>
	</head>

	<body <?php body_class(); ?>>
		<?php do_action( 'x_before_site_begin' ); ?>
			<div id="top" class="site">
				<?php do_action( 'x_after_site_begin' ); ?>

