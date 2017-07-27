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
    <section class="x-navbar-wrap banner home">
        <div class="<?php x_navbar_class(); ?>">
            <div class="x-navbar-inner">
                <div class="x-container max width">
                    <div id="loginControls">
                        <a href="<?php get_site_url(); ?>/search/" style="margin-right:8px;"><i class="fa fa-search fa-lg"></i></a>
                        <?php if ( $current_user->ID && get_option( 'articleprepayment' . $current_user->ID ) ): ?>
                            <br/>Article Credit (1)
                        <?php endif; ?>
                    </div>
                    <?php x_get_view( 'global', '_nav', 'primary' ); ?>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
    </section>

<?php } else if ( ! is_front_page() ) { ?>
    <section class="x-navbar-wrap banner">
        <div class="<?php x_navbar_class(); ?> bluebg">
        </div>
    </section>

<?php } elseif ( ! is_front_page() && ! is_page( 'home' ) && ! is_page( 'profile' ) && ! is_page( 'search' ) && ! is_search() && ! is_404() ) { ?>
<?php } else if ( ! is_front_page() ) {
    echo '<div style="display: block; height: 90px;"></div>';
} ?>
