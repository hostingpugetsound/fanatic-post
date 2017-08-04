<?php

// =============================================================================
// VIEWS/GLOBAL/_BANNER.PHP
// -----------------------------------------------------------------------------
// Outputs the h1 tags/banner.
// =============================================================================

$navbar_position = x_get_navbar_positioning();
$logo_nav_layout = x_get_logo_navigation_layout();
$is_one_page_nav = x_is_one_page_navigation();

global $current_user;
//wp_get_current_user();

?>


<?php if ( is_front_page() ) { ?>
    <section class="homeSlider">
        <div class="sliderContainer">
            <div class="x-column x-sm x-1-3 fanBox animated fadeInLeft">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/fan.jpg" />
            </div>

            <div class="x-column x-sm x-1-3 sliderText">
                <h1>BE THE BEAT</h1>
                <h2>RAISE YOUR VOICE, report on the game, <br />represent your team.</h2>
                <a href="#" class="btn btn-primary">Find Games Now</a>

            </div>
            <div class="x-column x-sm x-1-3 foeBox animated fadeInRight">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/foe.jpg" />
            </div>
        </div>
    </section>


<?php } else if ( is_page() || is_archive() || is_singular('league') || is_singular('team') ) { ?>
    <section class="x-main full banner">
        <div class="x-content-band man">
            <div class="x-container max width">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

<?php } elseif ( ! is_front_page() && ! is_page( 'home' ) && ! is_page( 'profile' ) && ! is_page( 'search' ) && ! is_search() && ! is_404() ) { ?>
<?php } else if ( ! is_front_page() ) {
    echo '<div style="display: block; height: 90px;"></div>';
} ?>
