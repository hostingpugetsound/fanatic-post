<?php

// =============================================================================
// VIEWS/GLOBAL/_NAVBAR.PHP
// -----------------------------------------------------------------------------
// Outputs the navbar.
// =============================================================================

$navbar_position = x_get_navbar_positioning();
$logo_nav_layout = x_get_logo_navigation_layout();
$is_one_page_nav = x_is_one_page_navigation();

global $current_user;
//wp_get_current_user();

$adClient      = "ca-pub-6614460239177654";
$adSlot        = "9635782928";
$sidebarAdSlot = "9635782928";

?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">


<div class="x-navbar-wrap">
    <div class="<?php x_navbar_class(); ?> blackbg">

        <div class="x-navbar-inner">
            <div class="x-container max width">


                <div id="loginControls">

                    <a href="<?php get_site_url(); ?>/search/" style="margin-right:8px;"><i
                                class="fa fa-search fa-lg"></i></a>

                    <?php if ( $current_user->ID && get_option( 'articleprepayment' . $current_user->ID ) ): ?>

                        <br/>Article Credit (1)

                    <?php endif; ?>
                </div>
                <?php x_get_view( 'global', '_nav', 'primary' ); ?>


                <div style="clear:both;"></div>

            </div>
        </div>
        <div class="x-navbar-inner whitebg">
            <div class="x-container max width">
                <?php x_get_view( 'global', '_nav', 'secondary' ); ?>
            </div>
        </div>
    </div>
</div>
<?php if ( ! is_front_page() && ! is_page( 'home' ) && ! is_page( 'profile' ) && ! is_page( 'search' ) && ! is_search() && ! is_404() ) { ?>
    <div class="adWrapper">
        <div class="adContainer">

            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- TDF Placeholder -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="<?php echo $adClient; ?>"
                 data-ad-slot="<?php echo $adSlot; ?>"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

        </div>
    </div>
<?php } else if ( ! is_front_page() ) {
    echo '<div style="display: block; height: 90px;"></div>';
} ?>
