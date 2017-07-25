<?php

// =============================================================================
// VIEWS/GLOBAL/_BRAND.PHP
// -----------------------------------------------------------------------------
// Outputs the brand.
// =============================================================================

$site_name        = get_bloginfo( 'name' );
$site_description = get_bloginfo( 'description' );
//$logo             = x_make_protocol_relative( x_get_option( 'x_logo' ) );
$sheetdir =            get_stylesheet_directory_uri();
$logo =					$sheetdir . "/framework/img/global/fanaticPostLogo.png";

$site_logo        = '<img src="' . $logo . '" alt="' . $site_description . '" style="width:253px !important; ">';

?>

<?php echo ( is_front_page() ) ? '<h1 class="visually-hidden">' . $site_name . '</h1>' : ''; ?>

<div class="burger hidden">
	<span class="slice"></span>
	<span class="slice"></span>
	<span class="slice"></span>
</div>

<a href="<?php echo home_url( '/' ); ?>" class="<?php x_brand_class(); ?>" title="<?php echo $site_description; ?>">
  <?php echo ( $logo == '' ) ? $site_name : $site_logo; ?>
</a>